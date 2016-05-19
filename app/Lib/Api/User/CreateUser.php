<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('User', 			'Model');
App::uses('Business', 			'Model');
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

		'role'					=> array('required' => false),

		'business_id' => array('required' => false)
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

		$profilePic		= isset($data['profile_pic_url']) ? trim($data['profile_pic_url']) : null;

		$gender 		= isset($data['gender']) ? $data['gender'] : 'female';
		$email 			= isset($data['email']) ? trim($data['email']) : null;

		$username		= trim($data['username']);
		$password		= isset($data['password']) ? $data['password'] : null;

		$birthday 	= isset($data['birthday']) ? $data['birthday'] : null;
		$role 	= isset($data['role']) ? $data['role'] : 'user';
		$businessId 	= isset($data['business_id']) ? $data['business_id'] : null;
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
				'profile_pic_url' => Configure::read('user.profile_pic'),
				'gender' => $gender,
				'username' => $username,
				'role' => $role,
				'birthday' => $birthday,
        'status' => 'new',
        'account_id' => $account['Account']['id'],
				'last_login_date' => null,
				'answered' => false,
				'email' => $email,
				'bio' => ''
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

		if ($role == 'business') {
			$Business = new Business();
			$businessData = array();
			$businessData['id'] = $businessId;
			$businessData['account_id'] = $account['Account']['id'];
			$businessData['profile_pic_url'] = Configure::read('business.profile_pic');
			$businessData['claimed'] = true;
			$business = $Business->saveAll($businessData, array('fieldlist' => array('Business' => array('account_id', 'profile_pic_url', 'claimed'))));
		}

		$return = array(
			'user' => $user['User'],
			'account' => $account['Account'],
      'credential' => $credential['Credential']
		);

		return new ApiResponse($return);

	}
}
