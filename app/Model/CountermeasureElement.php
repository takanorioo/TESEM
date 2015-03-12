<?php
/* User Model
 *
 */
class CountermeasureElement extends AppModel {
    public $name = 'CountermeasureElement';
	public $belongsTo = array('Countermeasure');
}
