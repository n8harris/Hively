<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Business', 		'Model');

/**
 * Class ListAllBusinesses
 *
 * Loads all businesses in Colorado
 *
 * @return businesses The businesses returned
 */
class ListAllBusiness extends ApiCall {

	protected $_permissions = '*';
	protected $_validation = array();

	protected function _execute(array $data = array()) {
		$Business = new Business();
		$Business->create();
    $businesses = $Business->find('all', array(
        'conditions' => array('claimed' => false)
    ));

		return new ApiResponse(array(
			'businesses' => $businesses
		));
	}
}
