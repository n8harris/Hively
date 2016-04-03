<?php

class Socrata {

	public $url = null;

	public function getBusinesses($limit, $offset) {

		$url = $this->url;
    if ($limit || $offset) {
      $url = $url . "?";
      if($limit && $offset) {
        $url = $url . '$limit=' . $limit . '&$offset=' . $offset;
      } else if ($limit) {
        $url = $url . '$limit=' . $limit;
      } else if ($offset) {
        $url = $url . '$offset=' . $offset;
      }
    }

		$data = $this->_curlRequest($url);

		return $data;
	}

	protected function _curlRequest($url, $isPost = false, $data = null, $useCache = true) {

		$key = md5($url);
		$result = $useCache ? Cache::read($key) : null;

		if(true || !$result) {

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, str_replace(" ", "%20", $url));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'X-App-Token: ' . Configure::read('colorado.key')
                  ));

			// debugging
//			curl_setopt($ch, CURLOPT_HEADER, false);
//			curl_setopt($ch, CURLOPT_VERBOSE, true);
//			curl_setopt($ch, CURLINFO_HEADER_OUT, false);

			if($isPost) {
				curl_setopt($ch, CURLOPT_POST, 1);
				if($data) {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				}
			}

			$result = curl_exec ($ch);
			curl_close ($ch);

			Cache::set('duration', '+10 minutes', 'default');

			Cache::write($key, $result);
			Cache::set(null);
		}

		return json_decode($result);
	}

}
