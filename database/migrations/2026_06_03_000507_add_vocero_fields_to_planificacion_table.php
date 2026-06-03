<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE planificacion MODIFY estatus ENUM('1','2','3','4','5') NOT NULL DEFAULT '1'");
        DB::statement("ALTER TABLE planificacion ADD COLUMN motivo_rechazo_vocero TEXT NULL AFTER estatus");
        DB::statement("ALTER TABLE planificacion ADD COLUMN id_firma_coordinador INT NULL");
        DB::statement("ALTER TABLE planificacion ADD COLUMN id_firma_vocero INT NULL");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE planificacion DROP COLUMN id_firma_vocero");
        DB::statement("ALTER TABLE planificacion DROP COLUMN id_firma_coordinador");
        DB::statement("ALTER TABLE planificacion DROP COLUMN motivo_rechazo_vocero");
        DB::statement("ALTER TABLE planificacion MODIFY estatus ENUM('1','2','3','4') NOT NULL DEFAULT '1'");
    }
};
