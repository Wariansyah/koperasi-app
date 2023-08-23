<?php
// app/Helpers/helpers.php

use App\Models\Kas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

if (!function_exists('generateDailyKas')) {
    function generateDailyKas($user)
    {
        // Ambil saldo akhir dari user pada hari sebelumnya (jika ada)
        $kas_akhir = Kas::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->value('kas_akhir');

        // Ambil transaksi kas user pada hari ini (jika ada)
        $currentKasRecord = Kas::where('user_id', $user->id)
            ->whereDate('date', now()->toDateString())
            ->sum('kas_masuk') - Kas::where('user_id', $user->id)
            ->whereDate('date', now()->toDateString())
            ->sum('kas_keluar');

        // Hitung kas awal untuk user pada hari berikutnya
        $kasAwalNextDay = $kas_akhir + $currentKasRecord;

        // Buat data transaksi kas baru untuk user pada hari berikutnya
        $transaction = new Kas();
        $transaction->user_id = $user->id;
        $transaction->date = now()->addDay()->toDateString(); // Tanggal hari berikutnya
        $transaction->kas_awal = $kasAwalNextDay;
        $transaction->kas_masuk = 0; // Reset kas masuk
        $transaction->kas_keluar = 0; // Reset kas keluar
        $transaction->kas_akhir = $kasAwalNextDay;
        $transaction->save();
    }
}
