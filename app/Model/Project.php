<?php
/* User Model
 *
 */
class Project extends AppModel {

    public $name = 'Project';
    public $hasMany = array('UsersProject');

    public function getPublicProjects()
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'Project.type' => '2'

            )
        ));
        return $result;
    }

    public function getOwnProjects($user_id)
    {
        $result = $this->find('all', array(
             'conditions' => array(
                'Project.user_id' => $user_id,
            )
        ));
        return $result;
    }

    public function getInvitedProjects($user_id)
    {
        $result = $this->find('all', array(
             'conditions' => array(
                'UsersProject.user_id' => $user_id,
            )
        ));

        return $result;
    }
    public function getRelatedProjects($project_id)
    {
        $result = $this->find('first', array(
             'conditions' => array(
                'Project.id' => $project_id,
            )
        ));
        
        $result['Member'][0] = $result['Project']['user_id'];
        for($i = 0; $i < count($result['UsersProject']); $i++){
            $result['Member'][$i+1] = $result['UsersProject'][$i]['user_id'];
        }

        return $result;
    }
    

    public function checkOwnProjects($user_id, $project_id)
    {
        $result = $this->find('all', array(
             'conditions' => array(
                'Project.user_id' => $user_id,
                'Project.id' => $project_id,
            )
        ));
        if (empty($result)) 
        {
            return false;
        } else {
            return true;
        }
        
    }

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
