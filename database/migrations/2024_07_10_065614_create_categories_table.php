<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing UNSIGNED BIGINT primary key
            $table->string('name')->unique(); // Adds a 'name' column with a unique constraint
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}