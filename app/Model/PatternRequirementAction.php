<?php

class PatternRequirementAction extends AppModel {
	
    public $name = 'PatternRequirementAction';

    public $belongsTo = array('PatternRequirement');


    public function getPatternRequirementAction($pattern_requirement_action_id)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'PatternRequirementAction.id' => $pattern_requirement_action_id,
            )
        ));
        return $result;
    }

    public function getPatternRequirements($pattern_requirement_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'PatternRequirementAction.pattern_requirement_id' => $pattern_requirement_id,
            )
        ));

/*
        for($i = 0; $i < count($result); $i++) {
            for($j = 0; $j < count($result[$i]['PatternRelation']); $j++) {

                $label = $this->find('first', array(
                    'conditions' => array(
                        'PatternElement.id' => $result[$i]['PatternRelation'][$j]['pattern_element_relation_id'],
                    ),
                    'fields' => array("PatternElement.element")
                ));

                $result[$i]['PatternRelation'][$j]['name'] = $label['PatternElement']['element'];
            }
        }
*/
        return $result;
    }
}
