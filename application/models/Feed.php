<?php 
class Feed extends Zend_Db_Table_Abstract
  {

    protected $_primary = "feed_id";
	  protected $_name = 'feed';

  	  /*public function insert(&$data)
      {
          return parent::insert($data);
      }*/
      
      public function selectFeeds()
      {
          $where ="feed_active=1";
          return $this->fetchAll($where);
      }
      
  }
?>
