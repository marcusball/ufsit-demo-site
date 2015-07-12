<?php
namespace pirrs;
class PageResponse extends Response{
	public function __construct(){
		parent::__construct();
		$this->responseType = ResponseType::PAGE;
	}
    
    public function printErrors(){
        echo '<ul class="errors">';
        foreach($this->getErrors() as $error){
            echo '    <li class="error">'.$error.'</li>';
        }
        echo '</ul>';
    }
}
?>
