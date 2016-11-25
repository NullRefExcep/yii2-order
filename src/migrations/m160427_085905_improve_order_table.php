<?php

use nullref\core\traits\MigrationTrait;
use yii\db\Migration;

class m160427_085905_improve_order_table extends Migration
{
    use MigrationTrait;

    public function up()
    {
        $this->addColumn('{{%order}}', 'telephone', $this->string());
        $this->addColumn('{{%order}}', 'email', $this->string());
        $this->addColumn('{{%order}}', 'address', $this->string());
        $this->addColumn('{{%order}}', 'description', $this->string());
        $this->addColumn('{{%order}}', 'status_id', $this->integer());
        $this->addColumn('{{%order}}', 'user_id', $this->integer());
        $this->addColumn('{{%order}}', 'price', $this->decimal(10, 2)->notNull());

        $this->addColumn('{{%order_item}}', 'amount', $this->integer()->defaultValue(0));
        $this->addColumn('{{%order_item}}', 'offer_id', $this->integer()->notNull());
        $this->addColumn('{{%order_item}}', 'price', $this->decimal(10, 2)->notNull());
        $this->createIndex('offer_id', '{{%order_item}}', 'offer_id');
        $this->addForeignKey('offer_fk', '{{%order_item}}', 'offer_id', '{{%product_variant}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->createTable('{{%order_status}}', [
            'id' => $this->primaryKey(),
            'is_active' => $this->boolean()->notNull()->defaultValue(false),
            'name' => $this->string(),
        ], $this->getTableOptions());

        $this->createIndex('status_id', '{{%order}}', 'status_id');
        $this->addForeignKey('status_fk', '{{%order}}', 'status_id', '{{%order_status}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('status_fk', '{{%order}}');
        $this->dropIndex('status_id', '{{%order}}');
        $this->dropTable('{{%order_status}}');


        $this->dropForeignKey('offer_fk', '{{%order_item}}');
        $this->dropIndex('offer_id', '{{%order_item}}');

        $this->dropColumn('{{%order_item}}', 'amount');
        $this->dropColumn('{{%order_item}}', 'offer_id');
        $this->dropColumn('{{%order_item}}', 'price');

        $this->dropColumn('{{%order}}', 'telephone');
        $this->dropColumn('{{%order}}', 'email');
        $this->dropColumn('{{%order}}', 'address');
        $this->dropColumn('{{%order}}', 'description');
        $this->dropColumn('{{%order}}', 'status_id');
        $this->dropColumn('{{%order}}', 'user_id');
        $this->dropColumn('{{%order}}', 'price');
    }
}
