<?php
namespace ufsit;
use ohmy\Auth2;

class OAuthManager{
	private $dbCon;
    
    private $user;
   
    private $accessToken = null;
	
	public function __construct(User $user){
		$this->dbCon = ResourceManager::getDatabaseController();
		$this->user = $user;
	}
    
    public function getOma(){
        $token = $this->getAccessToken();

        //If we do not have a valid token
        if($token === false || $token == null || $this->accessToken->isExpired()){
            //We'll assume the user has an expired access token, and still possesses a refresh token
            $refreshedToken = $this->refreshAccessToken();
            //If we successfully received a new access token
            if($refreshedToken !== false){
                $token = $this->accessToken = $refreshedToken;
            }
        }
            
        if($token !== false && !$this->accessToken->isExpired()){
            $ohMy = Auth2::legs(3)->access($token->token);
            return $ohMy;
        }
        
        //Unable to get object, token is probably expired
        Log::warning('Unable to get Oma object!');
        return false;
    }
    
    public function getAuthorization(){
        $ohMy = Auth2::legs(3)
            # configuration
            ->set(array(
                'id'       => OAUTH_ID,
                'secret'   => OAUTH_SECRET,
                'redirect' => OAUTH_REDIRECT
            ))
            # oauth flow
            ->authorize(OAUTH_SERVER_AUTHORIZE_URL)
            ->access(OAUTH_SERVER_ACCESS_URL)
            ->finally(function($data) use(&$authorizationData) {
                $authorizationData = json_decode(json_encode($data)); //json_decode(json_encode()) is a hack way to convert $data from an array, to an object.
            });
        
        return $authorizationData;
    }
    
    /*
     * Checks the database to see if the user has a refresh token.
     * If one is present, this will call the server, and attempt to
     *   receive a refreshed access token. If one is received,
     *   it will update user records with the new token.
     * returns the new /ufsit/user/AccessToken object, or false if unable.
     */
    private function refreshAccessToken(){
        //Attempt to get a refresh token from the user's records
        $refreshToken = $this->dbCon->getUserRefreshToken($this->user->getUid());
        
        //If we actually have a refresh token for the user
        if($refreshToken !== false){
            //Call an OAuth chain that will eventually return the access token (if successful)
            $refreshRequest = Auth2::legs(3)
                //Setup the OAuth client with proper config details
                ->set(array(
                    'id'       => OAUTH_ID,
                    'secret'   => OAUTH_SECRET,
                ))
                
                //Create a refresh action, and send the request to the token url
                ->refresh($refreshToken->token, $this->generateUrl('oauth/token'));
                
                //Get the result of the refresh_token request
            $refreshRequest->finally(function($response) use(&$accessToken){
                    //Make sure the request was successful, and we got an access token
                    
                    $accessToken = false;
                    
                    //We should always receive the status code, if it's not there,
                    //then something went horribly wrong with the server's response.
                    
                    if(!isset($response['status_code'])){
                        return;
                    }
                    if($response['status_code'] == 200){
                        if(isset($response['access_token'])){
                            //Take the access token and update the user's details
                            $update = $this->dbCon->updateUserAccessToken($this->user->getUid(), $response['access_token'], $response['expires_in']);
                            
                            if($update !== false){
                                $newToken = new \ufsit\user\AccessToken();
                                $newToken->uid = $this->user->getUid();
                                $newToken->token = $response['access_token'];
                                $newToken->expires = $response['expires_in'];
                                $newToken->updated = time();
                                
                                $accessToken = $newToken;
                                return;
                            }
                        }
                    }
                    elseif($response['status_code'] == 400){
                        //The refresh token has probably expired
                        if(isset($response['error']) && $response['error'] == 'invalid_grant'){
                            $this->dbCon->updateUserRefreshToken($this->user->getUid(),''); //Clear the token so we know it's no longer valid
                        }
                    }
                    Log::debug(json_encode($response));
                });
            
            //Return the access token if we were successful
            if(isset($accessToken) && $accessToken !== false){
                return $accessToken;
            }
        }
        //Shit didn't work.
        return false;
    }
    
    /*
     * Generates the request url to the specified api path.
     * $apiPath: path within api host (format "api/target.php").
     * $params: key-value pairs to append as url query parameters.
     */
    private function generateUrl($apiPath){
        $url = OAUTH_API_BASE . $apiPath;
        return $url;
    }
    
    /*
     * Returns true if the user, who this manager instance is handling,
     *   is has a valid oauth access token stored.
     */
    public function hasAccessToken(){
        $accessToken = $this->getAccessToken();
        return ($accessToken !== false && $accessToken != '');
    }
    
    /*
     * Returns the access token for the user who this manager instance is handling.
     * Will use cached access token, or the one set by setAccessToken(), or will
     *   query the database if not set. 
     * This will also perform the work necessary to refresh an expired access token,
     *   assuming the user has a valid refresh token in the database.
     * $forceDatabaseQuery: (optional) force a database lookup, overwrite cached value.
     * returns false on error.
     */
    public function getAccessToken($forceDatabaseQuery = false){
        if($this->accessToken == null || $this->accessToken == '' || $forceDatabaseQuery){
            $this->accessToken = $this->dbCon->getUserAccessToken($this->user->getUid());
        }
        return $this->accessToken;
    }
    
    /*
     * Temporarily sets the access token to be used for making requests.
     * This only sets it for this instance of OAuthManager, it does not save to the database.
     */
    public function setAccessToken(\ufsit\user\AccessToken $accessToken){
        $this->accessToken = $accessToken;
    }
    
    /*
     * Checks if the user's access token is expired.
     * returns true if expired (or if no valid access token)
     */
    public function accessTokenIsExpired(){
        $token = $this->getAccessToken();
        if($token !== false){
            return $token->isExpired();
        }
    }
    
    private function hasRefreshToken(){
        return ($this->dbCon->getUserRefreshToken($this->user->getUid()) !== false);
    }
    
    
    /***********************************************************/
    /** Quick Access Methods                                  **/
    /***********************************************************/
    private function GET($url, Array $params = null){
        //Get an ohMy-OAuth instance for this manager 
        $oma = $this->getOma();
        if($oma !== false){
            //If we were not given a params array, we'll make it an empty array
            if($params == null){ $params = array(); }
            
            //We need to send an access token, so add that to the param array
            if(!isset($params['access_token'])){
                $params['access_token'] = $this->getAccessToken()->token;
            }
            return $oma->GET($this->generateUrl($url),$params,array('User-Agent' => 'ufsit-web'));
        }
        return false;
    }
    
    private function POST($url, Array $params = null){
        //Get an ohMy-OAuth instance for this manager 
        $oma = $this->getOma();
        if($oma !== false){
            //If we were not given a params array, we'll make it an empty array
            if($params == null){ $params = array(); }
            
            //We need to send an access token, so add that to the param array
            if(!isset($params['access_token'])){
                $params['access_token'] = $this->getAccessToken()->token;
            }
            return $oma->POST($this->generateUrl($url),$params,array('User-Agent' => 'ufsit-web'));
        }
        return false;
    }
    
    /***********************************************************/
    /** API Access Methods                                    **/
    /***********************************************************/
    
    //Dummy function, don't actually use this.
    //Example that would perform an API request of 'somedata'
    //  to the API url of 'http://example.com/api/somedata'
    //  assuming 'http://example.com/' is set as the value of
    //  OAUTH_API_BASE in serverconfig.php
    public function exampleApiRequest($exampleParam){
        //Perform a GET request to the API endpoint url 'api/somedata',
        //  where the full url will be 'api/somedata' appended to the url
        //  in OAUTH_API_BASE as set in serverconfig.php
        $request = $this->GET('api/somedata',array('data'=>$exampleParam));
        
        //Make sure there were no errors with setting up the request
        if($request !== false){
            //Perform the request, then do something with the response data
            $request->then(function($data) use(&$response){
                //We're expecting json as the result, so decode it
                $response = json_decode($data->text());
            });
        
            //Return the decoded json data
            return $response;
        }
        //Something went wrong
        return false;
    }
    
    /////////////////////////////////////////////////////////////
    // Place more API access methods for your application here //
    /////////////////////////////////////////////////////////////
}

?>