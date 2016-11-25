<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\behaviors;

use nullref\order\models\Order;
use nullref\order\models\OrderItem;
use yii\base\Behavior;
use yii\base\Event;

class StockOrderBehavior extends Behavior
{
    use StockTrait;

    /**
     * @return array
     */
    public function events()
    {
        return [
            OrderItem::EVENT_BEFORE_UPDATE => 'update',
            OrderItem::EVENT_BEFORE_INSERT => 'update',
        ];
    }

    /**
     * @param Event $e
     */
    public function update(Event $e)
    {
        /** @var Order $order */
        $order = $e->sender;

        if (!$order->hasErrors() && $order->isAttributeChanged('status_id')) {
            $isReturnOrderStatus = $this->isReturnOrderStatus($order->status_id);
            $isNotReturnOrderStatus = !$this->isReturnOrderStatus($order->getOldAttribute('status_id'));
            if ($isReturnOrderStatus && $isNotReturnOrderStatus) {
                $this->addAmountToStock($order);
            }
            if (!$isReturnOrderStatus && !$isNotReturnOrderStatus) {
                $this->removeAmountFromStock($order);
            }
        }
    }

    /**
     * @param Order $order
     */
    protected function addAmountToStock(Order $order)
    {
        foreach ($order->getItems() as $item) {
            $this->updateAmount($item, $item->amount);
        }
    }

    /**
     * @param Order $order
     */
    protected function removeAmountFromStock(Order $order)
    {
        foreach ($order->getItems() as $item) {
            $this->updateAmount($item, -$item->amount);
        }
    }

    /**
     * @param Event $e
     */
    public function delete(Event $e)
    {
        /** @var OrderItem $orderItem */
        $orderItem = $e->sender;
        if (!$this->isReturnOrderStatus($orderItem->order)) {
            $this->updateAmount($orderItem, $orderItem->amount);
        }
    }
}