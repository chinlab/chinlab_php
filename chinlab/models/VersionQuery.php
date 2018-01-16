<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\patient\models\Version;

/**
 * VersionQuery represents the model behind the search form about `app\models\Version`.
 */
class VersionQuery extends Version
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['version_id'], 'integer'],
            [['version_name', 'version_design', 'version_url', 'version_time', 'version_device'], 'safe'],
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
        $query = Version::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
        		'query' => $query,
        		'sort' => [
        				'defaultOrder' => [
        					'version_time' => SORT_DESC,
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
            'version_id' => $this->version_id,
            'version_time' => $this->version_time,
        ]);

        $query->andFilterWhere(['like', 'version_name', $this->version_name])
            ->andFilterWhere(['like', 'version_design', $this->version_design])
            ->andFilterWhere(['like', 'version_url', $this->version_url])
            ->andFilterWhere(['like', 'version_device', $this->version_device]);

        return $dataProvider;
    }
}
