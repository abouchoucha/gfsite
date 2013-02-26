<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'  
xmlns:b='http://www.google.com/2005/gml/b'  
xmlns:data='http://www.google.com/2005/gml/data'  
xmlns:expr='http://www.google.com/2005/gml/expr'  
xmlns:fb='http://www.facebook.com/2008/fbml'  
xmlns:og='http://opengraph.org/schema/'>
<head> 
<?php require_once 'browserdetection.php';
 	$a_browser_data = browser_detection('full');
 	if ( $a_browser_data[0] == 'ie' &&  ( $a_browser_data[1] <= 6 ) ){
		?>
		<script language="javascript" >alert('Browser Not supported .Please Upgrade'); </script>
<?php exit;}	?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/tcl">

<meta name='description' content="<?php echo $this->escape($this->description); ?>" />
<meta name='keywords' content="<?php echo $this->escape($this->keywords); ?>" />
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

<meta name="google-site-verification" content="IimGcUJQ1N1Fr6nwjo0nsjWPXcSPJdnLU37z8ivy7pU" />
<meta http-equiv="Content-Script-Type" content="text/tcl">
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    if ($server == 'notfornow') {
?>
 <meta name="verify-v1" content="cuRLxie74zjP5P9S7YGeoQzlyBGzf1iUjBuXGa4a8nw=" />
<?php  }  ?>

<title><?php echo $this->escape($this->title); ?></title>
<link rel="shortcut icon" href="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/favicon.ico" type="image/x-icon"/>  
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/layouts.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/goalface.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/menu.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/jqModal.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/jquery.cluetip.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/jquery-ui-1.8.16.custom.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/jquery.loadmask.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/fb-traffic-pop.css' rel="stylesheet" type="text/css" media="screen"/> 
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    if ($server == 'local') {
?>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery-1.6.4.min.js" type="text/javascript"></script>
<?php  } else { ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
<?php  } ?>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/xhtml/menu.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.hoverIntent.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.cluetip.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jqModal.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.autocomplete.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/easyTooltip.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/validate.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/date.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/detect_timezone.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jstz.min.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.loadmask.min.js" type="text/javascript"></script>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/fb-traffic-pop.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.cookie.js" type="text/javascript"></script>



<?php
    if ($server == 'beta') {
?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-6160648-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php  } ?>


</head>

<body class="home">


  <div id="wrapper">

    <?php include 'header.php';?>

    <?php echo $this->render($this->actionTemplate); ?>
    
    <?php include 'footer.php'; ?>
 
  </div><!--end wrapper-->
  <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    if ($server != 'beta') {
  ?>
  <!-- Show something here -->

  <?php } ?>

</body>
</html>
