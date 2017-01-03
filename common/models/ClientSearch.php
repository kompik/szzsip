<?php

namespace common\models;

use yii\data\ActiveDataProvider;

class ClientSearch extends Client // extends from Tour see?
{
    // add the public attributes that will be used to store the data to be search
    public $acronym;
    public $name;
    public $status;
    public $creator;
    public $created;
    public $type;
    

    // now set the rules to make those attributes safe
    public function rules()
    {
        return [
            // ... more stuff here
            [['acronym', 'name', 'status',  'creator', 'created', 'type'], 'safe'],
            // ... more stuff here
        ];
    }
    
// ... model continues here
    public function search($params)
    {
        // create ActiveQuery
        $query = Client::find()->having(['<>', 'status', Client::STATUS_DELETED]);
        // Important: lets join the query with our previously mentioned relations
        // I do not make any other configuration like aliases or whatever, feel free
        // to investigate that your self
        $query->joinWith(['creator']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Important: here is how we set up the sorting
        // The key is the attribute name on our "TourSearch" instance
        $dataProvider->sort->attributes['acronym'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['client.acronym' => SORT_ASC],
            'desc' => ['client.acronym' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['creator'] = [
            'asc' => ['user.id' => SORT_ASC],
            'desc' => ['user.id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['client.status' => SORT_ASC],
            'desc' => ['client.status' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['name'] = [
            'asc' => ['client.id' => SORT_ASC],
            'desc' => ['client.id' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['created'] = [
            'asc' => ['client.created_at' => SORT_ASC],
            'desc' => ['client.created_at' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['type'] = [
            'asc' => ['client.type' => SORT_ASC],
            'desc' => ['client.type' => SORT_DESC],
        ];
        // No search? Then return data Provider
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
        ])
        ->andFilterWhere(['like', 'client.acronym', $this->acronym])
        ->andFilterWhere(['like', 'user.id', $this->creator])
        ->andFilterWhere(['like', 'client.status', $this->status])
        ->andFilterWhere(['like', 'client.type', $this->type])
        ->andFilterWhere(['like', 'client.id', $this->name]);
        
        
        if(isset($this->created) && $this->created!=''){
            $date_explode = explode("-", $this->created);
            $date1 = trim($date_explode[0]);
            $date2= trim($date_explode[1]);
            $query->andFilterWhere(['between', Client::tableName().'.created_at', strtotime($date1), strtotime($date2)]);
        }
        // We have to do some search... Lets do some magic


        return $dataProvider;
    }
}