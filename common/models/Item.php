<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property string $title
 * @property int $price
 * @property int $quantity
 *
 * @property OrderItem[] $orderItems
 */
class Item extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['quantity', 'default', 'value' => 99],
            [['title', 'price'], 'required'],
            [['price', 'quantity'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'price' => 'Price',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderItemQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['item_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ItemQuery(get_called_class());
    }
}
