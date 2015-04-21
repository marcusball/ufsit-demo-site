<?php
namespace pirrs;
class ResponseType extends BasicEnum{
	const PAGE = 1;
	const API = 2;
	const HTML = 3; //When only the template html file is used, and no PageObject is present.
	const RAW = 4; //Do no handling at all, just echo the text.
}
?>