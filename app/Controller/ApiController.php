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

App::uses('Alloy', 'Lib/Alloy');
App::uses('UserSession', 'Model');

class ApiController extends AppController {


	function beforeFilter() {

		$this->layout = 'ajax';
		$this->view = 'api';
		$this->viewPath = '';
		$this->response->type('application/json');

		parent::beforeFilter();
	}

	public function index() {

		$data = $this->request->query + $this->request->data;

		$api		= isset($data['api']) 	? $data['api'] : null;
		$call		= isset($data['call']) 	? $data['call'] : null;

		// Check for authorization header
		$authHeader = apache_request_headers();

		if(isset($authHeader['Authorization'])){
			$sessionId = $authHeader['Authorization'];
		} else {
			$sessionId	= isset($data['session_id']) 	? $data['session_id'] : null;
		}

		unset($data['api']);
		unset($data['call']);
		unset($data['session_id']);

		$response = Alloy::instance()->dispatch($api, $call, $sessionId, $data);

		$this->autoRender = false;
		$this->response->type('json');
		$this->response->body(json_encode($response->toArray(), JSON_NUMERIC_CHECK));
	}
}
