<?php

class BlogController extends GoalFaceController {
	
	function init () {
		Zend_Loader::loadClass ( 'Blog' ) ;
		Zend_Loader::loadClass ( 'BlogPost' ) ;
		Zend_Loader::loadClass ( 'BlogPostComment' ) ;
		Zend_Loader::loadClass ( 'Zend_Filter_StripTags' ) ;
		Zend_Loader::loadClass ( 'Zend_Debug' ) ;
		Zend_Loader::loadClass ( 'Zend_Feed' ) ;
		$this->updateLastActivityUserLoggedIn();
	}
	
	function indexAction () {
	
	}
	
	function showblogsAction () {
		$view = Zend_Registry::get ( 'view' ) ;
		$view->title = "All Blogs" ;
		$blog = new Blog ( ) ;
		$blogList = $blog->showAllBlogs () ;
		
		$view->allblogs = $blogList ;
		
		$view->actionTemplate = 'blogs.php' ;
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) ) ;
	
	}
	
	public function showuserblogAction () {
		$session = new Zend_Session_Namespace('userSession');
		$view = Zend_Registry::get ( 'view' ) ;
		$view->title = "Show User Blog" ;
		$screenName = $this->_request->getParam ( 'username', $session->screenName  ) ;
		$blog = new Blog();
		$blogResult = $blog->findBlogByUserId($session->userId);
		$blogRow = $blog->find($blogResult[0]['blog_id']);
		$view->blog = $blogRow->current();
		$blogPost = new BlogPost ( ) ;
		$postPerBlog = $blogPost->findBlogPostPerBlogOrUser( $blogResult[0]['blog_id'] ) ;
		$view->blogPosts = $postPerBlog ;
		$view->screenName = $screenName ;
		$view->blogId = $blogResult[0]['blog_id'];
		$view->actionTemplate = 'viewprofileblog.php' ;
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) ) ;
	
	}
	
	public function showuserblogpostAction () {
		
		$view = Zend_Registry::get ( 'view' ) ;
		$view->title = "Show Blog Post" ;
		$id = trim ( $this->_request->getParam ( 'id' ) ) ;
		$blogpost = new BlogPost ( ) ;
		$bprow = $blogpost->find ( $id ) ;
		$rowtmp = $bprow->current () ;
		//fetch the comments per blogpost
		$blopc = new BlogPostComment();
		$blogpostcommentrows = $blopc->findCommentsByBlogPost($id , 30);
		$view->blogpostcomments = $blogpostcommentrows;
		$view->blogpost = $rowtmp ;
		$view->actionTemplate = 'viewprofileblogpost.php' ;
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) ) ;
	
	}
	
	public function showmyblogAction () {
		$view = Zend_Registry::get ( 'view' ) ;
		$view->title = "Blog Details" ;
		$session = new Zend_Session_Namespace('userSession');
		$blogpost = new BlogPost ( ) ;
		$blog = new Blog ( ) ;
		$postPerUser = $blogpost->findBlogPostPerUser ( $session->userId ) ;
		$view->postsPerUser = $postPerUser ;
		
		$userBlog = $blog->fetchRow ( 'user_id=' . $session->userId ) ;
		if ($userBlog != null) {
			$view->actionTitle = "Edit my Blog" ;
			$view->actionToDo = "edit" ;
		} else {
			$view->actionTitle = "Create my Blog" ;
			$view->actionToDo = "create" ;
		}
		
		$view->blog = $userBlog ;
		
		
		$view->actionTemplate = 'viewmyprofileblogpost.php' ;
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) ) ;
	}
	
	public function createmyblogAction () {
		
		$view = Zend_Registry::get ( 'view' ) ;
		$view->title = "Create My Blog" ;
		
		$blog = new Blog ( ) ;
		$session = new Zend_Session_Namespace('userSession');
		if ($this->_request->isPost ()) {
			$filter = new Zend_Filter_StripTags ( ) ;
			$description = trim ( $filter->filter ( $this->_request->getPost ( 'description' ) ) ) ;
			$title = trim ( $filter->filter ( $this->_request->getPost ( 'title' ) ) ) ;
			
			$data = array ( 'user_id' => $session->userId , 'description' => $description , 'title' => $title ) ;
			$whattodo = $this->_request->getPost ( 'actionToDo' ) ;
			//insert
			if ($whattodo == "create") {
				$blog->insert ( $data ) ;
				echo 'You can start writing posts now ' ;
			} else if ($whattodo == "edit") {
				$blog->update ( $data, 'user_id =' . $session->userId ) ;
				echo 'Your blog has been updated.You can start writing posts now ' ;
			}
			//update
			

			return ;
		} else
			//if exists edit
			$userBlog = $blog->fetchRow ( 'user_id=' . $session->userId ) ;
		if ($userBlog != null) {
			$view->actionTitle = "Edit my Blog" ;
			$view->actionToDo = "edit" ;
		} else {
			$view->actionTitle = "Create my Blog" ;
			$view->actionToDo = "create" ;
		}
		
		$view->blog = $userBlog ;
		$view->actionTemplate = 'blogCreateForm.php' ;
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) ) ;
	
	}
	
	public function createnewpostAction () {
		$view = Zend_Registry::get ( 'view' ) ;
		$view->title = "New Blog Post" ;
		//$contextPath = Zend_Registry::get ( "contextPath" ) ;
		$blogpost = new BlogPost ( ) ;
		$filter = new Zend_Filter_StripTags ( ) ;
		if ($this->_request->isPost ()) {
			
			$content = $this->_request->getPost ( 'content' ) ;
			echo $content ;
			$title = trim ( $filter->filter ( $this->_request->getPost ( 'title' ) ) ) ;
			$tags = trim ( $filter->filter ( $this->_request->getPost ( 'tags' ) ) ) ;
			$postreadpermission = trim ( $filter->filter ( $this->_request->getPost ( 'readPerm' ) ) ) ;
			$postcommentpermission = trim ( $filter->filter ( $this->_request->getPost ( 'commentPerm' ) ) ) ;
			$blogid = trim ( $filter->filter ( $this->_request->getPost ( 'blogid' ) ) ) ;
			$data = array ( 'blog_id' => $blogid , 'posttext' => $content , 'postcaption' => $title , 'tags' => $tags , 'postdate' => trim ( date ( "Y-m-d H:i:s" ) ) , 'postreadpermission' => $postreadpermission , 'postcommentpermission' => $postcommentpermission ) ;
			
			$whattodo = $this->_request->getPost ( 'actionToDo' ) ;
			//insert
			if ($whattodo == "create") {
				$blogpost->insert ( $data ) ;
				//echo 'Your post has been created successfully. . <a href=' . $contextPath . '/blog/createNewPost> New Post</a>' ;
				$this->_redirect ( "/blog/showMyBlog" ) ;
			} else if ($whattodo == "edit") {
				$blogpostid = $this->_request->getPost ( 'blogpostid' ) ;
				$blogpost->update ( $data, 'blogpost_id =' . $blogpostid ) ;
				//echo 'Your post has been updated . <a href=' . $contextPath . '/blog/createNewPost> New Post</a>' ;
			}
			
			return ;
		}
		$session = new Zend_Session_Namespace('userSession');
		$blog = new Blog ( ) ;
		$userBlog = $blog->fetchRow ( "user_id=" . $session->userId ) ;
		//fetech blogpost
		$blopostid = trim ( $filter->filter ( $this->_request->getParam ( "id" ) ) ) ;
		$existsblogpost = $blogpost->fetchRow ( "blogpost_id=" . $blopostid ) ;
		$view->blogpost = $existsblogpost ;
		if ($existsblogpost != null) {
			$view->actionTitle = "Edit my Post" ;
			$view->actionToDo = "edit" ;
		} else {
			$view->actionTitle = "Create new Post" ;
			$view->actionToDo = "create" ;
		}
		$view->blog = $userBlog ;
		$view->actionTemplate = 'blogPostCreateForm.php' ;
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) ) ;
	
	}
	
	/**
	 * Tu comentario va aca
	 *
	 */
	public function addblogpostcommentAction () {
		
	
		$comment = $this->_request->getPost ( "comment" ) ;
		$blogpostid = $this->_request->getPost ( "idtocomment" ) ;
		$session = new Zend_Session_Namespace('userSession');
		$comment_create = trim ( date ( "Y-m-d H:i:s" ) ) ;
		$data = array ( 'blogpost_id' => $blogpostid , 
						'comment_userid' => $session->userId , 
						'comment_text' => $comment , 
						'date' => $comment_create ,
						'ip' => $_SERVER['REMOTE_ADDR']
						) ;
		
		$postcomment = new BlogPostComment();
		$postcomment->insert ( $data ) ;
		
		$blogpost = new BlogPost();
		$blogRow = $blogpost->fetchRow("blogpost_id=".$blogpostid);
		$blogRow->num_comments = $blogRow->num_comments + 1;
		//update num of post
		$blogpost->update($blogRow->toArray(),"blogpost_id=".$blogpostid);
		
		//insert activity type
		$variablesToReplace = array('user_name1' => $session->screenName,
			         				'user_name2' => $blogpost->findPostOwner($blogpostid),
									'post_title' => $blogRow->postcaption,
									'post_id'    =>	$blogpostid
									);
		$activityType = Constants::$_ADD_BLOG_COMMENT_ACTIVITY;
		
		$activityBlogComment = new Activity();
		$activityBlogComment->insertUserActivityByActivityType($activityType,
														$variablesToReplace,
														$blogpostid);
		
		
//		echo "<dl class=\"comment\"> " ;
//		echo "<dt> " ;
//		
//		echo "<a href=\"$contextPath/profile/index/id/" . $session->userId . "\"> " ;
//		echo "<img border=\"0\" src=\"$contextPath/public/images/photos/thumbscomm/".substr($session->mainPhoto,0,strpos($session->mainPhoto , ".")) . "48x48.jpg\"/> " ;
//		echo "</a> " ;
//		echo "</dt> " ;
//		echo "<dd> " ;
//		echo "<span class=\"name\"><a href=\"$contextPath/profile/index/id/$session->userId\">".$session->screenName."</a></span>" ;
//		
//		echo "<span class=\"date\">" . $date . "</span> " ;
//		echo "<p>" . $comment . "</p> " ;
//		echo "</dd> " ;
//		echo "</dl> " ;
		$contextPath = Zend_Registry::get ( "contextPath" ) ;
		$date = date ( ' F j , Y', strtotime ( $comment_create ) ) . "&nbsp; " . date ( ' g:i a', strtotime ( $comment_create ) ) ;
		$thumbfoto = substr($session->mainPhoto,0,strpos($session->mainPhoto , "."));
		
		$newComment = "<div style='background-image:url(" .Zend_Registry::get("contextPath") ."/public/images/photos/thumbscomm/" . $thumbfoto ."48x48.jpg);background-repeat: no-repeat;background-position: 0px 10px;' class='BlogComment'>
               <a href=' " .$contextPath . "/username/" .$session->screenName."'>" . $session->screenName ."</a> <span>|</span> <a href=''>See All Comments</a>
              <br /> " .
                  $date . "
              <p> ".
              $comment  . "
            </p>
         </div>";
		
		
		echo $newComment;
		
	}
	
	public function showblogtestpageAction () {
    $view = Zend_Registry::get ( 'view' ) ;
		$view->title = "Blog Test page" ;
	
		$view->actionTemplate = 'blogTestPage.php' ;
		$this->_response->setBody ( $view->render ( 'site.tpl.php' ) ) ;
   }
	
	public function generatefeedAction(){
		
			$screenName = $this->_request->getParam ( 'username', 'unknown' ) ;
			$blogId = $this->_request->getParam ( 'id', 0 ) ;
			$blogPost = new BlogPost ( ) ;
			$postPerBlog = $blogPost->findBlogPostPerBlogOrUser($blogId) ;
		
			// base URL for generated links
            $domain = 'http://' . $this->getRequest()->getServer('HTTP_HOST');
			
			$feedData = array(
                'title'   => sprintf("%s's Blog", $screenName),
                'link'    => $domain ,
                'charset' => 'UTF-8',
                'entries' => array()
            );

            // build feed entries based on returned posts
            foreach ($postPerBlog as $post) {
//                $url = $this->getCustomUrl(
//						                    array('username' => $this->user->username,
//						                          'url' => $post->url),
//						                   		 'post'
//						                		);

                $entry = array(
                    'title'       => $post['postcaption'],
                    'link'        => $domain .'/blog/showuserblogpost/id/'. $post['blogpost_id'],
                    'description' => $post['posttext'],
                    'lastUpdate'  => strtotime($post['postdate']),
                    //'category'    => array()
                );

                // attach tags to each entry
//                foreach ($post->getTags() as $tag) {
//                    $entry['category'][] = array('term' => $tag);
//                }

                $feedData['entries'][] = $entry;
            }

            // create feed based on created data
            $feed = Zend_Feed::importArray($feedData, 'rss');

            // disable auto-rendering since we're outputting an image
            $this->_helper->viewRenderer->setNoRender();

            // output the feed to the browser
            $feed->send();
		
		
	}
	
	
	public function noRouteAction () {
		$this->_redirect ( '/' ) ;
	}

}
?>