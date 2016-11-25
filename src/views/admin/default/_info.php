<?php

/* @var $this yii\web\View */
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $model nullref\order\models\Order */
?>

<?php Pjax::begin([
    'id' => 'orderInfo',
    'timeout' => 9999999,
]) ?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'price:decimal',
        'created_at:datetime',
    ],
]) ?>
<?php Pjax::end() ?>