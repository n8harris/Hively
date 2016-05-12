<?php

App::uses('Category','Model');

class ListAllCategory extends ApiCall {

  protected $_permissions = '*';
	protected $_validation = array();

	protected function _execute(array $data = array()) {

		$Category = new Category();
    $categories = $Category->getCategories();

    return new ApiResponse(array(
			'categories' => $categories
		));

  }
}
