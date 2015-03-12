<?php
/* User Model
 *
 */
class CountermeasureBind extends AppModel {
    public $name = 'CountermeasureBind';
    public $belongsTo = array('CountermeasureElement', 'Label');

}
