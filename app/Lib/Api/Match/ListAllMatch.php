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
		$reviewed = isset($data['reviewed']) ? $data['reviewed'] : "false";
		$approved = isset($data['approved']) ? $data['approved'] : "false";

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
			if (!empty($user)) {
				$user = $user['User'];
				if ($role == 'user') {
					$business = $Business->find('first', array('conditions' => array('account_id' => $user['account_id'])));
					if (!empty($business)) {
						$business = $business['Business'];
						$business['percentage'] = round($match['Match']['match_percentage']);
						$business['match_id'] = $match['Match']['id'];
						$businesses[] = $business;
					}
				} else {
					$user['percentage'] = round($match['Match']['match_percentage']);
					$user['match_id'] = $match['Match']['id'];
					$users[] = $user;
				}
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
