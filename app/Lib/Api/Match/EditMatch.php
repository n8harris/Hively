<?php

App::uses('Match', 'Model');

class EditMatch extends ApiCall {

	protected $_permissions = array('user', 'business');

	protected $_validation = array(
		'match_id' 	=> array('required' => true),
	);

	protected function _execute(array $data = array()) {
		$Match = new Match();
		$Match->create();
		$matchId = isset($data)
		$matchToEdit = $Match->findById($data['match_id']);
		$fieldsToSave = array();
		if (!empty($matchToEdit)) {
			foreach ($data as $field => $value) {
				if (!$Match->hasField($field)) {
					unset($data[$field]);
				} else {
					trim($data[$field]);
					$fieldsToSave[] = $field;
				}
			}
			$data['id'] = $matchToEdit['Match']['id'];
			$user = $Match->saveAll($data, array('fieldlist' => array('Match' => $fieldsToSave)));
			$returnedMatch = $Match->findById($matchToEdit['Match']['id']);
			if (!$match) {
				return new ApiResponse(null, -1, "There was a problem updating the match");
			} else {
				return new ApiResponse(array(
					'match' => $returnedMatch['Match']
				));
			}
		} else {
			return new ApiResponse(null, -1, "There was a problem retrieving the match to update");
		}
	}
}
