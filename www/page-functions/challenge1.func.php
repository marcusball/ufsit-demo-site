<?php
namespace ufsit;

class Chal1Page extends PageObject{
    protected $win;
    protected $message;
    public function executePost(){
        if($this->request->issetReqList('word1','number1') === true){
            list($word1,$number1) = $this->request->getReqList('word1','number1');
            
            if($word1 === 'entomology' && $number1 === '8'){
                $this->win = true;
                $this->message = 'You\'ve successfully logged in! ' . KEY_1;
            }
            else{
                $this->win = false;
                $this->message = 'Incorrect login information!';
            }
        }
        else{
            $this->win = false;
            $this->message = 'Missing login information!';
        }
        
        
        if(!$this->win){
            header(sprintf('HTTP/1.1 %.03d %s',401,'Unauthorized'));
            $this->response->rawContent = true;
        }
    }
}
?>