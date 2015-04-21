<?php
namespace pirrs;
class PageResponse extends Response{
	public function __construct(){
		parent::__construct();
		$this->responseType = ResponseType::PAGE;
	}
}
?>