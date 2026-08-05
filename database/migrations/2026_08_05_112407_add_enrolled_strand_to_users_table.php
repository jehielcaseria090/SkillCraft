<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only meaningful for role = 'student'. Set automatically to the
            // creating teacher's strand when added via the CMS, or chosen
            // manually by admin. Nullable because students who self-register
            // through Unity have no strand until they actually play a mission
            // (see PlayerController::index — it also checks assessment history
            // as a fallback for these unlabeled accounts).
            $table->string('enrolled_strand')->nullable()->after('specialization');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('enrolled_strand');
        });
    }
};
