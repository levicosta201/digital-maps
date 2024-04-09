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
        if (!Schema::hasTable('points')) {
            Schema::create('points', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->string('name');
                $table->integer('latitude');
                $table->integer('longitude');
                $table->time('open_hour')->nullable();
                $table->time('close_hour')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
