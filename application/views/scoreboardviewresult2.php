 <?php $config = Zend_Registry::get ( 'config' );
    $offset = $config->time->offset->daylight; ?>
<script type="text/javascript">
jQuery(document).ready(function(){

	 jQuery("span[id^='matchHour_']").each(function() {
	  var date_time =  jQuery(this).text();
	  var date = new Date(date_time);
	  var date_time_os = calcTimeOffset(date,<?php echo $offset;?>);
	   jQuery(this).text(formatDate(date_time_os, 'HH:mm'));
	});

});
</script>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?> 

<?php
   $contcountry = 0;
   $temp1 = 'league';
   $temp2 = 'date';
 ?> 
 

<?php foreach($this->matchcount as $nrmatch){ ?>
    <!-- scoreboard Country Header grouping-->
    <div class="DropShadowHeader BrownGradientForDropShadowHeader">
        <div class="DownArrow" onclick="toggleCompetition('<?php echo str_replace(" ","",$this->escape($nrmatch["country_name"]));?>',this);"></div>
        <h4 style="background-image: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $this->escape($nrmatch["country_id"]);?>.png);" class="WithArrowToLeft WithCountryFlag">
            <a href="<?php if($nrmatch["country_id"] != 1) { echo $urlGen->getShowDomesticCompetitionsByCountryUrl($nrmatch["country_name"],$nrmatch["country_id"], true); } else { echo $urlGen->getShowRegionUrl(strval('International'), True);}   ?>">
                <?php echo $this->escape($nrmatch["country_name"]);?>
            </a>
        </h4>
        <span>
        (<?php echo $this->escape($nrmatch["matchescount"]);?><?php echo $this->escape($nrmatch["matchescount"])>1?" matches)":" match)";?>
        </span>
    </div>

    <div class="toggle_container" id="<?php echo str_replace(" ","",$this->escape($nrmatch["country_name"]));?>">
        <div class="matchesdata">
            <?php foreach($this->matchlist as $match) { ?>
                <?php   if(trim($match["country_e_id"]) == trim($nrmatch["country_e_id"])){ ?>
                    <?php	if(trim($temp2) != trim($match["country_e_id"]) . trim($match["startdate"])){
                        echo "<p class='day'>" . date ('l - F j , Y' , strtotime($match['startdate'])) . "</p>";?>
                    <?php } ?>
                    <?php if(trim($temp1) != trim($match["stage_id"]) . trim($match["country_e_id"]). trim($match["startdate"])){ ?>
                         <p class="baltic">
                            <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($match["stage_name"], $match["competition_id"], True); ?>">
                              <?php echo $this->escape($match["stage_name"]);?>
                            </a>
                          </p>
                    <?php } ?>
                    <ul>
                     <li class="teamhome">
                        <a href="">
                          <?php //echo $this->escape($match["home_team"]) ;?>
                          <?php echo $this->escape(substr($match["home_team"], 0, 20)) ;?>
                        </a>
                     </li>
                     <li class="score">
                     <?php if($match["status_text"] != 'Not started'){ ?>
                        <a href="">
                        <?php echo $this->escape($match["home_score"]);?> - <?php echo $this->escape($match["away_score"]);?>
                        </a>
                      <?php } else { ?>
                          vs.
                      <?php } ?>
                      </li>
                     <li class="teamaway"><a href="">
                      <?php echo $this->escape(substr($match["away_team"], 0, 17)) ;?>
                      </a>
                      </li>
                     <li class="final">
                        <?php if($match["status_text"] == 'Not started'){ ?>
                             
                                <?php echo $match['starttime'];?>
                            
                        <?php } else { ?>
                            <a href=""><?php echo $this->escape($match["status_text"]) ;?></a>
                        <?php } ?>
                     </li>
                    </ul>

                <?php }
             $temp1 = $match["stage_id"] . $match["country_e_id"] . $match["startdate"];
             $temp2 = $match["country_e_id"] . $match["startdate"];
       } ?>
        </div>
    </div>

<?php
$contcountry++; } ?>




