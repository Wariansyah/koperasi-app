<?php

// app/Helpers/helpers.php

use App\Models\User;
use App\Models\Kas;

if (!function_exists('generateDailyKas')) {
    function generateDailyKas()
    {
        $users = User::all();

        foreach ($users as $user) {
            // Ambil saldo akhir dari user pada hari sebelumnya (jika ada)
            $kasAkhirHariSebelumnya = Kas::where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->value('kas_akhir');

            // Ambil transaksi kas user pada hari ini (jika ada)
            $transaksiHariIni = Kas::where('user_id', $user->id)
                ->whereDate('date', now()->toDateString())
                ->sum('kas_masuk') - Kas::where('user_id', $user->id)
                ->whereDate('date', now()->toDateString())
                ->sum('kas_keluar');

            // Hitung kas awal untuk user pada hari berikutnya
            $kasAwalHariBerikutnya = $kasAkhirHariSebelumnya + $transaksiHariIni;

            // Buat data transaksi kas baru untuk user pada hari berikutnya
            $transaction = new Kas();
            $transaction->user_id = $user->id;
            $transaction->date = now()->addDay()->toDateString(); // Tanggal hari berikutnya
            $transaction->kas_awal = $kasAwalHariBerikutnya;
            $transaction->kas_akhir = $kasAwalHariBerikutnya; // Saldo akhir masih sama dengan saldo awal karena belum ada transaksi pada hari itu
            $transaction->save();
        }
    }
}
