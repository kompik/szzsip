<?php

namespace common\models;

use yii\data\ActiveDataProvider;

class ProjectSearch extends Project // extends from Tour see?
{
    // add the public attributes that will be used to store the data to be search
    public $owner;
//    public $client;
    
        public function attributeLabels()
    {
        return [

            'Owner' => Yii::t('app', 'Właściciel'),
        ];
    }
 
    // now set the rules to make those attributes safe
    public function rules()
    {
        return [
            // ... more stuff here
            [['owner'], 'safe'],
            // ... more stuff here
        ];
    }
// ... model continues here
    public function search($params)
    {
        // create ActiveQuery
        $query = Project::find();
        // Important: lets join the query with our previously mentioned relations
        // I do not make any other configuration like aliases or whatever, feel free
        // to investigate that your self
        $query->joinWith(['owner']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Important: here is how we set up the sorting
        // The key is the attribute name on our "TourSearch" instance
    //    $dataProvider->sort->attributes['client'] = [
    //        // The tables are the ones our relation are configured to
    //        // in my case they are prefixed with "tbl_"
    //        'asc' => ['user.username' => SORT_ASC],
    //        'desc' => ['user.username' => SORT_DESC],
    //    ];
        // Lets do the same with country now
        $dataProvider->sort->attributes['owner'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];
        // No search? Then return data Provider
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        // We have to do some search... Lets do some magic
        $query->andFilterWhere([
            //... other searched attributes here
        ])
        // Here we search the attributes of our relations using our previously configured
        // ones in "TourSearch"
    //    ->andFilterWhere(['like', 'user.username', $this->client])
        ->andFilterWhere(['like', 'user.username', $this->owner]);

        return $dataProvider;
    }
}