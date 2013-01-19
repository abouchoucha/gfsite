<?php 
require_once 'Zend/Db/Table/Abstract.php';
require_once 'application/controllers/util/Constants.php';

class Report extends Zend_Db_Table_Abstract {
	protected $_primary = "report_id";
    protected $_name = 'report' ;
    
    
    public function insert(array $data){
    	
    	$session = new Zend_Session_Namespace ( 'userSession' );
    	$data['report_reported_by'] = $session->userId;
    	$data['report_time'] = trim ( date ( "Y-m-d H:i:s" ) );
    	parent::insert($data);
    }

    public function findAllComplaints($userId , $limit = 'n'){
	
		$db = $this->getAdapter () ;
		$sql = " select r.report_time, r.report_text, r.report_type, u.user_id, u.screen_name ,r.report_reported_to,r.report_reported_by ";
		$sql .= " from report r,  user u  ";
		$sql .= " where r.report_reported_by =" .$userId . " and r.report_reported_to = u.user_id  ";
		$sql .= " union";
		$sql .= " select r.report_time, r.report_text, r.report_type, u.user_id, u.screen_name ,r.report_reported_to,r.report_reported_by ";
		$sql .= " from report r,  user u  ";
		$sql .= " where r.report_reported_to =" .$userId . " and r.report_reported_by = u.user_id  ";
		$sql .= " order by report_time desc";
		if($limit == 'y'){
			$sql .= " limit 15";
		}
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		return $rows ;
	
	}

    public function findMyComplaints($userId , $limit = 'n'){
	
		$db = $this->getAdapter () ;
		$sql = " select r.report_time, r.report_text, r.report_type, u.user_id, u.screen_name ,r.report_reported_to,r.report_reported_by ";
		$sql .= " from report r,  user u  ";
		$sql .= " where r.report_reported_by =" .$userId . " and r.report_reported_to = u.user_id  ";
		$sql .= " order by report_time desc";
		if($limit == 'y'){
			$sql .= " limit 15";
		}
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		return $rows ;
	
	}

    public function findComplaintsPerUser($userId , $limit = 'n'){
	
		$db = $this->getAdapter () ;
		$sql = " select r.report_time, r.report_text, r.report_type, u.user_id, u.screen_name ,r.report_reported_to,r.report_reported_by ";
		$sql .= " from report r,  user u  ";
		$sql .= " where r.report_reported_to =" .$userId . " and r.report_reported_by = u.user_id  ";
		$sql .= " order by report_time desc";
		if($limit == 'y'){
			$sql .= " limit 15";
		}
		//	echo $sql;
		$result = $db->query ( $sql ) ;
		$rows = $result->fetchAll () ;
		return $rows ;
	
	}

}    




?>
