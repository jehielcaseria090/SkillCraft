<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id('module_id');
            $table->unsignedBigInteger('strand_id');
            $table->string('module_name');
            $table->string('competency_area');
            $table->timestamps();

            $table->foreign('strand_id')
                  ->references('strand_id')
                  ->on('strands')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
