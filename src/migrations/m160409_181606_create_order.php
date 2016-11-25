<?php

use yii\db\Migration;

/**
 * Handles the creation for table `order`.
 */
class m160409_181606_create_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('{{%order_item}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey('order_item_fk', '{{%order_item}}', 'order_id', '{{%order}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('order_item_fk', '{{%order_item}}');
        $this->dropTable('{{%order}}');
        $this->dropTable('{{%order_item}}');
    }
}
