<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\events;


use nullref\order\models\OrderItem;
use yii\base\Event;

class OrderItemEvent extends Event
{
    /** @var  OrderItem */
    public $item;

}