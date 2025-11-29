<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar reserva_id a pedidos
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('reserva_id')->nullable()->after('user_id')->constrained('reservas')->onDelete('set null');
        });

        // Agregar reserva_id a horarios
        Schema::table('horarios', function (Blueprint $table) {
            $table->foreignId('reserva_id')->nullable()->after('estado')->constrained('reservas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['reserva_id']);
            $table->dropColumn('reserva_id');
        });

        Schema::table('horarios', function (Blueprint $table) {
            $table->dropForeign(['reserva_id']);
            $table->dropColumn('reserva_id');
        });
    }
};