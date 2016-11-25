<?php

namespace nullref\order\behaviors;

use nullref\order\interfaces\Order as OrderInterface;
use nullref\order\interfaces\OrderItem as OrderItemInterface;
use yii\base\Event;
use yii\behaviors\AttributeBehavior;

/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */
class PriceCalculator extends AttributeBehavior
{
    public function init()
    {
        parent::init();
        if (count($this->attributes) === 0) {
            $this->attributes = [
                OrderInterface::EVENT_ITEM_ADD => 'price',
                OrderInterface::EVENT_ITEM_REMOVE => 'price',
                OrderInterface::EVENT_ITEM_UPDATE => 'price',
            ];
        }
        if ($this->value === null) {
            $this->value = function (Event $e) {
                /** @var OrderInterface $sender */
                $sender = $e->sender;
                return array_reduce($sender->getItems(), function ($sum, OrderItemInterface $item) {
                    return $sum + $item->getCost();
                }, 0);
            };
        }
    }
}