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
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_induk')->after('name')->nullable();
            $table->string('alamat')->after('no_induk')->nullable();
            $table->string('telepon')->after('email')->nullable();
            $table->string('status')->after('telepon')->nullable();
            $table->string('jenkel')->after('status')->nullable();
            $table->date('tgl_lahir')->after('jenkel')->nullable();
            $table->string('tmpt_lahir')->after('tgl_lahir')->nullable();
            $table->decimal('limit_pinjaman', 14, 2)->after('tmpt_lahir')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
