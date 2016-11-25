<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\behaviors;

use app\helpers\Memoize;
use app\modules\catalog\interfaces\Stockable;
use nullref\order\models\Order;
use nullref\order\models\OrderItem;
use nullref\order\models\OrderStatus;
use nullref\order\models\StatusSettings;
use yii\db\ActiveRecordInterface;


trait StockTrait
{
    /**
     * @return array
     */
    public function getReturnStatusIds()
    {
        return array_map(function (OrderStatus $status) {
            return $status->id;
        }, StatusSettings::getReturnStatuses());
    }

    /**
     * @param OrderItem $orderItem
     * @param $quantity
     */
    protected function updateAmount(OrderItem $orderItem, $quantity)
    {
        $offer = $orderItem->getOffer();
        if ($offer instanceof Stockable) {
            /** Add additional logic if need */
            if ($quantity < 0) {
                $offer->removeToStock(abs($quantity));
            } else {
                $offer->addToStock($quantity);
            }
            if ($offer instanceof ActiveRecordInterface) {
                $offer->save();
            }
        }
    }

    /**
     * @param $statusId
     * @return bool
     */
    protected function isReturnOrderStatus($statusId)
    {
        $statusIds = Memoize::call([$this, 'getReturnStatusIds']);

        return in_array($statusId, $statusIds, false);
    }
}