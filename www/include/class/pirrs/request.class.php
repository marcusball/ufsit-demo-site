<?php
namespace pirrs;
class Request{
	private $args;
	private $method;
	private $req;
	
	public $user;
	
	private static $methodMap = array(
		'GET' => RequestMethod::GET, 
		'POST' => RequestMethod::POST,
		'PUT' => RequestMethod::PUT,
		'PATCH' => RequestMethod::PUT,
		'DELETE' => RequestMethod::DELETE
	);

	public function __construct(){
		$this->args = array();
		$this->method = $this->readMethod();
		$this->user = ResourceManager::getCurrentUser();
		
		$this->req = array_merge($_GET,$_POST); //did this instead of $_REQUEST because I don't want $_COOKIES included
	}
	
	private function readMethod(){
		$method = trim(strtoupper($_SERVER['REQUEST_METHOD']));
		
		if(isset(self::$methodMap[$method])){
			return self::$methodMap[$method];
		}
		return RequestMethod::GET;
	}
	
	/*
	 * Returns an APIRequestMethod enum value.
	 */
	public function getMethod(){
		if($this->method == null){
			$this->method = $this->readMethod();
		}
		return $this->method;
	}
	
	public function isGet(){
		return $this->getMethod() == RequestMethod::GET;
	}
	public function isPost(){
		return $this->getMethod() == RequestMethod::POST;
	}
	
	public function setArgs(array $args){
		$this->args = array_merge($this->args,$args);
	}
	
	public function issetArg($arg){
		return isset($this->args[$arg]);
	}
	
	/*
	 * Gets the request arg specified by $arg.
	 * If the value of that arg is an array, null is returned; Use getArrayArg().
	 */
	public function getArg($arg){
		if(isset($this->args[$arg])){
			if(is_array($this->args[$arg])){ //If an arg is somehow an array, you should use a getArrayArg function. 
				return null;
			}
			return $this->args[$arg];
		}
		return null;
	}
	
	/*
	 * Gets the request arg specified by $arg.
	 * If the value of that arg is NOT an array, null is returned; Use getArg().
	 */
	public function getArgArray($arg){
		if(isset($this->args[$arg])){
			if(!is_array($this->args[$arg])){ 
				return null;
			}
			return $this->args[$arg];
		}
		return null;
	}
	
	/*
	 * Checks if datum identified by $req is present in $_REQUEST.
	 * returns true iff isset is true for $_REQUEST[$req] and if $_REQUEST[$req] is NOT an array.
	 * return false, otherwise. 
	 */
	public function issetReq($req){
		return isset($this->req[$req]) && !is_array($this->req[$req]);
	}
	
	/*
	 * Checks if datum identified by $req is present in $_REQUEST.
	 * returns true iff isset is true for $_REQUEST[$req] and if $_REQUEST[$req] IS an array.
	 * return false, otherwise. 
	 */
	public function issetReqArray($req){
		return isset($this->req[$req]) && is_array($this->req[$req]);
	}
	
	/* 
	 * Checks if the data specified by the params list is present in $_REQUEST.
	 * returns true iff isset is true for $_REQUEST[$param[$i]] and NONE are arrays.
	 * returns list of missing req values if false.
	 */
	public function issetReqList(){
		$toTest = func_get_args();
		$missing = array();
		$isset = true;
		foreach($toTest as $testArg){
			$isset = $isset & ($isMissing = isset($this->req[$testArg]) && !is_array($this->req[$testArg]));
			if($isMissing){
				$missing[] = $testArg; //Append $testArg to the list of missing arguments
			}
		}
		if(!$isset){
			return $missing;
		}
		return true;
	}
	
	/* 
	 * Checks if the data specified by the params list is present in $_REQUEST.
	 * returns true iff isset is true for $_REQUEST[$param[$i]] and ALL are arrays.
	 * returns list of missing req values if false.
	 */
	public function issetReqArrayList(){
		$toTest = func_get_args();
		$missing = array();
		$isset = true;
		foreach($toTest as $testArg){
			$isset = $isset & ($isMissing = isset($this->req[$testArg]) && is_array($this->req[$testArg]));
			if($isMissing){
				$missing[] = $testArg; //Append $testArg to the list of missing arguments
			}
		}
		if(!$isset){
			return $missing;
		}
		return true;
	}
	
	/* 
	 * Gets the datum in $_REQUEST as identified by $req.
	 * Returns the data iff it exists (isset is true), and it is NOT an array.
	 * Returns null otherwise.
	 */
	public function getReq($req){
		if(isset($this->req[$req])){
			if(is_array($this->req[$req])){
				return null;
			}
			return $this->req[$req];
		}
		return null;
	}
	
	/* 
	 * Gets the datum in $_REQUEST as identified by $req.
	 * Returns the data iff it exists (isset is true), and it IS an array.
	 * Returns null otherwise.
	 */
	public function getReqArray($req){
		if(isset($this->req[$req])){
			if(!is_array($this->req[$req])){
				return null;
			}
			return $this->req[$req];
		}
		return null;
	}
    
    /*
     * When given a number of string req keys as parameters, this will
     *   return an array populated with the corresponding req values.
     * If a there does not exist a value corresponding to an input key,
     *   null will be returned in its place. 
     * Note: If the value corresponding to a given is an array, 
     *   it will NOT be returned, null will be given. This function will not
     *   return any arrays as values. If you are expecting an array,
     *   use the getReqArrayList(...) function. 
     * The intent of this is to enable code such as this:
     *   list($value1,$value2,..) = $request->getReqList('key1','key2',..);
     */
    public function getReqList(){
		$toGet = func_get_args();
        $toReturn = array();
		foreach($toGet as $testArg){
			$isset = isset($this->req[$testArg]) && !is_array($this->req[$testArg]);
			if($isset){
                $toReturn[] = $this->req[$testArg];
            }
            else{
                $toReturn[] = null;
            }
		}
		return $toReturn;
	}
    
    /*
     * When given a number of string req keys as parameters, this will
     *   return an array populated with the corresponding req values.
     * If a there does not exist a value corresponding to an input key,
     *   null will be returned in its place. 
     * Note: If the value corresponding to a given is NOT an array, 
     *   it will NOT be returned, null will be given. This function will ONLY
     *   return a value if it is an array. If you are expecting a non-array,
     *   use the getReqList(...) function. 
     * The intent of this is to enable code such as this:
     *   list($valueArray1,$valueArray2,..) = $request->getReqList('key1','key2',..);
     */
    public function getReqArrayList(){
		$toGet = func_get_args();
        $toReturn = array();
		foreach($toGet as $testArg){
			$isset = isset($this->req[$testArg]) && is_array($this->req[$testArg]);
			if($isset){
                $toReturn[] = $this->req[$testArg];
            }
            else{
                $toReturn[] = null;
            }
		}
		return $toReturn;
	}
	
	/*
	 * Assuming a form is submitted, with a form key named according to FORM_KEY_DEFAULT_INPUT_NAME,
	 * This will verify the $_POSTed form key is valid.
	 */
	public function isValidFormSubmission(){
		$manager = ResourceManager::getFormKeyManager();
		return $manager->isValidFormRequest();
	}
    
    /*
     * Get the current url, encoded in a safely-printable fashion.
     * $append: extra data to append to the url (optional).
     */
    public function getSafeUrl($append = null){
		$url = getCurrentUrl();
		if($append !== null){
			$url .= $append;
		}
		return htmlentities(urlencode($url));
	}
}
?>
