<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\order\models;


use yii\data\ActiveDataProvider;
use yii\db\Expression;

class OrderSearch extends Order
{
    public $totalPriceFrom;
    public $totalPriceTo;
    public $totalPrice;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['totalPrice', 'totalPriceTo', 'totalPriceFrom'], 'safe'];
        return $rules;
    }


    public function search($params)
    {
        $this->load($params);

        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->user_id === '0') {
            $query->andWhere(['user_id' => null]);
        } else {
            $query->andFilterWhere([
                'user_id' => $this->user_id,
            ]);
        }

        $query->select(['order.*', new Expression('SUM(order_item.price * order_item.amount) as `totalPrice`')]);

        $dataProvider->sort->attributes['totalPrice'] = [
            'asc' => ['totalPrice' => SORT_ASC],
            'desc' => ['totalPrice' => SORT_DESC],
        ];
        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        $query->joinWith('itemsRelation');
        $query->with('delivery');
        $query->with('type');
        $query->with('payment');
        $query->with('status');
        $query->with('user');
        $query->groupBy('order.id');

        $query->andFilterWhere(['>=', 'totalPrice', $this->totalPriceFrom]);
        $query->andFilterWhere(['<=', 'totalPrice', $this->totalPriceTo]);
        $query->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'email', $this->email]);

        $query->andFilterWhere([
            'type_id' => $this->type_id,
            'delivery_id' => $this->delivery_id,
            'payment_id' => $this->payment_id,
            'status_id' => $this->status_id,
            'user_id' => $this->user_id,
        ]);

        return $dataProvider;
    }
}
