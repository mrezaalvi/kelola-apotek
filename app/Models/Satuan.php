<?php

namespace App\Models;

use App\Models\User;
use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satuan extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'digunakan', 'created_by', 'last_edited_by'];

    public function nama(): Attribute
    {
        return new Attribute(
            set: fn($value) => Str::upper($value), 
        );
    }

    public function produks(): HasMany{
        return $this->hasMany(Produk::class);
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
