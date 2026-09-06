<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('misi_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('attempts')->cascadeOnDelete();
            // lab-mi | match | organ-materi | organ-kuis | jalur
            $table->string('misi', 20);
            $table->unsignedInteger('benar')->default(0);
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('skor')->default(0); // 0-100
            $table->json('data')->nullable();
            $table->timestamps();
            $table->unique(['attempt_id', 'misi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('misi_results');
    }
};
