<?php
define('IS_PRODUCTION',false);

define('SITE_LABEL','template');
define('SITE_NAME','Template');
define('SITE_DOMAIN_TOP','template.local'); //The highest level of the domain of this site. (No subdomains).
define('SITE_DOMAIN','www.'.SITE_DOMAIN_TOP); //Primary (sub)domain of this website (www.example.com / example.com).

define('DB_ENABLE',true); //Set to false to disable database connection
define('DB_PDO_NAME','pgsql'); // The PDO name for your database server
define('DB_NAME', 'my_database');
define('DB_USER', 'user');
define('DB_PASSWORD', 'password');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

define('PATH_INCLUDE','/include');
define('PATH_CLASS',PATH_INCLUDE.'/class');

//The default log to write Log messages to.
define('SERVER_LOG_PATH','./server/debug.log');

define('PASSWORD_SALT',''); //You should add something random here if you're using the included authentication functions.

?>