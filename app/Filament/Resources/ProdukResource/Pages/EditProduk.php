<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Actions;
use App\Models\Satuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProdukResource;

class EditProduk extends EditRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['update_all_price'] = true;
        $data['update_all_diskon'] = true;
    
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['last_edited_by'] = auth()->id();
    
        return $data;
    }

    /** 
     *  Customizing the saving process
    */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DB::transaction(function() use ($record, $data){
            if(!$data['satuan']){
                $record->multiSatuan()->delete();
                $data['kemasan'] = null;
            }
            $record->update($data);

            foreach($record->multiSatuan as $multiSatuan)
            {
                if($data['update_all_price'])
                {
                    $multiSatuan->harga_beli = $this->record->harga_beli * $multiSatuan->nilai_konversi;
                    $multiSatuan->harga_jual = $this->record->harga_jual * $multiSatuan->nilai_konversi;
                }

                if($data['update_all_diskon'])
                {     
                    $multiSatuan->diskon = $this->record->diskon;
                    $multiSatuan->diskon2 = $this->record->diskon2;
                }

                $multiSatuan->save();
            }
            
            

            $record->kemasan = $this->getKemasan($record);
            $record->save();
        }, 5);
    
        return $record;
    }

    public function getKemasan(Model $produk):null | string
    {
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
