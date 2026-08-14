<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formation_antenne', function (Blueprint $table) {
            $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('antenne_id')->constrained()->cascadeOnDelete();
            $table->primary(['formation_id', 'antenne_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formation_antenne');
    }
};
