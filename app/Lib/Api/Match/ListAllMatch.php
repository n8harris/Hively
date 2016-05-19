<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Match', 		'Model');
App::uses('User', 		'Model');
App::uses('Business', 		'Model');

class ListAllMatch extends ApiCall {

	protected $_permissions = array('user', 'business');
	protected $_validation = array();

	protected function _execute(array $data = array()) {
		$Match = new Match();
		$Match->create();
		$User = new User();
		$User->create();
		$Business = new Business();
		$Business->create();
		$conditions = array();
		$conditions['conditions'] = array();
		$session = Alloy::instance()->getSession();

		$userId = isset($session['UserSession']['user_id']) ? trim($session['UserSession']['user_id']) : null;
		$role = isset($session['UserSession']['role']) ? trim($session['UserSession']['role']) : null;
		$reviewed = isset($data['reviewed']) ? trim($data['reviewed']) : 0;
		$approved = isset($data['approved']) ? trim($data['approved']) : 0;

    $matches = $Match->find('all', array(
			'conditions' => array(
				'user_id' => $userId,
				'reviewed' => $reviewed,
				'approved' => $approved
			)
		));
		$users = array();
		$businesses = array();
		foreach($matches as $match) {
			$user = $User->findById($match['Match']['match_id']);
			$user = $user['User'];
			if ($role == 'user') {
				$business = $Business->findByAccountId($user['account_id']);
				$business = $business['Business'];
				$business['percentage'] = round($match['Match']['match_percentage']);
				$business['match_id'] = $match['Match']['id'];
				$businesses[] = $business;
			} else {
				$user['percentage'] = round($match['Match']['match_percentage']);
				$user['match_id'] = $match['Match']['id'];
				$users[] = $user;
			}
		}

		$returnValues = array(
			'matches' => $matches,
			'users' => $users,
			'businesses' => $businesses
		);

		return new ApiResponse(
			$returnValues
		);
	}
}
