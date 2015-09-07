<?php


class PatternOcl extends AppModel {
	
    public $name = 'PatternOcl';
/*
    public $hasMany = array(
        'PatternAttribute',
        'PatternMethod',
        'PatternRelation'
    );
*/
	public $belongsTo = array('Pattern');


    public function getPatternOcl($pattern_ocl_id)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'PatternOcl.id' => $pattern_ocl_id,
            )
        ));
        return $result;
    }

    public function getPatternOcls($pattern_id)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'PatternOcl.pattern_id' => $pattern_id,
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
