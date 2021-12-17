<?php
/**
 * Class User
 *
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */

namespace Models;

use Library\BaseModel;

class User extends BaseModel
{
    /**
     * Integer
     */
    public $id;
    
    /**
     * String
     */
    public $email;
    
    /**
     * String
     */
    public $password;
    
    /**
     * String
     */
    public $name;
    
    /**
     * Integer
     */
    public $gender;
    
    /**
     * String
     */
    public $phone;
    
    /**
     * decimal
     */
    public $balance;
    
    /**
     * String
     */
    public $created;    
    
    /**
     * String
     */
    public $updated;    
    
    /**
     * String
     */
    protected static $_table_name = 'user';
        
    /**
     * Method to bind the data to the model (e.g. for storing into the database)
     *
     * @param 	array       $array        The array to bind to the object
     * @return 	boolean                   True on success, false on failure
     * @since 	2.0
     */
    public function bind($array = array())
    {
        $this->_curExc = null;
        $new           = !$this->id;
        
        unset($array['id']);
        unset($array['created']);
        unset($array['updated']);
        
        if ( isset($array['password']) && !empty(trim($array['password'])) ) {
            $this->password = password_hash(trim($array['password']), PASSWORD_DEFAULT);
        }
        
        unset($array['password']);
        
        if ( !parent::bind($array) ) {
            return false;
        }
        
        $this->email = trim($this->email);
        $this->name  = trim($this->name);
        $this->phone = trim($this->phone);
        $datetime    = date('Y-m-d H:i:s', time()); // Greenwich Mean Date and Time To MySQL
        
        if ($new) {
            $this->created = $datetime;
            $this->updated = '0000-00-00 00:00:00';
            
        } else {
            $this->updated = $datetime;
        }
        
        return true;
    }
    
    /**
     * Method to check the whole model properties (e.g. before storage into the database)
     *
     * @return 	boolean    True on success, false on failure
     * @since 	2.0
     */
    public function check()
    {
        try {
            $this->_curExc = null;
            
            if ( !parent::check() ) {
                return false;
            }
            
            $this->email = trim($this->email);
            $this->name  = trim($this->name);
            $this->phone = trim($this->phone);
            
            if ( empty($this->name) ) {
                throw new Exception('The user\'s name can not be empty!', 234);
            }
            
            if ( empty($this->email) ) {
                throw new Exception('The user\'s email can not be empty!', 234);
            }
            
            if ( !empty($this->gender) && !in_array((int) $this->gender, [0, 1]) ) {
                throw new Exception('The user\'s gender is not a valid value!', 234);
            }
            
        } catch (Exception $e) {
            $this->raiseException($e, __CLASS__.'::'.__METHOD__, 'Error');
            return false;
        }
        
        return true;
    }
}
