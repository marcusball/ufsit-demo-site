<?php
require_once 'require.php';
require_once 'validation.php';

define('AUTH_RESULT_ERROR',0);
define('AUTH_RESULT_SUCCESS',1);

class Authentication{
	private $_sqlCon;
	
	private $_isLoggedIn = false; //If the user has been validated as being logged in.
	private $_hasCheckedAuthentication = false; //If the class has checked yet whether the user is logged in. 
	
	/*
	 * Constructor for the Authentication class
	 * Arguments: PDO sql connection object.
	 */
	public function __construct(){
		//Loop to handle all of the arguments given in the contructor
		for($i=0;$i<func_num_args();$i++){
			$arg = func_get_arg($i);
			if(is_object($arg)){
				$class = get_class($arg);
				if($class == 'PDO'){
					$this->_sqlCon = $arg;
				}
			}
		} 
		session_start();
		$this->checkAuthentication();
	}
	
	private function checkAuthentication(){
		$this->_isLoggedIn = $this->hasAuthentication();
		$this->_hasCheckedAuthentication = true;
	}
	
	private function hasAuthentication(){
		if(!isset($_SESSION['USER_ID']) || (trim($_SESSION['USER_ID'])=='')) { 
			return false;
		}
		if(!$this->sessionNotExpired()){
			return false;
		}
		return true;
	}
	
	private function sessionNotExpired(){
		if(!isset($_SESSION['COUNT']) || $_SESSION['COUNT'] <= 0){
			return false;
		}
		$_SESSION['COUNT'] -= 1; //Decrement the count
		$this->renewSessionCount();
		return true;
	}
	private function renewSessionCount(){
		session_regenerate_id();
		$_SESSION['COUNT'] = SESSION_USE_COUNT;
	}
	
	/**** Below here are authentication methods for registration and login ****/
	
	/*
	 * Register new users
	 * Notice: This method only performs minimal error checking, be sure to validate input first!
	 */
	public function registerNewUser($email,$firstName,$lastName,$password){
		if(!isset($email,$firstName,$lastName,$password)){
			throw new Exception('All parameters of registerNewUser must be present and not null!');
		}
		if(!Validator::validEmail($email, INPUT_EMAIL_MAX_LENGTH, INPUT_EMAIL_MIN_LENGTH)){
			return new AuthenticationResult(AUTH_STATUS_ERROR,'Email address does not appear to be valid!');
		}
		if(!Validator::validLength($password, INPUT_PASSWORD_MAX_LENGTH, INPUT_PASSWORD_MIN_LENGTH)){
			return new AuthenticationReturn(AUTH_STATUS_ERROR,'Password does not appear to be valid!');
		}                  
	}
}

class AuthenticationResult{
	private $_status;
	private $_mes;
	public function __construct($status,$message){
		if($status == AUTH_RESULT_SUCCESS){
			$this->_status = true;
		}
		else{
			$this->_status = false;
		}
		$this->_mes = $message;
	}
	
	public function isSuccessful(){
		return $this->_status;
	}
	public function getMessage(){
		return $this->_mes;
	}
}
?>