<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
<?php $config = Zend_Registry::get ( 'config' );
       $root_crop = $config->path->crop;
?>
<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>

<div class="profilee1">
    <p class="imggg2">
      <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($this->nickname, $this->playerfname, $this->playerlname, $this->playerid, true ,$this->playercommonname); ?>">
           <?php
                $config = Zend_Registry::get ( 'config' );
                $path_player_photos = $config->path->images->players . $this->playerid .".jpg" ;
                if (file_exists($path_player_photos))
            { ?>
              <img id="player<?php echo $this->playerid;?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/players/<?php echo $this->playerid; ?>.jpg"/>
          <?php } else {  ?>
              <img id="player<?php echo $this->playerid;?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=150&h=150&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/>
          <?php }  ?>
      </a>
    </p>
    <p class="posit">
    	Position: &nbsp;<?php  if (empty($this->playerpos)){echo "Unavailable"; } else {echo $this->playerpos;} ?>
    	<br>
		Club:&nbsp;
		<a href="<?php echo $urlGen->getClubMasterProfileUrl ( $this->playerteamid, $this->playerteamseoclub, True ); ?>">
                <?php echo $this->playerteamclub; ?>
         </a>
         <br>
         Nationality:&nbsp;<?php if (empty($this->playercountry)){echo "Unavailable"; } else {echo  $this->playercountry;} ?>
    </p>


   
    	<!-- <p class="im1">Add to Favorites</p>-->
   
    	<!--<p class="im2">Remove from Favorites</p>-->
   
    <p class="imfb">
    	<script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script>
    	<a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click()" target="_blank">Share on Facebook</a>
    </p>
    <p class="im3"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/rss/id/<?php echo $this->playerid; ?>">Subscribe to Updates</a></p>
    
    <!--<p class="im4"><a id="select" href="#">Head-to-Head Challenge</a></p>-->
    <p class="im4"><a class="playerHead2Head" id="selectplayer1" href="#">Head-to-Head Challenge</a></p>
    
    <p class="im5">
			<!-- AddToAny BEGIN -->
			<a class="a2a_dd" href="http://www.addtoany.com/share_save?linkurl=http%3A%2F%2Fwww.goalface.com&amp;linkname=GoalFace">Share This Profile</a>
			<script type="text/javascript">
			var a2a_config = a2a_config || {};
			a2a_config.linkname = "GoalFace - <?php echo $this->player_common_name;?> Profile";
			a2a_config.linkurl = "<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>";
			a2a_config.onclick = 1;
			a2a_config.num_services = 6;
			a2a_config.prioritize = ["facebook", "twitter", "yahoo_bookmarks", "digg", "google_bookmarks", "stumbleupon"];
			</script>
			<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
			<!-- AddToAny END -->

    </p>
</div>



