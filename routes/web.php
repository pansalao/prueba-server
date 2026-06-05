<?php

use App\Livewire\Planificacion\CreatePlanificacion;
use App\Livewire\Planificacion\ListPlanificacion;
use App\Livewire\Planificacion\UpdatePlanificacion;
use App\Livewire\Planificacion\ShowPlanificacion;



use App\Livewire\Contenido\CreateContenido;
use App\Livewire\Contenido\ListContenido;
use App\Livewire\Contenido\UpdateContenido;
use App\Livewire\Contenido\ShowContenido;

use App\Livewire\Tema\CreateTema;
use App\Livewire\Tema\ListTema;
use App\Livewire\Tema\UpdateTema;
use App\Livewire\Tema\ShowTema;

use App\Livewire\Usuario\CreateUsuario;
use App\Livewire\Usuario\ListUsuario;

use App\Livewire\IndicadorLogro\CreateIndicadorLogro;
use App\Livewire\IndicadorLogro\ListIndicadorLogro;
use App\Livewire\IndicadorLogro\UpdateIndicadorLogro;
use App\Livewire\IndicadorLogro\ShowIndicadorLogro;

use App\Livewire\Bibliografia\CreateBibliografia;
use App\Livewire\Bibliografia\UpdateBibliografia;
use App\Livewire\Bibliografia\ShowBibliografia;
use App\Livewire\Bibliografia\ListBibliografia;

use App\Livewire\Recurso\CreateRecurso;
use App\Livewire\Recurso\UpdateRecurso;
use App\Livewire\Recurso\ShowRecurso;
use App\Livewire\Recurso\ListRecurso;

use App\Livewire\Estrategia\CreateEstrategia;
use App\Livewire\Estrategia\UpdateEstrategia;
use App\Livewire\Estrategia\ShowEstrategia;
use App\Livewire\Estrategia\ListEstrategia;


use App\Livewire\TecnicaEvaluacion\CreateTecnicaEvaluacion;
use App\Livewire\TecnicaEvaluacion\UpdateTecnicaEvaluacion;
use App\Livewire\TecnicaEvaluacion\ShowTecnicaEvaluacion;
use App\Livewire\TecnicaEvaluacion\ListTecnicaEvaluacion;

use App\Livewire\TipoEvaluacion\CreateTipoEvaluacion;
use App\Livewire\TipoEvaluacion\UpdateTipoEvaluacion;
use App\Livewire\TipoEvaluacion\ShowTipoEvaluacion;
use App\Livewire\TipoEvaluacion\ListTipoEvaluacion;

use App\Livewire\Evento\CreateEvento;
use App\Livewire\Evento\ListEvento;
use App\Livewire\Evento\UpdateEvento;
use App\Livewire\Evento\ShowEvento;

use App\Livewire\Calendario\CreateCalendario;
use App\Livewire\Calendario\ListCalendario;
use App\Livewire\Calendario\UpdateCalendario;
use App\Livewire\Calendario\ShowCalendario;
use App\Livewire\Calendario\ExcelCalendarioExport;
use App\Livewire\Calendario\EditarCalendario;
use App\Livewire\Calendario\JustificacionesCalendario;

use App\Livewire\Bitacora\ListBitacora;

use App\Livewire\Vocero\PanelVocero;

use Illuminate\Support\Facades\Route;

Route::get('login', [\App\Http\Controllers\Auth\ExternalLoginController::class, 'login'])->name('login');

Route::get('/', function () {
    abort(404);
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/perfil', \App\Livewire\Perfil\ShowPerfil::class)
    ->middleware(['auth', 'verified'])
    ->name('perfil');

Route::get('/seleccionar-rol', \App\Livewire\Auth\SeleccionarRol::class)
    ->name('seleccionar-rol');


Route::middleware(['auth', /*'role:1'*/])->group(function () {

    Route::get('contenido/list', ListContenido::class)->middleware('can:listar-contenido')->name('contenido/listar');
    Route::get('contenido/create', CreateContenido::class)->middleware('can:crear-contenido')->name('contenido/crear');
    Route::get('contenido/update/{id}', UpdateContenido::class)->middleware('can:editar-contenido')->name('contenido/update');
    Route::get('contenido/show/{id}', ShowContenido::class)->middleware('can:ver-contenido')->name('contenido/show');

    Route::get('tema/list', ListTema::class)->middleware('can:listar-tema')->name('tema/listar');
    Route::get('tema/create', CreateTema::class)->middleware('can:crear-tema')->name('tema/crear');
    Route::get('tema/update/{id}', UpdateTema::class)->middleware('can:editar-tema')->name('tema/update');
    Route::get('tema/show/{id}', ShowTema::class)->middleware('can:ver-tema')->name('tema/show');

    Route::get('planificacion/list', ListPlanificacion::class)->middleware('can:listar-planificacion')->name('planificacion/listar');
    Route::get('planificacion/historial', \App\Livewire\Planificacion\HistoryPlanificacion::class)->middleware('can:listar-planificacion')->name('planificacion.historial');
    Route::get('planificacion/create', CreatePlanificacion::class)->middleware('can:crear-planificacion')->name('planificacion/crear');
    Route::get('planificacion/update/{planificacionId}', UpdatePlanificacion::class)->middleware('can:editar-planificacion')->name('planificaciones.update');
    Route::get('planificacion/show/{planificacionId}', ShowPlanificacion::class)->middleware('can:ver-planificacion')->name('planificacion/show');
    Route::get('planificacion/reporte-cumplimiento', [\App\Http\Controllers\ReporteController::class, 'cumplimiento'])->middleware(['log.activity:REPORTE'])->name('planificacion.reporte.cumplimiento');
    // Rutas para Reportes PDF (Abrir en pestaña)
    Route::get('planificacion/reporte-general', [\App\Http\Controllers\ReportePlanificacionController::class, 'reporteGeneral'])->middleware(['can:listar-planificacion', 'log.activity:REPORTE'])->name('planificacion.reporte.general');
    Route::get('planificacion/reporte-detalle/{id}', [\App\Http\Controllers\ReportePlanificacionController::class, 'reporteDetalle'])->middleware(['can:ver-planificacion', 'log.activity:REPORTE'])->name('planificacion.reporte.detalle');

    Route::get('calendario/reporte', fn() => ExcelCalendarioExport::descargar())->middleware(['can:listar-calendario', 'log.activity:REPORTE'])->name('calendario.reporte');
    Route::get('calendario/reporte/{id}', fn($id) => ExcelCalendarioExport::descargar($id))->middleware(['can:listar-calendario', 'log.activity:REPORTE'])->name('calendario.reporte.especifico');

    Route::get('planificacion/acuerdo-aprendizaje/{id}', [\App\Http\Controllers\ReportePlanificacionController::class, 'acuerdoAprendizaje'])->middleware(['can:ver-planificacion', 'log.activity:REPORTE'])->name('planificacion.acuerdo');
    Route::get('planificacion/aprobacion-vocero/{id}', function () {
        abort(404);
    })->name('planificacion.aprobacion-vocero');


    Route::get('indicador-logro/list', ListIndicadorLogro::class)->middleware('can:listar-indicador-logro')->name('indicador-logro/listar');
    Route::get('indicador-logro/create', CreateIndicadorLogro::class)->middleware('can:crear-indicador-logro')->name('indicador-logro/crear');
    Route::get('indicador-logro/update/{id}', UpdateIndicadorLogro::class)->middleware('can:editar-indicador-logro')->name('indicador-logro/update');
    Route::get('indicador-logro/show/{id}', ShowIndicadorLogro::class)->middleware('can:ver-indicador-logro')->name('indicador-logro/show');

    Route::get('bibliografia/list', ListBibliografia::class)->middleware('can:listar-bibliografia')->name('bibliografia/listar');
    Route::get('bibliografia/create', CreateBibliografia::class)->middleware('can:crear-bibliografia')->name('bibliografia/crear');
    Route::get('bibliografia/update/{id}', UpdateBibliografia::class)->middleware('can:editar-bibliografia')->name('bibliografia/update');
    Route::get('bibliografia/show/{id}', ShowBibliografia::class)->middleware('can:ver-bibliografia')->name('bibliografia/show');

    // Rutas para Recursos
    Route::get('recurso/list', ListRecurso::class)->middleware('can:listar-recurso')->name('recurso/listar');
    Route::get('recurso/create', CreateRecurso::class)->middleware('can:crear-recurso')->name('recurso/crear');
    Route::get('recurso/update/{recursoId}', UpdateRecurso::class)->middleware('can:editar-recurso')->name('recurso/update');
    Route::get('recurso/show/{id}', ShowRecurso::class)->middleware('can:ver-recurso')->name('recurso/show');

    // Rutas para Estrategias Pedagógicas
    Route::get('estrategia/list', ListEstrategia::class)->middleware('can:listar-estrategia')->name('estrategia/listar');
    Route::get('estrategia/create', CreateEstrategia::class)->middleware('can:crear-estrategia')->name('estrategia/crear');
    Route::get('estrategia/update/{id}', UpdateEstrategia::class)->middleware('can:editar-estrategia')->name('estrategia/update');
    Route::get('estrategia/show/{id}', ShowEstrategia::class)->middleware('can:ver-estrategia')->name('estrategia/show');


    // Rutas para Técnicas de Evaluación
    Route::get('tecnica-evaluacion/list', ListTecnicaEvaluacion::class)->middleware('can:listar-evaluacion')->name('tecnica-evaluacion/listar');
    Route::get('tecnica-evaluacion/create', CreateTecnicaEvaluacion::class)->middleware('can:crear-evaluacion')->name('tecnica-evaluacion/crear');
    Route::get('tecnica-evaluacion/update/{id}', UpdateTecnicaEvaluacion::class)->middleware('can:editar-evaluacion')->name('tecnica-evaluacion/update');
    Route::get('tecnica-evaluacion/show/{id}', ShowTecnicaEvaluacion::class)->middleware('can:ver-evaluacion')->name('tecnica-evaluacion/show');

    // Rutas para Tipos de Evaluación
    Route::get('tipo-evaluacion/list', ListTipoEvaluacion::class)->middleware('can:listar-tipo-evaluacion')->name('tipo-evaluacion/listar');
    Route::get('tipo-evaluacion/create', CreateTipoEvaluacion::class)->middleware('can:crear-tipo-evaluacion')->name('tipo-evaluacion/crear');
    Route::get('tipo-evaluacion/update/{id}', UpdateTipoEvaluacion::class)->middleware('can:editar-tipo-evaluacion')->name('tipo-evaluacion/update');
    Route::get('tipo-evaluacion/show/{id}', ShowTipoEvaluacion::class)->middleware('can:ver-tipo-evaluacion')->name('tipo-evaluacion/show');

    // Rutas para Eventos
    Route::get('evento/list', ListEvento::class)->middleware('can:listar-evento')->name('evento/listar');
    Route::get('evento/create', CreateEvento::class)->middleware('can:crear-evento')->name('evento/crear');
    Route::get('evento/update/{id}', UpdateEvento::class)->middleware('can:editar-evento')->name('evento/update');
    Route::get('evento/show/{id}', ShowEvento::class)->middleware('can:ver-evento')->name('evento/show');

    // Rutas para Calendario Académico
    Route::get('calendario/list', ListCalendario::class)->middleware('can:listar-calendario')->name('calendario.list');
    Route::get('calendario/create/{id?}', CreateCalendario::class)->middleware('can:crear-calendario')->name('calendario.create');
    Route::get('calendario/show/{id}', ShowCalendario::class)->middleware('can:ver-calendario')->name('calendario.show');
    Route::get('calendario/editar/{id}', EditarCalendario::class)->middleware('can:cambiar-estatus-calendario')->name('calendario.editar');
    Route::get('calendario/notas/{id}', \App\Livewire\Calendario\NotasCalendario::class)->middleware('can:cambiar-estatus-calendario')->name('calendario.notas');
    Route::get('calendario/justificaciones/{id}', JustificacionesCalendario::class)->middleware('can:ver-calendario')->name('calendario.justificaciones');

    // Módulo de Permisos (DAECE)
    Route::get('permiso/list', \App\Livewire\Permiso\ListPermiso::class)->middleware('can:listar-permiso')->name('permiso/listar');
    Route::get('permiso/update/{permisoId}', \App\Livewire\Permiso\UpdatePermiso::class)->middleware('can:editar-permiso')->name('permiso/update');


    // Rutas para Bitácora
    Route::get('bitacora/list', ListBitacora::class)->middleware('can:listar-bitacora')->name('bitacora/listar');
    Route::get('bitacora/show/{id}', \App\Livewire\Bitacora\ShowBitacora::class)->middleware('can:ver-bitacora')->name('bitacora/show');

    // Rutas para Firmas
    Route::get('firma/mi-firma', \App\Livewire\Firma\ManageFirma::class)->middleware('auth')->name('firma/mi-firma');

    // Módulo Voceros
    Route::get('voceros', PanelVocero::class)->name('voceros.panel');
});

Route::middleware(['auth'])->group(function () { });


require __DIR__ . '/auth.php';

