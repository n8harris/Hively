<?php

App::uses('Match', 'Model');

/**
 * Class EditMatch
 *
 * Edits a match based on fields specified in the payload
 *
 * @return match The edited match
 */

class EditMatch extends ApiCall {

	protected $_permissions = array('user', 'business');

	protected $_validation = array(
		'match' 	=> array('required' => true),
	);

	protected function _execute(array $data = array()) {
		$Match = new Match();
		$Match->create();
		$matchId = $data['match'];
		$matchToEdit = $Match->findById($matchId);
		$fieldsToSave = array();
		if (!empty($matchToEdit)) {
			//Get fields we need to update
			foreach ($data as $field => $value) {
				if (!$Match->hasField($field)) {
					unset($data[$field]);
				} else {
					trim($data[$field]);
					$fieldsToSave[] = $field;
				}
			}
			$data['id'] = $matchToEdit['Match']['id'];
			//Only save fields from fieldsToSave
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
			return new ApiResponse(null, -1, $matchId);
		}
	}
}
