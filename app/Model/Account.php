<?php

App::uses('AppModel', 		'Model');
App::uses('User', 			'Model');
App::uses('Account', 		'Model');

class Account extends AppModel {

  public $name = 'Account';
  var $useDbConfig = 'mongo';
  public $mongoSchema = array(
      'key' => array('type' => 'long'),
      'status' => array('type' => 'text'),
      'access_expires' => array('type' => 'date'),
      'created' => array('type' => 'date')
  );

	public function generateKey() {

		$found 		= false;
		$key 		= null;

		while(!$found) {

			$key = rand(1000000000000, 9999999999999);

			$existing = $this->findByKey($key);

			if(!$existing) {
				$found = true;
			}
		}
		return $key;
	}

}
