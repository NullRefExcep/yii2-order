<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\behaviors;

use nullref\order\models\OrderItem;
use Yii;
use yii\base\Behavior;
use yii\base\Event;

class StockItemBehavior extends Behavior
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
            OrderItem::EVENT_BEFORE_DELETE => 'delete',
            OrderItem::EVENT_BEFORE_VALIDATE => 'validate',
        ];
    }

    /**
     * @param Event $e
     */
    public function validate(Event $e)
    {
        /** @var OrderItem $orderItem */
        $orderItem = $e->sender;
        if ($orderItem->order && $this->isReturnOrderStatus($orderItem->order->status_id) && $orderItem->isAttributeChanged('amount')) {
            $orderItem->addError('amount', Yii::t('order', 'Can\'nt update amount with Cancellation order status'));
        }
    }


    /**
     * @param Event $e
     */
    public function update(Event $e)
    {
        /** @var OrderItem $orderItem */
        $orderItem = $e->sender;

        if (!$orderItem->hasErrors() && $orderItem->isAttributeChanged('amount')) {
            $this->updateAmount($orderItem, $orderItem->getOldAttribute('amount') - $orderItem->getAttribute('amount'));
        }
    }


    /**
     * @param Event $e
     */
    public function delete(Event $e)
    {
        /** @var OrderItem $orderItem */
        $orderItem = $e->sender;
        if ($orderItem->order) {
            if (!$this->isReturnOrderStatus($orderItem->order->status_id)) {
                $this->updateAmount($orderItem, $orderItem->amount);
            }
        } else {
            $this->updateAmount($orderItem, $orderItem->amount);
        }
    }

}