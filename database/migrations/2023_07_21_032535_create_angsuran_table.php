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
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('rekening_pinjaman');
            $table->integer('angsuran_ke');
            $table->integer('nominal_produk');
            $table->integer('nominal_untung');
            $table->integer('nominal_untung');
            $table->integer('nominal_untung');
            $table->string('jadwal_ansur');
            $table->date('tgl_bayar');
            $table->integer('nominal_byr_pokok');
            $table->integer('nominal_byr_untung');
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
        Schema::dropIfExists('angsuran');
    }
};
