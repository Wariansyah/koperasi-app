<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'list-user',
            'create-user',
            'edit-user',
            'delete-user',
            'list-produk',
            'create-produk',
            'edit-produk',
            'delete-produk',
            'list-ledger',
            'create-ledger',
            'edit-ledger',
            'delete-ledger',
            'list-anggota',
            'create-anggota',
            'edit-anggota',
            'delete-anggota',
            'list-company',
            'create-company',
            'edit-company',
            'delete-company',
         ];
      
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}
