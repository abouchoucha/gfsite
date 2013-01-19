<?php
/**
 * Region
 *
 * @author Jorge Vasquez
 * @version
 */

class Region extends Zend_Db_Table_Abstract {

	protected $_primary = "region_id";
    protected $_name = 'region' ;
	
	public function getRegions($roundid) {

	    $db = $this->getAdapter ();
	    $sql = " select rg.region_group_name ";
	    $sql .= " from region r, regiongroup rg ";
	    $sql .= " where r.region_group_id = rg.region_group_id ";
	    $sql .= " and  r.region_id =" . $roundid;
	    //echo $sql;
	    $result = $db->query ( $sql );
	    $row = $result->fetch ();
	    return $row;

    }
  
  public function getRegionGroupName ($regionId) {
      $db = $this->getAdapter ();
      $sql = " select rg.region_group_name ";
      $sql .= " from region r, regiongroup rg ";
      $sql .= " where r.region_group_id = rg.region_group_id ";
      $sql .= " AND r.region_id = " . $regionId;
       //echo $sql;
	    $result = $db->query ( $sql );
	    $row = $result->fetch ();
	    return $row;
  }  


    
}
?>
