<?php
class PageObject{
	private $_DATA = null;
	private $_STATUS = 200;
	
	protected $dbCon;
	private $_errors;
	
	protected $Req;
	
	/** Constructor
	 ** Currently takes optional PDO connection argument
	 ** There really isn't much of a reason to modify this unless you really need something initialized before performRequest()
	 **/
	public function __construct(){
		for($i=0;$i<func_num_args();$i++){	//Loop through all of the arguments provided to the instruction ("RequestObject($arg1,$arg2,...)").
			$arg = func_get_arg($i);
			if(is_object($arg)){ 			//If this argument is of class-type object (basically anything not a primative data type). 
				$class = get_class($arg); 	//Get the actual class of the argument
				if($class == 'DBController'){		//Hey look! It's our SQL object
					$this->dbCon = $arg; 	//We should save this. 
				}
			}
		} 
		$errors = array();
		$this->Req = $_REQUEST;
	}
	
	/*
	 * This function will be called before the template begins executing.
	 */
	public function preExecute(){}
	
	/*
	 * This function will be called after the template has completed execution.
	 */
	public function postExecute(){}

	/*
	 * Used by the page functions to change the status and return data.
	 * As of this writing, data is useless. 
	 * Status is a standard HTTP status code (200, 404, etc).
	 */
	protected function setResult($status,$data = null){
		$this->_STATUS = $status;
		$this->_DATA = $data;
	}
	
	/*
     * The function is used by request.php to get the output data once the request has been performed.
	 */
	public function getData(){
		if($this->_DATA != null){
			return $this->_DATA;
		}
		else{
			return false;
		}
	}
	
	/** This function is used by request.php to get the result status once the request has been performed **/
	public function getStatus(){
		return $this->_STATUS;
	}
	
	public function pageTitle(){
		echo SITE_NAME;
	}
		
	protected function addError($message){
		$this->_errors[] = $message; //Append the message
	}
	public function getErrors(){
		return $this->_errors;
	}
	public function hasErrors(){
		return count($this->_errors) > 0;
	}
	public function outputErrors($message = null){
		if($message == null){
			$message = 'The following errors were reported:';
		}
		echo $message;
		echo '<ul class="errorsList">';
		foreach($this->_errors as $error){
			echo '<li class="errorItem">'.$error.'</li>';
		}
		echo '</ul>';
	}
	
	/* Let's have some nice helper functions. */
	protected function issetReqList(){
		$toTest = func_get_args();
		$missing = array();
		$isset = true;
		foreach($toTest as $testArg){
			$isset = $isset & ($isMissing = isset($_REQUEST[$testArg]));
			if($isMissing){
				$missing[] = $testArg; //Append $testArg to the list of missing arguments
			}
		}
		if(!$isset){
			return $missing;
		}
		return true;
	}
	
	protected function issetReq(){
		$toTest = func_get_args();
		foreach($toTest as $testArg){
			if(!isset($_REQUEST[$testArg])){
				return false;
			}
		}
		return true;
	}
	
	protected function reqList(){
		$requested = func_get_args();
		$requestedArgs = array();
		foreach($requested as $requestedArg){
			if(isset($_REQUEST[$requestedArg])){
				$requestedArgs[] = $_REQUEST[$requestedArg];
			}
			else{
				$requestedArgs[] = null;
			}
		}
		return $requestedArgs;
	}
	
	public function currentInput($name){
		if(isset($this->Req[$name])){
			echo htmlspecialchars($this->Req[$name]);
		}
		else{
			echo "";
		}
	}
}
?>