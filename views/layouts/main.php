<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/img/logo.png')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link id="theme-stylesheet" href="<?= Yii::getAlias('@web/css/site.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
        <a href="https://villahermosa.tecnm.mx/" target="_blank"><img src="/img/itvh.png" alt="Logo itvh" class="logo"></a>
    </div>
</div>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'ASSRP',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-azul fixed-top'],
        'togglerContent' => '<span class="navbar-toggler-icon"></span>',
    ]);
    
    $menuItems = [ ['label' => 'Home', 'url' => ['/site/index']] ];
    if (!Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Crear', 'items' => [['label' => 'Archivo', 'url' => ['/archivo/create']],['label' => 'Carga Masiva', 'url' => ['/carga-masiva/create']],['label' => 'Caja', 'url' => ['/caja/create']],]];
        $menuItems[] = ['label' => 'Buscar', 'items' => [['label' => 'Alumno', 'url' => ['/alumno/index']],['label' => 'Caja', 'url' => ['/caja/index']],['label' => 'Anaquel', 'url' => ['/anaquel/index']],]];
        $menuItems[] = ['label' => 'Escanear', 'url' => ['/site/scan']];
        $menuItems[] = ['label' => 'Cerrar sesión (' . Html::encode(Yii::$app->user->identity->username) . ')','url' => ['/user-management/auth/logout'],'linkOptions' => ['data-method' => 'post', 'class' => 'nav-link'],];
    } else {
        $menuItems[] = ['label' => 'Iniciar Sesión', 'linkOptions' => ['data-bs-toggle' => 'modal','data-bs-target' => '#loginModal','style' => 'cursor: pointer;'], 'options' => ['class' => 'nav-item'],];
    }
    $menuItems[] = ['label' => '<div class="theme-switch-wrapper"><input type="checkbox" id="theme-switch" class="theme-switch"><label for="theme-switch" class="theme-switch-label"></label><span class="theme-switch-icon"><i class="bi bi-sun-fill"></i><i class="bi bi-moon-fill"></i></span></div>','encode' => false,'options' => ['class' => 'nav-item'],'align' => 'right',];
    echo Nav::widget(['options' => ['class' => 'navbar-nav ms-auto'],'items' => $menuItems,]);
    NavBar::end();
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

<footer id="footer" class="mt-auto py-3">
    <div class="container">
        <div class="wraper">
            <div class="center" style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                <div class="bottom_about">
                    <a href="https://www.gob.mx/sep" target="_blank">
                        <img class="logo-pie-pagina" src="https://sws.villahermosa.tecnm.mx/plantillas/itvh/images/logos/logosep2019.png" style="width:240px;" alt="SEP">
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
                Teléfonos: 01(993) 353-02-59 <a href="http://villahermosa.tecnm.mx/site/identidad.jsp?view=directoriotel">Extensiones</a><br>
                Instituto Tecnologico de Villahermosa - Copyright © 2017 - Todos los Derechos Reservados<br>
                <a href="/">Inicio</a> | <a href="#">Mapa del Sitio</a> | <a href="">Políticas de Privacidad</a>
                <br><br><br>
            </div>
        </div>
        <a href="#" class="scrollup" style="display: none; right: 20px;">Scroll</a>
    </div>
</footer>

<!-- === CÓDIGO DEL MODAL DE LOGIN (SÓLO PARA INVITADOS) === -->
<?php if (Yii::$app->user->isGuest): ?>
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel"><i class="bi bi-person-circle me-2"></i>Iniciar Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- ========================================================================= -->
                    <!-- === INICIO DE LA CORRECCIÓN DEFINITIVA ================================ -->
                    <!-- ========================================================================= -->
                    <!-- El formulario ahora es un formulario normal, no AJAX. Esto es más simple y robusto. -->
                    <?php
                    $model = new \webvimark\modules\UserManagement\models\forms\LoginForm();
                    $form = \yii\bootstrap5\ActiveForm::begin([
                        'id' => 'login-form-standalone',
                        'action' => ['/user-management/auth/login'], // Apunta a la acción de login original de Webvimark
                        'method' => 'post',
                        'fieldConfig' => [
                            'template' => "{input}\n{error}",
                        ],
                    ]);
                    ?>
                        <div class="mb-3">
                            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control form-control-lg', 'placeholder' => 'Usuario']) ?>
                        </div>
                        <div class="mb-3">
                            <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control-lg', 'placeholder' => 'Contraseña']) ?>
                        </div>
                        <div class="mb-3">
                            <?= $form->field($model, 'rememberMe')->checkbox() ?>
                        </div>
                        <div class="d-grid">
                            <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-lg', 'name' => 'login-button']) ?>
                        </div>
                    <?php \yii\bootstrap5\ActiveForm::end(); ?>
                    <!-- ========================================================================= -->
                    <!-- === FIN DE LA CORRECCIÓN ================================================= -->
                    <!-- ========================================================================= -->
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->endBody() ?>

<!-- Script para el interruptor de tema -->
<script>
    const themeSwitch = document.getElementById('theme-switch');
    const stylesheet = document.getElementById('theme-stylesheet');
    const currentTheme = localStorage.getItem('theme');
    function applyTheme(theme) {
        const themeUrl = (theme === 'siten') ? '<?= Yii::getAlias('@web/css/siten.css') ?>' : '<?= Yii::getAlias('@web/css/site.css') ?>';
        stylesheet.setAttribute('href', themeUrl);
        document.body.classList.toggle('dark-mode', theme === 'siten');
    }
    if (currentTheme) {
        themeSwitch.checked = (currentTheme === 'siten');
        applyTheme(currentTheme);
    } else {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        themeSwitch.checked = prefersDark;
        applyTheme(prefersDark ? 'siten' : 'site');
    }
    themeSwitch.addEventListener('change', function() {
        const newTheme = this.checked ? 'siten' : 'site';
        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
