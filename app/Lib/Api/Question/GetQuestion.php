<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Response', 'Model');

/**
 * Class GetQuestion
 *
 * Loads all questions defined in Contentful CMS
 *
 * @return questions The questions returned from the API call
 */
class GetQuestion extends ApiCall {

	protected $_permissions = array('user', 'business');
	protected $_validation = array();

	protected function _execute(array $data = array()) {
		$Response = new Response();
		$session = Alloy::instance()->getSession();
		$userId = isset($session['UserSession']['user_id']) ? $session['UserSession']['user_id'] : null;
		$responses = null;
		$excludedQuestions = null;

		if (isset($userId)) {
			$responses = $Response->find('all', array(
				'conditions' => array(
					'user_id' => $userId,
				)
			));
		} else {
			return new ApiResponse(null, -1, "Error loading questions");
		}

		if (!empty($responses)) {
			$excludedQuestions = $responses;
		} else {
			$excludedQuestions = array();
		}

		//Use Contentful PHP SDK to retrieve our questions
		$client = new \Contentful\Delivery\Client(Configure::read('contentful.key'), Configure::read('contentful.space'));
		$query = new \Contentful\Query();
		$query->setContentType('question');
		$isBusiness = isset($data['business']) ? trim($data['business']) : 'false';
		$query->where('fields.isBusinessQuestion', $isBusiness);
		foreach($excludedQuestions as $question){
			$query->where('sys.id', $question['Response']['question_id'], "ne");
		}
		$questions = $client->getEntries($query);
		$questionsList = array();

		//Generate simpler JSON structure for calculating matches
		foreach($questions->getIterator() as $question) {
			$questionsPush = array();
			$questionsPush['title'] = $question->getTitle();
			$questionsPush['id'] = $question->getId();
			$questionsPush['multiple'] = $question->getMultipleChoice();
			$questionOptions = $question->getMultipleOptions();
			$questionsPush['options'] = array();
			foreach($questionOptions as $option) {
				$questionOptionsPush = array();
				$multiple = $client->getEntry($option->getId());
				$contrib = $multiple->getCategoryContribution();
				$questionOptionsPush['title'] = $multiple->getTitle();
				$questionOptionsPush['id'] = $option->getId();
				if (!empty($contrib)) {
					$category = $contrib->getCategory();
					$questionOptionsPush['categories'] = array();
					$questionCategoryPush = array();
					$questionCategoryPush['contribution'] = $contrib->getPercentage();
					$questionCategoryPush['title'] = $category->getTestCategory1();
					$questionCategoryPush['id'] = $category->getId();
					array_push($questionOptionsPush['categories'], $questionCategoryPush);
					array_push($questionsPush['options'], $questionOptionsPush);
				} else {
					$questionOptionsPush['categories'] = array();
					$contribs = $multiple->getCategoriesContribution();
					foreach ($contribs as $contrib) {
						$questionContribPush = array();
						$category = $contrib->getCategory();
						$questionContribPush['contribution'] = $contrib->getPercentage();
						$questionContribPush['title'] = $category->getTestCategory1();
						$questionContribPush['id'] = $category->getId();
						array_push($questionOptionsPush['categories'], $questionContribPush);
					}
					array_push($questionsPush['options'], $questionOptionsPush);
				}

			}
			array_push($questionsList, $questionsPush);
		}

		return new ApiResponse(array(
			'questions' => $questionsList
		));
	}
}
