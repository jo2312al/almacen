<?php

namespace app\components;

use Yii;

/**
 * Helpers de autorización y visibilidad. La autorización real se valida también
 * en los controladores mediante AccessControl.
 */
final class RbacAccess
{
    public static function can(string $permission): bool
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->can($permission);
    }

    public static function role(): string
    {
        if (Yii::$app->user->isGuest) {
            return 'invitado';
        }

        foreach (['adminsuperior', 'admin', 'usuario', 'viewer'] as $role) {
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
