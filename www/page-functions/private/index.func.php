<?php
class PrivateIndex extends PageObject{
	private $realm = 'Super secret area';
	private $secret = 'Is this necessary? Probably not.';
	private $users = array('guest' => 'guest');
	public function preExecute(){
		if(!$this->user->isLoggedIn()){
			if($this->issetReq('logout')){
				header('Location: '.getCurrentUrl(false));
				return false;
			}
			
			
			if(empty($_SERVER['PHP_AUTH_DIGEST'])){
				$this->httpDigestPrompt();

				echo 'Wow, you don\'t belong here. Be gone!';
				return false;
			}
			elseif(!($data = $this->httpDigestParse($_SERVER['PHP_AUTH_DIGEST'])) || !isset($this->users[$data['username']])){
				$this->httpDigestPrompt();
			
				echo "Incorrect credentials! Your IP address {$_SERVER['REMOTE_ADDR']} has been logged and reported to the FBI.";
				return false;
			}

			// generate the valid response
			$A1 = md5($data['username'] . ':' . $this->realm . ':' . $this->users[$data['username']]);
			$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
			$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

			if ($data['response'] !== $valid_response){
				$this->httpDigestPrompt();
				
				echo "Incorrect credentials! Your IP address {$_SERVER['REMOTE_ADDR']} has been logged and reported to the FBI.";
				return false;
			}
			else{
				$this->user->giveCredentials($data['username']);
			}
		}
		else{
			if($this->issetReq('logout')){
				$this->user->logOut();
				
				$this->httpDigestPrompt(); //Easiest way to tell the browser that you're "logged out" is to just say you're not authorized
				echo 'You have logged out';
				return false;
			}
		}
		return true;
	}
	
	private function httpDigestPrompt(){
		header('HTTP/1.1 401 Unauthorized');
		header('WWW-Authenticate: Digest realm="'.$this->realm.'",qop="auth",nonce="'.base64_encode(openssl_random_pseudo_bytes(32)).'",opaque="'.md5($this->realm . $this->secret).'"');
	}
	
	private function httpDigestParse($txt){
		// protect against missing data
		$needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
		$data = array();
		$keys = implode('|', array_keys($needed_parts));

		preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

		foreach ($matches as $m) {
			$data[$m[1]] = $m[3] ? $m[3] : $m[4];
			unset($needed_parts[$m[1]]);
		}

		return $needed_parts ? false : $data;
	}
}
?>