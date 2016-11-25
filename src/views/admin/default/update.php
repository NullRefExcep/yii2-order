<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $itemsDataProvider \yii\data\DataProviderInterface */
/* @var $model nullref\order\models\Order */

$this->title = Yii::t('order', 'Update Order: ') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('order', 'Update');


/** @var \nullref\order\controllers\admin\DefaultController $controller */
$controller = Yii::$app->controller;

?>
<div class="order-update">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('order', 'List'), ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('order', 'Delete'), ['delete', 'id' => $model->id], ['class' => 'btn btn-danger', 'data-method' => 'post']) ?>
    </p>

    <?= $this->render('_info', ['model' => $model]) ?>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


    <?php if (!$model->isLockStatus()): ?>
        <?= $controller->actionOfferSearch($model->id) ?>
    <?php endif ?>

    <?= $this->render('_items', [
        'model' => $model,
        'itemsDataProvider' => $itemsDataProvider,
    ]) ?>

</div>
