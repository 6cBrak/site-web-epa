<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('antenne_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('modality', ['en_ligne', 'presentiel_jour', 'presentiel_soir'])->default('presentiel_jour');
            $table->unsignedInteger('capacity')->nullable();
            $table->unsignedInteger('seats_taken')->default(0);
            $table->enum('status', ['ouverte', 'complete', 'cloturee'])->default('ouverte');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formation_sessions');
    }
};
