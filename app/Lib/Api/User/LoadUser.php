<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('User', 			'Model');
App::uses('Account',		'Model');
App::uses('UserSession', 	'Model');

/**
 * Class LoadUser
 *
 * Loads all information needed for a user.
 *
 * @return user The user that is currently logged in
 * @return account The account of the logged in user
 * @return session The session of the logged in user
 */
class LoadUser extends ApiCall {

	protected $_permissions = '*';
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$session = Alloy::instance()->getSession();
		$session = array(
			'session_id' => UserSession::makeUnifiedId($session),
			'role' => $session['UserSession']['role'],
			'username' => isset($session['UserSession']['username']) ? $session['UserSession']['username'] : null,
			'user_id' => isset($session['UserSession']['user_id']) ? $session['UserSession']['user_id'] : null,
			'last_login_date' => isset($session['UserSession']['last_login_date']) ? $session['UserSession']['last_login_date'] : null
		);
		$user = null;

		$User = new User();
		$Account = new Account();
		$account = $Account->findById($user['User']['account_id']);

		if(isset($session['user_id'])) {
			$user = $User->findById($session['user_id']);
		}

		return new ApiResponse(array(
			'user' => $user ? $user['User'] : array(),
			'account' => $account ? $account['Account'] : array(),
			'session' => $session
		));
	}
}
