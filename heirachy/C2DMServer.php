<?php

/**
 * Register & AUTH the user to google services for the push reciever.
 * Generic class that links with CURL to OAUTH on Google C2DM Cloud.
 * 
 *
 * @author      Hamza Waqas (Mobile Application Engineer)
 * @package     C2DMServer
 * @link        www.plumtreegroup.net
 * @since       May, 2012
 * @version     v1.0
 */
class C2DMServer {
    
    /**
     * @var String Authentication String
     * Holds Google's Authentication String
     */
    private $_authString = "";
    
    /**
     * @var String Username
     * Google OAuth C2dm Username
     */
    private $_username = "";
    
    /**
     * @var String Password
     * Google OAuth C2dm Password
     */
    private $_password = "";
    
    /**
     *
     * @var String Google URL 
     */
    private $_OAuthurl = "https://www.google.com/accounts/ClientLogin";
    
    
    /**
     *
     * @var String $_C2DMSendurl
     * Holds the Send URL Fr the C2DM Server
     */
    private $_C2DMSendurl = "https://android.apis.google.com/c2dm/send";
    
    /**
     *
     * @var String Google Account Type 
     */
    private $_accountType = "GOOGLE";
    
    /**
     * @var String App Source
     */
    private $_source = "ptg-eintellionboard-1.9.6"; //Company-AppName-Version
    
    
    /**
     * @var String Google Service KEY
     */
    private $_service = "ac2dm";
    
    
    /**
     *
     * @var String $_message
     * Holds the message to send 
     */
    private $_message = "";

    /**
    * Get Google login auth token
    * @see http://code.google.com/apis/accounts/docs/AuthForInstalledApps.html
    */
    function getAuthToken() {
        $u = $this->getUsername();
        $p = $this->getPassword();
        
        if (empty($u) || empty($p)) {
            throw new Exception("username, password must all be set to get auth token");
        }

        // Initialize the curl object
        $curl = curl_init();
        if (!$curl) {
            return false;
        }

        curl_setopt($curl, CURLOPT_URL, $this->getOAuthUrl());
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $data = array(
            'Email' => $this->getUsername(),
            'Passwd' => $this->getPassword(),
            'accountType' => $this->_accountType,
            'source' => $this->_source,
            'service' => $this->_service
        );

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($curl);
        curl_close($curl);
        

        /*
         * if (strpos($response, '200 OK') === false) {
            return false;

        }
         */
        
        // Get the Auth string
        preg_match("/Auth=([a-z0-9_\-]+)/i", $response, $matches);
        $this->setAuthString($matches[1]);
        
    }

    /**
    * Send a Message to The Device
    */
    function sendMessage($deviceRegistrationId, $msgType) {
        $headers[] = 'Authorization: GoogleLogin auth='.$this->getAuthString();
        $data = array(
            'registration_id' => $deviceRegistrationId,
            'collapse_key' => $msgType,
            'data.payload' => urlencode($this->getMessage()),
            'data.title' => urlencode($this->getMessage())
        );
        
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->getC2DMSendUrl());
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($curl);
        curl_close($curl);
         
        return $response;
    }
    
    /**
     *
     * @param String $user 
     * Username For Google Auth
     */
    function setUsername($user) {
        $this->_username = $user;
    }
    
    /**
     *
     * @return String Username 
     */
    function getUsername() {
        return $this->_username;
    }
    
    /**
     *
     * @param String $pass 
     * Password For Google Auth
     */
    function setPassword($pass) {
        $this->_password = $pass;
    }
    
    /**
     * @return Password
     */
    function getPassword() {
        return $this->_password;
    }
    
    /**
     *
     * @return String URL
     * Returns the Google oAuth URL 
     */
    function getOAuthUrl() {
        return $this->_OAuthurl;
    }
    
    /**
     *
     * @return String 
     * Returns the C2DMSend Cloud Google URL 
     */
    function getC2DMSendUrl() {
        return $this->_C2DMSendurl;
    }
    
    /**
     *
     * @param String $aS 
     * Set Google Auth String
     */
    function setAuthString($aS) {
        $this->_authString = $aS;
    }
    
    /**
     * @return String Google Auth String
     */
    function getAuthString() {
        return $this->_authString;
    }
    /**
     * @param String $message
     * Message String that need to be sent to the device.
     */
    function setMessage($message) {
        $this->_message = $message;
    }
    
    /**
     * @return String 'Message'
     */
    function getMessage() {
        //return (empty($this->_message) ? 'No Message Found!.' : $this->_message);
        return $this->_message;
    }
}

