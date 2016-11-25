<?php

namespace nullref\order\models;

use Yii;
use nullref\useful\traits\Mappable;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property integer $status
 * @property string $name
 * @property string $note
 */
class Payment extends ActiveRecord
{
    use Mappable;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
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

    public static function createDefault()
    {
        $model = new self();
        $model->name = 'Default payment method';
        $model->status = true;
        $model->save();
        return $model;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 1],
            [['status'], 'integer'],
            [['name', 'status'], 'required'],
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
