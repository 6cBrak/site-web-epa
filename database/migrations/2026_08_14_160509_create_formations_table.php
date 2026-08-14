<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained()->cascadeOnDelete();
            $table->string('title_fr');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->text('objectives_fr')->nullable();
            $table->text('objectives_en')->nullable();
            $table->text('modules_fr')->nullable();
            $table->text('modules_en')->nullable();
            $table->text('prerequisites_fr')->nullable();
            $table->text('prerequisites_en')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('published')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
