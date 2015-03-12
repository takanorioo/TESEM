<?php
/* User Model
 *
 */
class Countermeasure extends AppModel {
    public $name = 'Countermeasure';
    public $hasMany = array('CountermeasureElement');

    public function getCountermeasures() {

        $result = $this->find('all', array(
            'conditions' => array(

            ),
        ));
        return $result;
    }
}
