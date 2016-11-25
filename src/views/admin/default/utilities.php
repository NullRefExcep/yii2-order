<?php
/**
 * @var $this \yii\web\View
 */
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

$this->title = Yii::t('order', 'Utilities');
?>

<div class="utilities-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
    </div>

    <p>
        <?= Html::a(Yii::t('order', 'Orders'), ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(FA::i(FA::_TRASH) . ' ' . Yii::t('order', 'Drop all orders'),
            ['utilities', 'drop-all-orders' => true], [
                'class' => 'btn btn-danger',
                'data-confirm' => Yii::t('order', 'Are you sure?'),
            ]) ?>
    </p>
</div>
