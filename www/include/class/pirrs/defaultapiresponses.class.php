<?php
namespace pirrs;
class DefaultAPIResponses{
	public static function Success($content=null){
		$response = new APIResponse();
		$response->setStatusCode(200);
		if(isset($content)){
			$response->setContent($content);
		}
		
		return $response;
	}
	
	public static function BadRequest(){
		$response = new APIResponse();
		$response->setStatusCode(400);
		
		return $response;
	}
	
	public static function NotFound(){
		$response = new APIResponse();
		$response->setStatusCode(404);
		
		return $response;
	}
	
	public static function Unauthorized(){
		$response = new APIResponse();
		$response->setStatusCode(401);
		
		return $response;
	}
	
	public static function ServerError(){
		$response = new APIResponse();
		$response->setStatusCode(500);
		
		return $response;
	}
}