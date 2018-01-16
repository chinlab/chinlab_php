<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\article\models\Tad;

/**
 * AdvertQuery represents the model behind the search form about `app\models\Advert`.
 */
class AdvertQuery extends Tad
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ad_id', 'is_ok'], 'integer'],
            [['ad_title', 'ad_img', 'ad_url'], 'safe'],
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
        $query = Tad::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'sort' => [
        		'defaultOrder' => [
        			'ad_id' => SORT_DESC,
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
            'ad_id' => $this->ad_id,
            'is_ok' => $this->is_ok,
        ]);

        $query->andFilterWhere(['like', 'ad_title', $this->ad_title])
            ->andFilterWhere(['like', 'ad_img', $this->ad_img])
            ->andFilterWhere(['like', 'ad_url', $this->ad_url]);

        return $dataProvider;
    }
}
