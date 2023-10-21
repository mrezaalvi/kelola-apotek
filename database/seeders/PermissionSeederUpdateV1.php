<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeederUpdateV1 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //  Lokasi
            [
                'name'  =>  'stock: view-any',
                'alias' =>  'Lihat Daftar Persediaan',
                'group' =>  'Persediaan',
            ],
            [
                'name'  =>  'stock: view',
                'alias' =>  'Lihat Detail Persediaan',
                'group' =>  'Persediaan',
            ],
            [
                'name'  =>  'stock: create',
                'alias' =>  'Buat Data Persediaan',
                'group' =>  'Persediaan',
            ],
            [
                'name'  =>  'stock: update',
                'alias' =>  'Ubah Data Persediaan',
                'group' =>  'Persediaan',
            ],
            [
                'name'  =>  'stock: delete',
                'alias' =>  'Hapus Data Persediaan',
                'group' =>  'Persediaan',
            ],

            // Supplier Permission
            [
                'name'  =>  'supplier: view-any',
                'alias' =>  'Lihat Daftar Supplier',
                'group' =>  'Data Supplier',
            ],
            [
                'name'  =>  'supplier: view',
                'alias' =>  'Lihat Detail Supplier',
                'group' =>  'Data Supplier',
            ],
            [
                'name'  =>  'supplier: create',
                'alias' =>  'Buat Data Supplier',
                'group' =>  'Data Supplier',
            ],
            [
                'name'  =>  'supplier: update',
                'alias' =>  'Ubah Data Supplier',
                'group' =>  'Data Supplier',
            ],
            [
                'name'  =>  'supplier: delete',
                'alias' =>  'Hapus Data Supplier',
                'group' =>  'Data Supplier',
            ],

            // Apoteker Permission
            [
                'name'  =>  'apoteker: view-any',
                'alias' =>  'Lihat Daftar Apoteker',
                'group' =>  'Data Apoteker',
            ],
            [
                'name'  =>  'apoteker: view',
                'alias' =>  'Lihat Detail Apoteker',
                'group' =>  'Data Apoteker',
            ],
            [
                'name'  =>  'apoteker: create',
                'alias' =>  'Buat Data Apoteker',
                'group' =>  'Data Apoteker',
            ],
            [
                'name'  =>  'apoteker: update',
                'alias' =>  'Ubah Data Apoteker',
                'group' =>  'Data Apoteker',
            ],
            [
                'name'  =>  'apoteker: delete',
                'alias' =>  'Hapus Data Apoteker',
                'group' =>  'Data Apoteker',
            ],

            [
                'name'  =>  'settings: apotek-profile',
                'alias' =>  'Pengaturan Profil Apotek',
                'group' =>  'Pengaturan',
            ],
        ];

        $superuser = Role::where('name', 'superuser')->first();
        $pemilik = Role::where('name', 'pemilik')->first();

        $this->command->warn(PHP_EOL . 'Creating permission...');
        $progressBar = new ProgressBar($this->command->getOutput(), count($permissions));

        foreach($permissions as $permission)
        {
            $permissionCreated = Permission::firstOrCreate([
                'name'  => $permission["name"],
                'alias' => $permission['alias'],
                'group' => $permission['group'],
            ]);

            if($superuser && !$pemilik->hasPermissionTo($permissionCreated->name))
                $superuser->givePermissionTo($permissionCreated);
            
            if($pemilik && !$pemilik->hasPermissionTo($permissionCreated->name))
            {
                if($permissionCreated->group != 'Persediaan')
                $pemilik->givePermissionTo($permissionCreated);
            }
                

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->getOutput()->writeln('');

    }
}
