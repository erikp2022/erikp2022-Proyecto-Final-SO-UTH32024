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
        Schema::create('lara_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lara_language_id');
            $table->string('key');
            $table->string('locale');
            $table->string('group');
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lara_translations');
    }
};
