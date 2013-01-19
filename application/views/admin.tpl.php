<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name='description' content="<?php echo $this->escape($this->description); ?>" />
<meta name='keywords' content="<?php echo $this->escape($this->keywords); ?>" />
<title><?php echo $this->escape($this->title); ?></title>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery-1.2.6.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.dimensions.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.hoverIntent.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.cluetip.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jqModal.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.autocomplete.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/ui.core.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/ui.tabs.js" type="text/javascript"></script>
<script type="text/JavaScript">

	jQuery.noConflict();

</script>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/layouts.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/goalface.css' rel="stylesheet" type="text/css" media="screen"/>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/jqModal.css' rel="stylesheet" type="text/css" media="screen"/>
</head>

<body  Zbody class="home">
  <div id="wrapper">

    <?php include 'adminheader.php';?>

    <?php echo $this->render($this->actionTemplate); ?>

    
  </div><!--end wrapper-->
</body>
</html>
