<?php
/* User Model
 *
 */
class PatternBehavior extends AppModel {

    public $name = 'PatternBehavior';

    public $hasMany = array('PatternBehaviorRelations');
    public $belongsTo = array('Pattern','PatternElement');

    public function getBehaviorUIs($pattern_id) {

        $result = $this->find('all', array(
             'conditions' => array(
                'PatternBehavior.pattern_id' => $pattern_id,
                'PatternBehavior.type' => BEHAVIOR_UI,
            ),
             "recursive" => 1,
        ));
        return $result;
    }

    public function getBehaviorActor($pattern_id) {

        $result = $this->find('first', array(
             'conditions' => array(
                'PatternBehavior.pattern_id' => $pattern_id,
                'PatternBehavior.type' => BEHAVIOR_ACTOR,
            ),
             "recursive" => 1,
        ));
        return $result;
    }


    public function getBehaviorElement($pattern_id) {

        $result = $this->find('all', array(
             'conditions' => array(
                'PatternBehavior.pattern_id' => $pattern_id,
            ),
            "recursive" => 1,
        ));

       

        for($i = 0; $i < count($result); $i++) {
            for($j = 0; $j < count($result[$i]['PatternBehaviorRelations']); $j++) {

                $behavior_id = $this->find('first', array(
                    'conditions' => array(
                        'PatternBehavior.id' => $result[$i]['PatternBehaviorRelations'][$j]['pattern_behavior_id'],
                    ),
                    "recursive" => 1,
                ));

                $behavior_relation_id = $this->find('first', array(
                    'conditions' => array(
                        'PatternBehavior.id' => $result[$i]['PatternBehaviorRelations'][$j]['behavior_relation_id'],
                    ),
                    "recursive" => 1,
                ));

                $result[$i]['PatternBehaviorRelations'][$j]['pattern_behavior_name'] = $behavior_id['PatternElement']['element'];
                $result[$i]['PatternBehaviorRelations'][$j]['pattern_behavior_relation_name'] = $behavior_relation_id['PatternElement']['element'];
            }
        }
        return $result;

    }
}
