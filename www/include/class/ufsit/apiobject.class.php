<?php
namespace ufsit;
class APIObject extends RequestObject{
	public function __construct(){
        parent::__construct();
        
		for($i=0;$i<func_num_args();$i++){	//Loop through all of the arguments provided to the instruction ("RequestObject($arg1,$arg2,...)").
			$arg = func_get_arg($i);
			if(is_object($arg)){ 			//If this argument is of class-type object (basically anything not a primative data type). 
				$class = get_class($arg); 	//Get the actual class of the argument
				if($class == APIRequest::class){	
					$this->request = $arg; 	//We should save this. 
				}
				elseif($class == APIRequest::class){
					$this->response = $arg;
				}
			}
		}
		
		if($this->request == null){
			$this->request = new APIRequest();
		}
		if($this->response == null){
			$this->setResponseType(ResponseType::API);
		}
	}
}
?>