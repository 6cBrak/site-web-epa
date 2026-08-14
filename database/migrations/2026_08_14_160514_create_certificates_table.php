<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('antenne_id')->constrained()->cascadeOnDelete();
            $table->string('learner_name');
            $table->date('issued_at');
            $table->enum('status', ['valide', 'revoque'])->default('valide');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
