<?php

/**
 * @var $dataProvider \yii\data\DataProviderInterface
 * @var $searchModel \yii\base\Model
 * @var $this \yii\web\View
 * @var $orderId integer
 */
use app\modules\catalog\models\Product;
use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FA;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->registerCss(<<<CSS
#offerSearchForm {
    margin-top: 10px;
    margin-bottom: 0;
}

#addOfferGrid table {
    margin-bottom: 10px;
}

#addOfferGrid .pagination {
    margin: 0;
}

#offerPanel .panel-heading,
#offerPanel .panel-body {
    padding: 5px 15px;
}

.pjax-offer-container {
    max-height: 300px;
    overflow-y: scroll;
}
#offerSearchForm .form-group {
    width: 200px;
}
#offerSearchForm .form-group button {
    margin-top: 20px;
}
CSS
);

$this->registerJs(<<<JS
var pjaxItemsContainer = jQuery('#pjaxItemsContainer');
jQuery(document).pjax('a.add-offer-btn', '#pjaxItemsContainer',{
    push:false,
    replace:false,
    scrollTo: pjaxItemsContainer.offset().top + 20
});
JS
);
?>

<p>
    <button class="btn btn-primary order-search-btn collapsed"
            type="button" data-toggle="collapse"
            data-target="#offerPanel"
            aria-expanded="false"
            aria-controls="#offerPanel">
        <i class="fa fa-plus"></i>
        <?= Yii::t('order', 'Add offer') ?>
    </button>
</p>


<div class="panel panel-default collapse" id="offerPanel" aria-expanded="true">
    <div class="panel-heading">
        <span class="panel-title">
            <i class="fa fa-search"></i>
            <?= Yii::t('order', 'Search offers') ?>
        </span>
    </div>
    <div class="panel-body">
        <div class="addOfferGrid " id="addOfferGrid">
            <?php $form = ActiveForm::begin([
                'options' => [
                    'data-pjax' => true,
                    'class' => 'form-inline form-group',
                ],
                'method' => 'get',
                'id' => 'offerSearchForm',
                'action' => ['offer-search', 'order_id' => $orderId],
            ]) ?>
            <?= $form->field($searchModel, 'name')
                ->widget(Select2::className(), [
                    'data' => Product::getTitleMap(),
                    'attribute' => 'title',
                    'model' => $searchModel,
                    'options' => [
                        'placeholder' => ' ',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
            ?>
            <?= $form->field($searchModel, 'sku')
                ->widget(Select2::className(), [
                    'data' => Product::getSkuMap(),
                    'attribute' => 'productSku',
                    'model' => $searchModel,
                    'options' => [
                        'placeholder' => ' ',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])
            ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('order', 'Search'), ['class' => 'btn btn-primary']) ?>
                <div class="help-block"></div>
            </div>
            <?php $form->end() ?>
            <hr>
            <?php Pjax::begin([
                'formSelector' => '#offerSearchForm',
                'enablePushState' => false,
                'id' => 'pjaxOfferContainer',
                'timeout' => 9999999,
                'options' => [
                    'class' => 'pjax-offer-container',
                ]
            ]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'pager' => [
                    'options' => ['class' => 'pagination pagination-sm'],
                ],
                'columns' => [
                    'id',
                    'price',
                    'title',
                    'amount',
                    'storage',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{add-to-order}',
                        'buttons' => [
                            'add-to-order' => function ($url, $model, $key) use ($orderId) {
                                $options = [
                                    'title' => Yii::t('order', 'Add to current order'),
                                    'aria-label' => Yii::t('order', 'Add to current order'),
                                    'class' => 'add-offer-btn',
                                ];
                                $url = Url::toRoute(['add-item', 'offer_id' => $model->id, 'order_id' => $orderId]);
                                return Html::a(FA::icon('archive'), $url, $options);
                            }
                        ],
                    ],
                ],
            ]) ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
