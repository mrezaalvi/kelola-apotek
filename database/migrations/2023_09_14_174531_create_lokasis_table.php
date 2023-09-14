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
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique();
            $table->enum('jenis', ['gudang', 'rak']);
            $table->boolean('digunakan')->default(true);
            $table->foreignId('created_by')
                ->nullable()
                ->constrained(
                    table: 'users', indexName: 'lokasi_created_by',
                )
                ->onDeleteNull();
            $table->foreignId('last_edited_by')
                ->nullable()
                ->constrained(
                    table: 'users', indexName: 'lokasi_last_edited_by',
                )
                ->onDeleteNull();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasis');
    }
};
