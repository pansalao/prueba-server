<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('planificacion', function (Blueprint $table) {
            $table->text('motivo_rechazo_vocero')->nullable()->after('archivo_contrato')->comment('Motivo de rechazo por parte del vocero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planificacion', function (Blueprint $table) {
            $table->dropColumn('motivo_rechazo_vocero');
        });
    }
};
