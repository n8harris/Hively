<?php

/*
  Migrate data for flex payment options.
  New Tables: Orders, Transactions
 */

class AddCategoriesShell extends AppShell {

  public $uses = array('Category');

  public function main() {
		$client = new \Contentful\Delivery\Client(Configure::read('contentful.key'), Configure::read('contentful.space'));
		$query = new \Contentful\Query();
		$query->setContentType('category');
		$contentfulCategories = $client->getEntries($query);
		$Category = new Category();
		$categories = $Category->getCategories();
		foreach($contentfulCategories->getIterator() as $contentfulCategory) {
			$addCategory = true;
			foreach($categories as $category) {
				if ($category['Category']['contentful_id'] == $contentfulCategory->getId()) {
					$addCategory = false;
				}
			}
			var_dump($contentfulCategory);
			if ($addCategory) {
				$categoryData = array(
		      'Category' => array(
		        'title' => $contentfulCategory->getTestCategory1(),
						'contentful_id' => $contentfulCategory->getId()
		      )
		    );

		    $Category->create();
		    $category = $Category->save($categoryData);
			}
		}
		print("========= Sync Completed ================ \n");
    die();
  }
}
