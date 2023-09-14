<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satuans = [
            'Pcs',
            'Botol',
            'Tablet',
            'Kapsul',
            'Kaplet',
            'Box',
            'Kotak',
            'Vial',
            'Tube',
            'Dus',
        ];

        $this->command->warn(PHP_EOL . 'Membuat data satuan...');
        $progressBar = new ProgressBar($this->command->getOutput(), count($satuans));

        foreach($satuans as $satuan)
        {
            Satuan::firstOrCreate([
                 'nama'  => $satuan,
            ]);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->getOutput()->writeln('');
    }
}
