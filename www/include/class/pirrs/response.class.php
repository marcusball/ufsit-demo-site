<?php 
namespace pirrs;
class Response{
	public $headers;
	protected $content = null;
	protected $errors;
	protected $statusCode = 200;
	protected $statusCodeMes = "It Is A Mystery"; //If OutputHandler does not have a message associated with the returned $statusCode, it will read this. 
	
	public $rawContent = false; //Set to true to disable json_encode-ing of $content
	public $responseType;
	
	public function __construct(){
		$this->headers = new ResponseHeaders();
	}
	
	/*
	 * This is basically a copy constructor. 
	 * Copies the data from $apiResponse into this.
	 * Note: will overwrite headers, but will not delete 
	 *   existing headers that don't exist in the given $apiResponse.
     * This also does NOT copy the $responseType value.
	 */
	public function apply(Response $apiResponse,$forceOverwrite = false){
		$this->headers->apply($apiResponse->headers);
		$this->setStatusCode($apiResponse->getStatusCode(), $this->statusCodeMes);
		
		if($forceOverwrite || $apiResponse->hasContent()){
			$this->setContent($apiResponse->getContent());
		}
		if($forceOverwrite || $apiResponse->hasErrors()){
			$this->addErrors($apiResponse->getErrors());
		}
	}
	
	public function setStatusCode($code, $mes = null){
		if($code >= 100 && $code < 600){
			$this->statusCode = $code;
			if($mes != null){
				$this->statusCodeMes = $mes;
			}
		}
	}
	
	public function getStatusCode(){
		return $this->statusCode;
	}
	
	public function getStatusCodeMessage(){
		return $this->statusCodeMes;
	}
	
	public function setContent($content){
		$this->content = $content;
	}
	
	public function getContent(){
		return $this->content;
	}
	
	public function hasContent(){
		return isset($this->content) && $this->content != null;
	}
	
	/*
	 * Add a single error, $message.
	 */
	public function addError($message){
		if(!isset($this->errors)){
			$this->errors = array();
		}
		if(is_array($message)){
			return false;
		}
		
		$this->errors[] = $message;
		return true;
	}
	
	/*
	 * Add an array of errors, given by $messages
	 */
	public function addErrors($messages){
		if(!is_array($messages)){
			return $this->addErrors(array($messages));
		}
		foreach($messages as $message){
			$this->addError($message);
		}
		return true;
	}
	
	public function getErrors(){
		return $this->errors;
	}
	
	public function hasErrors(){
		return isset($this->errors) && count($this->errors) > 0;
	}
}
?>