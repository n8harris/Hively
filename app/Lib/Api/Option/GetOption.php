<?php

App::uses('ApiCall', 		'Lib/Alloy');

/**
 * Class GetOption
 *
 * Loads option defined in Contentful CMS
 *
 * @return option The option returned from the API call
 */
class GetOption extends ApiCall {

	protected $_permissions = array('user');
	protected $_validation = array(
		'option' 	=> array('required' => true)
	);

	protected function _execute(array $data = array()) {
		$optionId = isset($data['option']) ? $data['option'] : null;
		$client = new \Contentful\Delivery\Client(Configure::read('contentful.key'), Configure::read('contentful.space'));
		$option = $client->getEntry($optionId);

		return new ApiResponse(array(
			'option' => $option
		));
	}
}
