<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id('assessment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mission_id');
            $table->enum('assessment_type', ['pre_test', 'post_test', 'practice']);
            $table->integer('score')->default(0);
            $table->float('accuracy_percentage')->default(0.0);
            $table->timestamp('taken_at')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('mission_id')
                  ->references('mission_id')
                  ->on('missions')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
