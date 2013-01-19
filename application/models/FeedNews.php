<?php
class FeedNews extends Zend_Db_Table_Abstract {
	
	protected $_primary = "feed_news_id";
	protected $_name = 'feednews';
	private $_today;
	private $_threeMonthsAgo;
	
	
	function init()  {
	
		$date = new Zend_Date();
		$this->_today = $date->toString ( 'YYYY-MM-dd') . " 23:59:59";
		$date->sub( '90', Zend_Date::DAY );
		$this->_threeMonthsAgo = $date->toString ( 'YYYY-MM-dd' ). " 00:00:00";
		
	}
	
	public function selectLatestFeeds($from ,$to,$type ,$category = null) {
		
		self::init();
		$db = $this->getAdapter ();
		
//		$select = $db->select ()->from ( array ('f' => 'feednews' ), array ('feed_news_id', 'feed_news_title', 'feed_news_description', 
//				'feed_news_date', 'feed_news_source', 'feed_news_url' ) )->limit ( 1,$numFeeds )->order ( 'feed_news_date DESC' );
//		$sql = $select->__toString ();
		
		$sql = "SELECT `f`.`feed_news_id`, `f`.`feed_news_title`, `f`.`feed_news_description`, `f`.`feed_news_date`, `f`.`feed_news_source`, `f`.`feed_news_url`, `f`.`feed_news_image_url`";
		$sql .= " FROM `feednews` AS `f` ";
		//$sql .= " WHERE f.feed_news_date > '". $this->_threeMonthsAgo  ."' and f.feed_news_date < '". $this->_today  ."' ";
		if(!is_null($category)){
        	$sql .= " WHERE `f`.`feed_news_category` like lower('%$category%') ";
        } 
        $sql .= " ORDER BY ";
		if($type == 'mostcommented'){
			$sql .= " numcomments desc";	
		}else if($type == 'mostread'){
			$sql .= " numreads desc";
		}else {
			$sql .= " `feed_news_date` DESC";
		}
		if(!is_null($from)){
			$sql .= " LIMIT ". $from ." , " .$to;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ( );		
		return $rows;
	}
 
  public function selectLatestFeedsPhoto($from,$to ,$category = null) {
        self::init();
		$db = $this->getAdapter ();
        $sql = "SELECT DISTINCT `f`.`feed_news_title`, `f`.`feed_news_id`,`f`.`feed_news_description`, `f`.`feed_news_date`, `f`.`feed_news_source`, `f`.`feed_news_url`, `f`.`feed_news_image_url`";
		$sql .= " FROM `feednews` AS `f` ";
        $sql .= " WHERE `f`.`feed_news_source` = 'BBC Football' ";
        if(!is_null($category)){
        	$sql .= " AND `f`.`feed_news_category` like lower('%$category%') ";
        } 
        $sql .= " ORDER BY ";
        $sql .= " `feed_news_date` DESC";
        if(!is_null($from)){
			$sql .= " LIMIT ". $from ." , " .$to;
		}
        //echo $sql;
        $result = $db->query ( $sql );
		$rows = $result->fetchAll ( );
		return $rows;
        }
        
  public function selectLatestFeedsBySource($from,$to ,$source) {
        self::init();
		$db = $this->getAdapter ();
        $sql = " SELECT `f`.`feed_news_id`, `f`.`feed_news_title`, `f`.`feed_news_description`, `f`.`feed_news_date`, `f`.`feed_news_source`, `f`.`feed_news_url`, `f`.`feed_news_image_url`";
		$sql .= " FROM `feednews` AS `f` ";
        $sql .= " WHERE `f`.`feed_news_source` = '$source' ";
       
        $sql .= " ORDER BY ";
        $sql .= " `feed_news_date` DESC";
        if(!is_null($from)){
			$sql .= " LIMIT ". $from ." , " .$to;
		}
        //echo $sql;
        $result = $db->query ( $sql );
		$rows = $result->fetchAll ( );
		return $rows;
        }

	
	public function getFeedsFromLastThreeMonths(){
		self::init();
		$db = $this->getAdapter ();
		$sql = " select count(*) ";
		$sql.= " from feednews ";
		$sql .= " WHERE feed_news_date > '". $this->_threeMonthsAgo  ."' and feed_news_date < '". $this->_today  . "'";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchColumn ( 0 );
		return $row;
		
	}
	
	
	public function selectLatestUpdateFromFeeds($feed_news_name) {
		$db = $this->getAdapter ();
		//	      $select = $db->select()
		//		      ->from(array('f' => 'feednews'),
		//		      array('UNIX_TIMESTAMP(feed_news_date)'))
		//		      ->order('feed_news_date DESC')
		//		      ->limit(1);
		//		  $sql = $select->__toString();
		Zend_Loader::loadClass ( 'Zend_Debug' );
		//		  Zend_Debug::dump($sql);
		

		$select = " SELECT UNIX_TIMESTAMP(feed_news_date) as date FROM feednews ";
		$select .= " WHERE feed_news_source = '$feed_news_name' ";	
		$select .= " order by feed_news_date desc ";
		$select .= " limit 1 ";
		echo $select;
		$row = $db->fetchRow ( $select );
		//Zend_Debug::dump($row);
		return $row;
	
	}

	public function selectFeedNewsFiltered ($query) {
	  self::init();
		$db = $this->getAdapter ();
		
		$sql = " SELECT f.feed_news_id, f.feed_news_title, f.feed_news_description, f.feed_news_date, f.feed_news_source, f.feed_news_url, f.feed_news_image_url";
		$sql .= " FROM feednews AS f ";
	  $sql .= " WHERE  f.feed_news_description like '%$query%' " ;
	  $sql .= " order by feed_news_date desc";
	  $sql .= " LIMIT 5 ";
    //echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ( );		
		return $rows;
	}
	
	
	public function selectFeedNewsById($feedNewsId) {
		
		self::init();
		$db = $this->getAdapter ();
		
		$sql = " SELECT `f`.`feed_news_id`, `f`.`feed_news_title`, `f`.`feed_news_description`, `f`.`feed_news_date`, `f`.`feed_news_source`, `f`.`feed_news_url`, `f`.`feed_news_image_url`";
		$sql .= " FROM `feednews` AS `f` ";
		$sql .= " WHERE f.feed_news_id = " . $feedNewsId;

        //echo $sql;
		$result = $db->query ( $sql );
		$rows = $result->fetchAll ( );		
		return $rows;
	
		
	}
	
	public function quote($value) {
		if ($value instanceof Zend_Db_Expr) {
			return $value->__toString ();
		} else if (is_array ( $value )) {
			foreach ( $value as &$val ) {
				$val = $this->quote ( $val );
			}
			return implode ( ', ', $value );
		}
	}
	
	public function insertFeedValues($data) {
		$db = $this->getAdapter ();
		
		//$sql = ' insert into feednews ' . '  (feed_news_title, feed_news_description, feed_news_date, feed_news_source, feed_news_url) ' . '   VALUES (:feed_news_title, :feed_news_description ,FROM_UNIXTIME(:feed_news_date), :feed_news_source, :feed_news_url )';
		$sql = ' insert into feednews ' . '  (feed_news_title, feed_news_description, feed_news_date, feed_news_source, feed_news_url, feed_news_image_url, feed_news_category) ' . '   VALUES (:feed_news_title, :feed_news_description ,FROM_UNIXTIME(:feed_news_date), :feed_news_source, :feed_news_url,:feed_news_image_url,:feed_news_category)';
                //echo $sql . "<br>";
		$result = $db->query ( $sql, $data );
	
	}
}
?>
