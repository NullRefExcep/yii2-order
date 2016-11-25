<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\models;

use nullref\order\interfaces\Offer as OfferInterface;


/**
 * Class OfferableOrder
 *
 * @package nullref\order\models
 *
 * @method addItem(OrderItem $orderItem)
 * @method static createItem()
 *
 */
trait OfferableOrder
{
    /**
     * @param OfferInterface $variant
     * @param array $attributes
     */
    public function addOffer(OfferInterface $variant, $attributes = [])
    {
        /** @var OrderItem $orderItem */
        $orderItem = self::createItem();

        $orderItem->setOffer($variant);

        $orderItem->setAttributes($attributes);

        $this->addItem($orderItem);
    }
}