<?php

App::uses('Socrata', 'Lib');

class AddBusinessesShell extends AppShell {

  public $uses = array('Business');

  public function main() {
		$Socrata = new Socrata();
		$Socrata->url = Configure::read('colorado.api');
    $businesses = $Socrata->getBusinesses(0, 0);
		$Business = new Business();
		foreach($businesses as $business){
			$Business->create();
			$businessData = array(
				'Business' => array (
					'name' => $business->entityname,
					'address_line_1' => $business->location_address,
					'city' => $business->location_city,
					'state' => $business->location_state,
					'zip' => $business->location_zip,
					'claimed' => false
				)
			);
			$Business->save($businessData);
		}
		print("========= Sync Completed ================ \n");
    die();
  }
}
