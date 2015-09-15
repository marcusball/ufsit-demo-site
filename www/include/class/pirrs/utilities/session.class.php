<?php
namespace pirrs\utilities;
class Session{
	/*
	 * Check if a session exists before running session_start().
	 * Source: http://php.net/manual/en/function.session-status.php
	 */
	public static function isSessionStarted(){
		if(php_sapi_name() !== 'cli'){
			if(version_compare(phpversion(), '5.4.0', '>=')){
				return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
			}
			else{
				return session_id() === '' ? FALSE : TRUE;
			}
		}
		return FALSE;
	}
}