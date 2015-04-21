<?php
namespace pirrs;
class APIObject{
	public $request;
	public $response;
	
	protected $dbCon;
	
	public function __construct(){
		for($i=0;$i<func_num_args();$i++){	//Loop through all of the arguments provided to the instruction ("RequestObject($arg1,$arg2,...)").
			$arg = func_get_arg($i);
			if(is_object($arg)){ 			//If this argument is of class-type object (basically anything not a primative data type). 
				$class = get_class($arg); 	//Get the actual class of the argument
				if($class == 'APIRequest'){		//Hey look! It's our SQL object
					$this->request = $arg; 	//We should save this. 
				}
				elseif($class == 'APIResponse'){
					$this->response = $arg;
				}
			}
		}
		
		if($this->request == null){
			$this->request = new APIRequest();
		}
		if($this->response == null){
			$this->response = new APIResponse();
		}
		
		$this->dbCon = ResourceManager::getDatabaseController();
	}
	
	/*
	 * This function will be called before the template begins executing.
	 */
	public function preExecute(){}
	
	/*
	 * This function will be called after the template has completed execution.
	 */
	public function postExecute(){}
	
	
	public function executeGet(){}
	public function executePost(){}
	public function executePut(){}
	public function executeDelete(){}
}
?>