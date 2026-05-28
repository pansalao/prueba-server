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
        Schema::table('vocero', function (Blueprint $table) {
            $table->dropUnique('unique_vocero_seccion');
            $table->tinyInteger('tipo_vocero')->default(1)->comment('1: Principal, 2: Secundario, 3: Terciario')->after('id_coordinador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vocero', function (Blueprint $table) {
            $table->dropColumn('tipo_vocero');
            // Nota: no podemos restaurar fácilmente el unique si ya hay duplicados,
            // pero para revertir la estructura básica:
            $table->unique('id_seccion', 'unique_vocero_seccion');
        });
    }
};
