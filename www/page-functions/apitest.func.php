<?php
namespace ufsit;
class Page extends APIObject{
    /*
     * This function gets executed when the server receives a GET request
     */
	public function executeGet(){
        $data = array(
            'method'=>'GET',
            'message' => 'you requested this',
            'data' => isset($_GET['data']) ? $_GET['data'] : 'You did not pass anything in the \'data\' url parameter.',
        );
        $this->response->setContent($data);  
    }
    
    /*
     * This function gets executed when the server receives a POST request
     */
    public function executePost(){
        $data = array(
            'method'=>'GET',
            'message' => 'you requested this via POST',
            'data' => isset($_GET['data']) ? $_GET['data'] : 'You did not pass anything in the \'data\' url parameter.',
            'stuff' => isset($_POST['stuff']) ? $_POST['stuff'] : 'You did not POST anything in the \'stuff\' parameter.',
        );
        $this->response->setContent($data);  
    }
}
?>
