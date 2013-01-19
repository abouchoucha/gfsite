<?php
class NewsFeed extends Zend_Db_Table_Abstract {
	
	protected $_primary = "news_id";
	protected $_name = 'newsfeed';
	
	private $_today;
	private $_threeMonthsAgo;
	
	
	function init()  {
	
		$date = new Zend_Date();
		$this->_today = $date->toString ( 'YYYY-MM-dd') . " 23:59:59";
		$date->sub( '90', Zend_Date::DAY );
		$this->_threeMonthsAgo = $date->toString ( 'YYYY-MM-dd' ). " 00:00:00";
	}
	
	
	public function selectNewsFeedsHome($from = null, $count = null ,$type) {
		$db = $this->getAdapter ();
		$sql = " select count(com.comment_party_id) as numcomments,ytbl.news_id, ytbl.news_headline, ytbl.news_this_created, ytbl.news_body_content, ytbl.news_provider,ytbl.news_afp_id,ytbl.news_revision_id, ytbl.news_number_reads as numreads ";
		$sql .= " from ( select news_afp_id,max(news_revision_id ) as max_revision";
		$sql .= " from newsfeed";
		$sql .= " group by news_afp_id) as xtbl";
		$sql .= " inner join newsfeed as ytbl on ytbl.news_afp_id = xtbl.news_afp_id";
		$sql .= " and ytbl.news_revision_id = xtbl.max_revision";
		$sql .= " left join comment as com on com.comment_party_id = ytbl.news_afp_id ";
		$sql .= " group by news_afp_id";
		$sql .= " order by ytbl.news_this_created desc";

		if(!is_null($from)){
			$sql .= " Limit " . $from . "," . $count;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	
	public function selectNewsFeedsCountry($from = null, $count = null ,$cCode) {
		$db = $this->getAdapter ();
		$sql = " select count(com.comment_party_id) as numcomments,ytbl.news_id, ytbl.news_headline, ytbl.news_slugline,ytbl.news_this_created, ytbl.news_body_content, ytbl.news_provider,ytbl.news_afp_id,ytbl.news_revision_id, ytbl.news_number_reads as numreads ";
		$sql .= " from ( select news_afp_id,max(news_revision_id ) as max_revision";
		$sql .= "	from newsfeed where news_slugline LIKE '%-".$cCode."-%'";
		$sql .= "	group by news_afp_id) as xtbl";
		$sql .= " inner join newsfeed as ytbl on ytbl.news_afp_id = xtbl.news_afp_id";
		$sql .= " and ytbl.news_revision_id = xtbl.max_revision";
		$sql .= " left join comment as com on com.comment_party_id = ytbl.news_afp_id ";
		$sql .= " group by news_afp_id";
		$sql .= " order by ytbl.news_this_created desc";

		if(!is_null($from)){
			$sql .= " Limit " . $from . "," . $count;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}

	public function selectNewsFeeds($from = null, $count = null ,$type = null , $all = null) {
		self::init();
		$db = $this->getAdapter ();
		$sql = " select ytbl.news_id, ytbl.news_headline, ytbl.news_this_created, ytbl.news_body_content, ytbl.news_provider,ytbl.news_afp_id,ytbl.news_revision_id, ytbl.news_number_reads as numreads,news_total_votes,news_total_rating_count, ";
		$sql .= " round( (news_total_rating_count/news_total_votes) , 2) as rating ,(select sum(news_num_comments) as num_comments";
		$sql .= "                                                                             from newsfeed ";
		$sql .= " 																			  where news_afp_id = ytbl.news_afp_id ";
		$sql .= " 																			  group by news_afp_id ) as numcomments ,";
		$sql .= "																	(select sum(news_num_shares) as news_num_shares ";
		$sql .= " 																			  from newsfeed ";
		$sql .= "																			  where news_afp_id = ytbl.news_afp_id ";
		$sql .= " 																			  group by news_afp_id ) as num_shares "; 
		$sql .= " from ( select news_afp_id,max(news_revision_id ) as max_revision";
		$sql .= "	from newsfeed";
		$sql .= "	group by news_afp_id) as xtbl";
		$sql .= " inner join newsfeed as ytbl on ytbl.news_afp_id = xtbl.news_afp_id";
		if(!is_null($all)){
			//$sql .= " and ytbl.news_this_created > '". $this->_threeMonthsAgo  ."' and ytbl.news_this_created < '". $this->_today  . "'";
		}
		$sql .= " and ytbl.news_revision_id = xtbl.max_revision";
		$sql .= " group by news_afp_id";
		$sql .= " order by ";
		if($type == 'mostcommented'){
			$sql .= " numcomments desc";	
		}else if($type == 'mostread'){
			$sql .= " numreads desc";
		}else if($type == 'highestrated'){
			$sql .= " rating desc";
		}else if($type == 'mostshared'){
			$sql .= " num_shares desc";	
		}else {
			$sql .= " ytbl.news_this_created desc";
		}
		
		if(!is_null($from)){
			$sql .= " Limit " . $from . "," . $count;
		}
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		return $row;
	}
	
	public function getTotalNewsCount() {
		self::init();
		$db = $this->getAdapter ();
		$sql = " select count(*)";
		$sql .= " from newsfeed";
		$sql .= " where news_this_created > '". $this->_threeMonthsAgo  ."' and news_this_created < '". $this->_today  . "'";
		
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchColumn ( 0 );
		return $row;
	}
	
	public function selectNewsArticle($articleid) {
		$db = $this->getAdapter ();
		$sql = "select news_id, news_provider, news_date_id, news_headline,news_dateline, news_slugline, news_byline, news_this_created, news_body_content, news_env_date, news_env_time, news_afp_id,news_number_reads,news_total_votes,news_total_rating_count,news_num_shares";
		$sql .= " from newsfeed";
		$sql .= " where news_id =" . $articleid;
		//echo $sql ;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		
		//Zend_Debug::dump($row);
		return $row;
	}
	
	public function selectNextNewsArticle($articleid) {
		$db = $this->getAdapter ();
		$sql = "select news_id, news_provider, news_date_id, news_headline, news_dateline,news_slugline, news_byline, news_this_created, news_body_content, news_env_date, news_env_time, news_afp_id";
		$sql .= " from newsfeed";
		$sql .= " where news_id > " . $articleid;
		$sql .= " order by news_id ASC Limit 0, 1";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		
		//Zend_Debug::dump($row);
		return $row;
	}
	
	public function selectPreviousNewsArticle($articleid) {
		$db = $this->getAdapter ();
		$sql = "select news_id, news_provider, news_date_id, news_headline, news_dateline, news_slugline, news_byline, news_this_created, news_body_content, news_env_date, news_env_time, news_afp_id";
		$sql .= " from newsfeed";
		$sql .= " where news_id < " . $articleid;
		$sql .= " order by news_id DESC Limit 0, 1";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();
		
		//Zend_Debug::dump($row);
		return $row;
	}
	
	public function selectMaxNewsFeedRevisionByNewsAfpId($newsAfpId){
		
		$db = $this->getAdapter ();
		$sql = "select news_id,news_headline,news_afp_id,news_num_comments from newsfeed ";
		$sql .= " where news_afp_id = '" . $newsAfpId . "'"; 
		$sql .= "and news_revision_id = (select max(news_revision_id) ";
		$sql .= "		from newsfeed where news_afp_id = '" . $newsAfpId . "'" ;
		$sql .= "	 )";
			
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll();
		
		//Zend_Debug::dump($row);
		return $row;
		
	}
	
	public function updateNewsFeed($data , $newsFeedId){
		$db = $this->getAdapter ();
		$where = $db->quoteInto ( "news_id = ?", $newsFeedId );
		return $this->update ( $data, $where );
		
	}

    public function getNewsArticlesForSearchResult ($newsid) {
        $db = $this->getAdapter ();
        $sql= "select news_id, news_headline, news_date_id,news_provider,news_body_content,max(news_revision_id ) as max_revision " ;
        $sql .= " from newsfeed ";
        $sql .= " where news_id = ". $newsid;
        $sql .= " group by news_afp_id ";
        // echo $sql;
        $result = $db->query ( $sql ) ;
		$row = $result->fetchAll() ;
		return $row ;
    }

    //used on building sitemap-news.xml
    public function selectNewsArticleIndex($count) {
        $db = $this->getAdapter ();
        $sql= " select news_id,news_date_id,news_headline,news_body_content,news_afp_id,max(news_revision_id ) as max_revision from newsfeed  group by news_afp_id order by news_date_id desc ";
        $sql .= " limit 0, ".$count;
        $result = $db->query ( $sql ) ;
		$row = $result->fetchAll () ;
		return $row ;
    }


}
?>
