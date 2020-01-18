<?php

use nullref\order\models\OrderStatus;
use nullref\order\models\OrderType;
use nullref\order\models\Payment;
use nullref\order\models\Delivery;
use app\modules\user\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model nullref\order\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'user_id')->widget(Select2::className(), [
                'data' => User::getMap('name', 'id', [], false),
                'options' => ['placeholder' => Yii::t('user', 'User')],

            ]) ?>
            <?= $form->field($model, 'status_id')->dropDownList(OrderStatus::getMap('title')) ?>

            <?= $form->field($model, 'payment_id')->dropDownList(Payment::getMap('name', 'id', ['status' => '1'])) ?>

            <?= $form->field($model, 'delivery_id')->dropDownList(Delivery::getMap('name', 'id', ['status' => '1'])) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 4, 'style' => 'height: 108px;']) ?>

            <?= $form->field($model, 'call_time')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'type_id')->dropDownList(OrderType::getMap()) ?>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('order', 'Create') : Yii::t('order', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
