<?php
/**
 * Region
 *
 * @author Jorge Vasquez
 * @version
 */

class RegionGroup extends Zend_Db_Table_Abstract {

	protected $_primary = "region_group_id";
    protected $_name = 'regiongroup' ;
	
	public function getAllRegions() {

	    $select = $this->select();
		$rows = $this->fetchAll($select);
		
		return $rows;

    }
    
}
?>
