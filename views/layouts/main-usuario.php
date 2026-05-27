<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\BootstrapAsset;
use webvimark\modules\UserManagement\components\GhostMenu;
use webvimark\modules\UserManagement\UserManagementModule;

BootstrapAsset::register($this);

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
<?php $this->title = 'ASSRP'; ?>

    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="icon" href="/img/logo.png" type="image/x-icon">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<!-- Banner -->
<div class="banner">
    <div class="banner-section logo-section">
        <a href="/site/index"><img src="/img/logo.png" alt="Logo de la app web" class="logo"></a>
    </div>
    <div class="banner-section empty-section"></div>
    <div class="banner-section logos-section">
    <a href="https://www.tecnm.mx/" target="_blank"><img src="/img/tecnm.png" alt="Logo tecnm" class="logo"></a>
    <a href="https://villahermosa.tecnm.mx/" target= "_blank"><img src="/img/itvh.png" alt="Logo itvh" class="logo"></a>
    </div>
</div>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => "ASSRP",
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-azul ']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index-usuario']],
            ['label' => 'Crear', 'url' => ['/alumno/index'], 'items' => [
                ['label' => 'Alumno', 'url' => ['/alumno/create']],
                ['label' => 'Caja', 'url' => ['/caja/create']],
            ]],
            ['label' => 'Listar', 'url' => ['/alumno/index'], 'items' => [
                ['label' => 'Alumno', 'url' => ['/alumno/index']],
                ['label' => 'Caja', 'url' => ['/caja/index']],
                ['label' => 'Archivo', 'url' => ['/archivo/index']],
                ['label' => 'Anaquel', 'url' => ['/anaquel/index']],
            ]],
            ['label' => 'Logout', 'url' => ['/user-management/auth/logout']],
        ],
    ])
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
    <div class="wraper">
        <div class="center" style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
            <div class="bottom_about">
                <a href="#">                    
                    <img class="logo-pie-pagina" src="https://sws.villahermosa.tecnm.mx/plantillas/itvh/images/logos/logosep2019.png" style="width:240px;"alt="SEP">    
                </a>  
            </div>        
        </div>
		</div>
    <br>
    <div class="copyright" style="text-align: center;">
        <br>
        <div class="wraper">
            Carretera Villahermosa - Frontera Km. 3.5 Ciudad Industrial<br>
            Villahermosa, Tabasco, Mexico. C.P. 86010<br>
            Teléfonos: 01(993) <!--353-02-59,--> 353-02-59 <a href="http://villahermosa.tecnm.mx/site/identidad.jsp?view=directoriotel">Extensiones</a><br>
            Instituto Tecnologico de Villahermosa - Copyright © 2017 - Todos los Derechos Reservados<br>
            <a href="/">Inicio</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#">Mapa del Sitio</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="">Politicas de Privacidad</a>
            <br><br><br>
        </div>
    </div>
    <a href="#" class="scrollup" style="display: none; right: 20px;">Scroll</a>
</div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>