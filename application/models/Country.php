<?php
class Country extends Zend_Db_Table_Abstract {
	
	protected $_primary = 'country_id';
	protected $_name = 'country';
	
	public function selectCountries() {
		$db = $this->getAdapter ();
		$sql = " select country_id ,country_name ";
		$sql .= " from country ";
		$sql .= " where regional <> 1 ";
		$sql .= " order by country_name ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	

	public function selectCountriesScoreboard() {
    	$db = $this->getAdapter ();
		$sql = " select country_id ,country_name ";
		$sql .= " from country ";
		$sql .= " order by country_id ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
  }
  
	public function selectCountriesScoreboardActive() {
    	$db = $this->getAdapter ();
		$sql = " SELECT DISTINCT lc.country_id, c.country_name ";
		$sql .= " FROM league_competition lc "; 
		$sql .= " INNER JOIN country c ON c.country_id = lc.country_id "; 
		$sql .= " WHERE lc.active = 1 ";
		$sql .= " order by lc.country_id ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
  }
  
  

	public function findCountryByRegion($regiongroupId) {
        $db = $this->getAdapter ();
        $sql = " select c.country_id ,c.country_name,COUNT(*) as totalteams ";
        $sql .= " from region r,regiongroup rg, country c, team t ";
        $sql .= " where rg.region_group_id = ". $regiongroupId;
        $sql .= " and rg.region_group_id = r.region_group_id ";
        $sql .= " and r.region_id = c.region_id ";
        $sql .= " and t.country_id = c.country_id ";
        $sql .= " and c.country_id >= 8 and c.country_id <= 900 ";
        $sql .= " and c.country_id IN (select distinct country_id from league_competition)";
        $sql .= " group by c.country_id ";
        $sql .= " order by country_name ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}

	public function findCountryById($country_id) {
		
		$db = $this->getAdapter () ;
		$where = $db->quoteInto ( "country_id = ?", $country_id ) ;
		return $this->fetchRow ( $where ) ;
	}


	public function findRegionIdByCountry($country_id){
		$db = $this->getAdapter ();
		$sql = " select c.country_id,c.country_name ,rg.region_group_id ";
		$sql .= " from  country c, region r, regiongroup rg ";
		$sql .= " where c.country_id=".$country_id;
		$sql .= " and c.region_id = r.region_id ";
		$sql .= " and r.region_group_id = rg.region_group_id ";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
}
?>
