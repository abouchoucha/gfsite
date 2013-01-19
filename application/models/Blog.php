<?php 
class Blog extends Zend_Db_Table_Abstract
{

      protected $_primary = "blog_id";
      protected $_name   = 'blog';
      
       public function showAllBlogs()
       {
           $db = $this->getAdapter();
           $sql  =  " select b.blog_id,bp.postdate,b.description ,b.title,u.screen_name,u.main_photo,u.user_id";
		   $sql .=  " from blog b , blogpost bp ,user u ";
		   $sql .=  " where b.blog_id =  bp.blog_id ";
		   $sql .=  " and b.user_id = u.user_id ";
		   $sql .=  " group by b.blog_id ";
		   $sql .=  " order by bp.postdate desc ";
		   
		   $result = $db->query ($sql);
       	   $rows = $result->fetchAll();
       	   return $rows;
       }
       
       public function findBlogByUserId($userId){
       	
       	   $db = $this->getAdapter();
           $sql  =  " select b.blog_id,b.description ,b.title";
		   $sql .=  " from blog b ";
		   $sql .=  " where b.user_id = " . $userId;
		   $result = $db->query ($sql);
       	   $rows = $result->fetchAll();
       	   return $rows;
       	
       }
      
}
?>
