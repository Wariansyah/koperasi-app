<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Kas;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Cache; // Import Cache facade

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
        // Your kas generation logic here
        // Ambil saldo akhir dari user pada hari sebelumnya (jika ada)
        $kas_akhir = Kas::where('user_id', $user->id)
            ->whereDate('date', $currentDate) // Ambil kas akhir dari tanggal saat pengguna login
            ->value('kas_akhir');

        // Ambil transaksi kas user pada hari ini (jika ada)
        $currentKasRecord = Kas::where('user_id', $user->id)
            ->whereDate('date', $currentDate)
            ->sum('kas_masuk') - Kas::where('user_id', $user->id)
            ->whereDate('date', $currentDate)
            ->sum('kas_keluar');

        // Hitung kas awal untuk user pada hari berikutnya
        $kasAwalNextDay = $kas_akhir + $currentKasRecord;

        // Buat data transaksi kas baru untuk user pada hari berikutnya
        $transaction = new Kas();
        $transaction->user_id = $user->id;
        $transaction->date = $currentDate; // Tanggal hari ini atau saat pengguna login
        $transaction->kas_awal = $kasAwalNextDay;
        $transaction->kas_masuk = 0; // Reset kas masuk
        $transaction->kas_keluar = 0; // Reset kas keluar
        $transaction->kas_akhir = $kasAwalNextDay;
        $transaction->save();
    }
}
