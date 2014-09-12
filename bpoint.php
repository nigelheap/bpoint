<?php



class Bpoint {

    const ENDPOINT = "https://www.bpoint.com.au/evolve/service.asmx?WSDL";

    private $client;
    private $result;
    private $successResultName;
    private $account = array(
        'username' => '',
        'password' => '',
        'merchantNumber' => ''
    );

    private $connected = false;

    /**
     * setup & connect
     */
    public function __construct($account = array()){

        if(!class_exists('SoapClient')){
            self::error('No SoapClient');
        }

        if(empty($account)){
            self::error('Please provide account details');
        }

        $this->account = $account;

        return $this;
    }

    public function connect(){

        if(!$this->client){
            $this->client = new SoapClient(self::ENDPOINT);
        }
            
        return $this;
    }

    /**
     * Doing things
     */
    
    public function processPayment($data){

        $this->successResultName = 'ProcessPaymentResult';
        $data = array_merge($this->account, array('txnReq' => $data));
        $this->result = $this->client->ProcessPayment($data);

        return $this;
    }

    public function addToken($data){

        $this->successResultName = 'AddTokenResult';
        $data = array_merge($this->account, array('tokenRequest' => $data));
        $this->result = $this->client->AddToken($data);

        return $this;
    }


    public function getRecentlyModifiedTokens($data){

        $this->successResultName = 'GetRecentlyModifiedTokensResult';
        $data = array_merge($this->account, $data);
        $this->result = $this->client->GetRecentlyModifiedTokens($data);

        return $this;
    }

    /**
     * Batch operations
     */

    public function downloadBatchByFilename($data){

        // need to login for this one.
        $this->login();

        $this->successResultName = 'DownloadBatchByFilenameResult';
        $data = array_merge($this->account, $data);
        $this->result = $this->client->DownloadBatchByFilename($data);

        return $this;

    }

    public function submitBatch($data){

        // need to login for this one.
        $this->login();

        $this->successResultName = 'SubmitBatchResult';
        $this->result = $this->client->SubmitBatch($data);

        return $this;
    }
    

    /**
     * Session/Auth
     */

    public function login(){

        $this->successResultName = 'LoginResult';
        $this->result = $this->client->Login($this->account);

        return $this;
    }

    public function logout(){

        $this->successResultName = 'LogoutResult';
        $this->result = $this->client->Logout($this->account);

        return $this;
    }
    

    /**
     * Response helpers
     */

    /**
     * Get the responce status
     * @return True/False [description]
     */
    public function success(){
        if($this->result->response->ResponseCode == 'SUCCESS'){
            return true;
        }
        return false;
    }

    
    public function getResponse(){
        if($this->success()){
            if(!empty($this->successResultName)){
                $resultName = $this->successResultName;
                return $this->result->$resultName;
            }
        } else {
            return $this->result->response->ResponseMessage;
        }

    }

    /**
     * Helpers
     */
    
    static private function error($message){
        throw new Exception($message);
    }
    
    
    
}