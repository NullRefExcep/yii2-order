<?php

use nullref\order\models\OrderStatus;
use yii\db\Migration;

class m160623_134553_add_delivery_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%delivery}}', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(),
            'name' => $this->string(),
            'note' => $this->text(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%delivery}}');
    }

}
