<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model nullref\order\models\OrderType */

$this->title = Yii::t('order', 'Update Order Type: ') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Order Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('order', 'Update');
?>
<div class="order-status-update">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('order', 'List'), ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
