<?php
/* User Model
 *
 */
class Pattern extends AppModel {
    public $name = 'Pattern';
    public $hasMany = array('PatternElement');
    public $belongsTo = array('User');

    public function getPatterns() {

        $result = $this->find('all', array(
            'conditions' => array(
            ),
        ));
        return $result;
    }

    public function getPattern($pattern_id) {

        $result = $this->find('first', array(
            'conditions' => array(
            	'Pattern.id' => $pattern_id,
            ),
        ));
        return $result;
    }
}
