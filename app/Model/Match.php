<?php

App::uses('AppModel',	'Model');

class Match extends AppModel {

	public $name = 'Match';
	var $useDbConfig = 'mongo';
	public $mongoSchema = array(
      'user_id' => array('type' => 'objectId'),
      'match_id' => array('type' => 'objectId'),
			'match_percentage' => array('type' => 'double'),
			'reviewed' => array('type' => 'bool'),
			'approved' => array('type' => 'bool')
  );
}
