<?php

App::uses('SecureCrypt', 	'Lib');
App::uses('AppModel', 		'Model');
App::uses('User', 			'Model');

class Credential extends AppModel {

  public $name = 'Credential';
  var $useDbConfig = 'mongo';
  public $mongoSchema = array(
      'user_id' => array('type' => 'objectId'),
      'username' => array('type' => 'string'),
      'hash' => array('type' => 'string'),
      'salt' => array('type' => 'string'),
      'failed_login_count' => array('type' => 'integer'),
      'locked' => array('type' => 'bool')
  );

	public static $lastLoginError = null;

	public static function validate($username, $password) {

		$Credential = new static();
		$cred 		= $Credential->find('first', array('conditions' => array('Credential.username' => $username)));

		if($cred) {

			if($cred['Credential']['locked']) {
				static::$lastLoginError = "You account has been locked due to failed login attempts.  Please reset your password.";
				return false;
			}

			if(SecureCrypt::hash($password, $cred['Credential']['salt'] . Configure::read('Security.salt')) == $cred['Credential']['hash']) {

				if($cred['Credential']['failed_login_count'] > 0) {
					$Credential->id = $cred['Credential']['id'];
					$Credential->saveField('failed_login_count', 0);
				}
				return $cred['Credential']['user_id'];

			} else {

				if($cred['Credential']['failed_login_count'] >= 5) {
					$cred['Credential']['locked'] = 1;
				}

				$cred['Credential']['failed_login_count']++;
				$Credential->id = $cred['Credential']['id'];
				$cred = $Credential->save($cred);
				static::$lastLoginError = "Username or email and password do not match.";
				return false;
			}
		}
		static::$lastLoginError = "Username or email and password do not match.";
		return false;
	}

	public function beforeValidate($options = array()) {

		// Create a salt and hash the password, which is what is actually stored in db
		if(isset($this->data['Credential']['password'])) {
			$this->data['Credential']['salt'] = SecureCrypt::makeSalt();
			$this->data['Credential']['hash'] = SecureCrypt::hash($this->data['Credential']['password'], $this->data['Credential']['salt'] . Configure::read('Security.salt'));
		}
	}

	public function comparePasswords($field = null) {

		return $this->data['Credential']['password'] == $field['confirm_password'];
	}
}
