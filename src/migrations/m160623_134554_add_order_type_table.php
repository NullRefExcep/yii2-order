<?php

use nullref\order\models\OrderStatus;
use yii\db\Migration;

class m160623_134554_add_order_type_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%order_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'percent' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%order_type}}');
    }

}
