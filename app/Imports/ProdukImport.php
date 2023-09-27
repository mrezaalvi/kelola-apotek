<?php

namespace App\Imports;

use App\Models;
use Carbon\Carbon;
use App\Models\Lokasi;
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
            
            if(!empty(trim($row['nama'])))
            {
                $produk = Models\Produk::where('nama', trim($row['nama']))->first();
                $listSatuan = $this->getSatuan($row['satuan']);
                
                $satuanDasarId = (count($listSatuan['satuan_dasar'])>0)?$listSatuan['satuan_dasar']['id']:null;
                $satuanLanjutan = $listSatuan['satuan_lanjutan'];

                $kemasan = "";

                if(count($satuanLanjutan)>0)
                {
                    $kemasan .= "1 ";

                    foreach($satuanLanjutan as $satuan)
                    {
                        $kemasan .= $satuan['nama']." isi ".$satuan['nilai_konversi']." ";
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
                    $produk = Models\Produk::firstOrCreate([
                        'nama' => $row['nama'],
                        'kode' => ($row['kodesku'])?trim(trim($row['kodesku'],";"),":"):$row['kodesku'],
                        'barcode' => ($row['barcode'])?trim(trim($row['barcode'],";"),":"):$row['barcode'],
                        'minimal_stok' => $row['stok_minimum'],
                        'pabrik' => $row['pabrik'],
                        'kemasan' => $kemasan,
                        'diskon' => $row['diskon'],
                        'diskon2' => (isset($row['diskon2']))?$row['diskon2']:0,
                        'harga_beli' => $row['harga_beli'],
                        'harga_jual' => $row['harga_jual'],
                        'satuan_id' => $satuanDasarId,
                        'created_by' => $user_id = auth()->id(),
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

                    if(!$produk->kemasan && $kemasan)
                        $produk->kemasan = $kemasan;
                    
                    if(($produk->diskon == 0) && isset($row['diskon']))
                        $produk->diskon = $row['diskon'];

                    if(($produk->diskon2 == 0) && isset($row['diskon2']))
                        $produk->diskon2 = $row['diskon2'];

                    if(!$produk->satuan_id && $satuanDasarId)
                        $produk->satuan_id = $satuanDasarId;

                    if(auth()->id())
                        $produk->last_edited_by = auth()->id();
                        
                    $produk->save();
                }

                $kategoriId = $this->getKategoriId($row['kategori']);
                
                if(count($kategoriId)>0 && $produk)
                    $produk->kategories()->sync($kategoriId, false);

                if(count($satuanLanjutan)>0 && $produk && $produk->multiSatuan()->count()<1)
                {
                    krsort($satuanLanjutan);
                    $multiple = 1;
                    $hargaBeli = floatval((trim($row['harga_beli']))?$row['harga_beli']:0);
                    $hargaJual = floatval((trim($row['harga_jual']))?$row['harga_jual']:0);
                    foreach($satuanLanjutan as $satuan)
                    {
                        $multiple *= $satuan['nilai_konversi'];
                        $produk->multiSatuan()->firstOrCreate(
                            ['satuan_lanjutan' => $satuan['id']],
                            ['nilai_konversi' => $multiple,'harga_beli' => $hargaBeli * $multiple,'harga_jual' => $hargaJual * $multiple,]
                        );
                    }
                }

                if(empty(trim($row['lokasi'])))
                    $row['lokasi'] = "Gudang Utama";
                
                $lokasiId = $this->getLokasiId($row['lokasi']);
                
                // $produk->persediaan()->firstOrCreate(
                //     [
                //         'satuan_id' => $satuanDasarId,
                //         'lokasi_id' => $lokasiId,
                //         'ref' => "",
                //         'no_batch' => $row['nobatch'], 
                //         'tgl_exp' => (trim($row['ed']))?Carbon::createFromFormat('d/m/Y', $row['ed'])->format('Y-m-d'):null,
                        
                //     ],
                //     [
                //         'harga_beli' => str_replace(",",".",$row['harga_beli']),
                //         'stok' => $row['stok'],
                //     ]
                // );

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
        
        $satuanDasarRecord = Models\Satuan::where('nama', $satuanDasar)->first();

        if(!$satuanDasarRecord){
            $satuanDasarRecord = Models\Satuan::create(['nama' => $satuanDasar, 'created_by' => auth()->id()]);
        }
            
        
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
                        $satuanLanjutanRecord = Models\Satuan::where('nama', $item[0])->first();
                        if(!$satuanLanjutanRecord)
                            $satuanLanjutanRecord = Models\Satuan::create(['nama'=>$item[0]]);

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
            $kategori = Models\Kategori::where('nama', Str::of($kategoriNama)->trim()->upper())->first();
            
            if(!$kategori)
                $kategori = Models\Kategori::create(['nama' => $kategoriNama, 'created_by' => auth()->id()]);

            array_push($kategoriId, $kategori->id);
        }
        return $kategoriId;
    }

    public function getLokasiId(?string $lokasiNama):int|null{
        if(!$lokasiNama)
            return null;

        if(empty(trim($lokasiNama)))
            return null;
        
        $lokasi = Models\Lokasi::where('nama', trim($lokasiNama))->first();
        if(!$lokasi)
        {
            $lokasi = Models\Lokasi::create(['nama'=>trim($lokasiNama), 'created_by' => auth()->id()]);
        }
        
        return ($lokasi)?->id;
    }

    public function startRow(): int
    {
        return 5;
    }
}
