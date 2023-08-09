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

            // Pastikan user memiliki hak akses yang sesuai untuk melakukan operasi ini,
            // misalnya berdasarkan peran (role) atau izin (permission).
            $user = User::find($user->id);
            $lastUpdateDate = $user->last_kas_update_date;

            $currentDate = Carbon::now();

            if (!$lastUpdateDate || $currentDate->isAfter($lastUpdateDate)) {
                $kasForCurrentDate = Kas::where('date', $currentDate->toDateString())->first();

                if ($kasForCurrentDate) {
                    $kasAwalNextDay = $kasForCurrentDate->kas_akhir + $kasForCurrentDate->kas_masuk - $kasForCurrentDate->kas_keluar;

                    $nextDate = $currentDate->copy()->addDay();
                    $nextDayRecord = Kas::where('date', $nextDate->toDateString())->first();

                    if (!$nextDayRecord) {
                        $newKasRecord = new Kas([
                            'kas_awal' => $kasAwalNextDay,
                            'kas_masuk' => 0,
                            'kas_keluar' => 0,
                            'kas_akhir' => $kasAwalNextDay,
                            'date' => $nextDate,
                        ]);
                        $newKasRecord->save();
                    }

                    // Update last_kas_update_date pada user
                    $user->last_kas_update_date = $currentDate;
                    $user->save();
                }
            }
        }

        return $response;
    }
}