<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use nullref\order\models\Order;
use nullref\order\models\Payment;
use nullref\order\models\Delivery;

/* @var $this yii\web\View */
/* @var $model nullref\order\models\Order */
$payment = Payment::getMap();
$delivery = Delivery::getMap();
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('order', 'List'), ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('order', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('order', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('order', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
            'created_at',
            'updated_at',
            'telephone',
            'email:email',
            'address',
            'description',
            'status_id',
            'user_id',
            'price',
            'call_time',
            [
                'label' => Yii::t('order', 'Payment'),
                'value' => call_user_func(function (Order $model) {
                    return $model->payment ? $model->payment->name : Yii::t('app', 'N/A');
                }, $model),
            ],
            [
                'label' => Yii::t('order', 'Delivery'),
                'value' => call_user_func(function (Order $model) {
                    return $model->delivery ? $model->delivery->name : Yii::t('app', 'N/A');
                }, $model),
            ],
        ],
    ]) ?>

</div>
