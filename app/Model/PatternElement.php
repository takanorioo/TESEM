<?php


class PatternElement extends AppModel {
	
    public $name = 'PatternElement';
    public $hasMany = array(
        'PatternAttribute',
        'PatternMethod',
        'PatternRelation'
    );
	public $belongsTo = array('Pattern');


    public function getPatternElement($pattern_element_id)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'PatternElement.id' => $pattern_element_id,
            )
        ));
        return $result;
    }

    public function getPatternElements($pattern_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'PatternElement.pattern_id' => $pattern_id,
            )
        ));

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
        return $result;
    }
    public function getKeyPatternElement($pattern_id)
    {
        $result = $this->find('first', array(
			'recursive' => -1,
            'conditions' => array(
				'and' => array(
	                'PatternElement.pattern_id' => $pattern_id,
	                'PatternElement.key_flag' => '1',
				),
			),
        ));
		$method = $this->PatternMethod->find('first', array(
					'recursive' => -1,
					'conditions' => array(
						'and' => array(
							'PatternMethod.pattern_element_id' => $result['PatternElement']['id'],
							'PatternMethod.key_flag' => '1',
							)
						)
					));
		$result['PatternMethod'] = $method['PatternMethod'];
		return $result;
	}

}
