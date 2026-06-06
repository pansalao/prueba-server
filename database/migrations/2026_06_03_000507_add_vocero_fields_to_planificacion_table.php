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
        
        if (!Schema::hasColumn('planificacion', 'motivo_rechazo_vocero')) {
            DB::statement("ALTER TABLE planificacion ADD COLUMN motivo_rechazo_vocero TEXT NULL AFTER estatus");
        }
        if (!Schema::hasColumn('planificacion', 'id_firma_coordinador')) {
            DB::statement("ALTER TABLE planificacion ADD COLUMN id_firma_coordinador INT NULL");
        }
        if (!Schema::hasColumn('planificacion', 'id_firma_vocero')) {
            DB::statement("ALTER TABLE planificacion ADD COLUMN id_firma_vocero INT NULL");
        }

        if (!Schema::hasColumn('vocero', 'notificado')) {
            DB::statement("ALTER TABLE vocero ADD COLUMN notificado TINYINT(1) DEFAULT 0 NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE vocero DROP COLUMN notificado");
        DB::statement("ALTER TABLE planificacion DROP COLUMN id_firma_vocero");
        DB::statement("ALTER TABLE planificacion DROP COLUMN id_firma_coordinador");
        DB::statement("ALTER TABLE planificacion DROP COLUMN motivo_rechazo_vocero");
        DB::statement("ALTER TABLE planificacion MODIFY estatus ENUM('1','2','3','4') NOT NULL DEFAULT '1'");
    }
};
