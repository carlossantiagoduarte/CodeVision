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
    Schema::table('events', function (Blueprint $table) {
        $table->string('banner_url')->nullable();
        $table->string('modality')->nullable(); 
        $table->string('registration_link')->nullable();
        $table->string('main_category')->nullable();
    });
}

public function down()
{
    Schema::table('events', function (Blueprint $table) {
        $table->dropColumn(['banner_url', 'modality', 'registration_link', 'main_category']);
    });
}

};
