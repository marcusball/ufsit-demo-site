<?php

require_once 'emailvalidate.php';
require_once 'require.php';
//require_once 'emailer.php';

session_start();

class User{
	private $dbReturn = null;
	private $sqlCon = null;
	private $authenticated = false;
	private $loggedIn = false;
	
	protected $opError = null;
	
	function debug($mes){
		echo $mes . "<br />";
	}
	function __construct(){
		try {
			$this->sqlCon = new PDO('pgsql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
			$this->sqlCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			logError($_SERVER['SCRIPT_NAME'],__LINE__,"Could not select database (".DB_NAME.").",$e->getMessage(),time());
		}
		
		if($this->authenticated == false){
			$this->loggedIn = $this->authenticate();
			$this->authenticated = true;
			if($this->loggedIn){
				$this->getInfo();
			}
		}
	}
	function isLoggedIn(){
		if($this->authenticated == false){
			$this->loggedIn = $this->authenticate();
			$this->authenticated = true;
		}
		return $this->loggedIn;
	}
	function getInfo(){
		if($this->isLoggedIn()){
			$infoQuery = "SELECT uid, email, first_name, last_name FROM users WHERE uid='{$_SESSION['USER_ID']}' LIMIT 1;";
			
			try{
				$stmt = $this->sqlCon->query($infoQuery);
				$this->dbReturn = $stmt->fetch();
				return $this->dbReturn;
				//return true;
			}
			catch(PDOException $e){
				logError($_SERVER['SCRIPT_NAME'],__LINE__,"Could not check user session! Query: \"$infoQuery\"",$e->getMessage(),time(),false);
			}
			return false;
		}
	}
			
	function authenticate(){
		if(!isset($_SESSION['USER_ID']) || (trim($_SESSION['USER_ID'])=='')) { 
			return false;
			//
		}
		else{
			return true;
			// $userID = $_SESSION['USER_ID'];
				
			// $infoQuery = "SELECT session FROM users WHERE uid='{$userID}' LIMIT 1;";
			// $info = null;
			// try{
				// $info = $this->sqlCon->query($infoQuery);
				// $info = $info->fetch();
			// }
			// catch(PDOException $e){
				// logError($_SERVER['SCRIPT_NAME'],__LINE__,"Could not check user session! Query: \"$infoQuery\"",$e->getMessage(),time(),false);
			// }
			// if($_SESSION['AUTH_KEY'] === $info['session']){
				// session_regenerate_id();
				// $authKey = newAuthKey();
				// $oldAuth = $_SESSION['AUTH_KEY'];
				// $_SESSION['AUTH_KEY'] = $authKey;
				
				// $updateAuthSQL = "UPDATE users SET session='{$authKey}' WHERE uid='{$userID}' LIMIT 1";
				// try{
					// $count = $this->sqlCon->exec($updateAuthSQL);
					
					// return true;
				// }
				// catch(PDOException $e){
					// logError($_SERVER['SCRIPT_NAME'],__LINE__,"Could not insert user's auth key into table! Query: \"{$updateAuthSQL}\"",mysql_error(),time(),false);
					// $_SESSION['AUTH_KEY'] = $oldAuth;
				// }
			// }
		}
		return false;
	}
	function uid(){
		return $this->getUserInfo('uid');
	}
	function getUserInfo($info){
		//echo $this->dbReturn['first_name'];
		if($this->dbReturn == null){
			$this->getInfo();
		}
		
		if(array_key_exists($info,$this->dbReturn)){
			return $this->dbReturn[$info];
		}
		else{
			return false;
		}
	}
	protected function setUserInfo($col,$val){
		$userID = $_SESSION['USER_ID'];
		
		$updateSQL = "UPDATE users SET {$col}='{$val}' WHERE uid='{$userID}' LIMIT 1";
		try{
			$this->sqlCon->exec($updateSQL);
		}
		catch(PDOException $e){
			logError($_SERVER['SCRIPT_NAME'],__LINE__,"Could not update user info! Column: \"{$col}\", Value: \"{$val}\" Query: \"{$updateAuthSQL}\"",mysql_error(),time(),false);
			return false;
		}
		
		$this->getInfo();
		return true;
	}
	function registerUser($email,$password,$fname,$lname){
	
		//Check if the user is already logged in
		if($this->isLoggedIn()){ 
			$this->opError = "User is already logged in.";
			return false;
		}
		
		//Make sure the email is valid
		if(!validEmail($email = cleanInput($email,0,500))){
			$this->opError = "The email address provided is not valid.";
			return false;
		}
		//Make sure the password, first name, and last name inputs are valid
		if(($password = cleanInput($password,6,40)) == false || ($fname = cleanInput($fname,0,100)) == false || ($lname = cleanInput($lname,0,100)) == false){
			$inv = "";
			if($password === false) $inv .= "password";
			if($fname === false) $inv .= ((strlen($inv) == 0)?"":", ")."first name";
			if($lname === false) $inv .= ((strlen($inv) == 0)?"":", ")."last name";
			$this->opError = "The following inputs appear to be invalid: $inv.";
			return false;
		}
		
		//Make sure the first and last name contain only valid characters
		if(!preg_match("/^[\w-]*$/",$fname) || !preg_match("/^[\w-]*$/",$lname)){
			$return["success"] = 0;
			array_push($return["errors"],"Your first name may only consist of alphanumeric characters or hyphens!");
			return $return;
		}
		
		
		$email = strtolower($email);
		
		/*
		 * TODO: Change to use better encryption
		 */
		$encrypted = sha1(sha1($password) . "" . sha1($email));
		
		//Build the query
		$userCheckQuery = "SELECT email FROM users WHERE email=:email LIMIT 1;"; //Make sure this keeps LIMIT 1
		try{
			$statement = $this->sqlCon->prepare($userCheckQuery);
			$statement->execute(array(':email' => $email));
		}
		catch(PDOException $e){
			$this->opError = "We're sorry, but something has gone wrong!";
			logError($_SERVER['SCRIPT_NAME'],__LINE__,"Error executing user check query! Query: \"$userCheckQuery\", Email: \"$email\".",$e->getMessage(),time());
			return false;
		}
		
		//If the query returned rows, then someone IS registered using this email
		if($statement->rowCount() > 0){
			$conflict = $statement->fetch();

			if($conflict['email'] == $email){
				$this->opError = "An account already exists associated with this email!";
				return false;
			}
		}
		else{
			/*
			 * Maybe this could be done more securely?
			 */
			$activationKey = sha1(uniqid(rand(), true).$email.SECRET_KEY);
			$nowDatetime = date('Y-m-d H:i:s');
			$insertQuery="INSERT INTO users (email,hash,first_name,last_name,creation_date,last_login) VALUES (:email,:password,:fname,:lname,:now,:now);";
			try{
				$this->sqlCon->beginTransaction();
				
				$regStatement = $this->sqlCon->prepare($insertQuery);
				$regStatement->execute(array(':email'=>$email,':password'=>$encrypted,':fname'=>$fname,':lname'=>$lname,':now'=>$nowDatetime));
				$uidValue = $this->sqlCon->lastInsertId('users_uid_seq');
				$confirmStatement = $this->sqlCon->prepare('INSERT INTO confirmations (confirm_code,user_id,date) VALUES (:confirmCode,:uid,:now);');
				$confirmStatement->execute(array(':confirmCode'=>$activationKey,':uid'=>$uidValue,':now'=>$nowDatetime));
				
				$this->sqlCon->commit();
			}
			catch(PDOException $e){
				logError($_SERVER['SCRIPT_NAME'],__LINE__,"An error occurred while registering a new user! Code: {$e->getCode()}.",$e->getMessage(),time(),false);

				$this->opError = "Oh no! An error occurred while trying to create your account! Please try again.";
				return false;
			}
			
			//If the uidValue is valid
			if($uidValue > 0 && $uidValue != false){
				// $emailer = new Emailer();
				// $sent = $emailer->sendAccountConfirmationEmail($uidValue,$activationKey);
				$this->giveCredentials($uidValue); 
				// if(!$sent){
					// array_push($return["errors"],'Your account was created successfully, but we couldn\'t send your confirmation email! Please send a message to support about it.');
					// logError($_SERVER['SCRIPT_NAME'],__LINE__,'Unable to send user\'s confirmation email!',null,time(),false);
				// }
				return true;
			}
			else{
				$this->opError = "Oh no! An error occurred while trying to create your account! Please try again.";
				return false;
			}
		}
		return false;
	}
	function logIn($email,$password){
		if($this->isLoggedIn()){
			$this->opError = 'You are already logged in!';
			return false;
		}
		
		if(($email = cleanInput($email,0,500)) == false){
			$this->opError = 'The email provided is invalid!';
			return false;
		}
		if(($password = cleanInput($password,8,40)) == false){
			$this->opError = 'The password provided is invalid!';
			return false;
		}
		
		if(!isset($_SESSION)){ //Fix for error: "Notice: A session had already been started - ignoring session_start()"
			session_start();
		}  
		session_unset();
		
		$email = strtolower($email);
		//$password = mysql_real_escape_string($password);
		
		/*
		 * TODO: Change to use better encryption
		 */
		$encrypted = sha1(sha1($password) . "" . sha1($email));

		$loginQuery = 'SELECT uid,hash,last_attempt,attempt_count,disabled FROM users WHERE email=:email LIMIT 1;';
		try{
			$loginStatement = $this->sqlCon->prepare($loginQuery);
			$loginStatement->execute(array(':email'=>$email));
			$loginResult = $loginStatement->fetch();
		}
		catch(PDOException $e){
			logError($_SERVER['SCRIPT_NAME'],__LINE__,"Could not log in user. Query: \"$loginQuery\"",$e->getMessage(),time());
			$this->opError = 'An error occurred while trying to log in!';
			return false;
		}
		
		if($loginStatement->rowCount() <= 0){
			$this->opError = 'Username or password is incorrect!';
			return false;
			// Username was wrong, but we don't tell the
			// user as this information could be exploited 
		}
		else{
			$currentTime = date("Y-m-d H:i:s");
			//$loginResult = mysql_fetch_assoc($loginQueryResponse);
			if($loginResult['disabled'] == 1 && (strtotime($loginResult['last_attempt']) + DISABLED_ACCOUNT_PERIOD) > time()){
				$seconds = (strtotime($loginResult['last_attempt']) + DISABLED_ACCOUNT_PERIOD) - time();
				$minutes = floor($seconds / 60);
				$remainingSeconds = $seconds % 60;
				$minString = ($minutes > 1) ? "$minutes minutes, " : (($minutes == 0) ? "" : "$minutes minute, ");
				$secString = ($seconds > 1) ? "$remainingSeconds seconds" : (($seconds == 0) ? "" : "$remainingSeconds second ");
				//showError("You must wait $minString $secString before you may try to log in!");
				$this->opError = "You must wait $minString $secString before you may try to log in!";
				return false;
			}
			else{
				$uidValue = $loginResult['uid'];
				if($loginResult['hash'] != $encrypted){
					$attemptCount = (int)$loginResult['attempt_count'] + 1;
					$accountDisabled = 0;
					if($attemptCount >= DISABLED_ACCOUNT_TRIES){
						$accountDisabled = 1;
					}
					$attemptCount = 0;
					//$updateSQL = "UPDATE users SET last_attempt='$currentTime',disabled='$accountDisabled',attempt_count='$attemptCount' WHERE uid='$uidValue' LIMIT 1;";
					$updateSQL = "UPDATE users SET last_attempt=:currentTime, disabled=:accountDisabled,attempt_count=:attemptCount WHERE uid=:uid;";
					try{
						$updateStatement = $this->sqlCon->prepare($updateSQL);
						$updateStatement->execute(array(':currentTime'=>$currentTime,':accountDisabled'=>$accountDisabled,':attemptCount'=>$attemptCount,':uid'=>$uidValue));
					}
					catch(PDOException $e){
						logError($_SERVER['SCRIPT_NAME'],__LINE__,"Could not log user's log-in attempt into users table! Query: \"$updateSQL\"",$e->getMessage(),time(),false);
					}

					if($attemptCount >= DISABLED_ACCOUNT_TRIES){
						$seconds = DISABLED_ACCOUNT_PERIOD;
						$minutes = floor($seconds / 60);
						$remainingSeconds = $seconds % 60;
						$minString = ($minutes > 1) ? "$minutes minutes " : (($minutes == 0) ? "" : "$minutes minute ");
						$secString = ($seconds > 1) ? "$remainingSeconds seconds" : (($seconds == 0) ? "" : "$remainingSeconds second ");

						$this->opError = "You have exceeded your maximum number of log in attempts!<br />You must wait $minString $secString before you may try to log in!";
						return false;
					}
					
					$this->opError = 'Username or password is incorrect!';
					// Password was wrong, but we don't tell the
					// user as this information could be exploited
					return false;
				}
				else{

					//$updateSQL = "UPDATE users SET last_login='$currentTime',last_attempt='$currentTime',disabled='0',attempt_count='0' WHERE uid='$uidValue' LIMIT 1;";
					$updateSQL = "UPDATE users SET last_login=:currentTime,last_attempt=:currentTime,disabled='0',attempt_count='0' WHERE uid=:uid;";
					try{
						$updateStatement = $this->sqlCon->prepare($updateSQL);
						$updateStatement->execute(array(':currentTime'=>$currentTime,':uid'=>$uidValue));
					}
					catch(PDOException $e){
						logError($_SERVER['SCRIPT_NAME'],__LINE__,"Could not log user's log-in into users table! Query: \"$updateSQL\"",$e->getMessage(),time(),false);
					}
					
					$this->giveCredentials($uidValue);
					return true;
					
					
				}
			}
		}
		return $return; 
	}
	private function giveCredentials($uidValue){
		session_regenerate_id();
		/*$authKey = newAuthKey();
		$_SESSION['USER_ID']=$uidValue;
		$_SESSION['AUTH_KEY']=$authKey;
		
		$updateAuthSQL = 'UPDATE users SET session=:session WHERE uid=:uid;';
		try{
			$updateStatement = $this->sqlCon->prepare($updateAuthSQL);
			$updateStatement->execute(array(':session'=>$authKey,':uid'=>$uidValue));
			$this->loggedIn = true;
			$this->authenticated = true;
		}
		catch(PDOException $e){
			logError($_SERVER['SCRIPT_NAME'],__LINE__,"Could not insert user's auth key into table! Query: \"{$updateAuthSQL}\"",$e->getMessage(),time(),false);
		}*/
		session_write_close();
	}
	function logOut(){
		if(!$this->isLoggedIn()){
			//header("Location: /");
			unset($_SESSION['USER_ID']);
			unset($_SESSION['AUTH_KEY']);
			// just in case
		}
		//Start session
		if (!isset ($_COOKIE[ini_get('session.name')])) {
			session_start();
		}

		unset($_SESSION['USER_ID']);
		unset($_SESSION['AUTH_KEY']);
		
		//header("Location: /");
	}
	
	function close(){
		$this->sqlCon = null; // close the connection
	}
	
	function getError(){
		return $this->opError;
	}
	
	function getFirstName(){ return $this->getUserInfo('first_name'); }
	function getLastName(){ return $this->getUserInfo('last_name'); }
	function getFullName(){ return $this->getUserInfo('first_name') . " " . $this->getUserInfo('last_name'); }
}
?>