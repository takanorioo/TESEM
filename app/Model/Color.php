<?php
/* User Model
 *
 */
class Color extends AppModel {

    public $name = 'Color';
    public function getColorList() {

        $result = $this->find('list', array());

        return $result;
    }

}
