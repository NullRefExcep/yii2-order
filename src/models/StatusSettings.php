<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */

namespace nullref\order\models;


use nullref\order\components\SettingsModel;
use pheme\settings\components\Settings;
use Yii;
use yii\helpers\Json;

class StatusSettings extends SettingsModel
{
    /** Section in setting table */
    const SETTINGS_SECTION = 'order.status';

    /** Settings */
    public $new;
    public $return;
    public $delivery;
    public $finalized;

    /**
     * @return OrderStatus
     * @throws \yii\base\InvalidConfigException
     */
    public static function getNewOrderStatus()
    {
        return self::getStatus('new');
    }

    /**
     * Return order status by setting key
     *
     * @param $name
     * @return OrderStatus
     * @throws \yii\base\InvalidConfigException
     */
    protected static function getStatus($name)
    {
        /** @var Settings $settings */
        $settings = Yii::$app->get('settings');
        $id = $settings->get($name, self::SETTINGS_SECTION);
        $status = Yii::$app->getDb()->cache(function () use ($id) {
            return OrderStatus::find()->where(['id' => $id])->one();
        });
        if ($status === null) {
            return OrderStatus::find()->one();
        }
        return $status;
    }

    /**
     * @return OrderStatus[]
     */
    public static function getReturnStatuses()
    {
        return self::getStatuses('return');
    }

    /**
     * Return order list of statuses by setting key
     * @param $name
     * @return OrderStatus
     * @throws \yii\base\InvalidConfigException
     */
    protected static function getStatuses($name)
    {
        /** @var Settings $settings */
        $settings = Yii::$app->get('settings');
        $id = Json::decode($settings->get($name, self::SETTINGS_SECTION));
        $status = Yii::$app->getDb()->cache(function () use ($id) {
            return OrderStatus::find()->where(['id' => $id])->all();
        });
        if ($status === null) {
            return OrderStatus::find()->all();
        }
        return $status;
    }

    /**
     * @return OrderStatus
     */
    public static function getDeliveryStatus()
    {
        return self::getStatus('delivery');
    }

    /**
     * @return OrderStatus
     */
    public static function getFinalizedStatuses()
    {
        return self::getStatuses('finalized');
    }

    /**
     * Convert json string to list
     */
    public function init()
    {
        parent::init();
        $this->return = Json::decode($this->return);
        $this->finalized = Json::decode($this->finalized);
    }

    /**
     * Convert return list to json string
     *
     * @param bool $runValidation
     * @return bool
     */
    public function save($runValidation = true)
    {
        $this->return = Json::encode($this->return);
        $this->finalized = Json::encode($this->finalized);
        return parent::save($runValidation);
    }

    /**
     * Implement abstract method
     *
     * @return string
     */
    public function getSection()
    {
        return self::SETTINGS_SECTION;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $attributes = ['new', 'delivery'];
        return [
            [$attributes, 'required'],
            [$attributes, 'number'],
            [['return', 'finalized'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'new' => Yii::t('order', 'New order status'),
            'return' => Yii::t('order', 'Return statuses'),
            'finalized' => Yii::t('order', 'Finalized statuses'),
            'delivery' => Yii::t('order', 'Delivery status'),
        ];
    }
}
