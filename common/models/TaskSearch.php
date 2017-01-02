<?php

namespace common\models;

use yii\data\ActiveDataProvider;

class TaskSearch extends Task
{

    // add the public attributes that will be used to store the data to be search
    public $creator;
    public $status;
    public $name;
    public $created;
    

    // now set the rules to make those attributes safe
    public function rules()
    {
        return [
            // ... more stuff here
            [['creator', 'status', 'name', 'created'], 'safe'],
            // ... more stuff here
        ];
    }
    
    public function attributeLabels()
    {
        return [
            
            'creator' => Yii::t('app', 'Właściciel zlecenia'),
            'client' => Yii::t('app', 'Klient'),
            'executive' => Yii::t('app', 'Wykonawca'),
            'status' => Yii::t('app', 'Status'),
            'name' => Yii::t('app', 'Nazwa'),
            'created' => Yii::t('app', 'Utworzony'),
            'project' => Yii::t('app', 'Projekt'),
        ];
    }
// ... model continues here
    public function search($params, $id = null)
    {
        // create ActiveQuery
        $query = Task::find()->having(['<>', 'status', Task::STATUS_DELETED]);
        // Important: lets join the query with our previously mentioned relations
        // I do not make any other configuration like aliases or whatever, feel free
        // to investigate that your self
        $query->joinWith('creator');
        if ($id) {
            $query->where(['order_id' => $id])->joinWith('orders');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Important: here is how we set up the sorting
        // The key is the attribute name on our "TourSearch" instance

        $dataProvider->sort->attributes['creator'] = [
            'asc' => ['user.id' => SORT_ASC],
            'desc' => ['user.id' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['status'] = [
            'asc' => ['task.status' => SORT_ASC],
            'desc' => ['task.status' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['name'] = [
            'asc' => ['task.name' => SORT_ASC],
            'desc' => ['task.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['created'] = [
            'asc' => ['task.created_at' => SORT_ASC],
            'desc' => ['task.created_at' => SORT_DESC],
        ];
        // No search? Then return data Provider
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
        ])
        ->andFilterWhere(['like', 'user.id', $this->creator])
        ->andFilterWhere(['like', 'task.status', $this->status])
        ->andFilterWhere(['like', 'task.name', $this->name]);
        
        
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