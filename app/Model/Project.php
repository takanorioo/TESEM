<?php
/* User Model
 *
 */
class Project extends AppModel {

    public $name = 'Project';

    public function getProjects($user_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'or' => array(
                    array('Project.user_id' => $user_id,),
                    array('Project.type' => '0'),
                 ),
            )
        ));
        return $result;
    }

    public function getProject($project_id)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'Project.id' => $project_id,
            )
        ));
        return $result;
    }
}
