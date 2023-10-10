<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UpdatePermissionName extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //  Lokasi
            [
                'name'  =>  'location: view-any',
                'alias' =>  'Lihat Daftar Lokasi',
                'group' =>  'Manajemen Lokasi',
            ],
            [
                'name'  =>  'location: view',
                'alias' =>  'Lihat Detail Lokasi',
                'group' =>  'Manajemen Lokasi',
            ],
            [
                'name'  =>  'location: create',
                'alias' =>  'Buat Data Lokasi',
                'group' =>  'Manajemen Lokasi',
            ],
            [
                'name'  =>  'location: update',
                'alias' =>  'Ubah Data Lokasi',
                'group' =>  'Manajemen Lokasi',
            ],
            [
                'name'  =>  'location: delete',
                'alias' =>  'Hapus Data Lokasi',
                'group' =>  'Manajemen Lokasi',
            ],
        ];

        $this->command->warn(PHP_EOL . 'Updating permission...');
        $progressBar = new ProgressBar($this->command->getOutput(), count($permissions));

        foreach($permissions as $permission)
        {
            $permissionData = Permission::where('name', $permission["name"])->first();

            if($permissionData)
            {
                $permissionData->alias = $permission['alias']; 
                $permissionData->group = $permission['group']; 
                $permissionData->save();
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->getOutput()->writeln('');
    }
}
