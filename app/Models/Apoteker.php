<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apoteker extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 
        'alamat', 
        'no_telp', 
        'email', 
        'stra_no',
        'stra_exp_date',
        'stra_file',
        'sipa_no',
        'sipa_exp_date',
        'sipa_file', 
        'digunakan', 
        'created_by',
        'last_edited_by',
    ];

    public function straExpDate(): Attribute
    {
        return new Attribute(
            // get: fn ($value) => strtoupper($value),
            set: fn ($value) => ($value)?Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d'):$value,
        );
    }

    public function sipaExpDate(): Attribute
    {
        return new Attribute(
            // get: fn ($value) => strtoupper($value),
            set: fn ($value) => ($value)?Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d'):$value,
        );
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
