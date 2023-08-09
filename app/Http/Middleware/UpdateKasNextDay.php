<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Kas;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Controllers\UserController;

class UpdateKasNextDay
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();

            // Pastikan user memiliki hak akses yang sesuai untuk melakukan operasi ini,
            // misalnya berdasarkan peran (role) atau izin (permission).
            $loggedInUser = User::find($user->id); // Retrieve the logged-in user from the database
            $lastUpdateDate = $loggedInUser->last_kas_update_date; // Use the retrieved user

            $currentDate = Carbon::now();

            if (!$lastUpdateDate || $currentDate->isAfter($lastUpdateDate)) {
                $kasForCurrentDate = Kas::where('date', $currentDate->toDateString())->first();

                if ($kasForCurrentDate) {
                    $kasAwalNextDay = $kasForCurrentDate->kas_akhir + $kasForCurrentDate->kas_masuk - $kasForCurrentDate->kas_keluar;

                    $nextDate = $currentDate->copy()->addDay();
                    $nextDayRecord = Kas::where('date', $nextDate->toDateString())->first();

                    if (!$nextDayRecord) {
                        $newKasRecord = new Kas([
                            'user_id' => $loggedInUser->id, // Associate the user_id
                            'kas_awal' => $kasAwalNextDay,
                            'kas_masuk' => 0,
                            'kas_keluar' => 0,
                            'kas_akhir' => $kasAwalNextDay,
                            'date' => $nextDate,
                        ]);
                        $newKasRecord->save();
                    }

                    // Update last_kas_update_date pada user
                    $loggedInUser->last_kas_update_date = $currentDate;
                    $loggedInUser->save();
                }
            }
        }

        return $response;
    }
}
