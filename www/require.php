<?php
/** Functions and definitions that will be included for every page **/
require 'config.php';

$path = dirname(__FILE__) . PATH_INCLUDE; 
set_include_path(get_include_path() . PATH_SEPARATOR . $path); //Adds the './include' folder to the include path
// That doesn't explain much, but basically, if I say "include 'file.php';", 
// it now searches './include' for file.php, as well as the default include locations.

/*
 * Includes all of the necessary helper classes and files.
 */
function init(){
	$classPath = dirname(__FILE__) . PATH_CLASS; //Get the path to our .class.php files 
	set_include_path(get_include_path() . PATH_SEPARATOR . $classPath); //Add that path to the include path
	
	spl_autoload_extensions('.class.php'); //Auto-load any of our .class.php classes
	spl_autoload_register();
	
	require_once 'password.php'; 
}

/*
 * Here's a happy little log function.
 * Use it for errors.
 * $description is for a written description of the problem.
 * $error is for the output of error functions.
 */
function logError($description, $error){
	$debug = debug_backtrace();
	if(isset($debug[0])){
		$data = sprintf("[%s][%s][%s] (%s): %s %s\n",
			'error',
			date('D, j M Y, \a\t g:i:s A'),
			$_SERVER['REMOTE_ADDR'],
			($debug[0]['file'].':'.$debug[0]['line']),
			$description,
			$error
		);
		file_put_contents(SERVER_LOG_PATH_ERRORS, $data, FILE_APPEND);
	}
}

/*
 * Nice little log function for warnings.
 */
function logWarning($description){
	$debug = debug_backtrace();
	if(isset($debug[0])){
		$data = sprintf("[%s][%s][%s] (%s): %s\n",
			'warning',
			date('D, j M Y, \a\t g:i:s A'),
			$_SERVER['REMOTE_ADDR'],
			$debug[0]['file'].':'.$debug[0]['line'],
			$description
		);
		file_put_contents(SERVER_LOG_PATH_ERRORS, $data, FILE_APPEND);
	}
}

/*
 * Echo safe
 * Hopefully echos information in a way that is safe to echo
 */
function es($message){
	echo htmlspecialchars($message);
}

function debug($message){
	echo $message . '<br />';
}

/*
 * Connects to a PDO database and returns an instance of DatabaseController, from databasecontroller.php
 * DO NOT call this function directly to access the database. 
 * This file calls it (in getDatabaseController() ONLY), and maintains a reference to the value.
 * Call getDatabaseController() to get a reference to it. 
 */
function SQLConnect(){
	try {
		$SQLCON = new DatabaseController(DB_PDO_NAME.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
		$SQLCON->setPDOAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		define('HAS_DATABASE',true);
		return $SQLCON;
	}
	catch(PDOException $e){
		logError("Could not select database (".DB_NAME.").",$e->getMessage(),time());
	}
	
	define('HAS_DATABASE',false);
	return new NoDatabaseController();
}

/*
 * Access method for receiving a reference to the database controller (DatabaseController).
 */
function getDatabaseController(){
	global $SQLCON;
	if($SQLCON !== null){
		//echo 'giving current dbCon';
		return $SQLCON;
	}
	else{
		return SQLConnect();
	}
}

/*
 * Access method for receiving a reference to the CurrentUser object. 
 */
function getCurrentUser(){
	global $USER;
	if($USER !== null){
		return $USER;
	}
	else{
		return new CurrentUser();
	}
}

/*
 * Access method for receiving a reference to the FormKeyManager object.
 */
function getFormKeyManager(){
	global $FORMKEYMAN;
	if($FORMKEYMAN !== null){
		return $FORMKEYMAN;
	}
	else{
		return new FormKeyManager();
	}
}

function parsePath($withQueryArgs = true){
	//http://stackoverflow.com/questions/16388959/url-rewriting-with-php
	$uri = rtrim( dirname($_SERVER["SCRIPT_NAME"]), '/' );
	$uri = '/' . trim( str_replace( $uri, '', $_SERVER['REQUEST_URI'] ), '/' );
	$uri = urldecode( $uri );
	if(!$withQueryArgs){
		$matchVal = preg_match('#^(?\'path\'[^\?]*)(?:\?.*)?$#i',$uri,$matches);
		if($matchVal !== 0 && $matchVal !== false){
			return $matches['path'];
		}
	}
	return $uri;
}

function cleanPath($path){
	if($path == '/') return $path;
	
	$matchVal = preg_match('#^/?(?:(?\'path\'.+)\.php)?(?:\?.*)?$#i',$path,$matches);
	if($matchVal === 0 || $matchVal === false){
		return false;
	}
	
	//If we get to here, we know the pattern matches
	//If path is not set, then nothing exists between the first character ('/'), and the query string ('?...')
	//So, if we have a path returned from the regex, then the url is something like "/xxxxx.php?ffffff"
	//Otherwise the path is "/?ffffff". 
	if(isset($matches['path'])){
		return $matches['path'];
	}
	else{
		return '/';
	}
}

function getRewritePath($path){
	global $REWRITE_RULES; //get rewrite rules from config.php
	foreach($REWRITE_RULES as $file=>$rule){
		$match = preg_match('#'.$rule.'#i',$path,$matches);
		if($match !== 0 && $match !== false){
			return array($file,$matches);
		}
	}
	return false;
}

function getCurrentUrl(){
	return parsePath();
}


/** Imports and includes **/
init(); //Import stuff

/** Create an SQL connection **/
$SQLCON = SQLConnect();
$USER = null; //Keep these in global scope
$FORMKEYMAN = null;
		
?>