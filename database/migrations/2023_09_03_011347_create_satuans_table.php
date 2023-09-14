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
        Schema::create('satuans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique();
            $table->boolean('digunakan')->default(true);
            $table->foreignId('created_by')
                ->nullable()
                ->constrained(
                    table: 'users', indexName: 'satuans_created_by'
                )
                ->nullOnDelete();
            $table->foreignId('last_edited_by')
                ->nullable()
                ->constrained(
                    table: 'users', indexName: 'satuans_last_edited_by'
                )
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satuans');
    }
};
