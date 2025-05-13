<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update answers table to include fields for multiple-choice questions
        Schema::table('answers', function (Blueprint $table) {
            // Add selected_answers field for multiple-choice questions
            $table->json('selected_answers')->nullable()->after('answer');

            // Modify is_correct to include partial correct option
            DB::statement("ALTER TABLE answers MODIFY COLUMN is_correct ENUM('Y','N','P') NOT NULL DEFAULT 'N'");

            // Add score field for partial credits
            $table->decimal('score', 5, 2)->default(0)->after('is_correct');
        });

        // Update grades table to include total_score field
        Schema::table('grades', function (Blueprint $table) {
            // Add total_score to store sum of all answer scores
            $table->decimal('total_score', 8, 2)->default(0)->after('total_correct');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert answers table changes
        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('selected_answers');
            $table->dropColumn('score');
            // Note: Changing ENUM back would require another DB::statement
        });

        // Revert grades table changes
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn('total_score');
        });
    }
};
