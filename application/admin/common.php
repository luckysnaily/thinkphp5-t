<?php 

function removeXSS($val){

	static $obj = null;

	if($obj === null){

		require '../extend/HTMLPurifier/HTMLPurifier.includes.php';

		$obj = new HTMLPurifier();
	}

	return $obj->purify($val);
}

