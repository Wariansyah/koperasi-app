<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $micro_id = explode(" ", microtime());
        $micro_id = $micro_id[1].substr($micro_id[0],2,6);

        User::create([
            'id'        => $micro_id,
            'name'      => 'superAdmin',
            'email'     => 'super@gmail.com',
            'password'  => Hash::make('123456'),
        ]);
    }
}
