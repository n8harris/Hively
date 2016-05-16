<?php

App::uses('AppController', 'Controller');
App::uses('Alloy', 'Lib/Alloy');
App::uses('Credential', 'Model');

class LandingController extends AppController {

	public $helpers = array("Cache");

	public $cacheAction = '1 hour';

	function beforeFilter() {

		$this->Cookie->name 	= Configure::read('cookie.name');
		$this->Cookie->key 		= Configure::read('cookie.key');
		$this->Cookie->time 	= Configure::read('cookie.time');

		$sessionId 				= $this->Cookie->read('session_id');
		$session 				= null;

		if($sessionId) {
			$session = $this->UserSession->loadIfValidFromUnified($sessionId);
		}
		if(!$session) {
			$session = $this->UserSession->start();
		}
		Alloy::instance()->setSession($session);

		parent::beforeFilter();
	}

	function beforeRender() {

		$response = Alloy::instance()->dispatch('user', 'load');
		$this->set(array('bootstrap' => $response->getData()));

		parent::beforeRender();
	}

	function afterFilter() {

		$this->Cookie->write('session_id', UserSession::makeUnifiedId(Alloy::instance()->getSession()), false);

		parent::afterFilter();
	}

	public function index() {

		$this->layout = 'landing';

	}

}
