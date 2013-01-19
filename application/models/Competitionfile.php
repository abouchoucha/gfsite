<?php
class Competitionfile extends Zend_Db_Table_Abstract
{
    protected $_primary = "competitionfile_id";
    protected $_name   = 'competitionfile';
    
    public function selectLeaguesByCountry()
    {
      $db = $this->getAdapter();
      $sql  = "select country_name ,country_id from country ";
      $sql .= "where country_id <900  and country_id >7 ";
      $sql .= "order by country_name";
      //echo $sql;
      $result = $db->query ($sql);
      $row = $result->fetchAll ();
      return $row;
    }
    
    public function selectCountriesByContinent($groupId)
    {
    	$db = $this->getAdapter();
    	$sql  = " select c.country_name ,c.country_id ,c.region_id ";
		$sql .= " from country c , region r ,regiongroup rg ";
		$sql .= " where c.country_id <900 and c.country_id >7 ";
		$sql .= " and c.region_id = r.region_id";
		$sql .= " and r.region_group_id = rg.region_group_id";
		$sql .= " and r.region_group_id  =" .$groupId;
		$sql .= " order by c.country_name";
    	//echo $sql;
    	
    	$result = $db->query ($sql);
      	$row = $result->fetchAll ();
      	return $row;
    }
    
    
    public function selectXMLFileByCountryLeague($countryid , $leagueid )
    {
      $db = $this->getAdapter();
      $sql = " select c.filename, l.competition_name , s.season_name "; 
      $sql .= " from competitionfile c , league_competition l , season s ";
      $sql .= " where c.country_id =" .$countryid;
      $sql .= " and league_id =". $leagueid ." and filetype='T' ";
      $sql .= " and c.league_id = l.competition_id ";
      $sql .= " and l.season_id = s.season_id ";
      //echo $sql;
      $result = $db->query ($sql);
      $row = $result->fetchAll ();
      return $row;
    }
    
    
 }
