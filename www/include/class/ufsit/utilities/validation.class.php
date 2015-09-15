<?php
namespace ufsit\utilities;
class Validation{
    const BASE16_CHARSET = '0123456789abcdef';
    const BASE36_CHARSET = '0123456789abcdefghijklmnopqrstuvwxyz';
    
	public static function isValidLength($input,$max,$min = 0){
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
	public static function isValidEmail($email, $maxLen, $minLen = 6,$clean = true){
		if(!is_null($email)){
			if($clean){
				$email = @strip_tags($email);	
				$email = @stripslashes($email);
				$email = trim($email);	
			}
			if(self::isValidLength($email,$maxLen,$minLen) && self::validEmailAddress($email)){
				return $email;
			}
		}
		return false;
	}
	
	/* 
	 * Checks if a name appears to be valid. Allows most unicode characters
	 * so as to permit names from other regions and cultures. Only denys names
	 * with a select set of unicode values.
	 * $name: the name to test
	 * $maxLen: the maximum length for the name
	 * $minLen: the minimum length for the name (default 3)
	 * $clean: when true, it will replace some occurrences of duplicate characters
	 * return: false if not valid, string (representing $name) if valid.
	 */
	public static function isValidName($name, $maxLen, $minLen = 3,$clean = true){
		//$invalidChars = '/[\u0000-\u0020\!\"\#\$\%\&\(\)\*\+\,0-9:;\<\=\>\?\@\[\\\]\^_\`\{\|\}\~\u00A0-\u00BF]/u';
		$validName = '~^(?:[\p{L}\p{Mn}\p{Pd}\']++\s?)++$~u'; //don't forget to double check with the regex used in cleaner.php
		if(preg_match($validName,$name) !== 1){
			return false;
		}
		if(!self::isValidLength($name,$maxLen,$minLen)){
			return false;
		}
		if($clean){
			$toClean = array(
				'/\s{2,}/', //two or more whitespace characters
				'/\p{Pd}+/u', //dash punctuation, one or more
				'/\.{2,}/' //two or more periods
			);
			$replaceWith = array(
				' ', //a single space
				'-', //a single minus sign
				'.'  //a single period
			);
			$name = preg_replace($toClean,$replaceWith,$name);
		}
		return $name;
	}
	
	
	/* From http://www.linuxjournal.com/article/9585 */
	/**
	Validate an email address.
	Provide email address (raw input)
	Returns true if the email address has the email 
	address format and the domain exists.
	*/
	private static function validEmailAddress($email)
	{
	   $isValid = true;
	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex)
	   {
		  $isValid = false;
	   }
	   else
	   {
		  $domain = substr($email, $atIndex+1);
		  $local = substr($email, 0, $atIndex);
		  $localLen = strlen($local);
		  $domainLen = strlen($domain);
		  if ($localLen < 1 || $localLen > 64)
		  {
			 // local part length exceeded
			 $isValid = false;
		  }
		  else if ($domainLen < 1 || $domainLen > 255)
		  {
			 // domain part length exceeded
			 $isValid = false;
		  }
		  else if ($local[0] == '.' || $local[$localLen-1] == '.')
		  {
			 // local part starts or ends with '.'
			 $isValid = false;
		  }
		  else if (preg_match('/\\.\\./', $local))
		  {
			 // local part has two consecutive dots
			 $isValid = false;
		  }
		  else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		  {
			 // character not valid in domain part
			 $isValid = false;
		  }
		  else if (preg_match('/\\.\\./', $domain))
		  {
			 // domain part has two consecutive dots
			 $isValid = false;
		  }
		  else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
		  {
			 // character not valid in local part unless 
			 // local part is quoted
			 if (!preg_match('/^"(\\\\"|[^"])+"$/',
				 str_replace("\\\\","",$local)))
			 {
				$isValid = false;
			 }
		  }
		  if(function_exists("checkdnsrr")){
			  if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
			  {
				 // domain not found in DNS
				 $isValid = false;
			  }
			}
	   }
	   return $isValid;
	}
    
    public static function isValidBase16($testHex){
        $regex = sprintf('/^[%s]+$/',self::BASE16_CHARSET); 
        return preg_match($regex, $testHex);
    }
    
    public static function isValidBase36($testBase36){
        $regex = sprintf('/^[%s]+$/',self::BASE36_CHARSET); 
        return preg_match($regex, $testBase36);
    }
}

?>
