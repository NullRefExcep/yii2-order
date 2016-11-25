<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\components;


use pheme\settings\components\Settings;
use Yii;
use yii\base\Model;

abstract class SettingsModel extends Model
{
    public function save($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        /** @var Settings $settings */
        $settings = Yii::$app->get('settings');

        foreach ($this->attributes() as $key) {
            $settings->set($key, $this->{$key}, $this->getSection());
        }
        return true;
    }

    public abstract function getSection();

    public function init()
    {
        parent::init();

        /** @var Settings $settings */
        $settings = Yii::$app->get('settings');

        foreach ($this->attributes() as $key) {
            $this->{$key} = $settings->get($key, $this->getSection());
        }
    }
}