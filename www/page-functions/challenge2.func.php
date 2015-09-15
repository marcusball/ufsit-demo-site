<?php
namespace ufsit;
class PostPage extends PageObject{
    protected $message;
    protected $win;
    
	public function executePost(){
        if($this->request->issetReq('user')){
            $user = $this->request->getReq('user');
            if($user === 'guest'){
                $this->message = 'Sorry, you do not have the proper permissions to post!';
                $this->win = false;
            }
            else if($user === 'bender' || $user === 'winnfield'){
                $this->message = sprintf('Contratulations! You posted as <strong>%s</strong>. %s',$user, KEY_2);
                $this->win = true;
            }
        }
        else{
            //$this->message = 'Sorry, you do not have the proper permissions to post!';
            //$this->win = false;
        }
    }
}
?>