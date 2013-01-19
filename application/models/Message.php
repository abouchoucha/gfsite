<?php
class Message extends Zend_Db_Table_Abstract {
	
	protected $_primary = "message_id" ;
	protected $_name = 'message' ;
	function init () {
		//Zend_Loader::loadClass ( 'Team' ) ;
		Zend_Loader::loadClass ( 'Zend_Debug' ) ;
		Zend_Loader::loadClass ( 'Pagination' ) ;
	}
	
	// Regular List of Messages from database
	public function findMessages($user_id){
		$db = $this->getAdapter();
		$sql = "select me.message_id as id, me.message_subject as subject, me.message_type as type, concat(substring(monthname(message_date),1,3),' ',day(message_date),' ,', year(message_date)) as date, me.message_status as status, me.message_content as content, me.message_img as img,
me.message_shout as shout, us.screen_name screen_name from message me inner join user us on me.user_from_id = us.user_id where me.user_id = ".$user_id;
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
	
	public function findStates(){
		$db = $this->getAdapter();
		$sql = "select id_description, description from message_description where observations = 'Message_status'";
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
	
	//Search for a single message on the Database
	public function findUniqueMessage($message_id){
		$db = $this->getAdapter();
		$sql = "select mt.description as typedescription, me.message_id as id, me.user_id, me.user_from_id, me.message_subject as subject, md1.description as type, concat(substring(monthname(message_date),1,3),' ',day(message_date),' ,', year(message_date)) as date, md.description as status, me.message_content
		as content, me.message_img, me.message_shout as shout, us.screen_name as screen_name,us.main_photo , us2.screen_name as owner from message me inner join user us on
		us.user_id = me.user_from_id inner join user us2 on us2.user_id = me.user_id left join message_description md on md.id_description = me.message_status  left join message_description
		md1 on md1.id_description = me.message_type inner join message_type mt ON me.message_type = mt.id where me.message_id = ".$message_id;
		//echo $sql;
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
	
	public function findUniqueMessageReply($message_id){
		$db = $this->getAdapter();
		$sql = "select me.message_id as id, me.user_id, me.user_from_id, me.message_subject as subject, md1.description as type, concat(substring(monthname(message_date),1,3),' ',day(message_date),' ,', year(message_date)) as date, md.description as status, me.message_content
		as content, me.message_img, me.message_shout as shout, us.screen_name as screen_name,us.main_photo , me.sentto as owner  from message me inner join user us on
		us.user_id = me.user_from_id inner join user us2 on us2.user_id = me.user_id left join message_description md on md.id_description = me.message_status  left join message_description
		md1 on md1.id_description = me.message_type where me.message_id = ".$message_id;
		//echo $sql;
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}


	
	//Delete messages from Database
	public function updateMessage($message_id, $data){
		$dim ="";
		
		$db = $this->getAdapter();
		$where = $db->quoteInto("message_id = ?", $message_id);
		$this->update($data, $where);
	
		return $dim;
	}
	
	
	
	//Delete desired messages from Database
	public function deleteMessage($message_id){
		$db = $this->getAdapter();
		$where = $db->quoteInto("message_id = ?", $message_id);
		return $this->delete($where);
	}
	
	public function findMessagesbyDate ($user_id){
		$db = $this->getAdapter();
		$sql = "select me.message_id as id, me.message_subject as subject, me.message_type as type, concat(substring(monthname(message_date),1,3),' ',day(message_date),' ,', year(message_date)) as date, me.message_status as status, me.message_content as content, me.message_img as img,
me.message_shout as shout, us.screen_name screen_name from message me inner join user us on me.user_from_id = us.user_id where me.user_id = ".$user_id." order by message_date";
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
	
	public function findRequests($user_id){
		$db = $this->getAdapter();
		$sql = "select me.message_id as id, me.message_subject as subject, md1.description as type, concat(substring(monthname(message_date),1,3),' ',day(message_date),' ,', year(message_date)) as date, md.description as status, me.message_content
as content, me.message_img, me.message_shout as shout, us.screen_name as screen_name from message me inner join user us on
us.user_id = me.user_from_id left join message_description md on md.id_description = me.message_status  left join message_description
md1 on md1.id_description = me.message_type where me.user_id = ".$user_id." and me.message_type = 8 ";
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
	
	public function findMessagesByStatus($user_id, $status){
		$db = $this->getAdapter();
		$sql = "select me.message_id as id, me.message_subject as subject, me.message_type as type, concat(substring(monthname(message_date),1,3),' ',day(message_date),' ,', year(message_date)) as date, me.message_status as status,
me.message_content as content, me.message_img as img, me.message_shout as shout, us.screen_name screen_name from message me
inner join user us on me.user_from_id = us.user_id where me.user_id = ".$user_id." and me.sent = 1";
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	
	}

	public function findMessagesByStatusGlobal($user_id, $status){
		$db = $this->getAdapter();
		$sql = "select me.message_id as id, me.message_subject as subject, me.message_type as type, concat(substring(monthname(message_date),1,3),' ',day(message_date),' ,', year(message_date)) as date, me.message_status as status,
me.message_content as content, me.message_img as img, me.message_shout as shout, us.screen_name screen_name from message me
inner join user us on me.user_from_id = us.user_id where me.user_id = ".$user_id." and me.message_status = ".$status;
		echo $sql;
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}

	public function findRequestsByStatus($user_id, $status){
		$db = $this->getAdapter();
		$sql = "select me.message_id as id, me.message_subject as subject, me.message_type as type, concat(substring(monthname(message_date),1,3),' ',day(message_date),' ,', year(message_date)) as date, me.message_status as status,
me.message_content as content, me.message_img as img, me.message_shout as shout, us.screen_name screen_name from message me
inner join user us on me.user_from_id = us.user_id where me.user_id = ".$user_id." and me.message_status = ".$status." and me.message_type = 8";
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
	
	public function findMessagesByType( $user_id , $type, $subtype ,$status){
		$db = $this->getAdapter();
		$sql = "select mt.description as typedescription, md.description as statusdescription, me.message_id as id, me.message_subject as subject, me.message_type as type, concat(substring(monthname(message_date),1,3),' ',day(message_date),' ,', year(message_date)) as date, me.message_status as status, ";
		$sql .=" me.message_content as content, me.message_img as img,me.message_shout as shout, us.screen_name screen_name ";
		if($subtype == 1){ 
			$sql .=",me.sentto as todest ";
		}
		$sql .=" from message me "; 
		$sql .="  inner join user us on me.user_from_id = us.user_id  ";
		$sql .=" inner join message_description md ON me.message_status = md.id_description "; 
		$sql .=" inner join message_type mt ON me.message_type = mt.id "; 
		$sql .=" where me.user_id = ".$user_id ;
		if(!is_null($type)){
			$sql .=" and me.message_type =" .$type;
		}
		if(!is_null($subtype)){
			$sql .=" and me.sent =" . $subtype;
		}
		if($status == 'n'){
			$sql .=" and me.message_status <> 5" ;
		}else if($status != 0){
			$sql .=" and me.message_status = " . $status ;
		}
		$sql .=" group by me.message_id desc "; 
		 
		//echo $sql;
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}

	
	
	public function doCountMessagesbyUser($user_id){
		$db = $this->getAdapter();
		$sql="select count(*) as total from message WHERE sent = 0  AND message_status = 4 and user_id= ".$user_id;
		//echo $sql; 
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
	
	public function doCountSentMessagesbyUser($user_id){
		$db = $this->getAdapter();
		$sql="select count(*) as total from message where sent = 1 and message_status <> 5 and user_id= ".$user_id;
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
	
	public function doCountRequestMessagesbyUser($user_id){
		$db = $this->getAdapter();
		$sql="select count(*) as total from message where message_type = 8 and message_status = 1 and user_id= ".$user_id;
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
	
	public function doCountDeletedMessagesByUser($user_id){
		$db = $this->getAdapter();
		$sql= "select count(*) as total from message me inner join user us on me.user_from_id = us.user_id where me.user_id = ".$user_id." and me.message_status = 5 ";
		//echo $sql; 
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
		
		
	}
	
	
	public function doCountNewMessagesbyUser($user_id){
		$db = $this->getAdapter();
		$sql="select count(*) as total from message where message_status in (1 , 4) and user_id= ".$user_id;
		//echo $sql; 
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
	
	public function findFriendInviteMessage($user_id , $to){
		$db = $this->getAdapter();
		$sql=" select message_id from message where message_type = 9 and message_status = 4  ";
		$sql.=" and sentto ='" . $to . "'";
		$sql.="  and  user_id = " . $user_id;
		//echo $sql; 
		$result = $db->query($sql);
		$row = $result->fetchColumn(0);
		return $row;
	}
	
	public function getEmailPendingMessages($user_id, $fromDate, $toDate){
		$db = $this->getAdapter();
		$sql=" select m.message_id , u.screen_name as sentByName from message m, user u  ";
		$sql.=" where m.user_id = " .$user_id ." and m.user_from_id = u.user_id	 and sent =0   ";
		$sql.=" and m.message_status <> 5  ";
		$sql.=" and m.emaildelivered = 'N'  ";
		$sql.=" and m.message_date between '" . $fromDate ."' and  '" . $toDate . "'";
		//echo $sql; 
		$result = $db->query($sql);
		$row = $result->fetchAll();
		return $row;
	}
}
?>
