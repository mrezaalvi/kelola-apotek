<?php

namespace App\Models;

use App\Models\User;
use App\Models\Satuan;
use App\Models\Kategori;
use App\Models\MultiSatuan;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'barcode', 'nama', 'satuan_id', 'deskripsi', 'pabrik', 'kemasan', 'minimal_stok', 'harga_beli', 'harga_jual', 'diskon', 'margin_harga', 'digunakan', 'created_by', 'last_edited_by'];

    public function nama(): Attribute
    {
        return new Attribute(
            set: fn($value) => Str::of($value)->trim()->upper(),
        );
    }

    public function kode(): Attribute
    {
        return new Attribute(
            set: fn($value) => ($value)?Str::of($value)->trim():$value,
        );
    }

    public function barcode(): Attribute
    {
        return new Attribute(
            set: fn($value) => ($value)?Str::of($value)->trim():$value,
        );
    }

    public function hargaBeli(): Attribute
    {
        return new Attribute(
            set: fn($value) => ($value)?str_replace(",",".",$value):$value,
        );
    }

    public function hargaJual(): Attribute
    {
        return new Attribute(
            set: fn($value) => ($value)?str_replace(",",".",$value):$value,
        );
    }

    public function marginHarga(): Attribute
    {
        return new Attribute(
            get: fn($value) => floatval($value),
            set: fn($value) => ($value)?str_replace(",",".",$value):$value,
        );
    }

    public function minimalStok(): Attribute
    {
        return new Attribute(
            set:fn($value) => ($value)?$value:0,
        );
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function multiSatuan(): HasMany
    {
        return $this->hasMany(MultiSatuan::class);
    }

    public function kategories(): BelongsToMany
    {
        return $this->belongsToMany(Kategori::class, 'produk_kategori');
    }

    public function persediaan(): HasMany
    {
        return $this->hasMany(Persediaan::class);
    }
    
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lastEditedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }
}
