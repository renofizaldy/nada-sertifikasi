<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privates {
	protected $salt = '';
	protected $iterations;
	protected $algorithms  = array();

	function __construct() {
		$this->algorithms = ['sha512', 'ripemd320', 'whirlpool'];
		$this->iterations = 2;
	}

	public function hash($user_salt, $toHash, $salt) {
		$this->salt = $salt;
		return self::staticHash($toHash, $user_salt, $this->salt, $this->iterations, $this->algorithms);
	}

	public static function staticHash($toHash, $user_salt, $salt, $iterations, $algorithms) {
		for ($i=0;$i<$iterations;$i++) {
			$toHash = $user_salt.$toHash.$salt;
			$tempHash = '';
			foreach($algorithms as $algo) {
				$tempHash .= \hash($algo, $toHash);
			}
			$toHash = \hash($algo, $tempHash);
		}
		return $toHash;
	}

}
