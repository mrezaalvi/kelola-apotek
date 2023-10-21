<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
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

    protected $fillable = ['produk_id', 'lokasi_id', 'satuan_id', 'ref','no_batch', 'tgl_exp', 'harga_beli', 'stok', 'created_by', 'last_edited_by'];

    public function stok(): Attribute
    {
        return new Attribute(
            get: fn($value)=>toDBDecimalFormat($value),
        );
    }
    
    public function hargaBeli(): Attribute
    {
        return new Attribute(
            get: fn($value)=>toDBDecimalFormat($value),
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

    /**
     * Get the created by that owns the Persediaan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the last edited by that owns the Persediaan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastEditedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }
}
