<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::create([
        //     'name' => 'Superuser',
        //     'username' => 'superuser',
        //     'email' => 'mrezaalvi@gmail.com',
        //     'password' => Hash::make('Muhammad_570M'),
        // ]);

        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            SatuanSeeder::class,
            LokasiSeeder::class,
            UpdatePermissionName::class,
            PermissionSeederUpdateV1::class,
            ApotekerSeeder::class,
        ]);
    }
}
