<?php
namespace ufsit\user;
class AccessToken{
    public $uid;
    public $token;
    public $expiration;
    public $updated;
    
    /*
     * Compares the token update time, and the expiration period
     *   to the current time to determine if the access token has expired.
     * returns true if the token is expired, false if it is not.
     */
    public function isExpired(){
        return (strtotime($this->updated) + $this->expiration) < time(); 
    }
}