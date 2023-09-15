<?php

namespace App\Models;

use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MultiSatuan extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'satuan_lanjutan', 'nilai_konversi'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan_lanjutan');
    }

}
