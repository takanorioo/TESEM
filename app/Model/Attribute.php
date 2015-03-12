<?php
/* User Model
 *
 */

class Attribute extends AppModel {
    public $name = 'Attribute';
    public $belongsTo = array('Label');

    public function getAttributeName($attribute_id) {

        $result = $this->find('first', array(
            'conditions' => array(
                'Attribute.id' => $attribute_id,
            ),
            'fields' => array("Attribute.name","Label.*")
        ));

        return $result;

    }

    public function getAttributeIdByName($attribute_name) {

        $result = $this->find('first', array(
            'conditions' => array(
                'Attribute.name' => $attribute_name,
            ),
            'fields' => array("Attribute.id")
        ));

        return $result;

    }



}
