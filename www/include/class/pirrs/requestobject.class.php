<?php
namespace pirrs;
class RequestObject{
    public $request;
	public $response;
    
	
	protected $dbCon;
    
    public function __construct(){
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