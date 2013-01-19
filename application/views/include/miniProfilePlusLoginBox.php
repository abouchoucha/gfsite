<?php $session = new Zend_Session_Namespace('userSession');?>

<div class="img-shadow">
  <?php if ($session->isMyProfile == 'n'){?>
  <div class="WrapperForDropShadow">
  <?php echo $this->render('include/loginbox.php'); ?>
  </div>
  <?php } ?>
 <div class="WrapperForDropShadow">
     <div id="Feedback">
         <strong>Feedback</strong>
         <br />
         <a href="<?php echo Zend_Registry::get("contextPath"); ?>/feedback">Tell us what you think</a> about GoalFace.  We'd
         love to hear what you have to say.
     </div>
 </div>
</div>
<!-- START Profile Box Include -->
<?php echo $this->render('include/miniProfileBox.php'); ?>
<!-- END Profile Box Include -->	