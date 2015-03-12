<?php
/* User Model
 *
 */
class Label extends AppModel {
    public $name = 'Label';
    public $hasMany = array('Attribute','Method','Relation');


    public function getElements($user_id, $project_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'Label.project_id' => $project_id,
            ),
            'joins' => array(
                array(
                    'type' => 'LEFT',
                    'table' => 'projects',
                    'alias' => 'Project',
                    'conditions' => "Label.project_id = Project.id"
                )
            ),
        ));

        for($i = 0; $i < count($result); $i++) {
            for($j = 0; $j < count($result[$i]['Relation']); $j++) {

                $label = $this->find('first', array(
                    'conditions' => array(
                        'Label.id' => $result[$i]['Relation'][$j]['label_relation_id'],
                    ),
                    'fields' => array("Label.name")
                ));

                $result[$i]['Relation'][$j]['name'] = $label['Label']['name'];
            }
        }
        return $result;
    }

    public function getPatternElements($pattern_id)
    {
        $result = $this->find('all', array(
            'conditions' => array(
                'Label.pattern_id' => $pattern_id,
            ),
        ));

        for($i = 0; $i < count($result); $i++) {
            for($j = 0; $j < count($result[$i]['Relation']); $j++) {

                $label = $this->find('first', array(
                    'conditions' => array(
                        'Label.id' => $result[$i]['Relation'][$j]['label_relation_id'],
                    ),
                    'fields' => array("Label.name")
                ));

                $result[$i]['Relation'][$j]['name'] = $label['Label']['name'];
            }
        }
        return $result;
    }

    public function getElement($label_id)
    {

        $result = $this->find('first', array(
            'conditions' => array(
                'Label.id' => $label_id,
            )
        ));
        return $result;
    }

    public function checkElement($label_id)
    {

        $count = $this->find('count', array(
            'conditions' => array(
                'Label.id' => $label_id,
            )
        ));

        if ($count > 0) {
            return true;
        }
        return false;
    }


}
