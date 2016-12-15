<?php
namespace frontend\models;

use yii\base\Model;
use common\models\Project;
use yii\db\Exception;
use Yii;

/**
 * Signup form
 */
class ProjectForm extends Project
{
    public $name;
    public $owner_id;
    public $client_id;
    public $description;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string'],
            ['name', 'required'],
            [['owner_id', 'client_id', 'status'], 'integer']
        ];
    }
    
    public function addProject(){
        
        $project = new Project();
        
        $project->name = $this->name;
        $project->status = $this->status ? $this->status : Project::STATUS_NEW;
        $project->owner_id = $this->owner_id ? $this->owner_id : Yii::$app->user->identity->id;
        $project->client_id = $this->client_id;
        $project->description = $this->description;
        
        
        if ($project->save(FALSE)){
            return TRUE;
        }
        $this->addErrors($project->getErrors());
        throw new Exception('Projekt nie został dodany.');
    }

}
