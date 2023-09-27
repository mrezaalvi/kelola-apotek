<?php

namespace App\Exports;

use App\Models\Produk;
use App\Models\Satuan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ProdukExport implements FromCollection, WithHeadings,  WithMapping, WithCustomCsvSettings
{

    public function collection()
    {
        $data = Produk::with('satuan', 'multiSatuan', 'kategories')->orderBy('nama')->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            'kode/sku',
            'barcode',
            'nama',
            'satuan',
            'kategori',
            'stok_minimum',
            'pabrik',
            'lokasi',
            'no.batch*',
            'ED*',
            'stok',
            'diskon',
            'diskon2',
            'harga_beli',
            'harga_jual',
        ]; 
    }

    public function map($produk): array
    {
        return[
            ($produk->kode)?";".$produk->kode:$produk->kode,
            ($produk->barcode)?";".$produk->barcode:$produk->barcode,
            $produk->nama,
            $this->satuanToString($produk->satuan, $produk->multiSatuan),
            $this->kategoriToString($produk->kategories),
            $produk->minimal_stok,
            $produk->pabrik,
            null,
            null,
            null,
            null,
            $produk->diskon,
            $produk->diskon2,
            $produk->harga_beli,
            $produk->harga_jual,
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'use_bom' => false,
            'output_encoding' => 'ISO-8859-1',
        ];
    }

    public function satuanToString($satuan, Collection | null $multiSatuan = null): null | string
    {
        if(!$satuan)
            return null;

        if($multiSatuan->count()<1)
            return ($satuan)?->nama;

        $satuanList = $satuan->nama;
        $divider = 1;
        foreach($multiSatuan as $multiSatuanItem){
            $satuanLanjutan = Satuan::find($multiSatuanItem->satuan_lanjutan);
            $satuanList = $satuanLanjutan->nama."@".$multiSatuanItem->nilai_konversi/$divider.";".$satuanList;
            $divider = $multiSatuanItem->nilai_konversi;
        }

        return $satuanList;
    }

    public function kategoriToString($kategoriData): null | string
    {
        $kategori = "";
        foreach($kategoriData as $kategoriItem)
        {
            $kategori .= $kategoriItem->nama.";";
        }  
        return $kategori;
    }


}
