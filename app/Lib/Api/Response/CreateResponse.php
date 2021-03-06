<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Response', 			'Model');
App::uses('User', 			'Model');

/**
 * Class CreateResponse
 *
 * Creates a question response using data from the form
 *
 * @return response The created response
 */

class CreateResponse extends ApiCall {

	protected $_permissions = array('user', 'business');
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$Response = new Response();
		$session 	= Alloy::instance()->getSession();

		$userId = isset($session['UserSession']['user_id']) ? trim($session['UserSession']['user_id']) : null;
		$questionTitle = isset($data['question_title']) ? trim($data['question_title']) : null;
		$questionId = isset($data['question_id']) ? trim($data['question_id']) : null;
		$questionCategories = isset($data['categories']) ? $data['categories'] : null;
		$questionPoints = isset($data['points']) ? trim($data['points']) : null;
		$lastQuestion = isset($data['last_question']) ? $data['last_question'] : null;
		$categoriesPoints = array();
		if($lastQuestion == "true"){
			$User = new User();
			$User->create();
			$session = Alloy::instance()->getSession();
			$userToEdit = $User->findById($session['UserSession']['user_id']);
			$userData = array();
			$userData['id'] = $userToEdit['User']['id'];
			$userData['answered'] = true;
			$user = $User->saveAll($userData, array('fieldlist' => array('User' => array('answered'))));
		}

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
				'question_categories' => $categoriesPoints,
				'question_id' => $questionId
      )
    );

    $Response->create();
    $response = $Response->save($responseData);

		$return = array(
			'response' => $response['Response'],
		);

		return new ApiResponse($return);

	}
}
