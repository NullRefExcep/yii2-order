<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model nullref\order\models\OrderStatus */

$this->title = Yii::t('order', 'Create Order Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Order Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-status-create">

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
