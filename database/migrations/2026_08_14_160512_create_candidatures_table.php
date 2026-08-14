<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('formation_session_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('antenne_id')->constrained()->cascadeOnDelete();
            $table->foreignId('promo_code_id')->nullable()->constrained()->nullOnDelete();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('education_level')->nullable();
            $table->string('nationality')->nullable();
            $table->string('city_country')->nullable();
            $table->enum('profile_type', ['etudiant', 'professionnel'])->default('etudiant');
            $table->string('cv_path')->nullable();
            $table->enum('start_preference', ['immediat', 'prochaine_rentree', 'session_specialisee'])->nullable();
            $table->string('how_heard')->nullable();
            $table->text('comment')->nullable();

            $table->enum('status', ['nouvelle', 'contactee', 'confirmee', 'refusee'])->default('nouvelle');
            $table->string('tracking_token')->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
