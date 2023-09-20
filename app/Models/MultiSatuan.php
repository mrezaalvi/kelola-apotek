<?php

namespace App\Models;

use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MultiSatuan extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'satuan_lanjutan', 'nilai_konversi', 'harga_beli','harga_jual', 'diskon', 'diskon2'];

    public function hargaBeli(): Attribute
    {
        return new Attribute(
            set: fn($value) => toDBDecimalFormat($value),
        );
    }

    public function hargaJual(): Attribute
    {
        return new Attribute(
            set: fn($value) => toDBDecimalFormat($value),
        );
    }

    public function marginHarga(): Attribute
    {
        return new Attribute(
            get: fn($value) => floatval($value),
            set: fn($value) => toDBDecimalFormat($value),
        );
    }

    public function diskon(): Attribute
    {
        return new Attribute(
            get: fn($value) => floatval($value),
            set: fn($value) => toDBDecimalFormat($value),
        );
    }

    public function diskon2(): Attribute
    {
        return new Attribute(
            get: fn($value) => floatval($value),
            set: fn($value) => toDBDecimalFormat($value),
        );
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan_lanjutan');
    }

}
