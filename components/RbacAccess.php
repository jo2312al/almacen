<?php

namespace app\components;

use Yii;

/** Helpers de autorizacion y visibilidad del sistema. */
final class RbacAccess
{
    public static function can(string $permission): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        if (Yii::$app->user->can('superadmin') || Yii::$app->user->can('adminsuperior')) {
            return true;
        }

        return Yii::$app->user->can($permission);
    }

    public static function role(): string
    {
        if (Yii::$app->user->isGuest) {
            return 'invitado';
        }

        if (Yii::$app->user->can('superadmin') || Yii::$app->user->can('adminsuperior')) {
            return 'adminsuperior';
        }

        foreach (['admin', 'usuario', 'viewer'] as $role) {
            if (Yii::$app->user->can($role)) {
                return $role;
            }
        }

        return 'sin-rol';
    }

    public static function homeRoute(): array
    {
        return ['/site/index'];
    }
}