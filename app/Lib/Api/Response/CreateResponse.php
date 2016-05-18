<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Response', 			'Model');

class CreateResponse extends ApiCall {

	protected $_permissions = array('user', 'business');
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$Response = new Response();
		$session 	= Alloy::instance()->getSession();

		$userId = isset($session['UserSession']['user_id']) ? trim($session['UserSession']['user_id']) : null;
		$questionTitle = isset($data['question_title']) ? trim($data['question_title']) : null;
		$questionCategories = isset($data['categories']) ? $data['categories'] : null;
		$questionPoints = isset($data['points']) ? trim($data['points']) : null;
		$categoriesPoints = array();

		foreach ($questionCategories as $category) {
			$categoryPush = array();
			$categoryPush['points'] = $questionPoints * $category['contribution'];
			$categoryPush['id'] = $category['id'];
			array_push($categoriesPoints, $categoryPush);
		}

    $responseData = array(
      'Response' => array(
        'user_id' => $userId,
				'question_title' => $questionTitle,
				'question_categories' => $categoriesPoints
      )
    );

    $Response->create();
    $response = $Response->save($responseData);

		$return = array(
			'response' => $response['Response']
		);

		return new ApiResponse($return);

	}
}
