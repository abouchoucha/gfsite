<?php 
class NewsFeedPhoto extends Zend_Db_Table_Abstract
  {

    protected $_primary = "photo_id";
	protected $_name = 'newsfeedphoto';

  	  /*public function insert(&$data)
      {
          return parent::insert($data);
      }*/
      
    public function selectNewsFeeds()
      {
          $db = $this->getAdapter();
          return $this->fetchAll(null ,null);
      }
          
  	public function selectNewsArticlePhotos( $news_id) {
  		$db = $this->getAdapter () ;
  		$sql = "select photo_id, photo_headline, photo_creator, photo_caption, photo_thumb_file, photo_quicklook_file,photo_quicklook_width, photo_preview_file";
  		$sql .= " from newsfeedphoto";
  		$sql .= " where news_id ='" . $news_id . "'";
  		//echo $sql;
  		$result = $db->query ( $sql ) ;
  		$row = $result->fetchAll () ;
  		//Zend_Debug::dump($row);
  		return $row ;
  	}
  	
  	public function selectNextPhotoItem($photoId) {
		$db = $this->getAdapter ();
		$sql = "select nfp.photo_id, nfp.photo_headline, nfp.photo_creator, nfp.photo_caption, nfp.photo_thumb_file, nfp.photo_quicklook_file,nfp.photo_quicklook_width, nfp.photo_preview_file,nf.news_date_id,nf.news_provider ";
		$sql .= " from newsfeedphoto nfp, newsfeed nf ";
        $sql .= " where nfp.news_id = nf.news_id ";
        $sql .= " and nfp.photo_id > ". $photoId;
		$sql .= " order by nfp.photo_id ASC Limit 0, 1";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();		
		//Zend_Debug::dump($row);
		return $row;
	}
	
	public function selectPreviousPhotoItem($photoId) {
		$db = $this->getAdapter ();
		$sql = "select nfp.photo_id, nfp.photo_headline, nfp.photo_creator, nfp.photo_caption, nfp.photo_thumb_file, nfp.photo_quicklook_file,nfp.photo_quicklook_width, nfp.photo_preview_file,nf.news_date_id,nf.news_provider ";
		$sql .= " from newsfeedphoto nfp, newsfeed nf ";
        $sql .= " where nfp.news_id = nf.news_id ";
        $sql .= " and nfp.photo_id <". $photoId;
		$sql .= " order by nfp.photo_id DESC Limit 0, 1";
		//echo $sql;
		$result = $db->query ( $sql );
		$row = $result->fetchAll ();		
		//Zend_Debug::dump($row);
		return $row;
	}
		
	public function selectGalleryPhotos ($pageNumber , $itemCountPerPage ,$limit = null) {
        $db = $this->getAdapter () ;


//		$sql = "select nfp.photo_id, nfp.photo_headline, nfp.photo_creator, nfp.photo_caption, nfp.photo_thumb_file, nfp.photo_quicklook_file,nfp.photo_quicklook_width, nfp.photo_preview_file,nf.news_date_id,nf.news_provider ";
//		$sql .= " from newsfeedphoto nfp, newsfeed nf ";
//        $sql .= " where nfp.news_id = nf.news_id ";
//        $sql .= " order by nfp.photo_id DESC";
/*
        $sql = "select nfp.photo_id, nfp.photo_headline, nfp.photo_creator, nfp.photo_caption, nfp.photo_thumb_file, nfp.photo_quicklook_file,nfp.photo_quicklook_width, nfp.photo_preview_file,nf.news_date_id,nf.news_provider ";
        $sql .= " from newsfeedphoto nfp, newsfeed nf ";
        $sql .= " where nfp.news_id = nf.news_id ";
        $sql .= " AND nf.news_revision_id = (SELECT MAX(news_revision_id ) FROM newsfeed WHERE news_afp_id = nf.news_afp_id) ";
        $sql .= " order by nfp.photo_id DESC";
*/
        $query = $this->select() 
        	->from(array('nfp' => 'newsfeedphoto'),
                    array('photo_id', 'photo_headline' ,'photo_creator','photo_caption' ,'photo_thumb_file','photo_quicklook_file','photo_quicklook_width','photo_preview_file'))
             ->join(array('nf' => 'newsfeed'),
             		'nfp.news_id = nf.news_id',
                    array('news_date_id','news_provider'))
             ->where('nf.news_revision_id = (SELECT MAX(news_revision_id ) FROM newsfeed WHERE news_afp_id = nf.news_afp_id)')
             ->order('photo_id DESC');
             
         if(!is_null($limit)){ 
            	 $query->limit($limit);
         }     
             
        $query->setIntegrityCheck(false); 
        //echo $query->__toString();
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
		$paginator->setCurrentPageNumber($pageNumber);
     	$paginator->setItemCountPerPage($itemCountPerPage);
		//echo $sql;
  		/*$result = $db->query ( $sql ) ;
  		$row = $result->fetchAll () ;*/
  		return $paginator ;
    }

    public function selectGalleryPhotoItem ($photoId) {
        $db = $this->getAdapter () ;
        $sql = "select nfp.photo_id, nfp.photo_headline, nfp.photo_creator, nfp.photo_caption, nfp.photo_thumb_file, nfp.photo_quicklook_file,nfp.photo_quicklook_width, nfp.photo_preview_file,nf.news_date_id,nf.news_provider ";
		$sql .= " from newsfeedphoto nfp, newsfeed nf ";
        $sql .= " where nfp.news_id = nf.news_id ";
        $sql .= " and nfp.photo_id = ". $photoId;
        $sql .= " order by nfp.photo_id DESC";
        $result = $db->query ( $sql ) ;
  		$row = $result->fetchAll () ;
  		//Zend_Debug::dump($row);
  		return $row ;
    }


    //temp here to work on selectGalleryPhotos
    public function selectPhotoGallery($count) 
    {
      $db = $this->getAdapter();
  		//$sql = " select news_id, news_headline, news_this_created, news_body_content, news_provider, news_afp_id" ;
  		//$sql .= " from newsfeed" ;
  		//$sql .= " order by news_this_created DESC";
  		$sql = " select ytbl.news_id, ytbl.news_headline, ytbl.news_this_created, ytbl.news_body_content, ytbl.news_provider,ytbl.news_afp_id,ytbl.news_revision_id" ;
      $sql .= " from ( select news_afp_id,max(news_revision_id ) as max_revision" ;
      $sql .= "	from newsfeed";
      $sql .= "	group by news_afp_id) as xtbl"; 
      $sql .= " inner join newsfeed as ytbl" ; 
      $sql .= "	on ytbl.news_afp_id = xtbl.news_afp_id" ;
      $sql .= "	and ytbl.news_revision_id = xtbl.max_revision"; 
      $sql .= " order by ytbl.news_this_created DESC";

  		if ($count != -1)
  		{
  			$sql .= " Limit ". $count;		
  		}
  		//echo $sql;
  		$result = $db->query ( $sql ) ;
  		$row = $result->fetchAll () ;
  		return $row ;
    }
    
    public function selectPhotosByCriteria ($criteria) {
        $db = $this->getAdapter () ;
        $sql = " select np.photo_id,np.photo_thumb_file";
        $sql .= " from newsfeedphoto np, newsfeed nf";
        $sql .= " where np.news_id = nf.news_id";
        $sql .= " and np.photo_headline LIKE  '%" . $criteria . "%' ";
        // echo $sql;
        $result = $db->query ( $sql ) ;
  		$row = $result->fetchAll () ;
  		return $row ;
	 }

      public function getPhotosForSearchResult ($photoid) {
        $db = $this->getAdapter () ;
        $sql = "select photo_id, photo_headline, photo_creator, photo_caption, photo_thumb_file ";
        $sql .= " from newsfeedphoto where photo_id = ". $photoid ;
          // echo $sql;
  		$result = $db->query ( $sql ) ;
  		$row = $result->fetchAll () ;
  		return $row ;
      }


}
	
?>
