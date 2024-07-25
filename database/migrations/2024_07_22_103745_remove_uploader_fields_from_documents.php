<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUploaderFieldsFromDocuments extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['uploader_name', 'uploader_title']);
        });
    }
    
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('uploader_name');
            $table->string('uploader_title');
        });
    }
    
}
