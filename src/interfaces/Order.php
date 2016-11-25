<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\interfaces;

/**
 * Interface Order
 * @package nullref\order\interfaces
 */
interface Order
{
    /**
     * Order item events
     */
    const EVENT_ITEM_ADD = 'itemAdd';
    const EVENT_ITEM_REMOVE = 'itemRemove';
    const EVENT_ITEM_UPDATE = 'itemUpdate';

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @return OrderItem[];
     */
    public function getItems();

    /**
     * @param $id
     * @return OrderItem|null
     */
    public function getItem($id);

    /**
     * @param OrderItem $model
     * @return mixed
     */
    public function addItem(OrderItem $model);

    /**
     * @param OrderItem $model
     * @return mixed
     */
    public function removeItem(OrderItem $model);
}