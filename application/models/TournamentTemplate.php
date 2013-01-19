<?php
class TournamentTemplate extends Zend_Db_Table_Abstract {
    protected $_name = 'tournament_template';
    protected $_primary = "id";


    public function init() {
      //load the other adapter
      $this->db2 = Zend_Registry::get('dbspocosy');
    }

   public function getPlayerDetails($playerid) {
      $sql = " SELECT pa.id,pa.name, p.name,p.value ";
      $sql .= " FROM property as p INNER JOIN participant as pa ON pa.id = p.objectFK ";
      $sql .= " WHERE p.objectFK = ". $playerid;
      return $this->db2->fetchAll($sql);
   }

   public function getLeaguesCountryRegion () {
      $sql = " SELECT  tt.name, ";
      $sql .= "        tt.countryFK, ";
      $sql .= "        c.name, ";
      $sql .= "        cg.country_id, ";
      $sql .= "        r.region_id, ";
      $sql .= "        r.region_name, ";
      $sql .= "        rg.region_group_id, ";
      $sql .= "        rg.region_group_name, count(tt.countryFK) as leaguestotal ";
      $sql .= " FROM tournament_template AS tt INNER JOIN ";
      $sql .= "        country AS c ON tt.countryFK = c.id INNER JOIN ";
      $sql .= "        countryg AS cg ON tt.countryFK = cg.country_e_id INNER JOIN ";
      $sql .= "        region AS r ON cg.region_id = r.region_id INNER JOIN ";
      $sql .= "        regiongroup AS rg ON r.region_group_id = rg.region_group_id ";
      $sql .= " WHERE tt.gender = 'male' and tt.countryFK is not NULL ";
      $sql .= " GROUP BY tt.countryFK ";
      $sql .= " ORDER by rg.region_sort_order,tt.countryFK, tt.id ";
      return $this->db2->fetchAll($sql);
   }
}
?>
