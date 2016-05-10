<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('User', 			'Model');
App::uses('Account',		'Model');
App::uses('Credential', 	'Model');

class CreateUser extends ApiCall {

	protected $_permissions = '*';
	protected $_validation = array(

		'first_name' 			=> array('required' => true),
		'last_name' 			=> array('required' => true),

		'gender' 				=> array('required' => true),

		'email' 				=> array('required' => false),

		'username' 				=> array('required' => true),

		'password' 				=> array('required' => false), // only required on create

		'birthday_month'		=> array('required' => true),
		'birthday_day'			=> array('required' => true),
		'birthday_year'			=> array('required' => true),

		'role'					=> array('required' => true)
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

		$avatarId		= intval($data['profile_pic_url']);

		$gender 		= $data['gender'] == 'male' ? 'male' : 'female';
		$email 			= isset($data['email']) ? trim($data['email']) : null;

		$username		= trim($data['username']);
		$password		= isset($data['password']) ? $data['password'] : null;

		$birthdayMonth 	= $data['birthday_month'];
		$birthdayDay 	= $data['birthday_day'];
		$birthdayYear 	= $data['birthday_year'];
		$role 	= $data['role'];

    $accountData = array(
      'Account' => array(
        'status' => 'new',
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
				'birthday' => sprintf("%s-%s-%s", $birthdayYear, $birthdayMonth, $birthdayDay),
        'status' => 'new',
        'account_id' => $account['Account']['id']
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

		$return = array(
			'user' => $user['User'],
			'account' => $account['Account'],
      'credential' => $credential['Credential']
		);

		return new ApiResponse($return);

	}
}
