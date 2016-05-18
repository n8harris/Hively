<?php

App::uses('AppModel', 		'Model');

class CategoryTotal extends AppModel {

  public $name = 'CategoryTotal';
  var $useDbConfig = 'mongo';
  public $mongoSchema = array(
      'user_id' => array('type' => 'objectId'),
      'category_totals' => array('type' => 'array')
  );
}
