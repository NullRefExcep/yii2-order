<?php

use nullref\order\models\OrderStatus;
use yii\db\Migration;

class m160623_135236_rename extends Migration
{
    public function up()
    {
        $this->renameColumn(OrderStatus::tableName(), 'status', 'is_active');
    }

    public function down()
    {
        $this->renameColumn(OrderStatus::tableName(), 'is_active', 'status');
    }
}
