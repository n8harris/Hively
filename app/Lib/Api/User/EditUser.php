<?php

App::uses('User', 'Model');
App::uses('UserSession', 'Model');

/**
 * Class EditUser
 *
 * Edits a user with data from the form
 *
 * @return user The edited user
 */

class EditUser extends ApiCall {

	protected $_permissions = array('user');

	protected $_validation = array();

	protected function _execute(array $data = array()) {
		$session = Alloy::instance()->getSession();
		$User = new User();
		$User->create();
		$userToEdit = $User->findById($session['UserSession']['user_id']);
		$fieldsToSave = array();
		//Only edit fields we need to
		if (!empty($userToEdit)) {
			foreach ($data as $field => $value) {
				if (!$User->hasField($field)) {
					unset($data[$field]);
				} else {
					trim($data[$field]);
					$fieldsToSave[] = $field;
				}
			}
			$data['id'] = $userToEdit['User']['id'];
			$user = $User->saveAll($data, array('fieldlist' => array('User' => $fieldsToSave)));
			$returnedUser = $User->findById($userToEdit['User']['id']);
			if (!$user) {
				return new ApiResponse(null, -1, "There was a problem updating the user");
			} else {
				return new ApiResponse(array(
					'user' => $returnedUser['User']
				));
			}
		} else {
			return new ApiResponse(null, -1, "There was a problem retrieving the user to update");
		}
	}
}
