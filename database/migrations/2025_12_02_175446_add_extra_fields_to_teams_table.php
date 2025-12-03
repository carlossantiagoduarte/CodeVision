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
    Schema::table('teams', function (Blueprint $table) {
$table->string('team_logo')->nullable();
$table->text('description')->nullable();
$table->text('skills_needed')->nullable();


    });
}

public function down()
{
    Schema::table('teams', function (Blueprint $table) {
        $table->dropColumn(['team_logo', 'description', 'skills_needed']);
    });
}

};
