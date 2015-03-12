<?php
/* User Model
 *
 */
class Method extends AppModel {
    public $name = 'Method';

    public function getLabel($method_id) {

        $result = $this->find('first', array(
            'conditions' => array(
                'Method.id' => $method_id,
            ),
            'joins' => array(
                array(
                    'type' => 'LEFT',
                    'table' => 'labels',
                    'alias' => 'Label',
                    'conditions' => "Method.Label_id = Label.id"
                )
            ),
            'fields' => array("Method.*","Label.*")
        ));
        return $result;
    }

    public function getMethod($method_id) {

        $result = $this->find('first', array(
            'conditions' => array(
                'Method.id' => $method_id,
            ),
            
        ));
        return $result;
    }


}
