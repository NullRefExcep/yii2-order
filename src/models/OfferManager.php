<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\models;


use nullref\order\interfaces\Offer;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Object;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;

class OfferManager extends Object
{
    /** @var string */
    public $offerSearch = null;

    protected $searchModel;

    public function init()
    {
        parent::init();
        if ($this->offerSearch === null) {
            throw new InvalidConfigException('"offerSearch" must be set');
        }
    }

    /**
     * @param $params
     * @return DataProviderInterface
     */
    public function getOfferDataProvider($params)
    {
        return call_user_func([$this->getSearchModel(), 'search'], $params);
    }

    /**
     * @return Model
     * @throws InvalidConfigException
     */
    public function getSearchModel()
    {
        if ($this->searchModel === null) {
            $this->searchModel = Yii::createObject($this->offerSearch);
        }
        return $this->searchModel;
    }

    /**
     * @param $id
     * @return array|null|Offer
     */
    public function findOfferById($id)
    {
        /** @var ActiveQuery $query */
        $query = call_user_func([$this->getSearchModel(), 'find']);
        return $query->andWhere(['id' => $id])->one();
    }
}