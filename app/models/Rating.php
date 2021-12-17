<?php
/**
 * Class Rating
 *
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */

namespace Models;

use Exception;
use Library\BaseModel;

class Rating extends BaseModel
{
    /**
     * Integer
     */
    public $id;
    
    /**
     * String
     */
    public $session_id;
    
    /**
     * Integer
     */
    public $user_id;
    
    /**
     * Integer
     */
    public $product_id;
    
    /**
     * Integer
     */
    public $rate;
    
    /**
     * String
     */
    public $created;
    
    /**
     * String
     */
    protected static $_table_name = 'rating';
    
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
        
        $this->user_id    = (int) $this->user_id;
        $this->product_id = (int) $this->product_id;
        $this->rate       = (int) $this->rate;
        
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
            
            $this->session_id = trim($this->session_id);
            $this->user_id    = (int) $this->user_id;
            $this->product_id = (int) $this->product_id;
            $this->rate       = (int) $this->rate;
            
            if ( empty($this->session_id) ) {
                throw new Exception('The rating session id can not be empty!', 234);
            }
            
            if ( $this->product_id < 1 ) {
                throw new Exception('The product is not specified!', 234);
            }
            
            if ( $this->rate < 1 || $this->rate > 5 ) {
                throw new Exception('Invalid rating value given!', 234);
            }
            
        } catch (Exception $e) {
            $this->raiseException($e, __CLASS__.'::'.__METHOD__, 'Error');
            return false;
        }
        
        return true;
    }
}
