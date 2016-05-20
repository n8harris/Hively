<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Response', 			'Model');

/**
 * Class ListAllResponse
 *
 * Gets responses for a user.
 *
 * @return responses The responses for a user
 */
class ListAllResponse extends ApiCall {

	protected $_permissions = array('user', 'business');
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$Response = new Response();
		$session = Alloy::instance()->getSession();
		$userId = isset($session['UserSession']['user_id']) ? $session['UserSession']['user_id'] : null;

		if (isset($userId)) {
			$responses = $Response->find('all', array(
				'conditions' => array (
					'user_id' => $userId
				)
			));
		} else {
			return new ApiResponse(null, -1, "Error finding responses");
		}

		return new ApiResponse(array(
			'responses' => $responses ? $responses : array()
		));
	}
}
