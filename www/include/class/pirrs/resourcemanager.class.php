<?php
namespace pirrs;
use \PDO;
class ResourceManager{
	/** Create an SQL connection **/
	private static $SQLCON = null;
	private static $USER = null;
	private static $FORMKEYMAN = null;
    private static $OAUTHMANAGER = null;

	/*
	 * Connects to a PDO database and returns an instance of DatabaseController, from databasecontroller.php
	 * DO NOT call this function directly to access the database. 
	 * This file calls it (in getDatabaseController() ONLY), and maintains a reference to the value.
	 * Call getDatabaseController() to get a reference to it. 
	 */
	public static function SQLConnect(){
        if(DB_ENABLE){
            try {
                self::$SQLCON = new DatabaseController();
                self::$SQLCON->setPDOAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                define('HAS_DATABASE',true);
                return self::$SQLCON;
            }
            catch(PDOException $e){
                logError("Could not select database (".DB_NAME.").",$e->getMessage(),time());
            }
        }
		
		define('HAS_DATABASE',false);
		return new NoDatabaseController();
	}

	/*
	 * Access method for receiving a reference to the database controller (DatabaseController).
	 */
	public static function getDatabaseController(){
		if(self::$SQLCON == null){
			//echo 'giving current dbCon';
			self::$SQLCON = self::SQLConnect();
		}
		return self::$SQLCON;
	}

	/*
	 * Access method for receiving a reference to the CurrentUser object. 
	 */
	public static function getCurrentUser(){
		if(static::$USER == null){
			self::$USER = new CurrentUser();
            self::$USER->initialize();
		}
		return self::$USER;
	}

	/*
	 * Access method for receiving a reference to the FormKeyManager object.
	 */
	public static function getFormKeyManager(){
		if(self::$FORMKEYMAN == null){
			self::$FORMKEYMAN = new FormKeyManager();
		}
		return self::$FORMKEYMAN;
	}
    
    /*
	 * Access method for receiving a reference to an OAuthManager object for the current user.
	 */
    public static function getOAuthManager(){
        if(self::$OAUTHMANAGER == null){
            $user = static::getCurrentUser();
            self::$OAUTHMANAGER = new OAuthManager($user);
        }
        return self::$OAUTHMANAGER;
    }
}
?>