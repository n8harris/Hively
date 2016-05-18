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

	protected $_permissions = array('user', 'business');
	protected $_validation = array();

	protected function _execute(array $data = array()) {
		$client = new \Contentful\Delivery\Client(Configure::read('contentful.key'), Configure::read('contentful.space'));
		$query = new \Contentful\Query();
		$query->setContentType('question');
		$isBusiness = isset($data['business']) ? trim($data['business']) : 'false';
		$query->where('fields.isBusinessQuestion', $isBusiness);
		$questions = $client->getEntries($query);
		$questionsList = array();

		foreach($questions->getIterator() as $question) {
			$questionsPush = array();
			$questionsPush['title'] = $question->getTitle();
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
