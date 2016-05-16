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
		$questionTitle = isset($data['title']) ? trim($data['title']) : null;
		$questionCategories = isset($data['categories']) ? trim($data['categories']) : null;

    $responseData = array(
      'Response' => array(
        'user_id' => $userId,
				'question_title' => $questionTitle,
				'question_categories' => $questionCategories
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
