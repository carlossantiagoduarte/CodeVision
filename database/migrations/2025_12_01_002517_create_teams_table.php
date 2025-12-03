<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();

            // Líder del equipo (usuario creador)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Evento relacionado
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            // Datos del equipo
            $table->string('name'); // Ahora sí coincide
            $table->string('leader_name');
            $table->string('leader_email');
            $table->string('leader_career');
            $table->string('leader_semester');
            $table->text('leader_experience')->nullable();

            $table->integer('max_members');
            $table->enum('visibility', ['Privado', 'Público']);

            // Requisitos del equipo
            $table->text('requirements')->nullable();

            // Código único para equipos privados
            $table->string('invite_code')->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
