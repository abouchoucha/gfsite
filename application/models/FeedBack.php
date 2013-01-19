<?php

/**
 * FeedBack
 *  
 * @author chocheraz
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class FeedBack extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'feedback';
	protected $_primary = "id";

}
