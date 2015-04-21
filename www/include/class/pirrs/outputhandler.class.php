<?php
namespace pirrs;
class OutputHandler{
	private static $statusCodes = array(
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		204 => 'No Content',
		301 => 'Moved Permanently',
        302 => 'Found',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		500 => 'Internal Server Error'
	);
		
	public static function preExecute(){
		ob_start();
	}
	public static function handlePageOutput(Response $output){
		if(!$output->rawContent){
			switch($output->getStatusCode()){
				case 200:
					static::handleSuccess();
					break;
				case 210: //I made this case up; "Use alternate data"
					static::handleUseAlternateData($output->getContent());
					break;
				case 302:
					static::handleTemporaryRedirect($output->getContent());
					break;
				case 400:
					static::handleBadRequest();
					break;
				case 401:
					static::handleNotAuthorized();
					break;
				case 403:
					static::handleForbidden();
					break;
				case 404: 
					static::handleNotFound();
					break;
			}
			
			$endTime = microtime(true);
			global $startTime;
			debug(sprintf('<br />Execution time: %5f seconds',($endTime - $startTime)));
		}
		else{
			$text = ob_get_clean();
			echo $text;
		}
	}
	public static function handleAPIOutput(Response $output){
		//ob_end_clean(); //Throw away any written text
		$text = ob_get_clean();
		echo $text;
		
		/* First we'll output the response code */
		$statusCode = $output->getStatusCode();
		$statusMessage = '';
		if(isset(self::$statusCodes[$statusCode])){
			$statusMessage = self::$statusCodes[$statusCode];
		}
		else{
			$statusMessage = $output->getStatusCodeMessage();
			
		}
		header(sprintf('HTTP/1.1 %.03d %s',$statusCode,$statusMessage));
		
		
		/* Set the content type header, if not already set. */
		if(!$output->headers->issetHeader('Content-Type')){
			$output->headers->set('Content-Type','application/json'); 
		}
		
		/* Send the remaining headers */
		$headers = $output->headers->getAll();
		foreach($headers as $header => $headerValue){
			header(sprintf('%s: %s',$header,$headerValue));
		} 
		
		if(!$output->rawContent){
			$outputData['status_code'] = $statusCode;
			$outputData['status_message'] = $statusMessage;
			if($output->hasContent()){
				//$outputData['content'] = $output->getContent();
				$apiContent = $output->getContent();
				if(is_array($apiContent)){
					$outputData = array_merge($outputData,$apiContent);
				}
				else{
					$outputData['content'] = $apiContent;
				}
			}
			if($output->hasErrors()){
				$outputData['error'] = $output->getErrors();
			}
			$outputData['request_id'] = UUID::v4();
			
			$endTime = microtime(true);
			global $startTime;
			$outputData['response_time'] = sprintf('%5f seconds',($endTime - $startTime));
			
			echo json_encode($outputData,JSON_PRETTY_PRINT);
		}
		else{
			echo $output->getContent();
		}
	}
	
	public static function handleRawOutput(Response $output){
		$text = ob_get_clean();
		echo $text;
		
		if($output->hasContent()){
			echo $output->getContent();
		}
	}
	
	private static function handleBadRequest(){
		ob_end_clean();
		echo 'Stop trying to break things';
	}
	private static function handleForbidden(){
		ob_end_clean();
		echo "None shall pass";
	}
	/*
	 * In this case, we just ignore any of the page output, and instead only output the
	 * data returned by a page via Page->setResult(210,$alternateData).
	 */
	private static function handleUseAlternateData($data){
		ob_end_clean();
		echo $data;
	}
	
	private static function handleNotFound(){
		//ob_end_clean();
		static::handleSuccess();
		echo "not found";
	}
	
	private static function handleSuccess(){
		$text = ob_get_clean();
		echo $text;
	}
	
	private static function handleNotAuthorized(){
		ob_end_clean();
		header('Location: /login.php');
	}
	
	private static function handleTemporaryRedirect($url){
		ob_end_clean();
		header('Location: '.str_replace(array('\r','\n'),'',$url));
	}
}
?>
