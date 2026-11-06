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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->string('seat_code'); // Contoh: A1, A2, B10
            $table->string('row');       // Contoh: A, B, C
            $table->integer('number');   // Contoh: 1, 2, 3
            $table->string('type')->default('regular'); // regular, sweetbox, vip
            $table->timestamps();

            // Mencegah kode kursi duplikat di studio yang sama
            $table->unique(['studio_id', 'seat_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
