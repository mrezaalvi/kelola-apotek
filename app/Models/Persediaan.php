<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Lokasi;
use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Persediaan extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'lokasi_id', 'satuan_id', 'ref','no_batch', 'tgl_exp', 'harga_beli', 'stok'];

    // public function tglExp(): Attribute
    // {
    //     return new Attribute(
    //         set: fn($value)=>(trim($value))?Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d'):null,
    //     );
    // }

    public function stok(): Attribute
    {
        return new Attribute(
            get: fn($value)=>floatval($value),
        );
    }

    public function produks(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function lokasis(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function satuans(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
