<?php

use nullref\order\models\OrderStatus;
use yii\db\Migration;

class m160623_134551_add_order_statuses extends Migration
{
    public function up()
    {
        $this->batchInsert(OrderStatus::tableName(), ['title', 'status'], [
            ['Новый', 1],
            ['Подтвержден клиентом', 1],
            ['Отказ', 1],
            ['Доставка', 1],
        ]);
    }

    public function down()
    {
        OrderStatus::deleteAll();
    }

}
