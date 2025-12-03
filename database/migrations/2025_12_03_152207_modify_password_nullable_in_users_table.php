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
    Schema::table('users', function (Blueprint $table) {
        // Permitir que el campo 'password' sea nullable
        $table->string('password')->nullable()->change();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        // Restaurar el campo 'password' a no ser nullable
        $table->string('password')->nullable(false)->change();
    });
}

};
