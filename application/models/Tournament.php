<?php
class Tournament extends Zend_Db_Table_Abstract {
	protected $_name = 'tournament';
	protected $_primary = "id";
   

	 public function init()
        {
        //load the other adapter
          $this->db2 = Zend_Registry::get('dbspocosy');
        }



    public function showAllTables(){
        $sql = "SELECT * from tournament_stage where tournamentFK = 4951";
        return $this->db2->fetchAll($sql);
      }
      
    public function getAllTournamentStagesPerCountry ($country_id) {
         $sql = " SELECT tt.id as template_id, tt.name AS template_name, tt.name_current, ts.id AS stage_id, ";
         $sql .= " ts.name AS stage_name,cg.country_id, cg.country_e_id,t.id AS t_id , MAX(t.name) AS t_name ";
         $sql .= " FROM countryg as cg INNER JOIN ";
         $sql .= " tournament_stage as ts ON ts.countryFK = cg.country_e_id INNER JOIN ";
         $sql .= " tournament AS t ON t.id = ts.tournamentFK INNER JOIN ";
         $sql .= " tournament_template AS tt ON tt.id = t.tournament_templateFK ";
         $sql .= " WHERE cg.country_id = " . $country_id ;
         $sql .= " GROUP by tt.id ";
         //echo $sql;
        return $this->db2->fetchAll($sql);
    }

}
?>
