<?php
/**
 * Class Order
 *
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */

namespace Models;

use Library\BaseModel;

class Order extends BaseModel
{
    /**
     * Integer
     */
    public $id;
    
    /**
     * Integer
     */
    public $user_id;
    
    /**
     * Decimal
     */
    public $price;
    
    /**
     * Integer
     */
    public $payment_status;
    
    /**
     * Integer
     */
    public $status;
    
    /**
     * String
     */
    public $paid;
    
    /**
     * String
     */
    public $created;
    
    /**
     * String
     */
    protected static $_table_name = 'order';
    
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
        
        if ( !parent::bind($array) ) {
            return false;
        }
        
        $this->user_id        = (int) $this->user_id;
        $this->payment_status = (int) $this->payment_status;
        $this->status         = (int) $this->status;
        
        if ( !in_array($this->payment_status, [0, 1]) ) {
            $this->payment_status = 0;
        }
        
        if ($new) {
            $this->created = date('Y-m-d H:i:s', time()); // Greenwich Mean Date and Time To MySQL
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
            
            $this->user_id        = (int) $this->user_id;
            $this->payment_status = (int) $this->payment_status;
            $this->status         = (int) $this->status;
            
            if ( $this->user_id < 1 ) {
                throw new Exception('The order\'s user is not specified!', 234);
            }
            
            if ( !in_array($this->payment_status, [0, 1]) ) {
                throw new Exception('Invalid order payment status!', 234);
            }
            
        } catch (Exception $e) {
            $this->raiseException($e, __CLASS__.'::'.__METHOD__, 'Error');
            return false;
        }
        
        return true;
    }
}
