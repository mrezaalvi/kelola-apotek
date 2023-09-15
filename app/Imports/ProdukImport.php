<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToCollection, WithHeadingRow, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        // Validator::make($rows->toArray(), [
        //      '*.nama' => 'required',
        //     //  '*.satuan' => 'required',
        //  ])->validate();
            // dd($rows);
        foreach ($rows as $row) {
            // $satuan 
            if(!$produk = Produk::where('nama', $row['nama'])
                    ->where('kode', $row['kodesku'])
                    ->where('barcode', $row['barcode'])
                    ->first()
                )
                $produk = Produk::firstOrCreate([
                    'nama' => $row['nama'],
                    'kode' => $row['kodesku'],
                    'barcode' => $row['barcode'],
                    'minimal_stok' => $row['stok_minimum'],
                    'pabrik' => $row['pabrik'],
                    'kemasan' => $row['kemasan'],
                    'diskon' => $row['diskon'],
                    'harga_beli' => $row['harga_beli'],
                    'harga_jual' => $row['harga_jual'],
                    // 'satuan_id' => $row['barcode'],
                ]);

                $kategoriId = $this->getKategoriId($row['kategori']);
        
                if(count($kategoriId)>0)
                    $produk->kategories()->sync($kategoriId, false);
        }
    }

    public function getSatuanId(?string $satuanName):int
    {
        $satuanName = Str::of($satuanName)->trim();
        $satuanNameArr = explode(';', $satuanName);
        $satuan = Satuan::where('nama', $satuanName)->first();

        if(!$satuan)
            $satuan = Satuan::create(['nama'=>$satuanName]);

        return $satuan->id;
    }

    public function getKategoriId(?string $kategoriNamaList):array
    {
        if(!trim($kategoriNamaList))
            return [];
        $kategoriNamaArr = explode(';',trim($kategoriNamaList));
        $kategoriId = [];
        foreach($kategoriNamaArr as $kategoriNama)
        {
            
            $kategori = Kategori::where('nama', Str::of($kategoriNama)->trim()->upper())->first();
            
            if(!$kategori)
                $kategori = Kategori::create(['nama' => $kategoriNama]);
            // dd($kategori->id);

            array_push($kategoriId, $kategori->id);
        }
        return $kategoriId;
    }

    public function startRow(): int
    {
        return 16;
    }
}
