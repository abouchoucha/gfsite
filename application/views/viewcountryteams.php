<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
     <?php require_once 'Common.php'; ?>
      <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>

<ul id="BreadCrumb">
  <li>
  <a href="/">Home</a></li>
  <li class="last">
    Clubs </li>
</ul>
<div id="ContentWrapper" class="TwoColumnLayout">
  <div class="FirstColumn">
<?php 
        $session = new Zend_Session_Namespace('userSession');
        if($session->email != null){
    ?>
    <div class="img-shadow">
      <div class="WrapperForDropShadow">
        <?php include 'include/loginbox.php';?> 
      </div>
    </div>
    <div class="img-shadow">
      <div class="WrapperForDropShadow">
        <div id="Feedback">
          <strong>Feedback</strong><br />
          <a href="<?php echo Zend_Registry::get("contextPath"); ?>/feedback/">Tell us what you think</a> about GoalFace.  We'd love to hear what you have to say.
        </div>
      </div>
    </div>
    <?php }else { ?> 
    <!--Me box Non-authenticated-->
    <div class="img-shadow">
      <div class="WrapperForDropShadow">
        <?php include 'include/loginNonAuthBox.php';?>
      </div>
    </div>  
    <!--Goalface Register Ad-->
    <div class="img-shadow">
      <div class="WrapperForDropShadow">
        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register">
          <img class="JoinNowHome" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" /></a>
      </div>
    </div>
    <?php } ?>

  </div>
  <!--end FirstColumnOfThree-->
  <div class="SecondColumn" id="SecondColumnHighlightBox">
    <h1>Clubs</h1>
    
    <div id="SecondColumnHighlightBoxContent" class="RegionContentListContainer">

        <p>
        <?php foreach($this->teams as $listteams) { ?>
          <a href="<?php echo $urlGen->getClubMasterProfileUrl($listtems['team_id'],$listteams['team_name'], True); ?>" title="<?php echo $listteams['team_name']; ?>">
          <?php echo $listteams['team_name']; ?>
          </a> <BR>
        <? } ?>
        </p>
       
    </div>
    <!--endSecondColumnHighlightBoxContent --> 
    <div id="SecondColumnHighlightBoxContentBottomImage">
    </div>
  </div>
  <!--end SecondColumnOfTwo and #SecondColumnHighlightBox--> 
</div>
<!--end ContentWrapper-->     
