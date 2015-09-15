<?php
namespace pirrs;
class ResponseHeaders{
	protected $headers;
	
	public function __construct(){
		$this->headers = array();
	}
	
	/*
	 * Sets the value of the $header header, overwrites if one already exists.
	 * If $value is null, the header is unset if it exists.
	 */
	public function set($header, $value){
		//$header = strtolower($header);
		if(!isset($value)){
			if(isset($this->headers[$header])){
				unset($this->headers[$header]);
			}
		}
		else{
			$this->headers[$header] = $value; 
		}
	}
	
	/*
	 * Set multiple headers using an associative array of $header => $value pairs. 
	 */
	public function arraySet($headers){
		if(!is_array($headers)){
			return; 
		}
		foreach($headers as $header=>$value){
			$this->set($header,$value);
		}
	}
	
	/*
	 * Gets the value of the header specified by $header.
	 * Returns the value, or null if the header is not set.
	 */
	public function get($header){
		//$header = strtolower($header);
		if(isset($this->headers[$header])){
			return $this->headers[$header];
		}
		return null;
	}
	
	/*
	 * Unsets the header given by $header, if it exists.
	 */
	public function remove($header){
		//$header = strtolower($header);
		if(isset($this->headers[$header])){
			unset($this->headers[$header]);
		}
	}
	
	public function issetHeader($header){
		//$header = strtolower($header);
		return isset($this->headers[$header]);
	}
	
	public function apply(ResponseHeaders $headers){
		foreach($headers->headers as $header => $value){
			$this->set($header,$value);
		}
	}
	
	public function getAll(){
		return $this->headers;
	}
}
?>