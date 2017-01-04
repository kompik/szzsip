<?php

namespace common\models;

use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    // add the public attributes that will be used to store the data to be search
    public $username;
    public $name;
    public $status;
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
    public function search($params, $client_id = null)
    {
        // create ActiveQuery
        $query = User::find()->having(['<>', 'status', User::STATUS_DELETED])->orderBy('lastname');
        // Important: lets join the query with our previously mentioned relations
        // I do not make any other configuration like aliases or whatever, feel free
        // to investigate that your self
//        $query->joinWith(['creator']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Important: here is how we set up the sorting
        // The key is the attribute name on our "TourSearch" instance
        $dataProvider->sort->attributes['username'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['user.acronym' => SORT_ASC],
            'desc' => ['user.acronym' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['status'] = [
            'asc' => ['user.status' => SORT_ASC],
            'desc' => ['user.status' => SORT_DESC],
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
        ->andFilterWhere(['like', 'user.name', $this->name])
        ->andFilterWhere(['like', 'user.status', $this->status])
        ->andFilterWhere(['like', 'user.type', $this->type]);
        
        
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