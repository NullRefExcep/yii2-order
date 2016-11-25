<?php
use nullref\order\models\OrderItem;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model \nullref\order\models\Order */
/* @var $itemsDataProvider \yii\data\DataProviderInterface */

$this->registerJs(<<<JS
var pjaxItemsContainer = jQuery('#pjaxItemsContainer');
jQuery(document).pjax('a.remove-item-btn', '#pjaxItemsContainer', {
    push: false,
    replace: false,
    scrollTo: pjaxItemsContainer.offset().top + 20
});
pjaxItemsContainer.on('pjax:complete', function () {
    jQuery.pjax.reload({container: '#orderInfo'});
    jQuery('.price a, .amount a').on('save', function (e, params) {
        var cur = jQuery(this);
        var cost = cur.parents('tr').find('.cost');
        var price = 0,
            amount = 0;
        if (cur.parent().hasClass('price')) {
            price = parseFloat(params.newValue);
            amount = parseFloat(cur.parents('tr').find('.amount a').text());
        }
        if (cur.parent().hasClass('amount')) {
            amount = parseFloat(params.newValue);
            price = parseFloat(cur.parents('tr').find('.price a').text());
        }
        cost.text((amount * price).toFixed(2));

        jQuery.pjax.reload({container: '#orderInfo'});
    });
});
pjaxItemsContainer.trigger('pjax:complete');
JS
);

$editUrl = Url::to(['/order/admin/default/edit-item', 'order_id' => $model->id]);
$isLockStatus = $model->isLockStatus();
?>


<div class="order-items">

    <?php Pjax::begin([
        'id' => 'pjaxItemsContainer',
        'timeout' => 9999999,
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $itemsDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'raw',
                'header' => Yii::t('order', 'Title'),
                'attribute' => 'offer_id',
                'value' => function (OrderItem $item) {
                    $offer = $item->getOffer();
                    return $offer->id . ' ' . Html::a($offer->getTitle(), [
                        '/catalog/admin/product/view', 'id' => $offer->product_id], [
                        'target' => '_blank',
                        'data-pjax' => '0',
                    ])
                    . $offer->getStorageTitle();
                },
            ],
            $isLockStatus ? [
                'attribute' => 'price',
            ] : [
                'class' => 'mcms\xeditable\XEditableColumn',
                'header' => Yii::t('order', 'Price'),
                'format' => 'raw',
                'editable' => [],
                'url' => $editUrl,
                'attribute' => 'price',
                'contentOptions' => ['class' => 'price'],
                'headerOptions' => ['class' => 'price'],
            ],
            $isLockStatus ? [
                'attribute' => 'amount',
            ] : [
                'class' => 'mcms\xeditable\XEditableColumn',
                'header' => Yii::t('order', 'Amount'),
                'format' => 'raw',
                'editable' => [
                    'success' => new JsExpression(<<<JS
function success(response) {
    if (!response.success) {
        return response.message;
    } else {
        return false;
    }
}
JS
                    ),
                ],
                'url' => $editUrl,
                'attribute' => 'amount',
                'contentOptions' => ['class' => 'amount'],
                'headerOptions' => ['class' => 'amount'],
            ],
            [
                'format' => ['decimal', '2:10'],
                'header' => Yii::t('order', 'Cost'),
                'attribute' => 'cost',
                'contentOptions' => ['class' => 'cost'],
                'headerOptions' => ['class' => 'cost'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'visible' => !$isLockStatus,
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, OrderItem $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => 1,
                            'class' => 'remove-item-btn',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', [
                            'delete-item',
                            'order_id' => $model->order ? $model->order->id : 0,
                            'item_id' => $model->id
                        ], $options);
                    }
                ],
            ]
        ]
    ]) ?>
    <?php Pjax::end() ?>
</div>