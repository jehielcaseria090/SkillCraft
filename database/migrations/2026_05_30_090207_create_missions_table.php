<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->id('mission_id');
            $table->unsignedBigInteger('module_id');
            $table->string('mission_title');
            $table->text('scenario_description');
            $table->integer('max_score')->default(100);
            $table->integer('difficulty_level')->default(1);
            $table->timestamps();

            $table->foreign('module_id')
                  ->references('module_id')
                  ->on('modules')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
