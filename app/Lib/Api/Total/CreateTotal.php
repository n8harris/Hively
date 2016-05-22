<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('CategoryTotal', 			'Model');
App::uses('Response', 			'Model');

/**
 * Class CreateTotal
 *
 * Creates a CategoryTotal entry which defines total category point values across all questions for a user
 *
 * @return response The CategoryTotal object
 */

class CreateTotal extends ApiCall {

	protected $_permissions = array('user', 'business');
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$CategoryTotal = new CategoryTotal();
		$Response = new Response();
		$Response->create();
		$session 	= Alloy::instance()->getSession();

		$userId = isset($session['UserSession']['user_id']) ? trim($session['UserSession']['user_id']) : null;
		$role = isset($session['UserSession']['role']) ? trim($session['UserSession']['role']) : null;
		$categoryTotals = array();
		$responses = $Response->find('all', array(
        'conditions' => array('user_id' => $userId)
    ));

		//Get all responses for user and merge point values together if the categories are the same
		foreach ($responses as $response) {
			foreach($response['Response']['question_categories'] as $category) {
				$categoryPush = array();
				$pushData = true;
				foreach($categoryTotals as $total){
					if ($total['id'] == $category['id']){
						$total['points'] += $category['points'];
						$pushData = false;
					}
				}
				if ($pushData) {
					$categoryPush['id'] = $category['id'];
					$categoryPush['points'] = $category['points'];
					array_push($categoryTotals, $categoryPush);
				}
			}
		}

    $totalData = array(
      'CategoryTotal' => array(
        'user_id' => $userId,
				'role' => $role,
				'category_totals' => $categoryTotals
      )
    );

    $CategoryTotal->create();
    $response = $CategoryTotal->save($totalData);

		$return = array(
			'response' => $response['CategoryTotal']
		);

		return new ApiResponse($return);

	}
}
