<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery-1.2.6.js" type="text/javascript"></script>
<html>
<head>
<title><?php echo $this->escape($this->title); ?></title>
<meta name='description' content="<?php echo $this->escape($this->description); ?>" />
<meta name='keywords' content="<?php echo $this->escape($this->keywords); ?>" />
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/goalface.css' rel="stylesheet" type="text/css" media="screen"/>
</head>

<body  Zbody class="home">
<div id="wrapper">

    <?php include 'headerExternalNews.php';?>

    <?php echo $this->render($this->actionTemplate); ?>
    
  </div><!--end wrapper-->
</body>
</html>
