<?php

/**
 * Subclass for representing a row from the 'records' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Record extends BaseRecord
{
}

sfPropelBehavior::add('Record', array('audit'));
