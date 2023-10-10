<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'name'  =>  'location: update',
                'alias' =>  'Ubah Data Persediaan',
                'group' =>  'Persediaan',
            ],
            // [
            //     'name'  =>  'location: delete',
            //     'alias' =>  'Hapus Data Persediaan',
            //     'group' =>  'Persediaan',
            // ],
        ];

        $this->command->warn(PHP_EOL . 'Creating permission...');
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
