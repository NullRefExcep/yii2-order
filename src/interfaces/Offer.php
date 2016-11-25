<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\interfaces;

/**
 * Interface Offer
 * @package nullref\order\interfaces
 *
 */
interface Offer
{
    /**
     * @return mixed
     */
    public static function find();

    /**
     * @return integer
     */
    public function getId();

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @return string
     */
    public function getTitle();
}