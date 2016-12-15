<?php

namespace common\models;

use yii\data\ActiveDataProvider;

class ProjectSearch extends Project // extends from Tour see?
{
    // add the public attributes that will be used to store the data to be search
    public $owner;
    public $client;
    public $status;
    public $name;
    public $created;
    
    public function attributeLabels()
    {
        return [

            'Owner' => Yii::t('app', 'Właściciel projektu'),
        ];
    }
 
    // now set the rules to make those attributes safe
    public function rules()
    {
        return [
            // ... more stuff here
            [['owner', 'client', 'status', 'name', 'created'], 'safe'],
            // ... more stuff here
        ];
    }
// ... model continues here
    public function search($params)
    {
        // create ActiveQuery
        $query = Project::find()->having(['<>', 'status', Project::STATUS_DELETED]);
        // Important: lets join the query with our previously mentioned relations
        // I do not make any other configuration like aliases or whatever, feel free
        // to investigate that your self
        $query->joinWith(['owner', 'client']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Important: here is how we set up the sorting
        // The key is the attribute name on our "TourSearch" instance
        $dataProvider->sort->attributes['client'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['client.id' => SORT_ASC],
            'desc' => ['client.id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['owner'] = [
            'asc' => ['user.id' => SORT_ASC],
            'desc' => ['user.id' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['status'] = [
            'asc' => ['project.status' => SORT_ASC],
            'desc' => ['project.status' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['name'] = [
            'asc' => ['project.name' => SORT_ASC],
            'desc' => ['project.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['created'] = [
            'asc' => ['project.created_at' => SORT_ASC],
            'desc' => ['project.created_at' => SORT_DESC],
        ];
        // No search? Then return data Provider
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
        ])
        ->andFilterWhere(['like', 'client.id', $this->client])
        ->andFilterWhere(['like', 'user.id', $this->owner])
        ->andFilterWhere(['like', 'project.status', $this->status])
        ->andFilterWhere(['like', 'project.name', $this->name]);
        
        
        if(isset($this->created) && $this->created!=''){
            $date_explode = explode("TO", $this->created);
            $date1 = trim($date_explode[0]);
            $date2= trim($date_explode[1]);
            $query->andFilterWhere(['between', 'created_at', $date1,$date2]);
        }
        // We have to do some search... Lets do some magic


        return $dataProvider;
    }
}