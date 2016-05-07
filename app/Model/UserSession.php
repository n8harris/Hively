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

App::uses('AppModel', 		'Model');
App::uses('SecureCrypt', 	'Lib');
App::uses('User', 			'Model');

class UserSession extends AppModel {

	public $sessionLength = 172800; // in seconds

	public $name = 'UserSession';
  var $useDbConfig = 'mongo';
  public $mongoSchema = array(
      'user_id' => array('type' => 'objectId'),
      'username' => array('type' => 'string'),
      'key' => array('type' => 'string'),
      'email' => array('type' => 'string'),
      'role' => array('type' => 'string'),
      'created' => array('type' => 'date'),
      'last_login_date' => array('type' => 'date')
  );


	public function generateKey($length = 30) {

		$charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$str = '';
		$count = strlen($charset);
		while ($length--) {
			$str .= $charset[mt_rand(0, $count-1)];
		}
		return $str;
	}

	public static function makeUnifiedId($userSession) {

		if(isset($userSession['UserSession'])) {
			$userSession = $userSession['UserSession'];
		}
		return $userSession['id'] . "-" . $userSession['key'];
	}

	public function start() {

		$this->create();
		return $this->save(array(
			'key' => SecureCrypt::makeSalt(),
			'role' => UserSession::ROLE_ANON
		));
	}

	public function loadIfValid($id, $key) {

		$conditions = array(
			'UserSession.id'	=> $id,
			'UserSession.key'	=> $key,
			"UserSession.created >=" => date("Y-m-d H:i:s", time() - $this->sessionLength)
		);
		return $this->find('first', array('conditions' => $conditions));
	}

	public function loadIfValidFromUnified($id) {

		$parts = explode("-", $id);
		if(count($parts) == 2) {
			return $this->loadIfValid($parts[0], $parts[1]);
		}
		return null;
	}

	public function destroy($id) {

		return $this->delete($id);
	}

	public function login($id, $user) {

		$data = array(
			'id' 		=> $id,
			'user_id' 	=> $user['User']['id'],
			'role'		=> $user['User']['role'],
			'username' 	=> $user['User']['username'],
			'last_login_date' => $user['User']['last_login_date']
		);
		if(isset($user['User']['email']) && $user['User']['email']) {
			$data['email'] = $user['User']['email'];
		}
		$status = $this->save($data);

		return $this->find('first', array('conditions' => array('UserSession.id' => $id)));
	}

	public function logout($sessionId) {
		$this->delete($sessionId);
		return $this->start();
	}


}
