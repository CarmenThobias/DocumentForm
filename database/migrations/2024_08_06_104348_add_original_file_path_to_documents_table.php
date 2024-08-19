<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginalFilePathToDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('original_file_name_doc')->nullable(); // Add for DOC/DOCX
            $table->string('original_file_name_pdf')->nullable(); // Add for PDF
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['original_file_name_doc', 'original_file_name_pdf']);
        });
    }
}
