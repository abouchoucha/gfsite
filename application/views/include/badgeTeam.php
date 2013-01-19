<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $root_crop = $config->path->crop;
 ?>
  
  <div class="WrapperForDropShadow">
      <div class="UpClose">
         <?php
                $config = Zend_Registry::get ( 'config' );
                $path_team_logos = $config->path->images->teamlogos . $this->teamid .".gif" ;
                if (file_exists($path_team_logos))
            { ?>
             <a title="<?php echo $this->teamname;?>" href="<?php echo $urlGen->getClubMasterProfileUrl($this->teamid,$this->teamseoname, True); ?>">
              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=160&h=160&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop; ?>/teamlogos/<?php echo $this->teamid ; ?>.gif"/>
             </a>
          <?php } else {  ?>
            <a title="<?php echo $this->teamname;?>" href="<?php echo $urlGen->getClubMasterProfileUrl($this->teamid,$this->teamseoname, True); ?>">
              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText.gif">
            </a>
          <?php }  ?>
        <div class="UpCloseData">
          <ul>
            <li>
              <strong>Country:</strong>
              <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->countryname,$this->countryid, true); ?>">
                <?php echo $this->countryname; ?>
              </a>
            </li>

            <?php $i = 1;
                    foreach ($this->competitionactive as $data) {
                  ?>
                  <?php if ($i == 1) {?>
                    <li>
                <strong>League:</strong>
                            <?php if($data["competition_type"] == 0) { ?>
                                   <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($data["competition_name"], $data["competition_id"], True); ?>"><?php echo $data["competition_name"]; ?></a>
                            <?php } elseif ($data["competition_type"] == 1) {?>
                                   <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($data["competition_name"], $data["competition_id"], True); ?>"><?php echo $data["competition_name"]; ?></a>
                            <?php } ?>
                          </li>
            <?php } elseif ($i == 2) { ?>
              <li>
                <strong>Other Competitions:</strong><br>
                <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($data["competition_name"], $data["competition_id"], True); ?>"><?php echo $data["competition_name"]; ?></a>
                <br>
             <?php } elseif ($i >= 3) { ?>
                <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($data["competition_name"], $data["competition_id"], True); ?>"><?php echo $data["competition_name"]; ?></a>
               <br>
             <?php }  ?>
              </li>
            <?php $i++; } ?>
          </ul>

          <ul>
            <li>
            <strong>Official Website:</strong>
            <a href="<?php echo $this->teamurl; ?>" onclick="window.open('<?php echo $this->teamurl; ?>'); return false;">website</a>
            </li>
          </ul>
           <!-- <p class="im1">Add to Favorites</p>
		    <p class="im2">Remove from Favorites</p>-->
		    
		    <p class="im5" ><script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script><a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click()" target="_blank">Share on Facebook</a></p>
		    <p class="im3"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/rss/id/<?php echo $this->playerid; ?>">Subscribe to Updates</a></p>
		    <p class="im4">Head-to-Head Challenge</p>
		    <p class="im5">
					<!-- AddToAny BEGIN -->
					<a class="a2a_dd" href="http://www.addtoany.com/share_save?linkurl=http%3A%2F%2Fwww.goalface.com&amp;linkname=GoalFace">Share This Profile</a>
					<script type="text/javascript">
					var a2a_config = a2a_config || {};
					a2a_config.linkname = "GoalFace - <?php echo $this->teamname;?> Profile";
					a2a_config.linkurl = "<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>";
					a2a_config.onclick = 1;
					a2a_config.num_services = 6;
					a2a_config.prioritize = ["facebook", "twitter", "yahoo_bookmarks", "digg", "google_bookmarks", "stumbleupon"];
					</script>
					<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
					<!-- AddToAny END -->
		
		    </p>
          <div style="clear:both"></div>
        </div>

      </div>
    </div>