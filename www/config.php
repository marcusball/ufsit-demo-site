<?php
require 'serverconfig.php';

date_default_timezone_set('America/New_York');

define('SERVER_INI_FILE','server/config.ini');

/****************************************************************/
/** Below are all functionality tweaks. I recommend you don't  **/
/** change any of this unless you know what you're doing.      **/
/****************************************************************/

//The extension for the php files that will be included
define('INCLUDE_PHP_EXTENSION','.func.php');
//The path to the php include files for each requested page
define('INCLUDE_PATH_PHP','page-functions/');
//The extension for the php template files that will be included
define('INCLUDE_TEMPLATE_EXTENSION','.php');
//The path for the template files corresponding to each php include file
define('INCLUDE_PATH_TEMPLATE','page-content/');

//The path for files that may be included in a page (ex: common header and footer files).
define('INCLUDE_PATH_PAGE_INCLUDE','page-include/');

//The external path that stylesheets will be located in. Make sure it ends with a '/'.
define('STYLESHEET_PATH','/res/styles/');


// Disable account after too many unsuccessful logins
define('DISABLED_ACCOUNT_PERIOD',60 * 5); 
// How many unsuccessful logins before an account is disabled
define('DISABLED_ACCOUNT_TRIES',5);

/****************************************************************/
/** Session config details.                                    **/
/****************************************************************/
//The number of times a session ID may be used before it will be refreshed.
define('SESSION_USE_COUNT',5); 


/****************************************************************/
/** Authentication config details.                             **/
/****************************************************************/
define('INPUT_EMAIL_MAX_LENGTH',255);
define('INPUT_EMAIL_MIN_LENGTH',6); // a@b.cd

define('INPUT_PASSWORD_MAX_LENGTH',255);
define('INPUT_PASSWORD_MIN_LENGTH',8);

define('AUTH_HASH_COMPLEXITY',14);
/****************************************************************/
/** The config definitions below here should NOT be modified!  **/
/** Unless you really know what you're doing, and are prepared **/
/** to make the changes required in other files, changing      **/
/** anything here will just break everything. Don't do it.     **/
/****************************************************************/


//Define the name of the class that will enclose any script that handles a request
define('REQUEST_CLASS_PARENT','PageObject');
define('REQUEST_FUNC_GET_TEMPLATE','getTemplate');
define('REQUEST_FUNC_PRE_EXECUTE','preExecute');
define('REQUEST_FUNC_POST_EXECUTE','postExecute');
//Define the name of the function within REQUEST_CLASS that will return the output data after the request has been handled
define('REQUEST_FUNC_RET_DATA','getData');
//Define the name of the function within REQUEST_CLASS that will return the output status after the request has been handled
define('REQUEST_FUNC_RET_STATUS','getStatus');

//Define the name of the variable that will be used to reference the template's page object.
define('TEMPLATE_REFERENCE_VARIABLE','Page'); //Ex: if this is 'Page', then inside the template, all functions calls will be made by calling $Page ($Page->doSomething()). 
define('GLOBAL_REFERENCE_VARIABLE','GlobalPage'); //Same as the above, except this will reference global functions provided by the RequestHandler.

define('STATUS_OKAY',200);
define('STATUS_NOT_FOUND',404);
define('STATUS_FAILURE',500);
?>