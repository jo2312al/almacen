<?php

namespace app\components;

use Yii;

final class Navigation
{
    public static function items(): array
    {
        if (Yii::$app->user->isGuest) {
            return [];
        }

        $items = [['label' => 'Inicio', 'url' => ['/site/index']]];
        $role = RbacAccess::role();

        if ($role === 'usuario') {
            return array_merge($items, [
                ['label' => 'Registrar documento', 'url' => ['/archivo/create'], 'visible' => RbacAccess::can('archivo.crear')],
                ['label' => 'Buscar', 'url' => ['/busqueda/index'], 'visible' => RbacAccess::can('archivo.ver')],
                ['label' => 'Escanear QR', 'url' => ['/site/scan'], 'visible' => RbacAccess::can('caja.ver')],
            ]);
        }

        if ($role === 'viewer') {
            return array_merge($items, [
                ['label' => 'Buscar', 'url' => ['/busqueda/index']],
                ['label' => 'Consultar QR', 'url' => ['/site/scan']],
            ]);
        }

        $items[] = ['label' => 'Documentos', 'url' => ['/archivo/index'], 'visible' => RbacAccess::can('archivo.ver')];
        $items[] = ['label' => 'Archivo físico', 'items' => [
            ['label' => 'Cajas', 'url' => ['/caja/index']],
            ['label' => 'Localizar documento', 'url' => ['/busqueda/index']],
            ['label' => 'Escanear QR', 'url' => ['/site/scan']],
        ], 'visible' => RbacAccess::can('caja.ver')];
        $items[] = ['label' => 'Procesamiento por lote', 'url' => ['/carga-masiva/index'], 'visible' => RbacAccess::can('carga.ver')];
        $items[] = ['label' => 'Personas y expedientes', 'url' => ['/alumno/index'], 'visible' => RbacAccess::can('alumno.ver')];
        $items[] = ['label' => 'Reportes', 'url' => ['/site/reportes'], 'visible' => RbacAccess::can('reporte.ver')];
        $items[] = ['label' => 'Catálogos documentales', 'url' => ['/site/catalogos'], 'visible' => RbacAccess::can('catalogo.ver')];
        $items[] = ['label' => 'Actividad documental', 'url' => ['/bitacora/index'], 'visible' => RbacAccess::can('actividad.ver')];
        $items[] = ['label' => 'Administración del sistema', 'url' => ['/admin'], 'visible' => RbacAccess::can('configuracion.administrar')];
        return $items;
    }
}
