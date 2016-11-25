<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\behaviors;


use nullref\order\models\OrderItem;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class OfferableBehavior
 * @package nullref\order\behaviors
 *
 */
class OfferableBehavior extends Behavior
{
    public $pk = 'id';

    public function getOrderItems()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        return $model->hasMany(OrderItem::className(), [$this->pk => 'offer_id'])->inverseOf('offer');
    }
}