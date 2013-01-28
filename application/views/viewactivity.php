<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php';
    $urlGen = new SeoUrlGen(); 
    $config = Zend_Registry::get ( 'config' );
    $session = new Zend_Session_Namespace('userSession'); 
    $tempDate = '1234';
    $today = date ( "m-d-Y" ) ;
    $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'  
xmlns:b='http://www.google.com/2005/gml/b'  
xmlns:data='http://www.google.com/2005/gml/data'  
xmlns:expr='http://www.google.com/2005/gml/expr'  
xmlns:fb='http://www.facebook.com/2008/fbml'  
xmlns:og='http://opengraph.org/schema/'>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="og:type" content="website" />
<meta property="fb:admins" content="500033921"/> 
<meta property="og:title" content="<?php echo $this->escape($this->title); ?>" />
<meta property="og:description" content="<?php echo $this->escape($this->description); ?>" />
<meta property="og:image" content="<?php echo $this->escape($this->imagefacebook); ?>"/>

<?php 
	$pageURL = 'http';
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
?>
<meta property="og:url" content="<?php echo $pageURL; ?>" />
</style>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery-1.3.2.js" type="text/javascript"></script>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "bf8f5586-8640-4cce-9bca-c5c558b3c0a1"});</script>
</head>
	<body>
			
		<div id="ActivityDetail">
			<div style="float:left;">
					<img src="<?php echo $this->activityimage;?>" alt="" />
			</div>
			<div style="float:left";>
                  <p><?php
                  if($today == date('m-d-Y' , strtotime($this->activity->activity_date))){
                  	echo 'Today at ' . date("H:i" , strtotime($this->activity->activity_date)) ;
                  }else if($yesterday == date('m-d-Y' , strtotime($this->activity->activity_date))){
                  	echo 'Yesterday at ' . date("H:i" , strtotime($this->activity->activity_date)) ;
                  }else {
                  	echo date(' F j' , strtotime($this->activity->activity_date)) ;
                }?>
            	</p>
	             <p> <?php echo $this->activity->activity_text; ?></p>
	                <span class='st_sharethis_hcount' displayText='ShareThis' st_image='<?php echo $this->escape($this->imagefacebook); ?>'></span>
					<span class='st_facebook_hcount' displayText='Facebook'></span>
					<span class='st_fblike_hcount' displayText='Facebook Like'></span>
					<span class='st_twitter_hcount' displayText='Tweet'></span>
					<span class='st_pinterest_hcount' displayText='Pinterest'></span>
					<span class='st_email_hcount' displayText='Email'></span>
	        
			</div>
		</div>
	

	</body>
</html>
