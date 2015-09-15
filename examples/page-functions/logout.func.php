<?php
namespace pirrs;
class Page extends PageObject{
	public function requireLoggedIn(){
		return true;
	}
	
	public function preExecute(){
		if($this->request->user->isLoggedIn()){
			$this->request->user->logOut();
			$this->response->forwardTo('/');
		}
	}
}
?>