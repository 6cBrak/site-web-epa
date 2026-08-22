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
        Schema::table('assistant_leads_captures', function (Blueprint $table) {
            $table->enum('priority', ['chaud', 'tiede', 'froid'])->default('tiede')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistant_leads_captures', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
};
