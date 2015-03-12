<?php
/* User Model
 *
 */
class SecurityDesignRequirement extends AppModel 
{
    public $name = 'SecurityDesignRequirement';
    public $belongsTo = array('Method','Pattern');
    public $hasMany = array('PatternBind');

    public function getSecurityDesignRequirement($method_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'SecurityDesignRequirement.method_id' => $method_id,
            ),
            "recursive" => 2,
        ));
        return $result;
    }

}
