<?php

use yii\helpers\Html;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $itemsDataProvider \yii\data\DataProviderInterface */
/* @var $model nullref\order\models\Order */

$this->title = Yii::t('order', 'Create Order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


/** @var \nullref\order\controllers\admin\DefaultController $controller */
$controller = Yii::$app->controller;

?>
<div class="order-create">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('order', 'List'), ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('order', 'Create') : Yii::t('order', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'data-submit-target' => '']) ?>

    </p>

    <?= $this->render('_info', ['model' => $model]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


    <?= $controller->actionOfferSearch($model->id) ?>

    <?= $this->render('_items', [
        'model' => $model,
        'itemsDataProvider' => $itemsDataProvider,
    ]) ?>

</div>
