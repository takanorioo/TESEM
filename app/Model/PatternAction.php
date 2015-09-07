<?php

class PatternAction extends AppModel {
	
    public $name = 'PatternAction';

//    public $belongsTo = array('PatternRequirement');

    public function getPatternAction($pattern_action_id)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'PatternAction.id' => $pattern_action_id,
            )
        ));
        return $result;
    }

    public function getPatternActions($pattern_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'PatternAction.pattern_id' => $pattern_id,
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
