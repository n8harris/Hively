<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Match', 			'Model');
App::uses('CategoryTotal', 			'Model');

/**
 * Class GetMatch
 *
 * Generates matches based on point totals from questions and categories associated with questions
 *
 * @return matches The matches generated from the matching algorithm
 */

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

		//Exclude ids that have already been matched
		foreach($matches as $match) {
			array_push($excludedIds, $match['Match']['match_id']);
		}

		$comparisonConditions = array();
		$comparisonConditions['conditions'] = array();
		/*if (!empty($excludedIds)) {
			$comparisonConditions['conditions']['NOT'] = array('CategoryTotal.user_id' => $excludedIds);
		}*/
		//$comparisonConditions['conditions']['role'] = $lookupRole;

		$comparisonTotals = $CategoryTotal->find('all', $comparisonConditions);
		$comparisonTotalsArray = array();
		foreach($comparisonTotals as $comparisonTotal) {
			$saveComparison = true;
			foreach($excludedIds as $id){
				if ($comparisonTotal['CategoryTotal']['user_id'] == $id) {
					$saveComparison = false;
				}
			}
			if ($saveComparison && $comparisonTotal['CategoryTotal']['role'] == $lookupRole) {
				array_push($comparisonTotalsArray, $comparisonTotal);
			}
		}

		$userTotals = $CategoryTotal->findByUserId($userId);
		if (!empty($userTotals)) {
			$userTotals = $userTotals['CategoryTotal'];
		}

		//Do matching algorithm
		foreach($comparisonTotalsArray as $comparisonTotal){
			$Match->create();
			$numMatches = 0;
			$totalPercentage = 0;
			$numPercentage = 0;
			$comparisonTotal = $comparisonTotal['CategoryTotal'];
			foreach($userTotals['category_totals'] as $userCategoryTotal){
				$numPercentage++;
				foreach($comparisonTotal['category_totals'] as $comparisonCategoryTotal){
					if($userCategoryTotal['id'] == $comparisonCategoryTotal['id']){
						$userCategoryPoints = $userCategoryTotal['points'];
						$comparisonCategoryPoints = $comparisonCategoryTotal['points'];
						//Calculate percentage similarity between two numbers
						$matchPercentage = (1 - (abs($userCategoryPoints - $comparisonCategoryPoints) / 100)) * 100;
						$totalPercentage = $totalPercentage + $matchPercentage;
						if (abs($userCategoryPoints - $comparisonCategoryPoints) <= Configure::read('match.total_difference')) {
							$numMatches++;
						}
					}
				}
			}
			if ($numMatches >= Configure::read('match.num_category')) {
				//Only create match entry if number of category matches is greater than or equal to what we defined
				$totalMatchPercentage = $totalPercentage / $numPercentage;
				$matchData = array(
						'user_id' => $userId,
						'match_id' => $comparisonTotal['user_id'],
						'match_percentage' => $totalMatchPercentage,
						'reviewed' => "false",
						'approved' => "false"
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
