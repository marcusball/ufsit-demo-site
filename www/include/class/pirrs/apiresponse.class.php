<?php
namespace pirrs;
class APIResponse extends Response{
	public function __construct(){
		parent::__construct();
		$this->responseType = ResponseType::API;
	}
}
?>