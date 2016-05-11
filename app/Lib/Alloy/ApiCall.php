<?php
/**
 *  Part of the Alloy Library
 *
 *  Copyright (c) 2012, Tyler Seymour <tyler@unwitty.com>
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 *  following conditions are met:
 *
 *  Redistributions of source code must retain the above copyright notice, this list of conditions and the following
 *  disclaimer.
 *
 *  Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following
 *  disclaimer in the documentation and/or other materials provided with the distribution.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 *  INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 *  SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 *  WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
 *  USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

App::uses('UserSession', 'Model');
App::uses('Alloy', 'Lib/Alloy');
App::uses('ApiException', 'Lib/Alloy');

/**
 * The base class for all API Calls.  Derive from this class and do the following:
 * 1) Override _execute(), returning a valid alloy\api\ApiResponse object
 * 2) Override $_permissions with a list of valid user roles who can access the call
 * 3) Override $_validation with a list of valid parameters for the call
 *
 */
class ApiCall {

	const	NOT_AUTHORIZED		= -1;
	const	INVALID_REQUEST		= -2;

	protected $_permissions 		= null;
	protected $_validation 			= null;

	protected $_validationErrors 	= array();

	/**
	 * Called by the dispatcher.
	 *
	 * @param array $data
	 * @return ApiResponse|void
	 */
	public function execute(array $data = array(), $isJson = false) {

		if($isJson) {
			$data = json_decode($data, true);
		}

		if($this->_permissions === null || $this->_validation === null) {
			throw new ApiException("You must define permissions and validation rules.");
		}

		if(!$this->_checkPermissions()) {
			return new ApiResponse(null, ApiCall::NOT_AUTHORIZED, "Not authorized");
		}
		if(!$this->_checkValidation($data)) {
			return new ApiResponse(null, ApiCall::INVALID_REQUEST, "Request parameters were invalid.", $this->_validationErrors);
		}

		return $this->_execute($data);
	}

	/**
	 * You should override this.
	 *
	 * @param array $data
	 * @return void
	 */
	protected function _execute(array $data) {
		throw new ApiException("You must override the 'execute()' function of this ApiCall");
	}

	/**
	 * Validate if the current user can access the call
	 * @return bool
	 */
	protected function _checkPermissions() {
		$session = Alloy::instance()->getSession();
		return $this->_permissions == '*' || in_array($session['UserSession']['role'], $this->_permissions);
	}

	/**
	 * Validate the data provided to the API call. Populates _validationErrors with any issues.
	 *
	 * @param array $data Name array of parameters passed to the call
	 * @return bool
	 */
	protected function _checkValidation(array $data) {

		// required keys
		foreach($this->_validation as $key => $rules) {
			if(isset($rules['required']) && $rules['required'] == true && !isset($data[$key])) {
				$this->_validationErrors[$key] = "Missing required field";
			}
		}
		return count($this->_validationErrors) == 0;
	}


}
