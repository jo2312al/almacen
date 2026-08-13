<?php
/** @var yii\web\View $this */
/** @var array $metrics */
/** @var app\models\CargaMasiva[] $recentLoads */
/** @var app\models\BitacoraAccion[] $recentActions */
$this->title = 'Inicio · ASSRP';
$role = app\components\RbacAccess::role();
if ($role === 'viewer') {
    echo $this->render('_dashboard_viewer');
} elseif ($role === 'usuario') {
    echo $this->render('_dashboard_usuario');
} else {
    echo $this->render('_dashboard_admin', compact('metrics', 'recentLoads', 'recentActions'));
}
