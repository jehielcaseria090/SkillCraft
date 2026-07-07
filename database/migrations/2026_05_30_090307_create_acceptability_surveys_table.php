<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acceptability_surveys', function (Blueprint $table) {
            $table->id('survey_id');
            $table->unsignedBigInteger('user_id');
            $table->float('usability_rating')->default(0.0);
            $table->float('interface_rating')->default(0.0);
            $table->float('educational_value')->default(0.0);
            $table->float('curriculum_alignment')->default(0.0);
            $table->float('overall_rating')->default(0.0);
            $table->text('comments')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acceptability_surveys');
    }
};
