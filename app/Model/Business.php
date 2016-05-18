<?php

App::uses('AppModel',	'Model');

class Business extends AppModel {

	public $name = 'Business';
	var $useDbConfig = 'mongo';
	public $mongoSchema = array(
      'account_id' => array('type' => 'objectId'),
      'name' => array('type' => 'string'),
			'address_line_1' => array('type' => 'string'),
			'city' => array('type' => 'string'),
			'state' => array('type' => 'string'),
			'zip' => array('type' => 'string'),
			'description' => array('type' => 'string'),
  );
}
