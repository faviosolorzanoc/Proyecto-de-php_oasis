<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('espacio_id')->constrained()->onDelete('cascade');
            $table->date('fecha_evento');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('num_personas');
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])->default('pendiente');
            $table->enum('metodo_pago', ['efectivo', 'yape', 'tarjeta'])->nullable();
            $table->enum('estado_pago', ['pendiente', 'pagado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->text('servicios_adicionales')->nullable(); // JSON de servicios extras
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};