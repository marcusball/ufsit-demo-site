<?php
class User extends UserRow{
	protected $dbCon;
	private $userData;
	
	public function __construct(){
		if(func_num_args() < 1 && $this->uid === null && get_class($this) !== 'CurrentUser'){ //uid was not supplied to constructor, and $uid has not already been set (as would be done if object created by function like pdo->fetchObject). 
			throw new Exception('User object expects at least one argument!');
			return;
		}
		$this->dbCon = getDatabaseController();

		if(func_num_args() >= 1){
			for($i=0;$i<func_num_args();$i++){
				$arg = func_get_arg($i);
				switch($i){
					case 0: //UID
						if(is_string($arg) && !ctype_digit($arg)){
							throw new Exception(sprintf('First parameter to User expects an integer, value was a %s. Value: '.$arg,(gettype($arg) != 'object')?gettype($arg):get_class($arg)));
						}
						$this->setUid((integer)$arg); //Keep this cast, it makes it safe to use === 
						break;
				}
			} 
		}
		else{
			$this->setUid($this->uid); //Yeah, this is bad, but it's 2am. I'll make this better later
		}
	}
	
	public function getUserInformation(){
		if(HAS_DATABASE){
			$tempData = $this->dbCon->getUserInformation($this->uid);
			if($tempData !== false){
				$this->userData = $tempData;
				return true;
			}
		}
		//If there is no database, then there is no user data to fetch.
		//This function should be modified as necessary on a per-application basis.
		return false;
	}
	
	private function setUid($uid){
		$this->uid = $uid;
		$this->getUserInformation();
	}
	
	public function getUid(){
		return $this->uid;
	}
	
	public function getFullName(){
		if($this->userData !== null){
			return $this->_userData['full_name'];
		}
		return '';
	}
}

?>