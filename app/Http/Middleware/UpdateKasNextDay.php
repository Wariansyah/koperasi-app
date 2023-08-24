<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Kas;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UpdateKasNextDay
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();

            $loggedInUser = User::find($user->id);
            $lastUpdateDate = $loggedInUser->last_kas_update_date;

            $currentDate = Carbon::now();
            $currentDateStr = $currentDate->toDateString();

            // Check if kas generation is already done for today
            if (!$lastUpdateDate || $currentDate->isAfter($lastUpdateDate)) {
                $shouldGenerate = Cache::remember('kas_generation_' . $user->id . '_' . $currentDateStr, 86400, function () use ($user, $currentDateStr) {
                    $currentKasRecord = Kas::where('user_id', $user->id)
                        ->whereDate('date', $currentDateStr)
                        ->first();

                    if (!$currentKasRecord) {
                        $this->generateKasForUser($user, $currentDateStr);
                        return true;
                    }

                    return false;
                });

                if ($shouldGenerate) {
                    // Update last_kas_update_date on the user
                    $loggedInUser->last_kas_update_date = $currentDate;
                    $loggedInUser->save();
                }
            }
        }

        return $response;
    }

    private function generateKasForUser($user, $currentDate)
    {
        // Get the last kas record for the user
        $lastKasRecord = Kas::where('user_id', $user->id)
            ->whereDate('date', '<=', $currentDate) // Consider kas records up to the current date
            ->orderBy('date', 'desc')
            ->first();

        $kasAwalNextDay = 0;
        if ($lastKasRecord) {
            $kasAwalNextDay = $lastKasRecord->kas_akhir;
        }

        // Create a new kas transaction record for the next day
        $transaction = new Kas();
        $transaction->user_id = $user->id;
        $transaction->date = $currentDate;
        $transaction->kas_awal = $kasAwalNextDay;
        $transaction->kas_masuk = 0;
        $transaction->kas_keluar = 0;
        $transaction->kas_akhir = $kasAwalNextDay;
        
        // Save the transaction
        $transaction->save();
    }
}
