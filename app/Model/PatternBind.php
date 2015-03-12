<?php
/* User Model
 *
 */
class PatternBind extends AppModel {
    public $name = 'PatternBind';
    public $belongsTo = array('PatternElement', 'Label');

}
