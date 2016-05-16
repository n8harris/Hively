<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('User', 			'Model');
App::uses('Account',		'Model');
App::uses('Credential', 	'Model');
App::uses('UserSession', 	'Model');

class CreateUser extends ApiCall {

	protected $_permissions = '*';
	protected $_validation = array(

		'first_name' 			=> array('required' => true),
		'last_name' 			=> array('required' => true),

		'email' 				=> array('required' => false),

		'username' 				=> array('required' => true),

		'password' 				=> array('required' => false), // only required on create

		'birthday'		=> array('required' => true),

		'role'					=> array('required' => false)
	);

	protected function _execute(array $data = array()) {

		$User 			= new User();
		$Account 		= new Account();
    $Credential = new Credential();

		$firstName 		= trim($data['first_name']);
		$lastName 		= trim($data['last_name']);
		$middleName 	= isset($data['middle_name']) ? trim($data['middle_name']) : null;
		$prefixName 	= isset($data['prefix_name']) ? trim($data['prefix_name']) : null;
		$suffixName 	= isset($data['suffix_name']) ? trim($data['suffix_name']) : null;

		$avatarId		= isset($data['profile_pic_url']) ? trim($data['profile_pic_url']) : null;

		$gender 		= isset($data['gender']) ? $data['gender'] : 'female';
		$email 			= isset($data['email']) ? trim($data['email']) : null;

		$username		= trim($data['username']);
		$password		= isset($data['password']) ? $data['password'] : null;

		$birthday 	= isset($data['birthday']) ? $data['birthday'] : null;
		$role 	= isset($data['role']) ? $data['role'] : 'user';
		$session 		= Alloy::instance()->getSession();

    $accountData = array(
      'Account' => array(
        'status' => 'active',
				'key' => $Account->generateKey()
      )
    );

    $Account->create();
    $account = $Account->save($accountData);

		$userData = array(
			'User' => array(
				'first_name' => $firstName,
				'last_name' => $lastName,
				'middle_name' => $middleName,
				'prefix_name' => $prefixName,
				'suffix_name' => $suffixName,
				'profile_pic_url' => $avatarId,
				'gender' => $gender,
				'username' => $username,
				'role' => $role,
				'birthday' => $birthday,
        'status' => 'new',
        'account_id' => $account['Account']['id'],
				'last_login_date' => null
			)
		);
		if($email) {
			$userData['User']['email'] = $email;
		} else {
			$userData['User']['email'] = null;
		}
    $User->create();
    $user = $User->save($userData);

		if($password) {
      $credentialData = array(
  			'Credential' => array(
  				'username' => $username,
          'user_id' => $user['User']['id'],
					'failed_login_count' => 0,
					'locked' => 0
  			)
  		);

      $Credential->create();
      $credentialData = $Credential->encryptPassword($password, $credentialData);
      $credential = $Credential->save($credentialData);

		}

		if (!empty($user) && !empty($account)) {
			$UserSession = new UserSession();
			$session = $UserSession->login($session['UserSession']['id'], $user);
			Alloy::instance()->setSession($session);
		}

		$return = array(
			'user' => $user['User'],
			'account' => $account['Account'],
      'credential' => $credential['Credential']
		);

		return new ApiResponse($return);

	}
}
