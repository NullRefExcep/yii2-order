<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\components;


use yii\base\Object;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRelationTrait;

/**
 * Class FakeRelation
 * @package nullref\order\components
 *
 * @property mixed $instance
 */
class FakeRelation extends Object implements ActiveQueryInterface
{
    use ActiveRelationTrait;

    public $modelClass;

    protected $_instance;

    public function __construct($instance, $link, $config = [])
    {
        $this->_instance = $instance;
        $this->link = $link;
        parent::__construct($config);
    }

    public function getInstance()
    {
        return $this->_instance;
    }

    public function setInstance($value)
    {
        $this->_instance = $value;
    }

    /**
     * @inheritdoc
     */
    public function asArray($value = true)
    {
    }

    /**
     * @inheritdoc
     */
    public function indexBy($column)
    {
    }

    /**
     * @inheritdoc
     */
    public function with()
    {
    }

    /**
     * @inheritdoc
     */
    public function via($relationName, callable $callable = null)
    {
    }

    /**
     * @inheritdoc
     */
    public function findFor($name, $model)
    {
        return $this->_instance;
    }

    /**
     * @inheritdoc
     */
    public function all($db = null)
    {

    }

    /**
     * @inheritdoc
     */
    public function one($db = null)
    {
    }

    /**
     * @inheritdoc
     */
    public function count($q = '*', $db = null)
    {
    }

    /**
     * @inheritdoc
     */
    public function exists($db = null)
    {
    }


    /**
     * @inheritdoc
     */
    public function where($condition)
    {

    }


    /**
     * @inheritdoc
     */
    public function andWhere($condition)
    {
    }


    /**
     * @inheritdoc
     */
    public function orWhere($condition)
    {
    }


    /**
     * @inheritdoc
     */
    public function filterWhere(array $condition)
    {
    }

    /**
     * @inheritdoc
     */
    public function andFilterWhere(array $condition)
    {
    }


    /**
     * @inheritdoc
     */
    public function orFilterWhere(array $condition)
    {
    }

    /**
     * @inheritdoc
     */
    public function orderBy($columns)
    {
    }

    /**
     * @inheritdoc
     */
    public function addOrderBy($columns)
    {
    }

    /**
     * @inheritdoc
     */
    public function limit($limit)
    {
    }

    /**
     * @inheritdoc
     */
    public function offset($offset)
    {
    }
}