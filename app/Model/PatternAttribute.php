<?php
/* User Model
 *
 */
class PatternAttribute extends AppModel 
{
    public $name = 'PatternAttribute';
	public $belongsTo = array('PatternElement');

    public function getPatternAttributeName($attribute_id) {

        $result = $this->find('first', array(
            'conditions' => array(
                'PatternAttribute.id' => $attribute_id,
            ),
            'fields' => array("PatternAttribute.name","PatternElement.*")
        ));

        return $result;

    }

    public function getPatternAttributeIdByName($attribute_name) {

        $result = $this->find('first', array(
            'conditions' => array(
                'PatternAttribute.name' => $attribute_name,
            ),
            'fields' => array("Attribute.id")
        ));

        return $result;

    }
}