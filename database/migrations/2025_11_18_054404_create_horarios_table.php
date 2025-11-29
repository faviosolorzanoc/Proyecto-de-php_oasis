<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('espacio_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('estado', ['disponible', 'ocupado', 'bloqueado'])->default('disponible');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->unique(['espacio_id', 'fecha', 'hora_inicio', 'hora_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};