<?php
class BlogPostComment extends Zend_Db_Table_Abstract {
	
	protected $_primary = "comment_id" ;
	protected $_name = 'blogpostcomment' ;
	
	
	public function findCommentsByBlogPost ( $blogpostid , $limit) {
		$db = $this->getAdapter () ;
		$sql = " select bpc.blogpost_id,bpc.comment_userid,bpc.comment_text as comment ,u.screen_name as screen_name,u.main_photo,bpc.date "; 
		$sql .= " from blogpostcomment bpc,user u  ";
		$sql .= " where bpc.comment_userid = u.user_id ";
		$sql .= " and bpc.blogpost_id = " .$blogpostid;
		$sql .= " order by bpc.date desc limit 0,".$limit;
		$result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
		
	}
}
?>
