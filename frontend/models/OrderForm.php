<?php
namespace frontend\models;

use yii\base\Model;
use common\models\Order;
use yii\db\Exception;
use Yii;

/**
 * Signup form
 */
class OrderForm extends Order
{
//    public $name;
//    public $owner_id;
//    public $client_id;
//    public $executive_id;
//    public $description;
//    public $status;
//
//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return [
//            [['name', 'description'], 'string'],
//            ['name', 'required'],
//            [['owner_id', 'client_id', 'status', 'executive_id'], 'integer']
//        ];
//    }
    
    public function addOrder(){
        
        $order = new Order();
        
        $order->name = $this->name;
        $order->status = $this->status ? $this->status : Project::STATUS_NEW;
        $order->owner_id = $this->owner_id ? $this->owner_id : Yii::$app->user->identity->id;
        $order->client_id = $this->client_id;
        $order->executive_id = $this->executive_id;
        $order->description = $this->description;
        $order->project_id = $this->project_id;
        
        
        if ($order->save()){
            return TRUE;
        }
        $this->addErrors($order->getErrors());
        throw new Exception('Zlecenie nie zostało dodane.');
    }

}
