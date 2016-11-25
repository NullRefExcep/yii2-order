<?php

namespace nullref\order\models;

use Yii;
use nullref\useful\traits\Mappable;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "delivery".
 *
 * @property integer $id
 * @property string $name
 * @property string $note
 */
class Delivery extends ActiveRecord
{
    use Mappable;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * @return self
     */
    public static function getDefault()
    {
        $record = self::find()->one();
        if ($record === null) {
            $record = self::createDefault();
        }
        return $record;
    }

    /**
     * @return Delivery
     */
    public static function createDefault()
    {
        $model = new self();
        $model->name = 'Default delivery method';
        $model->save();
        return $model;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['name', 'note', 'status'], 'required'],
            [['note'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => Yii::t('order', 'Active'),
            'name' => Yii::t('order', 'Name'),
            'note' => Yii::t('order', 'Note'),
        ];
    }
}
