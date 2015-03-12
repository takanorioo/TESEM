<?php

class PatternRelation extends AppModel 
{
    public $name = 'PatternRelation';
    public $belongsTo = array('PatternElement');
}
