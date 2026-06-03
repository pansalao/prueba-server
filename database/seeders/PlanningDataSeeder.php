<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanningDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 1. RECURSOS EDUCATIVOS ---
        $recursos = [
            ['nombre_recurso' => 'Video Beam / Proyector'],
            ['nombre_recurso' => 'Pizarra Acrílica y Marcadores'],
            ['nombre_recurso' => 'Laboratorio de Computación'],
            ['nombre_recurso' => 'Plataforma Moodle / Aula Virtual'],
            ['nombre_recurso' => 'Internet / Conexión Wi-Fi'],
            ['nombre_recurso' => 'Material Impreso (Guías/Libros)'],
            ['nombre_recurso' => 'Software Especializado (IDE/Simuladores)'],
            ['nombre_recurso' => 'Infografías y Mapas Mentales'],
            ['nombre_recurso' => 'Laptops / Tablets'],
            ['nombre_recurso' => 'Videos Educativos / YouTube'],
        ];
        foreach ($recursos as $r) {
            DB::table('recurso')->updateOrInsert(['nombre_recurso' => $r['nombre_recurso']], $r);
        }

        // --- 2. ESTRATEGIAS PEDAGÓGICAS (tecnica_actividad) ---
        $estrategias = [
            ['nombre_tecnica_actividad' => 'Aprendizaje Basado en Proyectos (ABP)'],
            ['nombre_tecnica_actividad' => 'Clase Magistral Participativa'],
            ['nombre_tecnica_actividad' => 'Debate y Discusión Dirigida'],
            ['nombre_tecnica_actividad' => 'Resolución de Problemas / Algoritmos'],
            ['nombre_tecnica_actividad' => 'Estudio de Casos Reales'],
            ['nombre_tecnica_actividad' => 'Aula Invertida (Flipped Classroom)'],
            ['nombre_tecnica_actividad' => 'Gamificación Educativa'],
            ['nombre_tecnica_actividad' => 'Aprendizaje Cooperativo'],
        ];
        foreach ($estrategias as $e) {
            DB::table('tecnica_actividad')->updateOrInsert(['nombre_tecnica_actividad' => $e['nombre_tecnica_actividad']], $e);
        }

        // --- 3. TÉCNICAS DE EVALUACIÓN ---
        $tecnicasEval = [
            ['nombre_tecnica_evaluacion' => 'Observación Directa'],
            ['nombre_tecnica_evaluacion' => 'Análisis de Producción Escrita'],
            ['nombre_tecnica_evaluacion' => 'Exposición Oral'],
            ['nombre_tecnica_evaluacion' => 'Prueba Práctica en Computador'],
            ['nombre_tecnica_evaluacion' => 'Portafolio de Evidencias'],
            ['nombre_tecnica_evaluacion' => 'Defensa de Proyecto'],
            ['nombre_tecnica_evaluacion' => 'Cuestionario / Examen'],
            ['nombre_tecnica_evaluacion' => 'Rúbricas de Evaluación'],
            ['nombre_tecnica_evaluacion' => 'Mapa Mental / Conceptual'],
        ];
        foreach ($tecnicasEval as $te) {
            DB::table('tecnica_evaluacion')->updateOrInsert(['nombre_tecnica_evaluacion' => $te['nombre_tecnica_evaluacion']], $te);
        }

        // --- 4. INSTRUMENTOS DE EVALUACIÓN ---
        $instrumentos = [
            ['nombre_instrumento' => 'Escala de Estimación'],
            ['nombre_instrumento' => 'Lista de Cotejo'],
            ['nombre_instrumento' => 'Rúbrica Holística'],
            ['nombre_instrumento' => 'Guía de Observación'],
            ['nombre_instrumento' => 'Prueba Objetiva'],
        ];
        foreach ($instrumentos as $inst) {
            DB::table('instrumento')->updateOrInsert(['nombre_instrumento' => $inst['nombre_instrumento']], $inst);
        }

        // --- 5. TIPOS DE EVALUACIÓN ---
        $tiposEval = [
            ['nombre_tipo_evaluacion' => 'Evaluación Diagnóstica'],
            ['nombre_tipo_evaluacion' => 'Evaluación Formativa'],
            ['nombre_tipo_evaluacion' => 'Evaluación Sumativa'],
            ['nombre_tipo_evaluacion' => 'Autoevaluación'],
            ['nombre_tipo_evaluacion' => 'Coevaluación'],
            ['nombre_tipo_evaluacion' => 'Heteroevaluación'],
        ];
        foreach ($tiposEval as $tve) {
            DB::table('tipo_evaluacion')->updateOrInsert(['nombre_tipo_evaluacion' => $tve['nombre_tipo_evaluacion']], $tve);
        }

        // --- 6. BIBLIOGRAFÍAS ---
        $bibliografias = [
            ['nombre_bibliografia' => 'Pressman, R. S. (2010). Ingeniería del software: un enfoque práctico.'],
            ['nombre_bibliografia' => 'Sommerville, I. (2011). Ingeniería de software.'],
            ['nombre_bibliografia' => 'Martin, R. C. (2008). Clean Code.'],
            ['nombre_bibliografia' => 'Tanenbaum, A. S. (2012). Redes de Computadoras.'],
        ];
        foreach ($bibliografias as $b) {
            DB::table('bibliografia')->updateOrInsert(['nombre_bibliografia' => $b['nombre_bibliografia']], $b);
        }

        // --- 7. TEMAS, OBJETIVOS Y CONTENIDOS (Para múltiples Unidades Curriculares) ---
        $materiasIds = ['1', '2', '3', '4', '5', '6']; // IDs detectados en SOGAC
        
        $unidadesMaster = [
            ['u' => '1', 't' => 'Unidad 1: Fundamentos y Evolución', 'objs' => [
                ['o' => 'Comprender los conceptos básicos y el ciclo de vida.', 'cont' => ['Definiciones', 'Modelos de Proceso']]
            ]],
            ['u' => '2', 't' => 'Unidad 2: Análisis y Requisitos', 'objs' => [
                ['o' => 'Identificar y documentar requisitos del sistema.', 'cont' => ['Elicitación', 'IEEE 830']]
            ]],
            ['u' => '3', 't' => 'Unidad 3: Diseño y Arquitectura', 'objs' => [
                ['o' => 'Aplicar patrones de diseño y modelado UML.', 'cont' => ['Diagramas de Clase', 'Patrones']]
            ]],
            ['u' => '4', 't' => 'Unidad 4: Calidad y Pruebas', 'objs' => [
                ['o' => 'Asegurar la calidad mediante pruebas sistemáticas.', 'cont' => ['Caja Blanca/Negra', 'SQA']]
            ]],
        ];

        foreach ($materiasIds as $materiaId) {
            foreach ($unidadesMaster as $uData) {
                // Tema
                DB::table('tema_unidad')->updateOrInsert(
                    ['titulo_tema' => $uData['t'] . " (Mat: $materiaId)"],
                    ['id_unidad_curricular' => $materiaId, 'unidad_tema' => $uData['u'], 'estatus' => '1']
                );
                $temaId = DB::table('tema_unidad')->where('titulo_tema', $uData['t'] . " (Mat: $materiaId)")->value('id_tema_unidad');

                foreach ($uData['objs'] as $objData) {
                    // Objetivo
                    DB::table('objetivo')->updateOrInsert(
                        ['titulo_objetivo' => $objData['o'] . " - T$temaId"],
                        ['id_tema_unidad' => $temaId, 'estatus' => '1']
                    );
                    $objId = DB::table('objetivo')->where('titulo_objetivo', $objData['o'] . " - T$temaId")->value('id_objetivo');

                    foreach ($objData['cont'] as $contTxt) {
                        // Contenido
                        DB::table('contenido')->updateOrInsert(
                            ['titulo_contenido' => $contTxt],
                            ['estatus' => '1']
                        );
                        $contId = DB::table('contenido')->where('titulo_contenido', $contTxt)->value('id_contenido');

                        // Relación
                        DB::table('detalle_objetivo')->updateOrInsert(
                            ['id_contenido' => $contId, 'id_objetivo' => $objId],
                            ['estatus' => '1']
                        );
                    }
                }
            }
        }

        // --- 8. PERMISOS DEL SISTEMA ---
        $permisos = [
            ['id_permiso' => 1, 'nombre_permiso' => 'Index de Perfil', 'estatus' => '1'],
            ['id_permiso' => 2, 'nombre_permiso' => 'Index de Seleccionar Rol', 'estatus' => '1'],
            ['id_permiso' => 3, 'nombre_permiso' => 'Listar de Contenido', 'estatus' => '1'],
            ['id_permiso' => 4, 'nombre_permiso' => 'Crear de Contenido', 'estatus' => '1'],
            ['id_permiso' => 5, 'nombre_permiso' => 'Editar de Contenido', 'estatus' => '1'],
            ['id_permiso' => 6, 'nombre_permiso' => 'Ver Detalles de Contenido', 'estatus' => '1'],
            ['id_permiso' => 7, 'nombre_permiso' => 'Listar de Tema', 'estatus' => '1'],
            ['id_permiso' => 8, 'nombre_permiso' => 'Crear de Tema', 'estatus' => '1'],
            ['id_permiso' => 9, 'nombre_permiso' => 'Editar de Tema', 'estatus' => '1'],
            ['id_permiso' => 10, 'nombre_permiso' => 'Ver Detalles de Tema', 'estatus' => '1'],
            ['id_permiso' => 13, 'nombre_permiso' => 'Listar de Planificacion', 'estatus' => '1'],
            ['id_permiso' => 14, 'nombre_permiso' => 'Crear de Planificacion', 'estatus' => '1'],
            ['id_permiso' => 15, 'nombre_permiso' => 'Editar de Planificacion', 'estatus' => '1'],
            ['id_permiso' => 16, 'nombre_permiso' => 'Ver Detalles de Planificacion', 'estatus' => '1'],
            ['id_permiso' => 17, 'nombre_permiso' => 'Reporte Cumplimiento de Planificacion', 'estatus' => '1'],
            ['id_permiso' => 18, 'nombre_permiso' => 'Reporte General de Planificacion', 'estatus' => '1'],
            ['id_permiso' => 19, 'nombre_permiso' => 'Reporte Detallado de Planificacion', 'estatus' => '1'],
            ['id_permiso' => 20, 'nombre_permiso' => 'Reporte de Calendario', 'estatus' => '1'],
            ['id_permiso' => 21, 'nombre_permiso' => 'Acuerdo Aprendizaje de Planificacion', 'estatus' => '1'],
            ['id_permiso' => 22, 'nombre_permiso' => 'Listar de Indicador Logro', 'estatus' => '1'],
            ['id_permiso' => 23, 'nombre_permiso' => 'Crear de Indicador Logro', 'estatus' => '1'],
            ['id_permiso' => 24, 'nombre_permiso' => 'Editar de Indicador Logro', 'estatus' => '1'],
            ['id_permiso' => 25, 'nombre_permiso' => 'Ver Detalles de Indicador Logro', 'estatus' => '1'],
            ['id_permiso' => 26, 'nombre_permiso' => 'Listar de Bibliografia', 'estatus' => '1'],
            ['id_permiso' => 27, 'nombre_permiso' => 'Crear de Bibliografia', 'estatus' => '1'],
            ['id_permiso' => 28, 'nombre_permiso' => 'Editar de Bibliografia', 'estatus' => '1'],
            ['id_permiso' => 29, 'nombre_permiso' => 'Ver Detalles de Bibliografia', 'estatus' => '1'],
            ['id_permiso' => 30, 'nombre_permiso' => 'Listar de Recurso', 'estatus' => '1'],
            ['id_permiso' => 31, 'nombre_permiso' => 'Crear de Recurso', 'estatus' => '1'],
            ['id_permiso' => 32, 'nombre_permiso' => 'Editar de Recurso', 'estatus' => '1'],
            ['id_permiso' => 33, 'nombre_permiso' => 'Ver Detalles de Recurso', 'estatus' => '1'],
            ['id_permiso' => 34, 'nombre_permiso' => 'Listar de Estrategia', 'estatus' => '1'],
            ['id_permiso' => 35, 'nombre_permiso' => 'Crear de Estrategia', 'estatus' => '1'],
            ['id_permiso' => 36, 'nombre_permiso' => 'Editar de Estrategia', 'estatus' => '1'],
            ['id_permiso' => 37, 'nombre_permiso' => 'Ver Detalles de Estrategia', 'estatus' => '1'],
            ['id_permiso' => 38, 'nombre_permiso' => 'Listar de Tecnica Evaluacion', 'estatus' => '1'],
            ['id_permiso' => 39, 'nombre_permiso' => 'Crear de Tecnica Evaluacion', 'estatus' => '1'],
            ['id_permiso' => 40, 'nombre_permiso' => 'Editar de Tecnica Evaluacion', 'estatus' => '1'],
            ['id_permiso' => 41, 'nombre_permiso' => 'Ver Detalles de Tecnica Evaluacion', 'estatus' => '1'],
            ['id_permiso' => 42, 'nombre_permiso' => 'Listar de Tipo Evaluacion', 'estatus' => '1'],
            ['id_permiso' => 43, 'nombre_permiso' => 'Crear de Tipo Evaluacion', 'estatus' => '1'],
            ['id_permiso' => 44, 'nombre_permiso' => 'Editar de Tipo Evaluacion', 'estatus' => '1'],
            ['id_permiso' => 45, 'nombre_permiso' => 'Ver Detalles de Tipo Evaluacion', 'estatus' => '1'],
            ['id_permiso' => 46, 'nombre_permiso' => 'Listar de Evento', 'estatus' => '1'],
            ['id_permiso' => 47, 'nombre_permiso' => 'Crear de Evento', 'estatus' => '1'],
            ['id_permiso' => 48, 'nombre_permiso' => 'Editar de Evento', 'estatus' => '1'],
            ['id_permiso' => 49, 'nombre_permiso' => 'Ver Detalles de Evento', 'estatus' => '1'],
            ['id_permiso' => 50, 'nombre_permiso' => 'Listar de Calendario', 'estatus' => '1'],
            ['id_permiso' => 51, 'nombre_permiso' => 'Crear de Calendario', 'estatus' => '1'],
            ['id_permiso' => 52, 'nombre_permiso' => 'Ver Detalles de Calendario', 'estatus' => '1'],
            ['id_permiso' => 53, 'nombre_permiso' => 'Editar de Calendario', 'estatus' => '1'],
            ['id_permiso' => 54, 'nombre_permiso' => 'Listar de Color', 'estatus' => '3'],
            ['id_permiso' => 55, 'nombre_permiso' => 'Crear de Color', 'estatus' => '3'],
            ['id_permiso' => 56, 'nombre_permiso' => 'Editar de Color', 'estatus' => '3'],
            ['id_permiso' => 57, 'nombre_permiso' => 'Ver Detalles de Color', 'estatus' => '3'],
            ['id_permiso' => 58, 'nombre_permiso' => 'Listar de Permiso', 'estatus' => '1'],
            ['id_permiso' => 59, 'nombre_permiso' => 'Editar de Permiso', 'estatus' => '1'],
            ['id_permiso' => 60, 'nombre_permiso' => 'Listar de Bitacora', 'estatus' => '1'],
            ['id_permiso' => 61, 'nombre_permiso' => 'Ver Detalles de Bitacora', 'estatus' => '1'],
            ['id_permiso' => 62, 'nombre_permiso' => 'Cambiar Estatus de Perfil', 'estatus' => '1'],
            ['id_permiso' => 63, 'nombre_permiso' => 'Cambiar Estatus de Seleccionar Rol', 'estatus' => '1'],
            ['id_permiso' => 64, 'nombre_permiso' => 'Cambiar Estatus de Contenido', 'estatus' => '1'],
            ['id_permiso' => 65, 'nombre_permiso' => 'Cambiar Estatus de Tema', 'estatus' => '1'],
            ['id_permiso' => 67, 'nombre_permiso' => 'Cambiar Estatus de Planificacion', 'estatus' => '1'],
            ['id_permiso' => 68, 'nombre_permiso' => 'Cambiar Estatus de Calendario', 'estatus' => '1'],
            ['id_permiso' => 69, 'nombre_permiso' => 'Cambiar Estatus de Indicador Logro', 'estatus' => '1'],
            ['id_permiso' => 70, 'nombre_permiso' => 'Cambiar Estatus de Bibliografia', 'estatus' => '1'],
            ['id_permiso' => 71, 'nombre_permiso' => 'Cambiar Estatus de Recurso', 'estatus' => '1'],
            ['id_permiso' => 72, 'nombre_permiso' => 'Cambiar Estatus de Estrategia', 'estatus' => '1'],
            ['id_permiso' => 73, 'nombre_permiso' => 'Cambiar Estatus de Tecnica Evaluacion', 'estatus' => '1'],
            ['id_permiso' => 74, 'nombre_permiso' => 'Cambiar Estatus de Tipo Evaluacion', 'estatus' => '1'],
            ['id_permiso' => 75, 'nombre_permiso' => 'Cambiar Estatus de Evento', 'estatus' => '1'],
            ['id_permiso' => 76, 'nombre_permiso' => 'Cambiar Estatus de Color', 'estatus' => '3'],
            ['id_permiso' => 77, 'nombre_permiso' => 'Cambiar Estatus de Permiso', 'estatus' => '1'],
            ['id_permiso' => 78, 'nombre_permiso' => 'Cambiar Estatus de Bitacora', 'estatus' => '1'],
            ['id_permiso' => 79, 'nombre_permiso' => 'Listar de Firma', 'estatus' => '3'],
            ['id_permiso' => 80, 'nombre_permiso' => 'Crear de Firma', 'estatus' => '3'],
            ['id_permiso' => 81, 'nombre_permiso' => 'Editar de Firma', 'estatus' => '3'],
            ['id_permiso' => 82, 'nombre_permiso' => 'Ver Detalles de Firma', 'estatus' => '3'],
            ['id_permiso' => 83, 'nombre_permiso' => 'Cambiar Estatus de Firma', 'estatus' => '1'],
            ['id_permiso' => 84, 'nombre_permiso' => 'Mi Firma de Firma', 'estatus' => '1'],
            ['id_permiso' => 85, 'nombre_permiso' => 'Aprobacion Vocero de Planificacion', 'estatus' => '1'],
        ];

        foreach ($permisos as $p) {
            DB::table('permiso')->updateOrInsert(['id_permiso' => $p['id_permiso']], $p);
        }

        // --- 9. ASOCIACIONES ROL-PERMISO ---
        // --- 9. ASOCIACIONES ROL-PERMISO ---
        $masterDataPerms = [
            22, 23, 24, 25, 69, // Indicador Logro: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            26, 27, 28, 29, 70, // Bibliografia: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            30, 31, 32, 33, 71, // Recurso: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            34, 35, 36, 37, 72, // Estrategia: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            38, 39, 40, 41, 73, // Tecnica Evaluacion: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            42, 43, 44, 45, 74, // Tipo Evaluacion: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
        ];

        // Vicerrector (Roles 31 y 4)
        $vicerrectorRoles = [31, 4];
        $vicerrectorPerms = array_merge([
            1, 2,  // Perfil, Seleccionar Rol
            46, 47, 48, 49, 75,  // Eventos: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            50, 51, 52, 53, 68, 20,  // Calendario: Listar, Crear, Ver Detalles, Editar, Cambiar Estatus, Reporte de Calendario
            58, 59, 77,  // Permisos: Listar, Editar, Cambiar Estatus
            79, 80, 81, 82, 83, 84,  // Firmas: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus, Mi Firma
        ], $masterDataPerms);

        // Coordinador (Roles 1, 5, 11, 30)
        $coordinadorRoles = [1, 5, 11, 30];
        $coordinadorPerms = array_merge([
            1, 2,  // Perfil, Seleccionar Rol
            7, 8, 9, 10, 65,  // Temas: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            3, 4, 5, 6, 64,  // Contenidos: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            13, 16, 17, 18, 19, 67,  // Planificacion: Listar, Ver Detalles, Reportes (Cumplimiento, General, Detallado), Cambiar Estatus
            79, 80, 81, 82, 83, 84,  // Firmas: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus, Mi Firma
        ], $masterDataPerms);

        // Profesor (Rol 2)
        $profesorRoles = [2];
        $profesorPerms = [
            1, 2,  // Perfil, Seleccionar Rol
            13, 14, 15, 16, 17, 18, 19, 21, 67,  // Planificacion: Listar, Crear, Editar, Ver Detalles, Reportes, Acuerdo de Aprendizaje, Estatus
            34, 35, 36, 37, 72,  // Estrategia: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            30, 31, 32, 33, 71,  // Recursos: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            38, 39, 40, 41, 73,  // Técnica Evaluación: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            42, 43, 44, 45, 74,  // Tipo Evaluación: Listar, Crear, Editar, Ver Detalles, Cambiar Estatus
            84,                  // Firma: Mi Firma
        ];

        // Estudiante (Rol 3)
        $estudianteRoles = [3];
        $estudiantePerms = [
            1, 2,  // Perfil, Seleccionar Rol
            13, 16,  // Planificacion: Listar, Ver Detalles
        ];

        // --- VOCERO: Buscar ID del rol VOCERO en BD externa y asignar permiso ---
        $voceroPermisos = [85];
        try {
            $voceroRolId = DB::connection('external_db')
                ->table('rol')
                ->where('rol_nombre', 'VOCERO')
                ->value('rol_codigo');
        } catch (\Exception $e) {
            $voceroRolId = null;
        }

        // Limpiar todas las relaciones existentes en rol_permiso
        DB::table('rol_permiso')->delete();

        // Función auxiliar para registrar los mapeos
        $insertMapping = function ($roles, $perms) {
            foreach ($roles as $roleId) {
                foreach ($perms as $permId) {
                    DB::table('rol_permiso')->insert([
                        'id_rol' => $roleId,
                        'id_permiso' => $permId,
                        'estatus' => '1',
                    ]);
                }
            }
        };

        $insertMapping($vicerrectorRoles, $vicerrectorPerms);
        $insertMapping($coordinadorRoles, $coordinadorPerms);
        $insertMapping($profesorRoles, $profesorPerms);
        $insertMapping($estudianteRoles, $estudiantePerms);

        // Asignar permiso de aprobación vocero al rol VOCERO (si se encontró)
        if ($voceroRolId) {
            foreach ($voceroPermisos as $permId) {
                DB::table('rol_permiso')->insert([
                    'id_rol' => $voceroRolId,
                    'id_permiso' => $permId,
                    'estatus' => '1',
                ]);
            }
        }

        // --- 11. MODIFICACIONES DE ESTRUCTURA A LA BASE DE DATOS PARA VOCEROS ---
        // Se agregaron los campos de control de flujo para la aprobación jerárquica de planificación
        try {
            DB::statement("ALTER TABLE planificacion MODIFY estatus ENUM('1','2','3','4','5') NOT NULL DEFAULT '1'");
        } catch (\Exception $e) { /* Ignorar si ya existe */ }

        try {
            DB::statement("ALTER TABLE planificacion ADD COLUMN motivo_rechazo_vocero TEXT NULL AFTER estatus");
        } catch (\Exception $e) { /* Ignorar si ya existe */ }

        try {
            DB::statement("ALTER TABLE planificacion ADD COLUMN id_firma_coordinador INT NULL");
        } catch (\Exception $e) { /* Ignorar si ya existe */ }

        try {
            DB::statement("ALTER TABLE planificacion ADD COLUMN id_firma_vocero INT NULL");
        } catch (\Exception $e) { /* Ignorar si ya existe */ }

        try {
            DB::statement("ALTER TABLE planificacion ADD COLUMN id_firma_docente INT NULL");
        } catch (\Exception $e) { /* Ignorar si ya existe */ }

        try {
            DB::statement("ALTER TABLE vocero ADD COLUMN notificado TINYINT(1) DEFAULT 0 NULL");
        } catch (\Exception $e) { /* Ignorar si ya existe */ }
    }
}