<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasis = [
            [
                'nama' => 'Gudang Utama',
                'jenis' => 'gudang',
            ],
            [
                'nama' => 'Rak 1',
                'jenis' => 'rak',
            ],
        ];

        $this->command->warn(PHP_EOL . 'Membuat data lokasi...');
        $progressBar = new ProgressBar($this->command->getOutput(), count($lokasis));

        foreach($lokasis as $lokasi)
        {
            Lokasi::firstOrCreate([
                 'nama'  => $lokasi['nama'],
                 'jenis'  => $lokasi['jenis'],
            ]);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->getOutput()->writeln('');
    }
}
