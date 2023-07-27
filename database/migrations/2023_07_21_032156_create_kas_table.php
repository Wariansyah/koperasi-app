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
    Schema::create('kas', function (Blueprint $table) {
        $table->id();
        $table->string('username'); // Assuming username will be a string field
        $table->integer('kas_awal');
        $table->integer('kas_masuk');
        $table->integer('kas_keluar');
        $table->integer('kas_akhir');
        $table->date('date'); // Assuming date will be stored as a date field
        $table->text('note')->nullable(); // Assuming note will be a text field, nullable means it's optional
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
        Schema::dropIfExists('kas');
    }
};
