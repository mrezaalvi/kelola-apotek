<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //  User
            [
                'name'  => 'user: view-any',
                'alias' => 'Lihat Daftar Pengguna',
                'group' => 'Manajemen Pengguna',
            ],
            [
                'name'  => 'user: view',
                'alias' => 'Lihat Detail Pengguna',
                'group' => 'Manajemen Pengguna',
            ],
            [
                'name'  => 'user: create',
                'alias' => 'Buat Data Pengguna',
                'group' => 'Manajemen Pengguna',
            ],
            [
                'name'  => 'user: update',
                'alias' => 'Ubah Data Pengguna',
                'group' => 'Manajemen Pengguna',
            ],
            [
                'name'  => 'user: delete',
                'alias' => 'Hapus Data Pengguna',
                'group' => 'Manajemen Pengguna',
            ],
            [
                'name'  => 'user: change-password',
                'alias' => 'Ubah Password Pengguna',
                'group' => 'Manajemen Pengguna',
            ],
            [
                'name'  => 'user: change-role',
                'alias' => 'Ubah Grup Pengguna',
                'group' => 'Manajemen Pengguna',
            ],

            //  Role
            [
                'name'  => 'role: view-any',
                'alias' => 'Lihat Daftar Grup Pengguna',
                'group' => 'Manajemen Grup Pengguna',
            ],
            [
                'name'  => 'role: view',
                'alias' => 'Lihat Detail Grup Pengguna',
                'group' => 'Manajemen Grup Pengguna',
            ],
            [
                'name'  => 'role: create',
                'alias' => 'Buat Grup Pengguna',
                'group' => 'Manajemen Grup Pengguna',
            ],
            [
                'name'  => 'role: update',
                'alias' => 'Ubah Grup Pengguna',
                'group' => 'Manajemen Grup Pengguna',
            ],
            [
                'name'  => 'role: delete',
                'alias' => 'Hapus Grup Pengguna',
                'group' => 'Manajemen Grup Pengguna',
            ],

            //  Permission
            [
                'name'  => 'permission: view-any',
                'alias' => 'Lihat Daftar Izin Akses',
                'group' => 'Manajemen Izin Pnegguna',
            ],
            
            //  Satuan
            [
                'name'  =>  'unit: view-any',
                'alias' =>  'Lihat Daftar Satuan',
                'group' =>  'Manajemen Satuan',
            ],
            [
                'name'  =>  'unit: view',
                'alias' =>  'Lihat Detail Satuan',
                'group' =>  'Manajemen Satuan',
            ],
            [
                'name'  =>  'unit: create',
                'alias' =>  'Buat Data Satuan',
                'group' =>  'Manajemen Satuan',
            ],
            [
                'name'  =>  'unit: update',
                'alias' =>  'Ubah Data Satuan',
                'group' =>  'Manajemen Satuan',
            ],
            [
                'name'  =>  'unit: delete',
                'alias' =>  'Hapus Data Satuan',
                'group' =>  'Manajemen Satuan',
            ],

            //  Kategori
            [
                'name'  =>  'category: view-any',
                'alias' =>  'Lihat Daftar Kategori',
                'group' =>  'Manajemen Kategori',
            ],
            [
                'name'  =>  'category: view',
                'alias' =>  'Lihat Detail Kategori',
                'group' =>  'Manajemen Kategori',
            ],
            [
                'name'  =>  'category: create',
                'alias' =>  'Buat Data Kategori',
                'group' =>  'Manajemen Kategori',
            ],
            [
                'name'  =>  'category: update',
                'alias' =>  'Ubah Data Kategori',
                'group' =>  'Manajemen Kategori',
            ],
            [
                'name'  =>  'category: delete',
                'alias' =>  'Hapus Data Kategori',
                'group' =>  'Manajemen Kategori',
            ],

            //  Lokasi
            [
                'name'  =>  'location: view-any',
                'alias' =>  'Lihat Daftar Kategori',
                'group' =>  'Manajemen Kategori',
            ],
            [
                'name'  =>  'location: view',
                'alias' =>  'Lihat Detail Kategori',
                'group' =>  'Manajemen Kategori',
            ],
            [
                'name'  =>  'location: create',
                'alias' =>  'Buat Data Kategori',
                'group' =>  'Manajemen Kategori',
            ],
            [
                'name'  =>  'location: update',
                'alias' =>  'Ubah Data Kategori',
                'group' =>  'Manajemen Kategori',
            ],
            [
                'name'  =>  'location: delete',
                'alias' =>  'Hapus Data Kategori',
                'group' =>  'Manajemen Kategori',
            ],

            //  Produk
            [
                'name'  =>  'product: view-any',
                'alias' =>  'Lihat Daftar Produk',
                'group' =>  'Manajemen Produk',
            ],
            [
                'name'  =>  'product: view',
                'alias' =>  'Lihat Detail Produk',
                'group' =>  'Manajemen Produk',
            ],
            [
                'name'  =>  'product: create',
                'alias' =>  'Buat Data Produk',
                'group' =>  'Manajemen Produk',
            ],
            [
                'name'  =>  'product: update',
                'alias' =>  'Ubah Data Produk',
                'group' =>  'Manajemen Produk',
            ],
            [
                'name'  =>  'product: delete',
                'alias' =>  'Hapus Data Produk',
                'group' =>  'Manajemen Produk',
            ],
            [
                'name'  =>  'product: import',
                'alias' =>  'Import Data Produk',
                'group' =>  'Manajemen Produk',
            ],
            [
                'name'  =>  'product: export',
                'alias' =>  'Export Data Produk',
                'group' =>  'Manajemen Produk',
            ],

        ];

        $roles = [
            'superuser',
            'pemilik',
            'apoteker',
            'kasir',
        ];

        if(!Permission::exists() && !Role::exists())
        {
            $this->command->warn(PHP_EOL . 'Creating permission...');
            $progressBar = new ProgressBar($this->command->getOutput(), count($permissions));

            foreach($permissions as $permission)
            {
                Permission::firstOrCreate([
                    'name'  => $permission["name"],
                    'alias' => $permission['alias'],
                    'group' => $permission['group'],
                ]);

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->command->getOutput()->writeln('');


            $this->command->warn(PHP_EOL . 'Creating role...');
            $progressBar = new ProgressBar($this->command->getOutput(), count($roles));

            foreach($roles as $role)
            {
                $createdRole = Role::firstOrCreate([
                    'name' => $role,
                ]);

                if($role == 'superuser')
                    $createdRole->syncPermissions(Permission::all());

                if($role == 'pemilik')
                {
                    $permissions = Permission::where('name', 'like', 'product:%')->get();
                    $permissions = $permissions->merge(Permission::where('name', 'like', 'category:%')->get());
                    $permissions = $permissions->merge(Permission::where('name', 'like', 'unit:%')->get());
                    $permissions = $permissions->merge(Permission::where('name', 'like', 'location:%')->get());
                    $permissions = $permissions->merge(Permission::where('name', 'like', 'user:%')->get());
                    $permissions = $permissions->merge(Permission::where('name', 'like', 'role:%')->get());
                    $createdRole->syncPermissions($permissions);
                }

                if($role == 'apoteker')
                {
                    // $permissions = Permission::where('name', 'product: view-any')->get();
                    // $permissions = $permissions->merge(Permission::where('name', 'category: view-any')->get());
                    // $permissions = $permissions->merge(Permission::where('name', 'unit: view-any')->get());

                    // $createdRole->syncPermissions($permissions);
                    $createdRole->syncPermissions([
                        'product: view-any',
                        'product: view',
                        'category: view-any',
                        'unit: view-any',
                    ]);
                }

                if($role == 'kasir')
                {
                    $createdRole->syncPermissions([
                        'product: view-any',
                        'product: view',
                        'category: view-any',
                        'unit: view-any',
                    ]);
                }
            }

            $progressBar->finish();
            $this->command->getOutput()->writeln('');
            $this->command->getOutput()->writeln('');
        }

        $users = [
            [
                'name'      => 'Superuser',
                'username'  => 'superuser',
                'email'     => 'mrezaalvi@gmail.com',
                'password'  => 'Muhammad_570M',
                'role'      => [
                    'superuser'
                ],
            ],
            [
                'name'      => 'Junaidi Fatrizal',
                'username'  => 'pemilik',
                'email'     => 'pemilik@example.com',
                'password'  => 'Muhammad->570M',
                'role'      => [
                    'pemilik'
                ],
            ],
            [
                'name'      => 'Junaidi Fatrizal',
                'username'  => 'apoteker01',
                'email'     => 'apoteker01@example.com',
                'password'  => 'apoteker1234',
                'role'      => [
                    'apoteker',
                ],
            ],
            [
                'name'      => 'Kasir 01',
                'username'  => 'kasir01',
                'email'     => 'kasir01@example.com',
                'password'  => 'kasir1234',
                'role'      => [
                    'kasir',
                ],
            ],
            [
                'name'      => 'Kasir 02',
                'username'  => 'kasir02',
                'email'     => 'kasir02@example.com',
                'password'  => 'kasir1234',
                'role'      => [
                    'kasir',
                ],
            ],
        ];

        if(!User::exists())
        {
            $this->command->warn(PHP_EOL . 'Creating user...');
        
            $progressBar = new ProgressBar($this->command->getOutput(), count($users));

            foreach($users as $user)
            {
                if(User::where('username', $user['username'])->count()<1)
                {
                    $userCreated = User::firstOrCreate([
                        'name'      => $user['name'],
                        'username'  => $user['username'],
                        'email'     => $user['email'],
                        'password'  => Hash::make($user['password']),
                    ]);
        
                    $userCreated->syncRoles($user['role']);
                    $progressBar->advance();
                }
                
            }

            $progressBar->finish();
            $this->command->getOutput()->writeln('');
            $this->command->getOutput()->writeln('');
        }
        
    }
}
