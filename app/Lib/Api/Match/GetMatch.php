<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Match', 			'Model');
App::uses('CategoryTotal', 			'Model');


class GetMatch extends ApiCall {

	protected $_permissions = array('user', 'business');
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$Match = new Match();
		$CategoryTotal = new CategoryTotal();
		$Match->create();
		$CategoryTotal->create();
		$session 	= Alloy::instance()->getSession();

		$userId = isset($session['UserSession']['user_id']) ? trim($session['UserSession']['user_id']) : null;
		$role = isset($session['UserSession']['role']) ? trim($session['UserSession']['role']) : null;
		$lookupRole = null;
		$excludedIds = array();
		if (empty($role)) {
			return new ApiResponse(null, -1, "Unable to get role for user");
		} else {
			$lookupRole = $role == 'business' ? 'user' : 'business';
		}
		$matches = $Match->find('all', array(
        'conditions' => array('user_id' => $userId)
    ));

		foreach($matches as $match) {
			$excludedIds[] = $match['Match']['match_id'];
		}

		$comparisonConditions = array();
		$comparisonConditions['conditions'] = array();
		if (!empty($excludedIds)) {
			$comparisonConditions['conditions']['NOT'] = array('user_id' => $excludedIds);
		}
		$comparisonConditions['conditions']['role'] = $lookupRole;

		$comparisonTotals = $CategoryTotal->find('all', $comparisonConditions);

		$userTotals = $CategoryTotal->findByUserId($userId);
		if (!empty($userTotals)) {
			$userTotals = $userTotals['CategoryTotal'];
		}

		foreach($comparisonTotals as $comparisonTotal){
			$numMatches = 0;
			$totalPercentage = 0;
			$comparisonTotal = $comparisonTotal['CategoryTotal'];
			foreach($userTotals['category_totals'] as $userCategoryTotal){
				foreach($comparisonTotal['category_totals'] as $comparisonCategoryTotal){
					if($userCategoryTotal['id'] == $comparisonCategoryTotal['id']){
						$userCategoryPoints = $userCategoryTotal['points'];
						$comparisonCategoryPoints = $comparisonCategoryTotal['points'];
						if (abs($userCategoryPoints - $comparisonCategoryPoints) <= Configure::read('match.total_difference')) {
							$matchPercentage = 100 - (( abs($userCategoryPoints - $comparisonCategoryPoints) / (($userCategoryPoints + $comparisonCategoryPoints) / 2) ) * 100);
							$totalPercentage = $totalPercentage + $matchPercentage;
							$numMatches++;
						}
					}
				}
			}
			if ($numMatches >= Configure::read('match.num_category')) {
				$totalMatchPercentage = $totalPercentage / $numMatches;
				$matchData = array(
					'Match' => array(
						'user_id' => $userId,
						'match_id' => $comparisonTotal['user_id'],
						'match_percentage' => $totalMatchPercentage,
						'reviewed' => 0,
						'approved' => 0
					)
				);
				$Match->save($matchData);
			}
		}

		$returnedMatches = $Match->find('all', array(
        'conditions' => array('user_id' => $userId)
    ));

		$return = array(
			'matches' => $returnedMatches
		);

		return new ApiResponse($return);

	}
}
