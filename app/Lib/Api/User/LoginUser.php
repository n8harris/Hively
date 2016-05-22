<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('User', 			'Model');
App::uses('Account', 			'Model');
App::uses('Credential',		'Model');
App::uses('UserSession', 	'Model');

/**
 * Class LoginUser
 *
 * Logs in a user with data from the form
 *
 * @return user The logged in user
 * @return account The account that is associated with the user
 */

class LoginUser extends ApiCall {

	protected $_permissions = '*';
	protected $_validation = array(
		'username' 	=> array('required' => true),
		'password' 	=> array('required' => true)
	);

	protected function _execute(array $data = array()) {

		$username	= trim($data['username']);
		$password	= $data['password'];

		$session 	= Alloy::instance()->getSession();

		if(strpos($username, '@')) {
			// user typed in an email, let's load the user record and get the username
			$User = new User();
			$user = $User->findByEmail($username);
			if($user) {
				$username = $user['User']['username'];
			}
		}

		$userId		= Credential::validate($username, $password);
		if($userId) {
			$User = new User();
			$Account = new Account();
			$user = $User->findById($userId);
			$account = $Account->findById($user['User']['account_id']);
			if(!$user) {
				return new ApiResponse(null, -1, "An error was encountered while logging in.", "Unable to load user record associated with credentials.");
			}

			$UserSession = new UserSession();
			if(!$session = $UserSession->login($session['UserSession']['id'], $user)) {
				return new ApiResponse(null, -1, "An error was encountered while logging in.", "Unable to attach user to session.");
			}

			$user['User']['last_login_date'] = date("Y-m-d H:i:s", time());
			$User->id = $userId;
			$User->saveField('last_login_date', $user["User"]['last_login_date']);

			Alloy::instance()->setSession($session);

			$response = array(
				'user' => $user['User'],
				'account' => $account['Account']
			);

			return new ApiResponse($response);
		} else {
			return new ApiResponse(null, -1, Credential::$lastLoginError);
		}
	}
}
