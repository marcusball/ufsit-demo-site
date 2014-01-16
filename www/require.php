<?php
/** Functions and definitions that will be included for every page **/
require 'config.php';

$path = 'include'; 
set_include_path(get_include_path() . PATH_SEPARATOR . $path); //Adds the './include' folder to the include path
// That doesn't explain much, but basically, if I say "include 'file.php';", 
// it now searches './include' for file.php, as well as the default include locations.


function logError($script,$line,$description, $error){
	$data = "File:        $script (Line: $line)\nDescription: ".$description."\nError:       ".$error."\nTime:        ".date('l, j F Y, \a\t g:i:s:u A')."\n--------------------------------\n";
	file_put_contents(LOG_PATH_ERRORS, $data, FILE_APPEND);
}

function SQLConnect(){
	try {
		$SQLCON = new PDO(DB_PDO_NAME.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
		$SQLCON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $SQLCON;
	}
	catch(PDOException $e){
		logError('require.php',__LINE__,"Could not select database (".DB_NAME.").",$e->getMessage(),time());
	}
	return null;
}

/** Create an SQL connection **/
$SQLCON = SQLConnect();
		
?>