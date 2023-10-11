<?php

namespace Database\Seeders;

use App\Models\Apoteker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ApotekerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!Apoteker::exists())
        {
            Apoteker::create([
                'nama' => 'apt. Junaidi Fatrizal, S.Farm',
                'no_telp' => '0811666300',
                'email' => '',
                'alamat' => '',
                'stra_no' => '12287112283060502',
                'stra_exp_date' => '05/06/2027',
                'stra_file' => 'apoteker/stra-12287112283060502.pdf',
                'sipa_no' => '131/SIPA/DPMPTSP-BTM.04/VII/2023',
                'sipa_exp_date' => null,
                'sipa_file' => null,
            ]);
        }
    }
}
