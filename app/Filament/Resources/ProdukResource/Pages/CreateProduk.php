<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Actions;
use App\Models\Satuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\ProdukResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduk extends CreateRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
    
    protected function afterCreate(): void
    {
        $this->record->kemasan = $this->getKemasan();
        $this->record->save(); 

        foreach($this->record->multiSatuan as $multiSatuan)
        {
            $multiSatuan->harga_beli = $this->record->harga_beli * $multiSatuan->nilai_konversi;
            $multiSatuan->harga_jual = $this->record->harga_jual * $multiSatuan->nilai_konversi;
            $multiSatuan->save();
        }
    }

    public function getKemasan():null | string
    {
        $produk = $this->record;

        if(!$produk->satuan)
            return null;
        if($produk->multiSatuan()->count()<1)
            return "1 ".$produk->satuan->nama;

        $multiSatuan = $produk->multiSatuan()->orderBy('nilai_konversi')->get();

        $kemasan = "";
        $loop = 0;
        for($i = 0; $i<$multiSatuan->count(); $i++)
        {
            $satuan = Satuan::find($multiSatuan[$i]->satuan_lanjutan);
            if($i > 0)
                $kemasan = $satuan->nama." isi ".$multiSatuan[$i]->nilai_konversi/$multiSatuan[$i-1]->nilai_konversi." ".$kemasan;
            else
                $kemasan = $satuan->nama." isi ".$multiSatuan[$i]->nilai_konversi." ".$kemasan;
    
        }
        return "1 ".$kemasan.$produk->satuan->nama;
    }
}
