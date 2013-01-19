<?php
class Photo extends Zend_Db_Table_Abstract {
	
	protected $_primary = "image_id";
	protected $_name = 'photo';
	
	function init() {
	
	}
	
	public function selectPhotosPerTag($tag_id, $type, $limit = null) {
		$db = $this->getAdapter ();
		$sql = " select p.image_id,p.image_file_name,p.image_location,p.image_caption,pt.tag_id,pt.tag_type_id ";
		$sql .= " from photo p, phototag pt ";
		$sql .= " where p.image_id = pt.image_id ";
		$sql .= " and pt.tag_type_id = " . $type;
		$sql .= " and pt.tag_id = " . $tag_id;
        $sql .= " ORDER BY image_date desc,image_id desc ";
		if (! is_null ( $limit )) {
			$sql .= " LIMIT " . $limit;
		}
		$result = $db->query ( $sql );
		//echo $sql ;
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findPhotosAll($limit = null ,$type = null) {
		$db = $this->getAdapter ();
		$sql = " select p.image_id,p.image_file_name,p.image_subject,p.image_caption ,round((image_total_rating_count/image_total_votes) , 2) as rating ,image_number_reads as numreads ";
		$sql .= " from photo p ";	
		/*if($type == 'mostrecent'){
			$sql .= "order by numreads desc";
		}*/
		if($type == 'mostviewed'){
			$sql .= " order by numreads desc";
		}
		if($type == 'mostrated'){
			$sql .= " order by rating desc";
		}
                $sql .= " order by RAND() ";
		if (!is_null ( $limit )) {
			$sql .= " LIMIT " . $limit;
		}
		$result = $db->query ( $sql );
		//echo $sql ;
		$rows = $result->fetchAll ();
		return $rows;
	}
	
	public function findUniquePhoto($photoid) {
		$db = $this->getAdapter ();
		$sql = "select p.image_id,p.image_file_name,p.image_subject,p.image_caption ,p.image_total_votes, p.image_total_rating_count,p.image_number_reads ";
		$sql .= " from photo p ";
		$sql .= " where p.image_id = " . $photoid;
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function findUniquePhotoByName($photoname) {
		$db = $this->getAdapter ();
		$sql = "select p.image_id,p.image_file_name,p.image_subject,p.image_caption";
		$sql .= " from photo p ";
		$sql .= " where p.image_file_name = '" . $photoname . "'";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function updatePhoto($data , $photoId){
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "image_id = ?", $photoId );
		return $this->update ( $data, $where );
		
	}
	
	public function selectNextPhoto($photoId) {
		$db = $this->getAdapter ();
		$sql = "select p.image_id,p.image_file_name,p.image_subject,p.image_caption ,p.image_total_votes, p.image_total_rating_count";
		$sql .= " from photo p";
		$sql .= " where p.image_id > " . $photoId;
		$sql .= " order by p.image_id ASC Limit 0, 1";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		
		//Zend_Debug::dump($row);
		return $row;
	}
	
	public function selectPreviousPhoto($photoid) {
		$db = $this->getAdapter ();
		$sql = "select p.image_id,p.image_file_name,p.image_subject,p.image_caption ,p.image_total_votes, p.image_total_rating_count";
		$sql .= " from photo p";
		$sql .= " where p.image_id < " . $photoid;
		$sql .= " order by p.image_id DESC Limit 0, 1";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		
		//Zend_Debug::dump($row);
		return $row;
	}
	
	public function selectNextPhotoItem($photoid , $tagType, $tagId) {
		$db = $this->getAdapter ();
		$sql = "select p.image_id,p.image_file_name,p.image_subject,p.image_caption ,p.image_total_votes, p.image_total_rating_count,pt.tag_id";
		$sql .= " from photo p , phototag pt";
		$sql .= " where p.image_id = pt.image_id and pt.tag_type_id = " .$tagType. " and pt.tag_id = " .$tagId;
		$sql .= " and p.image_id < " . $photoid;
		$sql .= " order by p.image_id DESC Limit 0, 1";
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		
		//Zend_Debug::dump($row);
		return $row;
	}
	
	public function selectPreviousPhotoItem($photoid , $tagType, $tagId) {
		$db = $this->getAdapter ();
		$sql = "select p.image_id,p.image_file_name,p.image_subject,p.image_caption ,p.image_total_votes, p.image_total_rating_count,pt.tag_id";
		$sql .= " from photo p , phototag pt";
		$sql .= " where p.image_id = pt.image_id and pt.tag_type_id = " .$tagType. " and pt.tag_id = " .$tagId;
		$sql .= " and p.image_id > " . $photoid;
		$sql .= " order by p.image_id ASC Limit 0, 1";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		
		//Zend_Debug::dump($row);
		return $row;
	}

}
?>