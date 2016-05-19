<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('User', 			'Model');

/**
 * Class GetUser
 *
 * Gets a user.
 *
 * @return user The user matched by the id
 */
class GetUser extends ApiCall {

	protected $_permissions = array('user', 'business');
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$User = new User();
		$userId = isset($data['user_id']) ? $data['user_id'] : null;

		if (isset($userId)) {
			$user = $User->findById($userId);
		} else {
			return new ApiResponse(null, -1, "Error finding user");
		}

		return new ApiResponse(array(
			'user' => $user ? $user['User'] : array()
		));
	}
}
