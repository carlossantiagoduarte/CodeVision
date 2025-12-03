<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Paso 1
            $table->string('title');
            $table->string('organizer');
            $table->string('location');
            $table->text('description');
            $table->string('email');
            $table->string('phone');
            $table->integer('max_participants');
            $table->text('requirements')->nullable();

            // Paso 2
            $table->date('start_date');
            $table->date('end_date');
            $table->string('image_url')->nullable();
            $table->text('documents_info')->nullable();
            $table->time('start_time');
            $table->time('end_time');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
