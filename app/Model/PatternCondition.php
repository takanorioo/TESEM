<?php
class PatternCondition extends AppModel {
	
    public $name = 'PatternCondition';

/*
    public $hasMany = array(
        'PatternRequirementAction'
    );
*/
	public $belongsTo = array('Pattern');


    public function getPatternCondition($pattern_condition_id)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'PatternCondition.id' => $pattern_condition_id,
            )
        ));
        return $result;
    }

    public function getPatternConditions($pattern_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'PatternCondition.pattern_id' => $pattern_id,
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
