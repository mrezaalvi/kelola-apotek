<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('persediaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')
                ->constrained();
            $table->foreignId('lokasi_id')
                ->constrained();
            $table->foreignId('satuan_id')
                ->constrained();
            $table->string('no_batch', 50)
                ->nullable();
            $table->date('tgl_exp')
                ->nullable();
            $table->decimal('harga_beli', 12, 2)
                ->default(0);
            $table->decimal('stok', 7, 2)
                ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persediaans');
    }
};
