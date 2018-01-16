<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Admin;

/**
 */
class AdminQuery extends Admin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userphone'], 'integer'],
            [['username', 'nickname'], 'safe'],
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
        $query = Admin::find();
        // add conditions that should always apply here
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'sort' => [
        		'defaultOrder' => [
        			'created_at' => SORT_DESC,
        		]
        	],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $check = ['id','status','userphone','username','created_at','updated_at','nickname','email','sys_type'];
        foreach ($params as $key => $value){
        	if(in_array($key, $check))
        	 $this->$key = $value;
        }
        if(!in_array($this->status, ['0','10']))
        	$this->status = NULL;
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        	'userphone' =>  $this->userphone,
        	'status'	=>  $this->status,
            'created_at' => $this->created_at,
        	'updated_at' => $this->updated_at,
        	'sys_type'   => $this->sys_type,
        ]);

        $query->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }
}
