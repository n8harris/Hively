<?php
/**
 *  Part of the Alloy Library
 *
 *  Copyright (c) 2012, Tyler Seymour <tyler@unwitty.com>
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 *  following conditions are met:
 *
 *  Redistributions of source code must retain the above copyright notice, this list of conditions and the following
 *  disclaimer.
 *
 *  Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following
 *  disclaimer in the documentation and/or other materials provided with the distribution.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 *  INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 *  SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 *  WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
 *  USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

class SecureCrypt {

	const ITERATIONS 	= 500;
	const HASH			= 'sha256';
	const LENGTH		= 128;

	public static function hash($string, $salt) {
		return SecureCrypt::_pbkdf2($string, $salt);
	}

	public static function makeSalt() {
		$salt = array();
		for($i = 0; $i < SecureCrypt::ITERATIONS; $i++) {
			$salt[] = rand(0, 100000);
		}
		return hash('sha256', implode("", $salt));
	}

	/**
	 * Based on @http://code.google.com/p/securestring/
	 */
	protected static function _pbkdf2($string, $salt) {

		// Compute the length of hash alg output.
		// Some folks use a static variable and save the value of the hash len.
		// Considering we are doing 1000s hmacs, doing one more won't hurt.
		$hashLen = strlen(hash(SecureCrypt::HASH, null, true));

		// compute number of blocks need to make $hashLen number of bytes
		$numBlocks = ceil(SecureCrypt::LENGTH / $hashLen);

		// blocks are appended to this
		$output = '';
		for ($i = 1; $i <= $numBlocks; ++$i) {
			$block = hash_hmac(SecureCrypt::HASH, $salt . pack('N', $i), $string, true);
			$ib = $block;
			for ($j = 1; $j < SecureCrypt::ITERATIONS; ++$j) {
				$block = hash_hmac(SecureCrypt::HASH, $block, $string, true);
				$ib ^= $block;
			}
			$output .= $ib;
		}

		// extract the right number of output bytes
		return substr(base64_encode($output), 0, SecureCrypt::LENGTH);
	}
}

?>