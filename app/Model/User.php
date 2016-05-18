<?php

App::uses('AppModel', 		'Model');
App::uses('SecureCrypt', 	'Lib');
App::uses('Credential', 	'Model');
App::uses('UserSession', 	'Model');

class User extends AppModel {

	public $name = 'User';
	var $useDbConfig = 'mongo';
  public $mongoSchema = array(
      'email' => array('type' => 'string'),
      'first_name' => array('type' => 'string'),
      'last_name' => array('type' => 'string'),
      'middle_name' => array('type' => 'string'),
      'suffix_name' => array('type' => 'string'),
      'prefix_name' => array('type' => 'string'),
      'gender' => array('type' => 'string'),
      'profile_pic_url' => array('type' => 'string'),
      'username' => array('type' => 'string'),
      'role' => array('type' => 'string'),
      'created' => array('type' => 'date'),
      'last_login_date' => array('type' => 'date'),
      'status' => array('type' => 'string'),
      'birthday' => array('type' => 'date'),
      'account_id' => array('type' => 'objectId'),
			'bio' => array('type' => 'string'),
			'answered' => array('type' => 'bool')
  );

	public function uniqueEmail($email) {

		$conditions = array(
			'User.email' => trim($email['email'])
		);

		if($this->id || isset($email['id'])) {
			$conditions[] = array('User.id !=' => $this->id ? $this->id : $email['id']);
		}
		return $this->find('count', array('conditions' => $conditions)) == 0;
	}
	public function uniqueUsername($username) {
		$conditions = array(
			'User.username' => trim($username['username'])
		);

		if($this->id || isset($username['id'])) {
			$conditions[] = array('User.id !=' => $this->id ? $this->id : $username['id']);
		}
		return $this->find('count', array('conditions' => $conditions)) == 0;
	}

	public static function loadFromSession($session) {

		$User = new static();
		return $User->findById($session['UserSession']['user_id']);
	}
}
