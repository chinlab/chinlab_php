<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\patient\models\UserOrder;

/**
 * OrderQuery represents the model behind the search form about `app\models\Order`.
 */
class OrderQuery extends UserOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'hospital_id', 'order_type', 'order_gender', 'order_age', 'order_city', 'order_state', 'create_time', 'update_time', 'is_delete'], 'integer'],
            [['order_number', 'order_time', 'order_name', 'order_phone', 'order_city_name', 'order_date', 'disease_name', 'disease_des', 'order_update_time', 'order_design', 'order_file'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserOrder::find()->asArray();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'sort' => [
        		'defaultOrder' => [
        			  'create_time' => SORT_DESC,
        			]
        		],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'hospital_id' => $this->hospital_id,
            'order_type' => $this->order_type,
            'order_time' => $this->order_time,
            'order_gender' => $this->order_gender,
            'order_age' => $this->order_age,
            'order_city' => $this->order_city,
            'order_date' => $this->order_date,
            'order_state' => $this->order_state,
            'order_update_time' => $this->order_update_time,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'is_delete' => $this->is_delete,
        ]);

        $query->andFilterWhere(['like', 'order_number', $this->order_number])
            ->andFilterWhere(['like', 'order_name', $this->order_name])
            ->andFilterWhere(['like', 'order_phone', $this->order_phone])
            ->andFilterWhere(['like', 'order_city_name', $this->order_city_name])
            ->andFilterWhere(['like', 'disease_name', $this->disease_name])
            ->andFilterWhere(['like', 'disease_des', $this->disease_des])
            ->andFilterWhere(['like', 'order_design', $this->order_design])
            ->andFilterWhere(['like', 'order_file', $this->order_file]);

        return $dataProvider;
    }
}
