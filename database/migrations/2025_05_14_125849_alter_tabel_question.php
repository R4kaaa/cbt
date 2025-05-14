<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('question_image')->nullable()->after('question');
            $table->string('audio_file')->nullable()->after('question_image');
            $table->enum('media_type', ['none', 'image', 'audio'])->default('none')->after('answers');
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('question_image');
            $table->dropColumn('audio_file');
            $table->dropColumn('media_type');
        });
    }
};
