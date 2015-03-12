<?php
/* User Model
 *
 */
class SecurityRequirement extends AppModel 
{
    public $name = 'SecurityRequirement';
    public $belongsTo = array('Method','Countermeasure');
    public $hasMany = array('CountermeasureBind');


    public function getSecurityRequirements($project_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
            	'SecurityRequirement.project_id' => $project_id,
            ),
            'fields' => array("Method.*")
        ));
        return $result;
    }

    public function getSecurityRequirement($method_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'SecurityRequirement.method_id' => $method_id,
            ),
            "recursive" => 2,
        ));
        return $result;
    }

}
