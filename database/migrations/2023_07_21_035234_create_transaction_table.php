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
        Schema::create('transaction', function (Blueprint $table) {
            $table->string('id',16)->primary();
            $table->string('jurnal_id');
            $table->date('tanggal');
            $table->string('jam');
            $table->string('rekening_pinjaman');
            $table->string('ledger');
            $table->string('keterangan');
            $table->integer('nominal_debet');
            $table->integer('nominal_kredit');
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
        Schema::dropIfExists('transaction');
    }
};
