<?php require_once 'seourlgen.php';
    $common = new Common();
    $urlGen = new SeoUrlGen(); 
    $config = Zend_Registry::get ( 'config' );

    $server = $config->server->host;

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
  xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
  itemscope itemtype="http://schema.org/Article">
  
  <head profile="http://www.w3.org/1999/xhtml/vocab"  prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# 
                  article: http://ogp.me/ns/article#"   >

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo $this->escape(strip_tags($this->activity->activity_text)); ?>" />

<!--- Google -->
<!-- Add the following three tags inside head. -->
<meta itemprop="name" content="<?php echo $this->escape($this->activitytitle); ?>">
<meta itemprop="description" content="<?php echo $this->escape(strip_tags($this->activity->activity_text)); ?>">
<meta itemprop="image" content="http://<?php echo $config->path->index->server->name; ?><?php echo $this->escape($this->imagefacebook); ?>">

<!--- Facebook Open Graph -->
<meta property="og:type" content="article" /> 
<meta property="fb:admins" content="500033921"/> 
<meta property="og:title" content="<?php echo $this->escape($this->activitytitle); ?>" />
<meta property="og:image" content="http://<?php echo $config->path->index->server->name; ?><?php echo $this->escape($this->imagefacebook); ?>"/>
<meta property="og:description" content="<?php echo $this->escape(strip_tags($this->activity->activity_text)); ?>" />
<meta property="og:url" content="<?php echo $pageURL; ?>" /> 

<meta name="google-site-verification" content="IimGcUJQ1N1Fr6nwjo0nsjWPXcSPJdnLU37z8ivy7pU" />
<meta http-equiv="Content-Script-Type" content="text/tcl">
<?php if ($server == 'notfornow') { ?>
 <meta name="verify-v1" content="cuRLxie74zjP5P9S7YGeoQzlyBGzf1iUjBuXGa4a8nw=" />
<?php  }  ?>

<title><?php echo $this->escape($this->activitytitle); ?> | GoalFace</title>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/layouts.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/goalface.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/menu.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/jqModal.css' rel="stylesheet" type="text/css" media="screen"/>

<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery-1.6.4.min.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery_cookie.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/xhtml/menu.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.hoverIntent.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jqModal.js" type="text/javascript"></script>
</head>

<body  Zbody class="home">
  <div id="wrapper">

  	<?php //include 'headerUserRegister.php';?>
    <?php include 'header.php';?>

    <?php echo $this->render($this->actionTemplate); ?>

    <?php include 'footer.php';?>
 
  </div><!--end wrapper-->
</body>
</html>
