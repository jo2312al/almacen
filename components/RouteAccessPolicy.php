<?php

namespace app\components;

use Yii;
use yii\base\ActionEvent;
use yii\web\ForbiddenHttpException;

/** Política de denegación por defecto para todas las rutas de la aplicación. */
final class RouteAccessPolicy
{
    private const PUBLIC_ROUTES = [
        'site/index', 'site/error', 'public/ajax-login', 'user-management/auth/login',
        'user-management/auth/logout', 'caja/consulta',
    ];

    private const EXACT = [
        'site/index' => '@', 'site/index-usuario' => '@',
        'site/menucrear' => 'archivo.crear', 'site/menubuscar' => 'archivo.ver',
        'site/scan' => 'caja.ver',
        'site/reportes' => 'reporte.ver', 'site/catalogos' => 'catalogo.ver',
        'archivo/index' => 'archivo.ver', 'archivo/view' => 'archivo.ver',
        'archivo/create' => 'archivo.crear', 'archivo/update' => 'archivo.editar',
        'archivo/delete' => 'archivo.eliminar', 'archivo/download' => 'archivo.descargar',
        'archivo/process-pdf' => 'archivo.procesar',
        'caja/index' => 'caja.ver', 'caja/view' => 'caja.ver',
        'caja/create' => 'caja.crear', 'caja/update' => 'caja.editar',
        'caja/delete' => 'caja.eliminar', 'caja/generar-qr' => 'caja.generarQr',
        'carga-masiva/index' => 'carga.ver', 'carga-masiva/view' => 'carga.ver',
        'carga-masiva/create' => 'carga.crear', 'carga-masiva/revisar' => 'carga.revisar',
        'alumno/index' => 'alumno.ver', 'alumno/view' => 'alumno.ver',
        'alumno/get-alumno-info' => 'alumno.ver', 'alumno/create' => 'alumno.crear',
        'alumno/update' => 'alumno.editar', 'alumno/delete' => 'alumno.eliminar',
        'busqueda/index' => 'archivo.ver', 'busqueda/localizar' => 'archivo.localizar',
        'reporte/cajas' => 'reporte.ver', 'reporte/alumnos' => 'reporte.ver',
        'reporte/alumno' => 'reporte.ver', 'reporte/exportar-cajas' => 'reporte.exportar',
        'reporte/exportar-alumno' => 'reporte.exportar',
        'bitacora/index' => 'actividad.ver', 'admin/default/index' => 'configuracion.administrar',
    ];

    private const CATALOGS = [
        'anaquel', 'area-generadora', 'carrera', 'clave-programatica',
        'fondo', 'generacion', 'nivelalmacenamiento', 'seccion-serie',
    ];

    public static function beforeAction(ActionEvent $event): void
    {
        $route = $event->action->uniqueId;
        if (in_array($route, self::PUBLIC_ROUTES, true)) {
            return;
        }

        $permission = self::EXACT[$route] ?? null;
        $controller = $event->action->controller->uniqueId;

        if (in_array($controller, self::CATALOGS, true)) {
            $permission = in_array($event->action->id, ['index', 'view'], true)
                ? 'catalogo.ver' : 'catalogo.administrar';
        } elseif (str_starts_with($route, 'user-management/')) {
            $permission = 'usuarios.administrar';
        } elseif (str_starts_with($route, 'admin/')) {
            $permission = 'configuracion.administrar';
        }

        // Rutas no inventariadas: exige autenticación y deja actuar a los filtros
        // propios. Así se conserva compatibilidad sin volverlas públicas.
        if ($permission === null) {
            $permission = '@';
        }

        $allowed = $permission === '@'
            ? !Yii::$app->user->isGuest
            : !Yii::$app->user->isGuest && Yii::$app->user->can($permission);

        // El usuario operativo puede editar únicamente dentro de su flujo. Hasta
        // que exista autoría por registro, la edición directa permanece denegada.
        if ($route === 'archivo/update' && Yii::$app->user->can('archivo.editar_propios') && !Yii::$app->user->can('archivo.editar')) {
            $allowed = false;
        }

        if (!$allowed) {
            if (Yii::$app->user->isGuest) {
                Yii::$app->user->loginRequired();
                $event->isValid = false;
                return;
            }
            throw new ForbiddenHttpException('No tienes permiso para realizar esta acción.');
        }
    }
}
