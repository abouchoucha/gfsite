<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>
<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
<div class="WrapperForDropShadow">
     <ul class="leftnavlist">
         <li class="<?php echo($this->playersMenuSelected == 'home'?'active':'noactive'); ?>">
            <a href="<?php echo $urlGen->getPlayersMainUrl(TRUE) ?>" title="Players Home">Players Home</a>
         </li>
         <li class="<?php echo($this->playersMenuSelected == 'featured'?'active':'noactive'); ?>">
            <a href="<?php echo $urlGen->getFeaturedPlayersUrl(TRUE) ?>" title="Featured Players">Featured Players</a>
         </li>
         <li class="<?php echo($this->playersMenuSelected == 'popular'?'active':'noactive'); ?>">
            <a href="<?php echo $urlGen->getPopularPlayersUrl(TRUE) ?>" title="Most Popular Players">Most Popular Players</a>
         </li>
        <li class="<?php echo($this->playersMenuSelected == 'directory'?'active':'noactive'); ?>">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayersbyalphabet/letter/A" title="Player Directory">Player Directory</a>
         </li>
          <li class="<?php echo($this->playerMenuSelected == 'headtohead'?'active':'noactive'); ?>">
            <a href="#" id="select1">Head-to-Head Challenge</a>
         </li>
     </ul>
</div>

   <div class="WrapperForDropShadow" style="border:none;margin-bottom: 0;">
     <a href="<?php echo Zend_Registry::get("contextPath"); ?>/subscribe" title="Subscriptions and Alers">                          
         <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/banner_generic_player.png" style="margin-top:10px;margin-bottom:0px;"/>
  	</a>
	</div>
