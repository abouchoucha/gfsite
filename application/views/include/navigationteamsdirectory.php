<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>
<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>

<div class="WrapperForDropShadow">
     <ul class="leftnavlist">
         <li class="<?php echo($this->teamsMenuSelected == 'home'?'active':'noactive'); ?>">
            <a href="<?php echo $urlGen->getClubsMainUrl(TRUE) ?>" title="Teams Main Page">Teams Home</a>
         </li>
         <li class="<?php echo($this->teamsMenuSelected == 'featured'?'active':'noactive'); ?>">
            <a href="<?php echo $urlGen->getFeaturedTeamsUrl(TRUE) ?>" title="Featured Teams">Featured Teams</a>
         </li>
         <li class="<?php echo($this->teamsMenuSelected == 'popular'?'active':'noactive'); ?>">
            <a href="<?php echo $urlGen->getPopularTeamsUrl(TRUE) ?>" title="Popular Teams">Popular Teams</a>
         </li>
         <li class="<?php echo($this->teamsMenuSelected == 'headtohead'?'active':'noactive'); ?>">
            <a href="#" id="select" title="Popular Teams">Head-to-Head Match Ups</a>
         </li>
     </ul>
</div>


   <div class="WrapperForDropShadow" style="border:none;margin-bottom: 0;">
     <a href="<?php echo Zend_Registry::get("contextPath"); ?>/subscribe" title="Subscriptions and Alers">                          
         <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/banner_generic_team.png" style="margin-top:10px;"/>
  	</a>
	</div>


