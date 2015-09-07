<?php
class PatternRequirement extends AppModel {
	
    public $name = 'PatternRequirement';

    public $hasMany = array(
        'PatternRequirementAction'
    );

	public $belongsTo = array('Pattern');


    public function getPatternRequirement($pattern_requirement_id)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'PatternRequirement.id' => $pattern_requirement_id,
            )
        ));
        return $result;
    }

    public function getPatternRequirements($pattern_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'PatternRequirement.pattern_id' => $pattern_id,
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
