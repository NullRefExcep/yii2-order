<?php

use nullref\order\models\OrderItem;
use nullref\order\models\OrderStatus;
use nullref\order\models\OrderType;
use app\modules\user\models\User;
use nullref\core\widgets\ActiveRangeInputGroup;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use nullref\order\models\Order;
use nullref\order\models\Payment;
use nullref\order\models\Delivery;

/* @var $this yii\web\View */
/* @var $searchModel \nullref\order\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$payment = Payment::getMap();
$delivery = Delivery::getMap();
$this->title = Yii::t('order', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>


    <p>
        <?= Html::a(Yii::t('order', 'Create Order'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('order', 'Utilities'), ['utilities'], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'user_id',
                'filter' => Select2::widget([
                    'data' => array_merge([0 => Yii::t('user', 'Guest')], User::getMap('name', 'id', [], false)),
                    'options' => ['placeholder' => Yii::t('user', 'User')],
                    'attribute' => 'user_id',
                    'model' => $searchModel,
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'value' => 'full_name',
            ],
            'telephone',
            'email',
            [
                'attribute' => 'status_id',
                'value' => 'status.title',
                'filter' => OrderStatus::getMap('title'),
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
            ],
            [
                'filter' => ActiveRangeInputGroup::widget([
                    'attributeFrom' => 'totalPriceFrom',
                    'attributeTo' => 'totalPriceTo',
                    'model' => $searchModel,
                ]),
                'attribute' => 'totalPrice',
                'value' => function (Order $model) {
                    return $model->getPrice();
                },
                'label' => Yii::t('order', 'Sum'),
                'format' => 'decimal'
            ],
            [
                'attribute' => 'type_id',
                'filter' => OrderType::getMap(),
                'label' => Yii::t('order', 'Order type'),
                'value' => function (Order $model) {
                    return $model->type ? $model->type->name : Yii::t('app', 'N/A');
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'payment_id',
                'filter' => Payment::getMap(),
                'label' => Yii::t('order', 'Payment type'),
                'value' => function (Order $model) {
                    return $model->payment ? $model->payment->name : Yii::t('app', 'N/A');
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'delivery_id',
                'filter' => Delivery::getMap(),
                'label' => Yii::t('order', 'Delivery type'),
                'value' => function (Order $model) {
                    return $model->delivery ? $model->delivery->name : Yii::t('app', 'N/A');
                },
                'format' => 'raw',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                    },
                    'excel' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Create '),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        $Newurl = \yii\helpers\Url::to(['/admin/order:createxls','id'=>$model->id]);
                        return Html::a('<span class="glyphicon glyphicon-print"></span>', $Newurl, $options);
                    },
                ],
                'template' => '{update}{excel}',
            ],
        ],
    ]); ?>

</div>
