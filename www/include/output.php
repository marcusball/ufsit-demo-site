<?php

class OutputHandler{
	public static function preExecute(){
		ob_start();
	}
	public static function handleOutput($status,$data = null){
		switch($status){
			case 404: 
				OutputHandler::handleNotFound();
				break;
			case 200:
				OutputHandler::handleSuccess();
				break;
		}
	}
	
	private static function handleNotFound(){
		ob_end_clean();
		echo "not found";
	}
	
	private static function handleSuccess(){
		$text = ob_get_clean();
		echo $text;
	}
}
?>