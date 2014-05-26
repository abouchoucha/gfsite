<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php';
    $common = new Common();
    $urlGen = new SeoUrlGen(); 
    $config = Zend_Registry::get ( 'config' );
    $session = new Zend_Session_Namespace('userSession'); 
    $tempDate = '1234';
    $today = date ( "m-d-Y" ) ;
    $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;

    $pageURL = 'http://';
    if ($_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML+RDFa 1.1//EN">
<html xmlns:fb="http://ogp.me/ns/fb#" lang="es" dir="ltr" version="HTML+RDFa 1.1"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:dc="http://purl.org/dc/terms/"
  xmlns:foaf="http://xmlns.com/foaf/0.1/"
  xmlns:og="http://ogp.me/ns#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
  xmlns:sioc="http://rdfs.org/sioc/ns#"
  xmlns:sioct="http://rdfs.org/sioc/types#"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:xsd="http://www.w3.org/2001/XMLSchema#">
  
  <head profile="http://www.w3.org/1999/xhtml/vocab"  prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# 
                  article: http://ogp.me/ns/article#"   >

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo $this->escape(strip_tags($this->activity->activity_text)); ?>" />


<!--- Facebook Open Graph -->
<meta property="og:type" content="website" /> 
<meta property="fb:admins" content="500033921"/> 
<meta property="og:title" content="<?php echo $this->escape($this->activitytitle); ?>" />
<meta property="og:image" content="http://<?php echo $config->path->index->server->name; ?>/<?php echo $this->escape($this->activityimage); ?>"/>
<meta property="og:description" content="<?php echo $this->escape(strip_tags($this->activity->activity_text)); ?>" />
<meta property="og:url" content="<?php echo $pageURL; ?>" /> 


<title><?php echo $this->escape($this->activitytitle); ?> | GoalFace</title>

<script type="text/javascript" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery-1.3.2.js"></script>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "bf8f5586-8640-4cce-9bca-c5c558b3c0a1"});</script>
</head>

<body>

<?php //echo $pageURL; ?>
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
	                <span class='st_sharethis' 
                        displayText='ShareThis' 
                        st_image='http://<?php echo $config->path->index->server->name; ?>/<?php echo $this->escape($this->activityimage); ?>'>
                  </span>
        					<span class='st_facebook_button' displayText='Share'></span>
        					<span class='st_fblike_button' displayText='Facebook Like'></span>
        					<span class='st_twitter_button' displayText='Tweet'></span>
        					<span class='st_pinterest_button' displayText='Pinterest'></span>
                  <span class='st_plusone_button' st_url="<?php echo $pageURL; ?>"></span>

			</div>
     <div style="float:left";>
        <?php 
                //$stTitle = $activity["activitytype_name"];
                $stTitle = strip_tags($this->activity->activity_text);
                $stURL = $pageURL;
                $stImage = 'http://' . $config->path->index->server->name . $this->escape($this->activityimage);
                $social_icons_images = 'http://' . $config->path->index->server->name ."/public/images/icons/social/";



              $args = array(
                'image_size' => 24
               ,'image_path' => $social_icons_images
               ,'separator'  => '<br />'
               ,'url' => $stURL
               ,'title' => $stTitle 
               ,'media_url' => $stImage
               ,'twitter_username' => 'goalface'
               ,'platforms' => array('facebook','twitter','pinterest','google-plus')
              );

               echo $common->social_links($args);
            ?>


		</div>
	

	</body>
</html>
