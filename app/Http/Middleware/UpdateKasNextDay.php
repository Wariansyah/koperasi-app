<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Kas;
use Carbon\Carbon;
use App\Models\User;

class UpdateKasNextDay
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();

            $loggedInUser = User::find($user->id); // Retrieve the logged-in user from the database
            $lastUpdateDate = $loggedInUser->last_kas_update_date; // Use the retrieved user

            $currentDate = Carbon::now();

            if (!$lastUpdateDate || $currentDate->isAfter($lastUpdateDate)) {
                $currentKasRecord = Kas::where('user_id', $loggedInUser->id)
                    ->whereDate('date', $currentDate->toDateString())
                    ->first();

                if ($currentKasRecord) {
                    // Calculate the initial balance for the next day using the current day's closing balance
                    $kasAwalNextDay = $currentKasRecord->kas_akhir + $loggedInUser->kas_masuk - $loggedInUser->kas_keluar;

                    $nextDate = $currentDate->copy()->addDay();
                    $nextDayRecord = Kas::where('user_id', $loggedInUser->id)
                        ->whereDate('date', $nextDate->toDateString())
                        ->first();

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
