<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleIdToDocumentsTable extends Migration
{
    public function up()
{
    // Ensure the column exists before adding the foreign key constraint
    if (!Schema::hasColumn('documents', 'role_id')) {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->default(1)->after('category_id');
        });
    }

    // Add the foreign key constraint
    Schema::table('documents', function (Blueprint $table) {
        $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('documents', function (Blueprint $table) {
        $table->dropForeign(['role_id']);
        $table->dropColumn('role_id');
    });
}



};
