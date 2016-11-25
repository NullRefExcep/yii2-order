<?php

namespace nullref\order\models;

use nullref\useful\traits\Mappable;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class OrderType
 * @package nullref\order\models
 *
 * @property integer $id
 *
 * @property string $name
 *
 * @property integer $percent
 *
 */
class OrderType extends ActiveRecord
{
    use Mappable;

    public static function tableName()
    {
        return '{{%order_type}}';
    }

    public function rules()
    {
        return [
            [['id', 'percent'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('order', 'ID'),
            'name' => Yii::t('order', 'Title'),
            'percent' => Yii::t('order', 'Percent (%)'),
        ];
    }
}