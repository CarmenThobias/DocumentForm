<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('faq_questions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('faq_subject_id');  // Foreign key to FAQ subjects
        $table->string('title');
        $table->text('content');
        $table->timestamps();

        // Foreign key constraint
        $table->foreign('faq_subject_id')->references('id')->on('faq_subjects')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_questions');
    }
};
