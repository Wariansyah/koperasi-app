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
            $table->string('no_induk')->after('name');
            $table->string('alamat')->after('no_induk');
            $table->string('telepon')->after('email');
            $table->string('status')->after('telepon');
            $table->string('jenkel')->after('status');
            $table->date('tgl_lahir')->after('jenkel');
            $table->string('tmpt_lahir')->after('tgl_lahir');
            $table->decimal('limit_pinjaman', 14,2)->after('tmpt_lahir')->nullable();
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
