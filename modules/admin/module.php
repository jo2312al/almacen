<?php

namespace app\modules\admin;

/**
 * Módulo admin
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Esto es crucial: le decimos al módulo que use el layout de AdminLTE3.
        $this->layout = '@vendor/hail812/yii2-adminlte3/src/views/layouts/main';
    }
}