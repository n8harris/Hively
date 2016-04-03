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

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('ApiResponse', 	'Lib/Alloy');
App::uses('ApiException', 	'Lib/Alloy');
App::uses('Inflector', 		'Utility');

class Alloy {

	protected static $_instance = null;

	protected $_apiMap 			= '[Call][Api]';
	protected $_apiCache		= array();
	protected $_session 		= null;

	public static function instance() {
		return Alloy::$_instance ? Alloy::$_instance : Alloy::$_instance = new Alloy;
	}

	final public function __clone() {
		throw new ApiException("Singleton class cannot be cloned.");
	}

	final public function __construct() {
		if(Alloy::$_instance) {
			throw new ApiException("Singleton class has already been instantiated.");
		}
	}

	public function dispatch($api, $call, $sessionId = null, array $data = array()) {

		$apiLibrary = str_replace(
			array("[Api]", "[Call]"),
			array(Inflector::camelize($api), Inflector::camelize($call)),
			$this->_apiMap
		);
		$location = "Lib/Api/" . Inflector::camelize($api);

		try {

			App::uses($apiLibrary, $location);

			if(!class_exists($apiLibrary)) {
				throw new ApiException(sprintf('API library %s does not exist.', $apiLibrary));
			}
			if(!isset($this->_apiCache[$apiLibrary])) {
				$this->_apiCache[$apiLibrary] = new $apiLibrary();
			}

			$response = $this->_apiCache[$apiLibrary]->execute($data);
			if(!($response instanceof ApiResponse)) {
				throw new ApiException('API call did not return ApiResponse object.');
			}
			return $response;
		} catch(Exception $ex) {
			if($ex instanceof ApiException) {
				return new ApiResponse(null, $ex->getCode(), $ex->getMessage(), $ex->getDebug());
			} else {
				return new ApiResponse(null, $ex->getCode(), "An error occurred.", $ex->getMessage());
			}
		}
	}

	public function getApiMap() {
		return $this->_apiMap;
	}

	public function setApiMap($map) {
		$this->_apiMap = $map;
	}

	public function getSession() {
		return $this->_session;
	}

	public function getUserId() {
		return isset($this->_session['UserSession']['user_id']) ? $this->_session['UserSession']['user_id'] : null;
	}

	public function setSession($session) {
		$this->_session = $session;
	}

}
