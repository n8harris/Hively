<?php

App::uses('AppModel', 		'Model');
App::uses('User', 			'Model');

class Response extends AppModel {

  public $name = 'Response';
  var $useDbConfig = 'mongo';
  public $mongoSchema = array(
      'user_id' => array('type' => 'objectId'),
      'question_title' => array('type' => 'string'),
      'question_categories' => array('type' => 'array')
  );

}
