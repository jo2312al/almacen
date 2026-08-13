<?php
use yii\helpers\Html;
$this->title = 'Consulta de caja';
?>
<div class="mx-auto" style="max-width:680px">
 <div class="card"><div class="card-body p-4 text-center">
  <i class="bi bi-check-circle text-success" style="font-size:2.5rem"></i>
  <h1 class="h3 mt-3">Caja institucional verificada</h1>
  <p class="lead mb-2"><?= Html::encode($model->caj_codigo) ?></p>
  <p class="text-muted">Por seguridad, la ubicación, el contenido y los documentos solo están disponibles para usuarios autorizados.</p>
  <?= Html::a('Iniciar sesión', ['/user-management/auth/login'], ['class'=>'btn btn-primary']) ?>
 </div></div>
</div>
