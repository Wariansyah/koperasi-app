<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('rekening')->unique();
            $table->string('no_induk')->unique();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->enum('jenkel', ['Laki-laki', 'Perempuan'])->nullable();
            $table->date('tnggl_lahir')->nullable();
            $table->string('tmpt_lahir')->nullable();
            $table->string('ibu_kandung')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('anggota');
    }
}
