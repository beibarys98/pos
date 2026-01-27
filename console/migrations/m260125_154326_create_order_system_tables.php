<?php

use yii\db\Migration;

class m260125_154326_create_order_system_tables extends Migration
{
    public function safeUp()
    {
        /** ITEM TABLE */
        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'price' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
        ]);

        /** ORDER TABLE */
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'key_number' => $this->string(50)->notNull()->unique(),
            'status' => $this->string(30)->notNull(),
            'total' => $this->integer()->notNull(),
        ]);

        /** ORDER_ITEM TABLE */
        $this->createTable('{{%order_item}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'price' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
        ]);

        /** INDEXES */
        $this->createIndex('idx-order_item-order_id', '{{%order_item}}', 'order_id');
        $this->createIndex('idx-order_item-item_id', '{{%order_item}}', 'item_id');

        /** FOREIGN KEYS */
        $this->addForeignKey(
            'fk-order_item-order_id',
            '{{%order_item}}',
            'order_id',
            '{{%order}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-order_item-item_id',
            '{{%order_item}}',
            'item_id',
            '{{%item}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-order_item-item_id', '{{%order_item}}');
        $this->dropForeignKey('fk-order_item-order_id', '{{%order_item}}');

        $this->dropTable('{{%order_item}}');
        $this->dropTable('{{%order}}');
        $this->dropTable('{{%item}}');
    }
}
