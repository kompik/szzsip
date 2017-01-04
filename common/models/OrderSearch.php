<?php

namespace common\models;

use yii\data\ActiveDataProvider;

class OrderSearch extends Order // extends from Tour see?
{
    // add the public attributes that will be used to store the data to be search
    public $owner;
    public $client;
    public $executive;
    public $status;
    public $name;
    public $created;
    public $project;
    

    // now set the rules to make those attributes safe
    public function rules()
    {
        return [
            // ... more stuff here
            [['owner', 'client', 'status', 'name', 'created', 'executive', 'project'], 'safe'],
            // ... more stuff here
        ];
    }
    
    public function attributeLabels()
    {
        return [
            
            'owner' => Yii::t('app', 'Właściciel zlecenia'),
            'client' => Yii::t('app', 'Klient'),
            'executive' => Yii::t('app', 'Wykonawca'),
            'status' => Yii::t('app', 'Status'),
            'name' => Yii::t('app', 'Nazwa zlecenia'),
            'created' => Yii::t('app', 'Utworzony'),
            'project' => Yii::t('app', 'Projekt'),
        ];
    }
// ... model continues here
    public function search($params, $project_id = null, $client_id = null)
    {
        // create ActiveQuery
        $query = Order::find()->having(['<>', 'status', Project::STATUS_DELETED]);
        if ($project_id) {
            $query->andHaving(['project_id' => $project_id]);
        }
        if ($client_id) {
            $query->andHaving(['client_id' => $client_id]);
        }
        // Important: lets join the query with our previously mentioned relations
        // I do not make any other configuration like aliases or whatever, feel free
        // to investigate that your self
        $query->joinWith(['owner', 'client', 'project']);

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
        $dataProvider->sort->attributes['project'] = [
            'asc' => ['project.id' => SORT_ASC],
            'desc' => ['project.id' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['status'] = [
            'asc' => ['order.status' => SORT_ASC],
            'desc' => ['order.status' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['name'] = [
            'asc' => ['order.name' => SORT_ASC],
            'desc' => ['order.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['created'] = [
            'asc' => ['order.created_at' => SORT_ASC],
            'desc' => ['order.created_at' => SORT_DESC],
        ];
        // No search? Then return data Provider
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
        ])
        ->andFilterWhere(['like', 'client.id', $this->client])
        ->andFilterWhere(['like', 'user.id', $this->owner])
        ->andFilterWhere(['like', 'executive.id', $this->executive])
        ->andFilterWhere(['like', 'project.id', $this->project])
        ->andFilterWhere(['like', 'order.status', $this->status])
        ->andFilterWhere(['like', 'order.name', $this->name]);
        
        
        if(isset($this->created) && $this->created!=''){
            $date_explode = explode("TO", $this->created);
            $date1 = trim($date_explode[0]);
            $date2= trim($date_explode[1]);
            $query->andFilterWhere(['between', 'created_at', $date1, $date2]);
        }
        // We have to do some search... Lets do some magic


        return $dataProvider;
    }
}