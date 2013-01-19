<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
<?php $config = Zend_Registry::get ( 'config' );
       $root_crop = $config->path->crop;
?>
      <div class="WrapperForDropShadow">
        <div class="UpClose">
          <?php if ($this->filename!= null or $this->filename!= '') { ?>
            <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($this->playernickname, $this->playerfname, $this->playerlname, $this->playerid, true ,$this->playercommonname); ?>">
             <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=180&h=230&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop; ?>/players/<?php echo $this->filename ?>"/>
            </a>
          <?php  } else { ?>
            <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($this->playernickname, $this->playerfname, $this->playerlname, $this->playerid, true ,$this->playercommonname); ?>">
              <img class="nophoto" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=180&h=180&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop; ?>/Player1Text.gif" />
            </a>
          <?php  } ?>
        <div class="profilee1">
	        <p class="im5">
	    		<script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script>
			    	<a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click()" target="_blank">Share on Facebook</a>
		    </p>
		    <p class="im3"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/rss/id/<?php echo $this->playerid; ?>">Subscribe to Updates</a></p>
		    <p class="im4"><a id="select" href="#">Head-to-Head Challenge</a></p>
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
          
          <div class="UpCloseData">
            <br>
              <strong>Position:</strong>
              <?php  if (empty($this->playerpos)){echo "Unavailable"; } else {echo $this->playerpos;} ?>
              <br>
              <strong>Club:</strong>
              <a href="<?php echo $urlGen->getClubMasterProfileUrl ( $this->playerteamid, $this->playerteamseoclub, True ); ?>">
                <?php echo $this->playerteamclub; ?>
              </a>
              <br>
              <strong>Nationality:</strong>
              <?php if (empty($this->playercountry)){echo "Unavailable"; } else {echo  $this->playercountry;} ?>
	             <br><br>
              <span class="title"><strong>Current League Stats</strong></span><br>

             <span>Games Played: </span>
             <?php if ($this->gamesplayed == 0  and $this->glscored > 0) { ?>
                    Unvailable<br>
             <?php } else { ?>
                <?php echo $this->gamesplayed; ?><br>
             <?php }  ?>

             <span>Goals Scored: </span>
             <?php echo $this->glscored; ?><br>

			 <span>Assists: </span>
             <?php echo $this->as; ?><br>

             <span>Yellow Cards: </span>

            <?php echo $this->yc; ?><br>


             <span>Red Cards: </span>

            <?php echo $this->rc; ?><br>

             <?php //echo $urlGen->getPlayerMasterProfileStatsUrl($this->playernickname, $this->playerfname, $this->playerlname, $this->playerid, true); ?>

          </div>
        </div>
      </div>