<?php
/* User Model
 *
 */
class UsersProject extends AppModel 
{
    public $name = 'UsersProject';
    public $belongsTo = array('Project');

    public function getInvitedProjects($user_id)
    {
        $result = $this->find('all', array(
             'conditions' => array(
                'UsersProject.user_id' => $user_id,
            )
        ));

        return $result;
    }

    
}
