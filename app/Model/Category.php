<?php

App::uses('AppModel', 		'Model');

class Category extends AppModel {

  public $name = 'Category';
  var $useDbConfig = 'mongo';
  public $mongoSchema = array(
      'title' => array('type' => 'string'),
      'contentful_id' => array('type' => 'string')
  );

	public function getCategories() {
		$categories = $this->find('all');
		return $categories;
	}
}
