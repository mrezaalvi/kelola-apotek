<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategories = [
            'Obat Sakit Kepala',
            'Obat Batuk',
            'Obat Flu & Pilek',
            'Obat Mata',
            'Susu Anak',
            'Umum',
            'Alat Medis',
            'Alat Kesehatan',
        ];

        $this->command->warn(PHP_EOL . 'Membuat data kategori...');
        $progressBar = new ProgressBar($this->command->getOutput(), count($kategories));

        foreach($kategories as $kategori)
        {
            Kategori::firstOrCreate([
                 'nama'  => $kategori,
            ]);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->getOutput()->writeln('');
    }
}
