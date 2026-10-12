<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('title_fr');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->string('type', 50)->default('autre');
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->foreignId('antenne_id')->nullable()->constrained()->nullOnDelete();
            $table->string('location')->nullable();
            $table->string('online_url')->nullable();
            $table->boolean('registration_open')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('published')->default(false);
            $table->timestamps();
        });

        Schema::create('evenement_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evenement_id')->constrained()->cascadeOnDelete();
            $table->string('title_fr');
            $table->string('title_en');
            $table->dateTime('starts_at')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('evenement_inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evenement_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone', 50);
            $table->string('profession');
            $table->string('nationality', 100)->nullable();
            $table->string('city');
            $table->string('epa_status', 30)->default('non');
            $table->string('epa_status_other')->nullable();
            $table->string('how_heard');
            $table->text('comment')->nullable();
            $table->string('token', 40)->unique();
            $table->timestamps();
        });

        Schema::create('evenement_inscription_session', function (Blueprint $table) {
            $table->foreignId('evenement_inscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evenement_session_id')->constrained()->cascadeOnDelete();
            $table->primary(['evenement_inscription_id', 'evenement_session_id'], 'inscription_session_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evenement_inscription_session');
        Schema::dropIfExists('evenement_inscriptions');
        Schema::dropIfExists('evenement_sessions');
        Schema::dropIfExists('evenements');
    }
};
