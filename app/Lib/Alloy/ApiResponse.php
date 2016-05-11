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

App::uses('UserSession', 	'Model');
App::uses('Alloy', 			'Lib/Alloy');

class ApiResponse {

	protected	$_session;
	protected	$_status;
	protected	$_errors;
	protected	$_message;
	protected 	$_data;
	protected 	$_debug;

	public function __construct($data = array(), $status = 1, $message = '', $errors = array(), $debug = '') {

		if($data === null) $data = array();

		$this->_data	= $data;
		$this->_status	= $status;
		$this->_errors	= $errors;
		$this->_message	= $message;
		$this->_debug	= $debug;
	}

	public function toArray() {

		$session = Alloy::instance()->getSession();

		$array = array(
			'session_id'	=> UserSession::makeUnifiedId($session),
			'email'         => isset($session['UserSession']['email']) ? $session['UserSession']['email'] : null,
			'role'			=> isset($session['UserSession']['role']) ? $session['UserSession']['role'] : 'anon',
			'status'		=> $this->_status,
			'message'		=> $this->_message,
			'errors'		=> $this->_errors,
			'data'			=> $this->_data
		);

		if($this->_debug) {
			$array['debug'] = $this->_debug;
		}
		return $array;
	}

	public function getStatus() {
		return $this->_status;
	}

	public function getErrors() {
		return $this->_errors;
	}
	public function getMessage() {
		return $this->_message;
	}
	public function getData() {
		return $this->_data;
	}
	public function getDebug() {
		return $this->_debug;
	}

}
