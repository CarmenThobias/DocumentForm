<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToDocumentsTable extends Migration
{
    public function up()
{
    Schema::create('subcategories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->unsignedBigInteger('category_id');
        $table->timestamps();
    
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
    });
    
}

public function down()
{
    Schema::table('subcategories', function (Blueprint $table) {
     
        $table->dropForeign(['category_id']);
       
    });
}

};
