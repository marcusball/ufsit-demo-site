<?php
/** This is the page that will handle all incoming api requests **/

require 'require.php'; 
require 'output.php';
require 'pageobj.php';
require 'nopage.php';

class RequestHandler{
	private $_request = null;
	private $_sqlCon = null;
	private $_user = null;
	
	/** Contructor
	 ** Optional arguments: string Request, PDO SQL connection
	 **/
	public function __construct(){
		for($i=0;$i<func_num_args();$i++){
			$arg = func_get_arg($i);
			if(is_string($arg)){
				$this->_request = $arg;
			}
			elseif(is_object($arg)){
				$class = get_class($arg);
				if($class == 'PDO'){
					$this->_sqlCon = $arg;
				}
			}
		}
		//$this->_user = new User();
	}
	
	/** Set the request **/
	public function setRequest($request){
		$this->_request = $request;
	}
	
	/* 
	 * Gets the include path for the script that handles the given request
	 * Returns false if there is no script for the input request
	 * Returns array of the path to the handling script, and the path to the template for the handling script
	 * The second element of the array will be false if the template file does not exist
	 */
	private function getRequestScript(){
		if($this->_request != null){
			$includeFile = INCLUDE_PATH_PHP.$this->_request.INCLUDE_PHP_EXTENSION;
			$templateFile = INCLUDE_PATH_TEMPLATE.$this->_request.INCLUDE_TEMPLATE_EXTENSION;
			
			$hasPhp = file_exists($includeFile);
			$hasTemplate = file_exists($templateFile);
			
			if($hasPhp || $hasTemplate){
				return array(($hasPhp)?$includeFile:false,($hasTemplate)?$templateFile:false);
			}
		}
		return array(false,false);
	}
	
	public function executeRequest(){
		list($requestedScript,$requestedTemplate) = $this->getRequestScript();
		if($requestedScript === false && $requestedTemplate === false){
			$this->handleOutput(404);
		}
		else{
			if($requestedScript !== false){
				require $requestedScript; //Bring in the script that will perform the server side operations for the requested page
				if(!$this->validRequestClass()){ //Make sure we have a valid page handler. 
					$this->unexpectedError();
				}
				
				$requestClass = REQUEST_CLASS;
				$requestHandler = new $requestClass($this->_sqlCon,$this->_user); //Instantiate our page handling object
				
				$this->interalPreExecute(); //Call the global RequestHandler pre-execution function. Take care of anything that should happen before the page begins loading. 
				call_user_func(array($requestHandler,REQUEST_FUNC_PRE_EXECUTE)); //Call the page specific pre-execution function. 
				
				if($requestedTemplate !== false){ //If we have a template file, import that.
					$this->includePageFile($requestedTemplate,$requestHandler);
				}
				
				call_user_func(array($requestHandler,REQUEST_FUNC_POST_EXECUTE)); //Page specific post-execution function. 
				
				$outputData = call_user_func(array($requestHandler, REQUEST_FUNC_RET_DATA));
				$outputStatus = call_user_func(array($requestHandler, REQUEST_FUNC_RET_STATUS));
				
				$this->handleOutput($outputStatus,$outputData);
			}
			else{ //Since we know we have either the script or the template, then, here, we must have only the template.
				$this->interalPreExecute(); //Call the global RequestHandler pre-execution function. Take care of anything that should happen before the page begins loading. 
				$templateReferenceVar = new NoPage(); //This is essentially to make error reporting obvious. If the template without an associated php file tries to call functions as though there is a php file for it, this will output some nice helpful errors. 
				$this->includePageFile($requestedTemplate,$templateReferenceVar);
			}
		}
	}
	private function validRequestClass(){
		if(!class_exists(REQUEST_CLASS)){
			return false;
		}
		return true;
	}
	

	private function interalPreExecute(){
		OutputHandler::preExecute();
	}
	
	private function handleOutput($status,$data = null){
		OutputHandler::handleOutput($status,$data);
	}
	
	private function unexpectedError(){
		$this->handleOutput(500);
	}
	
	private function includePageFile($file, $templateRequestHandler){
		${TEMPLATE_REFERENCE_VARIABLE} = $templateRequestHandler;
		${GLOBAL_REFERENCE_VARIABLE} = $this; //This will give the page a reference to this RequestHandler to access public methods 
		include $file; //Execution of the template begins. 
	}
	
	public function includeFile($file){
		$path = INCLUDE_PATH_PAGE_INCLUDE . $file;
		if(file_exists($path)){
			$this->includePageFile($path,new NoPage());
			return true;
		}
		return false;
	}
}

//Just remember that, internally "bla.com/" will still be considered "bla.com/index.php" when checking the rewrite condition. 
global $SQLCON;
$Handler = new RequestHandler($SQLCON);
if(isset($_GET['_request_page']) && trim($_GET['_request_page']) != ''){
	$Handler->setRequest($_GET['_request_page']);
}
$Handler->executeRequest();
?>