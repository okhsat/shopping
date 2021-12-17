<?php
/**
 * Class Product
 *
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */

namespace Models;

use Library\BaseModel;

class Product extends BaseModel
{
    /**
     * Integer
     */
    public $id;
    
    /**
     * String
     */
    public $name;
    
    /**
     * String
     */
    public $type;
    
    /**
     * String
     */
    public $unit;
    
    /**
     * String
     */
    public $price;
    
    /**
     * String
     */
    public $created;
    
    /**
     * String
     */
    protected static $_table_name = 'product';
    
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
        
        $this->name = trim($this->name);
        $this->type = trim($this->type);
        $this->unit = trim($this->unit);
        
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
            
            $this->name = trim($this->name);
            $this->type = trim($this->type);
            $this->unit = trim($this->unit);
            
            if ( empty($this->name) ) {
                throw new Exception('The product name can not be empty.', 234);
            }
            
        } catch (Exception $e) {
            $this->raiseException($e, __CLASS__.'::'.__METHOD__, 'Error');
            return false;
        }
        
        return true;
    }
}
