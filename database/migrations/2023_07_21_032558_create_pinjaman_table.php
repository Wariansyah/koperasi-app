<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simpanan', function (Blueprint $table) {
            $table->id();
            $table->integer('rekening');
            $table->string('user_id');
            $table->integer('nominal');
            $table->date('tgl_pinjam');
            $table->integer('keuntungan');
            $table->integer('rate_keuntugan');
            $table->dateTime('jangka_waktu');
            $table->dateTime('tgl_jatuh_tempo');
            $table->integer('sisa_pinjaman');
            $table->integer('sisa_keuntungan');
            $table->integer('nominal_tunggakan');
            $table->integer('kali_tunggakan');
            $table->date('tgl_tunggakan');
            $table->string('penggunaan');
            $table->date('tgl_lunas');
            $table->string('otoritasi_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simpanan');
    }
};
