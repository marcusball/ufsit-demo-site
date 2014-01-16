<?php
class NoPage{
    public function __set($name, $value)
    {
        echo 'Error: Unable to set $'.TEMPLATE_REFERENCE_VARIABLE.' property, '.$name.'! This template has no associated '.REQUEST_CLASS.' class file!';
		return null;
    }

    public function __get($name)
    {
         echo 'Error: Unable to get $'.TEMPLATE_REFERENCE_VARIABLE.' property, '.$name.'! This template has no associated '.REQUEST_CLASS.' class file!';
		 return null;
    }

    /**  As of PHP 5.1.0  */
    public function __isset($name)
    {
         echo 'Error: Unable to check isset of $'.TEMPLATE_REFERENCE_VARIABLE.' property, '.$name.'! This template has no associated '.REQUEST_CLASS.' class file!';
		 return false;
    }

    /**  As of PHP 5.1.0  */
    public function __unset($name)
    {
         echo 'Error: Unable to unset $'.TEMPLATE_REFERENCE_VARIABLE.' property, '.$name.'! This template has no associated '.REQUEST_CLASS.' class file!';
		 return null;
    }
	
	public function __call($name, $arguments)
    {
		echo 'Error: Unable to call $'.TEMPLATE_REFERENCE_VARIABLE.' function, '.$name.'()! This template has no associated '.REQUEST_CLASS.' class file!';
		 return null;
    }
}
