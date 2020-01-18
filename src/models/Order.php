<?php

namespace nullref\order\models;

use app\components\pricing\ClientRate;
use app\components\pricing\ExtraRateInterface;
use app\components\pricing\GuestRate;
use nullref\useful\traits\SessionModel;
use nullref\order\behaviors\PriceCalculator;
use nullref\order\behaviors\StockOrderBehavior;
use nullref\order\events\OrderItemEvent;
use nullref\order\interfaces\Order as OrderInterface;
use app\modules\user\models\User;
use nullref\useful\traits\Mappable;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use nullref\order\interfaces\OrderItem as IOrderItem;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property string $full_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $telephone
 * @property string $email
 * @property string $address
 * @property string $description
 * @property integer $status_id
 * @property integer $user_id
 * @property string $price
 * @property integer $payment_id
 * @property integer $delivery_id
 * @property integer $type_id
 * @property string $call_time
 *
 * @property OrderStatus $status
 * @property OrderItem[] $items
 * @property OrderItem[] $itemsRelation
 * @property $payment Payment
 * @property $delivery Delivery
 * @property $user User
 * @property $type OrderType
 */
class Order extends ActiveRecord implements OrderInterface, \Serializable
{
    use Mappable;
    use OfferableOrder;
    use SessionModel;

    /**
     * @var OrderItem[]
     */
    protected $_newItems = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     * @return OrderQuery
     */
    public static function find()
    {
        return new OrderQuery(self::className());
    }

    /**
     * @param $attributes
     * @return Order
     */
    public static function createDefault($attributes = [])
    {
        $order = new self();

        $order->setAttributes(array_merge([
            'telephone' => '99-999-999',
            'payment_id' => Payment::getDefault()->id,
            'delivery_id' => Delivery::getDefault()->id,
            'status_id' => StatusSettings::getNewOrderStatus()->id,
        ], $attributes));

        return $order;
    }

    /**
     * @return OrderItem
     * @throws \yii\base\InvalidConfigException
     */
    public static function createItem()
    {
        return Yii::createObject(OrderItem::className());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'default', 'value' => 0],
            [['price'], 'number'],
            [['created_at', 'updated_at', 'status_id', 'user_id', 'payment_id', 'delivery_id'], 'integer'],
            [['price', 'telephone', 'payment_id', 'delivery_id', 'type_id'], 'required'],
            [['full_name', 'telephone', 'email', 'address', 'call_time', 'description'], 'string', 'max' => 255],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(),
                'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * @return bool
     */
    public function isLockStatus()
    {
        $statusIds = array_unique(array_map(function (OrderStatus $status) {
            return $status->id;
        }, array_merge(StatusSettings::getReturnStatuses(), StatusSettings::getFinalizedStatuses())));

        return in_array($this->status_id, $statusIds, false);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
            'priceCalculator' => [
                'class' => PriceCalculator::className(),
            ],
            'stock' => [
                'class' => StockOrderBehavior::className(),
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('order', 'ID'),
            'full_name' => Yii::t('order', 'Full Name'),
            'created_at' => Yii::t('order', 'Created At'),
            'updated_at' => Yii::t('order', 'Updated At'),
            'telephone' => Yii::t('order', 'Telephone'),
            'email' => Yii::t('order', 'Email'),
            'address' => Yii::t('order', 'Address'),
            'description' => Yii::t('order', 'Order Note'),
            'status' => Yii::t('order', 'Status'),
            'status_id' => Yii::t('order', 'Status'),
            'user_id' => Yii::t('order', 'User ID'),
            'price' => Yii::t('order', 'Sum'),
            'payment_id' => Yii::t('order', 'Payment'),
            'type_id' => Yii::t('order', 'Type'),
            'delivery_id' => Yii::t('order', 'Delivery'),
            'call_time' => Yii::t('order', 'Comfortable time to call'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['id' => 'status_id'])->inverseOf('orders');
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @inheritdoc
     */
    public function removeItem(IOrderItem $model)
    {
        /** @var OrderItem $item */
        if ($item = OrderItem::findItem($this, $model)) {
            if (!$item->isNewRecord) {
                $item->delete();
            }
            if (isset($this->_newItems[$item->getId()])) {
                unset($this->_newItems[$item->getId()]);
            }
            $this->trigger(self::EVENT_ITEM_REMOVE, new OrderItemEvent(['item' => $model]));
        }
    }

    /**
     * Custom serialization
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->attributes,
            $this->_newItems,
        ]);
    }

    /**
     * Custom unserialization
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($this->attributes, $this->_newItems) = unserialize($serialized);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        foreach ($this->_newItems as $newItem) {
            /** @var $newItem OrderItem */
            $newItem->link('orderRelation', $this);
        }
    }

    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        $result = parent::validate($attributeNames, $clearErrors);

        $itemErrors = [];

        foreach ($this->items as $key => $item) {
            $itemValidate = $item->validate();
            if (!$itemValidate) {
                $itemErrors[$key] = $item->getErrors();
            }
            $result = $result && $itemValidate;
        }
        if (count($itemErrors)) {
            $this->addErrors(['items' => $itemErrors]);
        }

        return $result;
    }

    /**
     * Delete order items before delete order
     * @return bool
     * @throws \Exception
     */
    public function beforeDelete()
    {
        $items = $this->getItems();
        foreach ($items as $item) {
            $item->delete();
        }
        return parent::beforeDelete();
    }

    /**
     * @return OrderItem[]
     */
    public function getItems()
    {
        if ($this->isNewRecord) {
            return $this->_newItems;
        }
        return array_merge($this->itemsRelation, $this->_newItems);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['id' => 'delivery_id']);
    }

    /**
     * @param IOrderItem $model
     * @return mixed
     */
    public function addItem(IOrderItem $model)
    {
        $selectedItem = OrderItem::findItem($this, $model);

        if ($selectedItem === null) {
            $this->addNewItem($model);
        } else {
            $selectedItem->setAmount($selectedItem->getAmount() + $model->getAmount());
            $this->_newItems[$selectedItem->getId()] = $selectedItem;
        }
        $this->trigger(self::EVENT_ITEM_ADD, new OrderItemEvent(['item' => $model]));
    }

    /**
     * @param IOrderItem $model
     */
    protected function addNewItem(IOrderItem $model)
    {
        $itemId = 'new_' . uniqid(count($this->_newItems));
        $model->setId($itemId);
        $model->setOrder($this);
        $this->_newItems[$itemId] = $model;
    }

    /**
     * @param $id
     * @return IOrderItem|null
     */
    public function getItem($id)
    {
        if (isset($this->_newItems[$id])) {
            return $this->_newItems[$id];
        }

        return $this->getItemsRelation()->where(['id' => $id])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemsRelation()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id'])->inverseOf('orderRelation');
    }

    /**
     * @return ExtraRateInterface
     */
    public function getExtraRate()
    {
        if ($this->user) {
            return new ClientRate($this->user);
        }
        return new GuestRate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(OrderType::className(), ['id' => 'type_id']);
    }

    /**
     * @return float
     */
    protected function getItemsPrice()
    {
        return array_reduce($this->items, function ($sum, OrderItem $item) {
            return $sum + $item->getPrice();
        }, 0);
    }

   
}

?>
