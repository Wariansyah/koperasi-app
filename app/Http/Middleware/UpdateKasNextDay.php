<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Kas;
use Carbon\Carbon;

class UpdateKasNextDay
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            
            // Pastikan user memiliki hak akses yang sesuai untuk melakukan operasi ini,
            // misalnya berdasarkan peran (role) atau izin (permission).

            $lastUpdateDate = $user->last_kas_update_date ?? Carbon::now()->subDay()->format('Y-m-d');

            $currentDate = Carbon::now();
            $nextDate = $currentDate->copy()->addDay();

            if ($currentDate->isAfter($lastUpdateDate, true)) {
                $kasForCurrentDate = Kas::where('date', $currentDate)->first();

                if ($kasForCurrentDate) {
                    $kasAwalNextDay = $kasForCurrentDate->kas_akhir + $kasForCurrentDate->kas_masuk - $kasForCurrentDate->kas_keluar;

                    $nextDayRecord = Kas::where('date', $nextDate)->first();

                    if ($nextDayRecord) {
                        $nextDayRecord->update([
                            'kas_awal' => $kasAwalNextDay,
                            'kas_akhir' => $kasAwalNextDay,
                        ]);
                    } else {
                        $newKasRecord = Kas::create([
                            'kas_awal' => $kasAwalNextDay,
                            'kas_masuk' => 0,
                            'kas_keluar' => 0,
                            'kas_akhir' => $kasAwalNextDay,
                            'date' => $nextDate,
                        ]);
                    }

                    // Update last_kas_update_date pada user
                    $user->update([
                        'last_kas_update_date' => $currentDate->format('Y-m-d'),
                    ]);
                }
            }
        }

        return $response;
    }
}
