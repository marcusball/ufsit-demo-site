<?php
class PageObject{
	private $_DATA = null;
	private $_STATUS = 200;
	
	private $_sqlCon;
	/** Constructor
	 ** Currently takes optional PDO connection argument
	 ** There really isn't much of a reason to modify this unless you really need something initialized before performRequest()
	 **/
	public function __construct(){
		for($i=0;$i<func_num_args();$i++){	//Loop through all of the arguments provided to the instruction ("RequestObject($arg1,$arg2,...)").
			$arg = func_get_arg($i);
			if(is_object($arg)){ 			//If this argument is of class-type object (basically anything not a primative data type). 
				$class = get_class($arg); 	//Get the actual class of the argument
				if($class == 'PDO'){		//Hey look! It's our SQL object
					$this->_sqlCon = $arg; 	//We should save this. 
				}
			}
		} 
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
	
	/*
	 * Internal function to get the SQL connection.
	 */
	protected function sql(){
		return $this->_sqlCon;
	}
}
?>