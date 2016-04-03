<?php

App::uses('AppController', 'Controller');
App::uses('Alloy', 'Lib/Alloy');

class LandingController extends AppController {

	public $helpers = array("Cache");

	public $cacheAction = '1 hour';

	public function index() {

		$this->layout = 'landing';

	}

}
