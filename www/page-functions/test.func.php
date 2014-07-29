<?php
class Page extends PageObject{
	public function printTitle(){
		echo "Awesome title!";
	}
	
	public function someOtherPrint(){
		echo "Yeah text!";
		
		/*
		 * This uses the example rewrite case in $REWRITE_RULES at the top of config.php.
		 * 'num' refers to the captured, named group in the regular expression for matching a rewritten url.
		 * In the example case, if you visit 'youexample.local/test/3' (or any other number in the place of 3),
		 * then this script will receive that value (3) as an arg that can be used to modify data.
		 */
		if($this->issetArg('num')){ //If we received an arg
			$inputNumber = $this->arg('num');
			echo '<div>Wow, '.$inputNumber.' is a really cool number.</div>';
		}
	}
}
?>