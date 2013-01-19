<?php
class BlogPost extends Zend_Db_Table_Abstract {
	
	protected $_primary = "blogpost_id" ;
	protected $_name = 'blogpost' ;
	
	public function findBlogPostPerUser ( $userId ) {
		$db = $this->getAdapter () ;
		$sql = " select bp.blogpost_id,bp.postcaption,bp.postdate,bp.poststatus " ;
		$sql .= " from blogpost bp , blog b " ;
		$sql .= " where bp.blog_id = b.blog_id " ;
		$sql .= " and b.user_id=" . $userId ;
		$sql .= " order by postdate desc " ;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	}
	
	public function findBlogPostPerBlogOrUser ($blogId=null ,$userId =null) {
	
		$db = $this->getAdapter () ;
		$sql = "  select bp.postdate,u.screen_name,bp.blogpost_id,bp.postcaption,bp.posttext,u.user_id,bp.tags,bp.num_comments,bp.num_views " ;
		$sql .= " from blogpost bp ,blog b,user u " ;
		$sql .= " where b.blog_id = bp.blog_id " ; 
		if($blogId != null){
			$sql .= " and bp.blog_id = " . $blogId ;
		}
		if($userId != null){
			$sql .= " and u.user_id = " . $userId ;	
		}
		$sql .= " and b.user_id = u.user_id " ;
		$sql .= " order by bp.postdate desc " ;
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
	
	}
	
	public function findPostOwner($postId){
		
		$db = $this->getAdapter () ;
		$sql = "  select u.screen_name " ;
		$sql .= "  from blogpost bp, blog b, user u " ;
		$sql .= "  where bp.blogpost_id =" .$postId ;
		$sql .="  and bp.blogpost_id = b.blog_id " ;
		$sql .=" and b.user_id = u.user_id ";  
		
		//echo $sql;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchColumn() ;
		return $row ;
		
	}
}
?>
