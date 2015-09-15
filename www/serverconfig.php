<?php
define('IS_PRODUCTION',true);

define('SITE_LABEL','ufsit-hacksite');
define('SITE_NAME','UFSIT - Hack My Site');
define('SITE_DOMAIN_TOP','arbitrary.website'); //The highest level of the domain of this site. (No subdomains).
define('SITE_DOMAIN','some.'.SITE_DOMAIN_TOP); //Primary (sub)domain of this website (www.example.com / example.com).

define('DB_ENABLE',false); //Set to false to disable database connection
define('DB_PDO_NAME','pgsql'); // The PDO name for your database server
define('DB_NAME', 'my_database');
define('DB_USER', 'user');
define('DB_PASSWORD', 'password');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

//Set to true to enable basic OAuth2 client functionality
define('OAUTH_CLIENT_ENABLE',false);
//The API ID used to authenticate against a remote OAuth server
define('OAUTH_ID','your_oauth_id');
//The API Secret used to authenticate against a remote OAuth server
define('OAUTH_SECRET','your_oauth_secret');
//The local URL to which the remote OAuth server will redirect users
//  upon successful authentication.
define('OAUTH_REDIRECT','http://template.local/authorized.php');

//The remote API url for performing OAuth2 Authorize requests
define('OAUTH_SERVER_AUTHORIZE_URL','http://example.com/oauth/authorize');
//The remote API url for performing OAuth2 Access token requests
define('OAUTH_SERVER_ACCESS_URL','http://example.com/oauth/token');

//The base url of the remote API at which all API requests will be made.
//For example, if all API requests to example.com follow the pattern:
//  http://example.com/api/somedata, then, for this value
//  use 'http://example.com/api/', and subsequent API calls in 
//  OAuthManager may be used by using OAuthManager->generateUrl($url)
//  with the value of $url being 'somedata'. 
define('OAUTH_API_BASE','http://api.example.com/');


define('PATH_INCLUDE','/include');
define('PATH_CLASS',PATH_INCLUDE.'/class');

//The default log to write Log messages to.
define('SERVER_LOG_PATH','./server/debug.log');

//Add random stuff here to use as a salt for user passwords
define('PASSWORD_SALT','');

?>
