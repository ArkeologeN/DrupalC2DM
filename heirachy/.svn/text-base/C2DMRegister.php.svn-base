<?php

/**
 * Register Class that holds the Value Object for C2DM Registration Device Process
 * 
 *
 * @author      Hamza Waqas (Mobile Application Engineer)
 * @package     C2DMRegister
 * @link        www.plumtreegroup.net
 * @since       May, 2012
 * @version     v1.0
 */
class C2DMRegister {
    
    
    /**
     * @var String Identifier 
     */
    private $_identifier = "";
    
    
    /**
     *
     * @var String Org Id 
     */
    private $_orgid = "";
    
    /**
     *
     * @var String Device Name 
     */
    private $_deviceName = "";
    
    /**
     * @var String Device Id
     */
    private $_deviceId = "";
    
    /**
     *
     * @var String Application ID 
     */
    private $_appId = "";
    
    
    /**
     * @var String language
     */
    private $_language = "";
    
    /**
     *
     * @var Int Timezone 
     */
    private $_timezone = "";
    
    
    /**
     *
     * @var String Registration Id 
     */
    private $_registrationId = "";
    
    
    public function setIdentifier($identifier) {
        $this->_identifier = $identifier;
    }
    
    /**
     *
     * @param String $name 
     * Set the name of the device
     */
    public function setDeviceName($name) {
        $this->_deviceName = $name;
    }
    
    /**
     *
     * @param String $id 
     * Set the Device ID
     */
    public function setDeviceId($id) {
        $this->_deviceId = $id;
    }
    
    /**
     *
     * @param String $appid 
     * Set the Application ID
     */
    public function setAppId($appid) {
        $this->_appId = $appid;
    }
    
    /**
     *
     * @param String $lang 
     * Set the User Language
     */
    public function setLanguage($lang) {
        $this->_language = $lang;
    }
    
    /**
     * @param String $timezone 
     * Set the timezone data.
     */
    public function setTimezone($timezone) {
        $this->_timezone = $timezone;
    }
    
    /**
     *
     * @param String $rid 
     * Set the Registration Id of the device
     */
    public function setRegistrationId($rid) {
        $this->_registrationId = $rid;
    }
    
    /**
     * @param String $id
     * Set the Organization ID
     */
    public function setOrgId($id) {
        $this->_orgid = $id;
    }
    
    /**
     *
     * @return String 
     */
    public function getOrgId() {
        return (int)$this->_orgid;
    }
    
    /**
     * @return String 
     * 
     * Returns the Identifier String for unique Devices
     */
    public function getIdentifier() {
        return $this->_identifier;
    }
    
    /**
     * @return String Device Name 
     */
    public function getDeviceName() {
        return $this->_deviceName;
    }
    
    /**
     * @return String Device ID
     */
    public function getDeviceId() {
        return $this->_deviceId;
    }
    
    /**
     * @return String App ID
     */
    public function getAppId() {
        return $this->_appId;
    }
    
    /**
     * @return String Language
     */
    public function getLanguage() {
        return $this->_language;
    }
    
    /**
     * @return String Timezone
     */
    public function getTimezone() {
        return $this->_timezone;
    }
    
    /**
     *
     * @return String 
     * Get The Registration ID of the device.
     */
    public function getRegistrationId() {
        return $this->_registrationId;
    }
    
    /**
     * Inserts the Register Device Information to Database
     */
    public function Register() {
        try {            
            $exitance = db_select('devices','d')
                ->fields('d')->condition('identifier', $this->getIdentifier(), '=')->execute()->fetchAssoc();
            
            if ( empty($exitance)) {
                $query = db_insert('devices')->fields(array(
                    'identifier','device_name','orgid','device_id','register_id','timezone','registered'
                ))->values(array(
                    'identifier'    => $this->getIdentifier(),
                    'device_name'   => $this->getDeviceName(),
                    'orgid'         => $this->getOrgId(),
                    'device_id'     => $this->getDeviceId(),
                    'register_id'   => $this->getRegistrationId(),
                    'timezone'      => $this->getTimezone(),
                    'registered'    => time()
                ))->execute();
            }
        } catch(Exception $ex) {
            print "ERROR: "; echo "<pre>"; print_r($ex); echo "</pre>";
        }
    }
    
    /**
     * 
     */
    public function mapSequence() {
         $data = db_query("SELECT entity_id, to_push_at from pnof_push_sequence where orgid=".$this->getOrgId())->fetchAll();
         
         if ( !empty($data)) {
             foreach ($data as $sequence) {
                 try {
                    $query = db_insert('pnof_push_schedule')->fields(array(
                        'orgid','enid','regis_id','time_schedule'
                    ))->values(array(
                        'orgid'     => $this->getOrgId(),
                        'enid'      => $sequence->entity_id,
                        'regis_id'  => $this->getRegistrationId(),
                        'time_schedule'  => strtotime("+".$sequence->to_push_at.' minute')
                     ))->execute();
                    
                    echo 'Mapping';
                    print $sequence->to_push_at." \n";
                  } catch(Exception $ex) {
                      echo "<pre>"; print_r($ex); echo "</pre>";
                  }
             }
         }
         
         echo "push: <pre>"; print_r($data); echo "</pre>";
    }
    
    /**
     *
     * @param String $string 
     * 
     * The String to digest before going to Register validation
     */
    public function digest() {
        //echo hash('sha512',$this->getIdentifier());
        $this->setIdentifier(hash('sha512',$this->getIdentifier()));
    }
    
    /**
     * @param Int $did 
     * ID of the device you want to get
     */
    public function getDeviceById($did) {
        // Get From DB
    }
}

?>
