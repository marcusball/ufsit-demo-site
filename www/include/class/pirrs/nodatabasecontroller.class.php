<?php
namespace pirrs;
class NoDatabaseController{
    public function __set($name, $value)
    {
        debug('Error: Unable to set DatabaseController property, '.$name.'! There is currently no database access.');
		return null;
    }

    public function __get($name)
    {
         debug('Error: Unable to get DatabaseController property, '.$name.'! There is currently no database access.');
		 return null;
    }

    /**  As of PHP 5.1.0  */
    public function __isset($name)
    {
         debug('Error: Unable to check isset of DatabaseController property, '.$name.'! There is currently no database access.');
		 return false;
    }

    /**  As of PHP 5.1.0  */
    public function __unset($name)
    {
         debug('Error: Unable to unset DatabaseController property, '.$name.'! There is currently no database access.');
		 return null;
    }
	
	public function __call($name, $arguments)
    {
		debug('Error: Unable to call DatabaseController function, '.$name.'()! There is currently no database access.');
		 return false;
    }
}
