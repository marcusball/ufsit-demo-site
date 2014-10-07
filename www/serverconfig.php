<?php
define('IS_PRODUCTION',true);

define('SITE_LABEL','ufsit-hacksite');
define('SITE_NAME','UFSIT - Hack My Site');
define('SITE_DOMAIN_TOP','arbitrary.website'); //The highest level of the domain of this site. (No subdomains).
define('SITE_DOMAIN','some.'.SITE_DOMAIN_TOP); //Primary (sub)domain of this website (www.example.com / example.com).
define('DB_PDO_NAME','pgsql'); // The PDO name for your database server
define('DB_NAME', 'my_database');
define('DB_USER', 'user');
define('DB_PASSWORD', 'password');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

define('SERVER_LOG_PATH_ERRORS','./server/errors.log');
define('SERVER_LOG_PATH_WARNINGS','./server/warnings.log');

define('PASSWORD_SALT',''); //You should add something random here if you're using the included authentication functions.

?>