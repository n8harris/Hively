<?php

App::uses('ApiCall', 		'Lib/Alloy');
App::uses('Category', 			'Model');

class CreateCategory extends ApiCall {

	protected $_permissions = '*';
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$Category = new Category();

		$title = isset($data['title']) ? trim($data['title']) : null;
		$contentfulId = isset($data['contentful_id']) ? trim($data['contentful_id']) : null;

    $categoryData = array(
      'Category' => array(
        'title' => $title,
				'contentful_id' => $contentfulId
      )
    );

    $Category->create();
    $category = $Category->save($categoryData);

		$return = array(
			'category' => $category['Category']
		);

		return new ApiResponse($return);

	}
}
