<?php
require_once 'emailvalidate.php';
static class Validator{
	public static function validLength($input,$max,$min = 0){
        $len = strlen($input);
        if($len > $max) return false;
        if($len < $min) return false;
        return true;
    }
	
	/*
	 * Checks if an email appears to be valid.
	 * $email: The emaid address to test
	 * $maxLen: the maximum length we will allow for the email string
	 * $minLen: the smallest allowable length for the email string
	 * $clean: if true, we will also trim, strip_tags, stripslashes, etc. 
	 * Note: This will not shorten an email that is above maxLen.
	 */
	function validEmail($email, $maxLen, $minLen = 6,$clean = true){
		if(!is_null($email)){
			if($clean){
				$email = @strip_tags($email);	
				$email = @stripslashes($email);
				$email = trim($email);	
			}
			if(self::validLength($email,$maxLen,$minLen) && validEmail($email)){
				return true;
			}
		}
		return false;
	}
}

?>