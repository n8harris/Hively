<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Business', 			'Model');

/**
 * Class GetBusiness
 *
 * Gets a business.
 *
 * @return business The business matched by the id
 */
class GetBusiness extends ApiCall {

	protected $_permissions = array('user', 'business');
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$User = new User();
		$accountId = isset($data['account_id']) ? $data['account_id'] : null;

		if (isset($accountId)) {
			$business = $Business->findByAccountId($accountId);
		} else {
			return new ApiResponse(null, -1, "Error finding business");
		}

		return new ApiResponse(array(
			'business' => $business ? $business['Business'] : array()
		));
	}
}
