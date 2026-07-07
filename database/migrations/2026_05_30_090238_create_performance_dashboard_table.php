<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_dashboard', function (Blueprint $table) {
            $table->id('dashboard_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('strand_id');
            $table->float('average_score')->default(0.0);
            $table->integer('missions_completed')->default(0);
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('strand_id')
                  ->references('strand_id')
                  ->on('strands')
                  ->onDelete('cascade');

            $table->unique(['user_id', 'strand_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_dashboard');
    }
};
