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
        Schema::create('multi_satuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')
                ->constrained();
            $table->foreignId('satuan_lanjutan')
                ->constrained(
                    table: 'satuans', indexName: 'produk_multi_satuan'
                );
            $table->unsignedInteger('nilai_konversi')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multi_satuans');
    }
};
