<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'  
xmlns:b='http://www.google.com/2005/gml/b'  
xmlns:data='http://www.google.com/2005/gml/data'  
xmlns:expr='http://www.google.com/2005/gml/expr'  
xmlns:fb='http://www.facebook.com/2008/fbml'  
xmlns:og='http://opengraph.org/schema/'>
<body>
<?php $view = Zend_Registry::get ( 'view' ); ?>
<html>
<head>
<title><?php echo $this->escape($this->title); ?></title>
<meta name='description' content="<?php echo $this->escape($this->description); ?>" />
<meta name='keywords' content="<?php echo $this->escape($this->keywords); ?>" />
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/goalface.css' rel="stylesheet" type="text/css" media="screen"/>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery-1.2.6.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/validate.js" type="text/javascript"></script>

</head>

<body class="home">
<div id="wrapper">

    <?php include 'headerUserRegister.php';?>

    <?php echo $this->render($this->actionTemplate); ?>
    
	<?php //include 'footerUserRegistered.php';?>
  </div><!--end wrapper-->
</body>
</html>
