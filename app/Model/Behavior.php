<?php
/* User Model
 *
 */
class Behavior extends AppModel {

    public $name = 'Behavior';

    public $hasMany = array('BehaviorRelations');
    public $belongsTo = array('Method','Label');

    public function getBehaviorUIs($method_id) {

        $result = $this->find('all', array(
             'conditions' => array(
                'Behavior.method_id' => $method_id,
                'Behavior.type' => BEHAVIOR_UI,
            ),
             "recursive" => 1,
        ));
        return $result;
    }

    public function getBehaviorActor($method_id) {

        $result = $this->find('first', array(
             'conditions' => array(
                'Behavior.method_id' => $method_id,
                'Behavior.type' => BEHAVIOR_ACTOR,
            ),
             "recursive" => 1,
        ));
        return $result;
    }


    public function getBehaviorElement($method_id) {

        $result = $this->find('all', array(
             'conditions' => array(
                'Behavior.method_id' => $method_id,
            ),
            "recursive" => 1,
        ));

       

        for($i = 0; $i < count($result); $i++) {
            for($j = 0; $j < count($result[$i]['BehaviorRelations']); $j++) {

                $behavior_id = $this->find('first', array(
                    'conditions' => array(
                        'Behavior.id' => $result[$i]['BehaviorRelations'][$j]['behavior_id'],
                    ),
                    "recursive" => 1,
                ));

                $behavior_relation_id = $this->find('first', array(
                    'conditions' => array(
                        'Behavior.id' => $result[$i]['BehaviorRelations'][$j]['behavior_relation_id'],
                    ),
                    "recursive" => 1,
                ));

                $result[$i]['BehaviorRelations'][$j]['behavior_name'] = $behavior_id['Label']['name'];
                $result[$i]['BehaviorRelations'][$j]['behavior_relation_name'] = $behavior_relation_id['Label']['name'];
            }
        }
        return $result;

    }
}
