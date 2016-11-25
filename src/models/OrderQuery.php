<?php


namespace nullref\order\models;


use yii\db\ActiveQuery;

class OrderQuery extends ActiveQuery
{
    /**
     * todo
     * @return OrderQuery
     */
    public function withTotalPrice()
    {
        return $this;
    }
}