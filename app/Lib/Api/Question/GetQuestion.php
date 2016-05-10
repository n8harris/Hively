<?php

App::uses('ApiCall', 		'Lib/Alloy');

/**
 * Class GetQuestion
 *
 * Loads all questions defined in Contentful CMS
 *
 * @return questions The businesses returned from the API call
 */
class GetQuestion extends ApiCall {

	protected $_permissions = '*';
	protected $_validation = array();

	protected function _execute(array $data = array()) {
		$client = new \Contentful\Delivery\Client(Configure::read('contentful.key'), Configure::read('contentful.space'));
		$query = new \Contentful\Query();
		$query->setContentType('question');
		$questions = $client->getEntries($query);

		return new ApiResponse(array(
			'questions' => $questions
		));
	}
}
