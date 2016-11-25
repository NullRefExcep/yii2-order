<?php

namespace nullref\order;

use nullref\order\models\OfferManager;
use nullref\core\interfaces\IAdminModule;
use Yii;
use yii\base\Module as BaseModule;

/**
 * order module definition class
 */
class Module extends BaseModule implements IAdminModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'nullref\order\controllers';

    public static function getAdminMenu()
    {
        return [
            'label' => Yii::t('order', 'Orders'),
            'icon' => 'shopping-cart',
            'url' => ['/order/admin/default'],
        ];
    }
}
