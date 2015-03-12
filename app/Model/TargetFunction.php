<?php
/* User Model
 *
 */
class TargetFunction extends AppModel 
{
    public $name = 'TargetFunction';
    public $belongsTo = array('Method');


    public function getTargetFunctions($project_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
            	'TargetFunction.project_id' => $project_id,
            ),
            'fields' => array("Method.*","TargetFunction.*")
        ));
        return $result;
    }

}
