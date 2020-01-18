<?php

use nullref\order\models\OrderStatus;
use yii\db\Migration;

class m160623_134552_add_payment_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(),
            'name' => $this->string(),
            'note' => $this->text(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%payment}}');
    }

}
