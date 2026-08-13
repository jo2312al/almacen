<?php

use yii\db\Migration;

/** Configura los cuatro roles definitivos y permisos funcionales. */
class m260812_180000_configure_delivery_rbac extends Migration
{
    private const ROLE_PERMISSIONS = [
        'viewer' => [
            'archivo.ver', 'archivo.localizar', 'caja.ver',
        ],
        'usuario' => [
            'archivo.ver', 'archivo.crear', 'archivo.editar_propios',
            'archivo.descargar', 'archivo.procesar', 'archivo.revisar_propios',
            'archivo.localizar', 'caja.ver', 'alumno.ver', 'alumno.crear',
        ],
        'admin' => [
            'archivo.ver', 'archivo.crear', 'archivo.editar', 'archivo.eliminar',
            'archivo.descargar', 'archivo.procesar', 'archivo.revisar', 'archivo.localizar',
            'caja.ver', 'caja.crear', 'caja.editar', 'caja.eliminar', 'caja.generarQr',
            'alumno.ver', 'alumno.crear', 'alumno.editar', 'alumno.eliminar',
            'carga.ver', 'carga.crear', 'carga.revisar',
            'reporte.ver', 'reporte.exportar',
            'catalogo.ver', 'catalogo.administrar', 'actividad.ver',
        ],
        'adminsuperior' => [
            'archivo.ver', 'archivo.crear', 'archivo.editar', 'archivo.eliminar',
            'archivo.descargar', 'archivo.procesar', 'archivo.revisar', 'archivo.localizar',
            'caja.ver', 'caja.crear', 'caja.editar', 'caja.eliminar', 'caja.generarQr',
            'alumno.ver', 'alumno.crear', 'alumno.editar', 'alumno.eliminar',
            'carga.ver', 'carga.crear', 'carga.revisar',
            'reporte.ver', 'reporte.exportar',
            'catalogo.ver', 'catalogo.administrar', 'actividad.ver', 'auditoria.ver',
            'usuarios.administrar', 'roles.administrar', 'permisos.administrar',
            'configuracion.administrar', 'sistema.diagnosticar', 'mantenimiento.ejecutar',
        ],
    ];

    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $descriptions = [
            'adminsuperior' => 'Propietario funcional y técnico del sistema',
            'admin' => 'Administrador de la operación documental',
            'usuario' => 'Captura y consulta cotidiana',
            'viewer' => 'Consulta de solo lectura',
        ];

        foreach (self::ROLE_PERMISSIONS as $roleName => $permissions) {
            $role = $auth->getRole($roleName) ?: $auth->createRole($roleName);
            $role->description = $descriptions[$roleName];
            $auth->getRole($roleName) ? $auth->update($roleName, $role) : $auth->add($role);

            foreach ($permissions as $permissionName) {
                $permission = $auth->getPermission($permissionName);
                if (!$permission) {
                    $permission = $auth->createPermission($permissionName);
                    $permission->description = $permissionName;
                    $auth->add($permission);
                }
                if (!$auth->hasChild($role, $permission)) {
                    $auth->addChild($role, $permission);
                }
            }
        }

        // Compatibilidad: migra las cuentas conocidas a los roles definitivos.
        $mapping = [
            'superadmin' => 'adminsuperior',
            'adminsuperior' => 'adminsuperior',
            'admin' => 'admin',
            'prueba' => 'usuario',
            'usuario' => 'usuario',
            'viewer' => 'viewer',
        ];
        foreach ($mapping as $username => $roleName) {
            $userId = (new \yii\db\Query())->from('{{%user}}')->select('id')->where(['username' => $username])->scalar();
            if ($userId && !$auth->getAssignment($roleName, $userId)) {
                $auth->assign($auth->getRole($roleName), $userId);
            }
        }
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        foreach (array_keys(self::ROLE_PERMISSIONS) as $roleName) {
            if ($role = $auth->getRole($roleName)) {
                $auth->remove($role);
            }
        }
        foreach (array_unique(array_merge(...array_values(self::ROLE_PERMISSIONS))) as $permissionName) {
            if ($permission = $auth->getPermission($permissionName)) {
                $auth->remove($permission);
            }
        }
    }
}
