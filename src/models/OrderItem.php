<?php

namespace nullref\order\models;

use app\modules\catalog\interfaces\Stockable;
use nullref\order\behaviors\StockItemBehavior;
use nullref\order\components\FakeRelation;
use nullref\order\events\OrderItemEvent;
use nullref\order\interfaces\Offer as OfferInterface;
use nullref\order\interfaces\Order as OrderInterface;
use nullref\order\interfaces\OrderItem as OrderItemInterface;
use nullref\useful\traits\GetDefinition;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%order_item}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $amount
 * @property integer $offer_id
 * @property string $price
 *
 * @property OfferInterface $offer
 * @property OfferInterface $offerRelation
 *
 * @property Order $order
 * @property Order $orderRelation
 */
class OrderItem extends ActiveRecord implements OrderItemInterface, \Serializable
{
    use GetDefinition;

    const EVENT_SAVE = 'eventSave';

    /** @var string */
    public $offerModel = null;

    protected $_tmpId;
    protected $_order;
    protected $_offer;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_item}}';
    }

    /**
     * @param OrderInterface $order
     * @param OrderItemInterface $item
     * @return OrderItemInterface|null
     */
    public static function findItem(OrderInterface $order, OrderItemInterface $item)
    {
        /** @var $order Order */
        /** @var $item OrderItem */
        if ($order->isNewRecord) {
            $items = $order->getItems();
            foreach ($items as $position) {
                if ($position->offer_id === $item->offer_id) {
                    return $position;
                }
            }
        }
        return $order->getItemsRelation()->andWhere(['offer_id' => $item->offer_id])->one();
    }

    /**
     * @return int
     */
    public function getId()
    {
        if ($this->isNewRecord) {
            return $this->_tmpId;
        }
        return $this->id;
    }

    /**
     * @param $value
     */
    public function setPrice($value)
    {
        $this->price = $value;
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if ($this->offerModel === null) {
            if (($def = Yii::$container->getDefinitions()[static::className()]) && (isset($def['offerModel']))) {
                $this->offerModel = $def['offerModel'];
            }
        }
        if ($this->offerModel === null) {
            throw new InvalidConfigException('"offerModel" must be set');
        }
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'stock' => [
                'class' => StockItemBehavior::className(),
            ],
        ];
    }

    /**
     * Custom serialization
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->offerModel,
            $this->_tmpId,
            //   $this->_offer,
            $this->attributes,
        ]);
    }

    /**
     * Custom unserialization
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        list(
            $this->offerModel,
            $this->_tmpId,
            ) = $data;
        $this->attributes = $data[2];
        if ($this->offer_id) {
            $this->_offer = call_user_func([$this->offerModel, 'findOne'], $this->offer_id);;
        }
    }


    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->orderRelation;
    }

    /**
     * @param OrderInterface $order
     */
    public function setOrder(OrderInterface $order)
    {
        if ($this->isNewRecord) {
            $this->_order = $order;
        }
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $this->trigger(self::EVENT_SAVE);
        $isNewRecord = $this->isNewRecord;
        $result = parent::save($runValidation, $attributeNames);
        if ($this->order && !$isNewRecord) {
            $this->order->trigger(Order::EVENT_ITEM_UPDATE, new OrderItemEvent(['item' => $this]));
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['amount', 'default', 'value' => 1],
            ['amount', 'number', 'min' => 1],
            [['order_id', 'created_at', 'updated_at', 'amount', 'offer_id'], 'integer'],
            [['offer_id', 'price'], 'required'],
            [['price'], 'number'],
            [['offer_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->offerModel, 'targetAttribute' => ['offer_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            ['amount', 'availableAmount'],
        ];
    }

    /**
     * @param $attribute
     */
    public function availableAmount($attribute)
    {

        $offer = $this->getOffer();
        if ($offer instanceof Stockable) {
            $oldAmount = $this->getOldAttribute($attribute);
            $currentAmount = $this->getAttribute($attribute);
            $offerAmount = $offer->getInStock();
            if ($currentAmount - $oldAmount > $offerAmount) {
                $this->addError($attribute, Yii::t('catalog', 'More than stated amounts are available'));
            }

        }
    }

    /**
     * @return OfferInterface
     */
    public function getOffer()
    {
        return $this->offerRelation;
    }

    /**
     * @param OfferInterface $offer
     */
    public function setOffer(OfferInterface $offer)
    {
        $this->_offer = $offer;
        $this->offer_id = $offer->getId();
        $this->price = $offer->getPrice();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('order', 'ID'),
            'order_id' => Yii::t('order', 'Order ID'),
            'created_at' => Yii::t('order', 'Created At'),
            'updated_at' => Yii::t('order', 'Updated At'),
            'amount' => Yii::t('order', 'Amount'),
            'offer_id' => Yii::t('order', 'Offer ID'),
            'price' => Yii::t('order', 'Price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getOfferRelation()
    {
        if ($this->isNewRecord) {
            return new FakeRelation($this->_offer, ['id' => 'offer_id'], ['modelClass' => $this->offerModel]);
        }
        return $this->hasOne($this->offerModel, ['id' => 'offer_id'])->inverseOf('orderItems');
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getOrderRelation()
    {
        if ($this->isNewRecord) {
            return new FakeRelation($this->_order, ['id' => 'order_id'], ['modelClass' => Order::className()]);
        }
        return $this->hasOne(Order::className(), ['id' => 'order_id'])->inverseOf('itemsRelation');
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return $this->price * $this->amount;
    }

    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        if ($this->isNewRecord) {
            $this->_tmpId = $id;
        }
        $this->id = $this->_tmpId;
    }

    /**
     * @param $quantity
     */
    public function setAmount($quantity)
    {
        $this->amount = $quantity;
    }
}
