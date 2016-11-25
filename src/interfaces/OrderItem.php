<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\interfaces;

/**
 * Interface OrderItem
 * @package nullref\order\interfaces
 */
interface OrderItem
{
    /**
     * @param Order $order
     * @param OrderItem $item
     * @return OrderItem|null
     */
    public static function findItem(Order $order, OrderItem $item);

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @param $value
     */
    public function setPrice($value);

    /**
     * @return float
     */
    public function getCost();

    /**
     * @return integer
     */
    public function getAmount();

    /**
     * @param $quantity
     */
    public function setAmount($quantity);

    /**
     * @return Offer
     */
    public function getOffer();

    /**
     * @param Offer $offer
     */
    public function setOffer(Offer $offer);

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param $id
     */
    public function setId($id);

    /**
     * @param Order $order
     */
    public function setOrder(Order $order);

}