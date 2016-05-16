<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Socrata', 		'Lib');

/**
 * Class GetBusinesses
 *
 * Loads all businesses in Colorado
 *
 * @return businesses The businesses returned from the external API call
 */
class GetBusiness extends ApiCall {

	protected $_permissions = array('user');
	protected $_validation = array();

	protected function _execute(array $data = array()) {
		$limit = isset($data['limit']) ? $data['limit'] : false;
		$offset = isset($data['offset']) ? $data['offset'] : false;
		$Socrata = new Socrata();
		$Socrata->url = Configure::read('colorado.api');

    $businesses = $Socrata->getBusinesses($limit, $offset);

		return new ApiResponse(array(
			'businesses' => $businesses
		));
	}
}
