<?php

use nullref\order\models\OrderStatus;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \nullref\order\models\StatusSettings */

$statuses = OrderStatus::getMap();

$this->title = Yii::t('order', 'Customize');
?>
<div class="delivery-type-index">

    <div class="row">
        <div class="col-lg-12">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>
    <?php $form = ActiveForm::begin() ?>
    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'new')->dropDownList($statuses) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'delivery')->dropDownList($statuses) ?>
        </div>
        <div class="col-md-6" style="padding-left: 20px;">
            <?= $form->field($model, 'return')->checkboxList($statuses, ['separator' => '<br>']) ?>
        </div>
        <div class="col-md-6" style="padding-left: 20px;">
            <?= $form->field($model, 'finalized')->checkboxList($statuses, ['separator' => '<br>']) ?>
        </div>


        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('order', 'Change'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php $form->end() ?>

</div>
