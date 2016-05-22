<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('UserSession', 	'Model');

/**
 * Class LogoutUser
 *
 * Logs a user out
 *
 */

class LogoutUser extends ApiCall {

	protected $_permissions = '*';
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$session 	= Alloy::instance()->getSession();

		$Session = new UserSession();
		$session	= $Session->logout($session['UserSession']['id']);
		Alloy::instance()->setSession($session);

		return new ApiResponse();
	}
}
