<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
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
        foreach ($rows as $row) {
            if(!empty($row['nama']))
            {
                $produk = Produk::where('nama', trim($row['nama']))->first();
                $listSatuan = $this->getSatuan($row['satuan']);
                
                $satuanDasarId = (count($listSatuan['satuan_dasar'])>0)?$listSatuan['satuan_dasar']['id']:null;
                $satuanLanjutan = $listSatuan['satuan_lanjutan'];

                $kemasan = "";
                if(count($satuanLanjutan)>0)
                {
                    $kemasan .= "1 ";
                    
                    $multiple = 0;
                    foreach($satuanLanjutan as $satuan)
                    {
                        $kemasan .= $satuan['nama']." X ".$satuan['nilai_konversi']." ";
                    }
                    $kemasan .= $listSatuan['satuan_dasar']['nama'];
                }
                else
                {
                    if(count($listSatuan['satuan_dasar'])>0)
                        $kemasan = "1 ". $listSatuan['satuan_dasar']['nama'];
                }

                if(!$produk)
                {
                    $produk = Produk::firstOrCreate([
                        'nama' => $row['nama'],
                        'kode' => $row['kodesku'],
                        'barcode' => $row['barcode'],
                        'minimal_stok' => $row['stok_minimum'],
                        'pabrik' => $row['pabrik'],
                        'kemasan' => $kemasan,
                        'diskon' => $row['diskon'],
                        'harga_beli' => $row['harga_beli'],
                        'harga_jual' => $row['harga_jual'],
                        'satuan_id' => $satuanDasarId,
                    ]);
                }
                else
                {
                    if(!$produk->kode && $row['kodesku'])
                        $produk->kode = $row['kodesku'];
                    
                    if(!$produk->barcode && $row['barcode'])
                        $produk->barcode = $row['barcode'];
                    
                    if($row['stok_minimum'])
                        $produk->minimal_stok = $row['stok_minimum'];
                    
                    if(!$produk->pabrik && $row['pabrik'])
                        $produk->pabrik = $row['pabrik'];
                    
                    if(($produk->harga_beli == 0) && $row['harga_beli'])
                        $produk->harga_beli = $row['harga_beli'];

                    if(($produk->harga_jual == 0) && $row['harga_jual'])
                        $produk->harga_jual = $row['harga_jual'];

                    if(!$produk->satuan_id && $satuanDasarId)
                        $produk->satuan_id = $satuanDasarId;

                    $produk->save();
                }

                $kategoriId = $this->getKategoriId($row['kategori']);
        
                if(count($kategoriId)>0)
                    $produk->kategories()->sync($kategoriId, false);

                if(count($satuanLanjutan)>0)
                {
                    $multiple = 1;
                    foreach($satuanLanjutan as $satuan)
                    {
                        $multiple *= $satuan['nilai_konversi'];
                        $produk->multiSatuan()->updateOrCreate(
                            ['satuan_lanjutan' => $satuan['id'],],
                            ['nilai_konversi' => $multiple,]
                        );
                    }
                }

                $lokasiId = $this->getLokasiId($row['lokasi']);
            }
        }
        Notification::make()
            ->success()
            ->title('Proses import berhasil')
            ->body('Seluruh data dalam file berhasil diimport')
            ->send();
    }

    public function getSatuan(?string $satuanList):array
    {
        $satuanLanjutan = [];

        $satuan = [
            'satuan_dasar' => [],
            'satuan_lanjutan' => [],
        ];

        if(empty($satuanList))
            return $satuan;
        
        $satuanArr = explode(';', trim($satuanList));

        $satuanDasar = trim(array_slice($satuanArr, -1, 1)[0]);
        
        $satuanDasarRecord = Satuan::where('nama', $satuanDasar)->first();

        if(!$satuanDasarRecord)
            $satuanDasarRecord = Satuan::create(['nama' => $satuanDasar]);
        
        $satuan['satuan_dasar'] = [
            'id' => $satuanDasarRecord->id,
            'nama' => $satuanDasarRecord->nama,
        ];

        if(count($satuanArr)>1)
        {
            $satuanLanjutan = array_slice($satuanArr, 0, count($satuanArr)-1);
            if(count($satuanLanjutan)>0)
            {
                $multiSatuan = [];
                foreach($satuanLanjutan as $item){
                    $item = explode('@',$item);
                    if(count($item)==2)
                    {
                        $satuanLanjutanRecord = Satuan::where('nama', $item[0])->first();
                        if(!$satuanLanjutanRecord)
                            $satuanLanjutanRecord = Satuan::create(['nama'=>$item[0]]);

                        array_push($multiSatuan, [
                            'id' => $satuanLanjutanRecord->id,
                            'nama' => $satuanLanjutanRecord->nama,
                            'nilai_konversi' => $item[1],
                            ]);
                    }
                        
                }
            }
            
            $satuan['satuan_lanjutan'] = $multiSatuan;
        }

        return $satuan;
    }

    public function getKategoriId(?string $kategoriNamaList):array
    {
        if(empty($kategoriNamaList))
            return [];
        $kategoriNamaArr = explode(';',trim($kategoriNamaList));
        $kategoriId = [];
        foreach($kategoriNamaArr as $kategoriNama)
        {
            
            $kategori = Kategori::where('nama', Str::of($kategoriNama)->trim()->upper())->first();
            
            if(!$kategori)
                $kategori = Kategori::create(['nama' => $kategoriNama]);

            array_push($kategoriId, $kategori->id);
        }
        return $kategoriId;
    }

    public function getLokasiId(?string $lokasiNama):int{
        $lokasi = Lokasi::where('nama', trim($lokasiNama))->first();
        if(!$lokasi)
            $lokasi = Lokasi::create(['nama'=>trim($lokasiNama)]);
        
        return ($lokasi)?->id;
    }

    public function startRow(): int
    {
        return 16;
    }
}
