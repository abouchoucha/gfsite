<?php $session = new Zend_Session_Namespace('userSession'); ?>
<?php require_once 'seourlgen.php';
 	$urlGen = new SeoUrlGen();?>
<?php require_once 'Common.php'; ?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 
<script type="text/JavaScript">
jQuery(document).ready(function() {
    jQuery('.tabscontainer').hide();
    jQuery('div.cont').hide();
    jQuery('#tab1AllContent').show();
    jQuery('#tab2AllContent').hide();
    jQuery('#tab1content').show();     
    jQuery('#tab1').addClass('active');
	  //Default load all matches stats
  	findMatchPlayerStats(0);
  	findPlayerSeasonStats(1);
 	  jQuery('#statTab ul li').click(function(){
  		 jQuery('#tab1AllContent,#tab2AllContent').hide();
  		 tab_id = jQuery(this).attr('id');  		 
 	     //show div content
       	 jQuery('#' + tab_id + 'AllContent').show();
         jQuery('#tab1,#tab2').removeClass('active');
         jQuery(this).addClass('active');
 	  });

	});	

function findMatchPlayerStats(teamid) {
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showmatchplayerstats/detail/yes/id/<?php echo $this->playerid;?>/team/'+teamid;
	jQuery('#data').html("<div class='ajaxloadmodule'></div>"); 
	jQuery('#data').load(url);
}   

function findPlayerSeasonStats(formatType) {
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerministats/playerId/<?php echo $this->playerid;?>/format/'+formatType;
	jQuery('#ajaxdata').html("<div class='ajaxloadmodule'></div>"); 
	jQuery('#ajaxdata').load(url);
}
</script>



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
                    
                    <?php }else { ?>
                    
                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>
                    
                    <!--Goalface Register Ad-->
           
                    <?php } ?>

              		<!--Player Profile Badge-->

                        <?php echo $this->render('include/badgePlayerNew.php');?>

                  <!--Player Profile left Menu-->
                 <div class="img-shadow" style="margin-left:2px;margin-top:10px;">
          			<?php echo $this->render('include/navigationPlayerNew.php');?>
        		</div>


                <?php  if($session->email == null){  ?>
             <!--Goalface Join Now-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                        <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
                           <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/join_now_green.jpg" style="margin-bottom:-3px;"/>
                        </a>
                        </div>
                    </div>
                <?php  } ?>


          </div><!--end FirstColumnOfThree-->
                
        <div id="SecondColumnStats" class="SecondColumn">
            <div class="ammid" style=" padding-left: 10px;padding-top: 10px;">
            <?php if ($session->email != null) { ?>
                <?php if ($this->isFavorite == 'false') { ?>
 						        <a id="btn_player_on_<?php echo $this->playerid;?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $this->playerid;?>);">
                    			<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribeprofile.png" alt="Subscribe to <?php echo $this->playername;?>'s updates">
                    		</a>
                    		<a id="btn_player_off_<?php echo $this->playerid;?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $this->playerid;?>);">
                    			<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribeprofile.png" alt="Unsubscribe to <?php echo $this->playername;?>'s updates">
                    		</a>
                          <?php } else { ?>
                        	<a id="btn_player_off_<?php echo $this->playerid;?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $this->playerid;?>);">
                    			<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribeprofile.png" alt="Unsubscribe to <?php echo $this->playername;?>'s updates">
                    		</a>
                    		<a id="btn_player_on_<?php echo $this->playerid;?>" class="subscribe  ScoresClosed" href="javascript:" onclick="subscribeToPlayer(<?php echo $this->playerid;?>);">
                    			<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribeprofile.png" alt="Subscribe to <?php echo $this->playername;?>'s updates">
                    		</a>
                    <?php }  ?>
						<?php } else { ?>
						<a id="btn_playerid_<?php echo $this->playerid;?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo  $this->playerid;?>);">
            	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribeprofile.png" alt="Subscribe to <?php echo $this->player_name_short;?>'s updates">
            </a>
						<?php } ?>
								<div class="PlayerInfoWrapper DetailStatHeader">
									<div class="PlayerInfo">
							      <div class="PlayerHighLevel"><!--Player high level-->
                    <!-- set the background image to show the logo in the right corner, the image is positioned in the css-->
                        <div style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->playerteamid;?>.gif')" >
                            <p style="font-size: 24px;margin-bottom: 5px;margin-top: 5px;"><?php echo $this->playername; ?>&nbsp;<?php echo $this->jerseyclub; ?></p>
                            <ul class="PersonalInfo">
                                <li>Position:<span><?php if (empty($this->playerpos)){echo 'Unavailable'; } else {echo $this->playerpos;} ?></span></li>
                                <li>Height:<span><?php if (empty($this->playerheight)){echo "&nbsp;Unavailable"; } else {echo $this->playerheight . "&nbsp;cm";}?></span></li>
                                <li>Weight:<span><?php if (empty($this->playerweight)){echo "&nbsp;Unavailable"; } else {echo $this->playerweight . "&nbsp;kg" ;}?></span></li>
                            </ul>
                            <ul class="PersonalInfo">
                                <li>Club:<span><?php echo $this->playerteamclub; ?></span></li>
                                <li>Date of Birth:<span><?php echo $this->playerdob; ?></span></li>
                                <li>Place of Birth:<span><?php echo $this->playerdobcity; ?></span></li>
                            </ul>                                           
                        </div>
										</div>
									</div>
								</div>

                <div id="statTab" class="tabs">
        					<ul>
        						<li id="tab1"><a href="javascript:void(0);">Match Statistics</a></li>
                    <li id="tab2"><a href="javascript:void(0);">Season Statistics</a></li>
        					</ul>
        				</div>
                
                <div id="tab1AllContent" class="tabscontainer nosideborder">
                   <span class="show">Show:&nbsp;&nbsp;</span>
                   <p class="all">
                    	
                      	<select id="playermatchstatsdropdown" class="all" onchange="javascript:findMatchPlayerStats(this.value)">
                         	<option value="0" selected>All</option>
                          <?php $team_type = "";  ?>
                          <?php foreach ($this->teamselect as $teamdata) {  ?>  
                							<?php if ($teamdata['team_other_type'] != $team_type) { ?> <!-- If type has changed -->
                								<?php if ($team_type != "") { ?>  <!-- If there was already a type active -->
                								  <?php  echo "</optgroup><option></option>"; ?> 
                								<?php  } ?> 
                								<optgroup label="<?php if($teamdata['team_other_type'] =='club') {echo "Club Teams";} else {echo "National Teams";} ?>"> <!-- open a new group -->
                								<?php  $team_type = $teamdata['team_other_type']; ?>
                                            <?php  } ?>  
                							<option value="<?php echo $teamdata["team_other_id"]; ?>" ><?php echo $teamdata["team_other_name"]; ?></option>
                          <?php  } ?>
                            </optgroup>
                       </select>
                     </p>
 
									<div id="data" class="statdata1">

                 	</div>
                 	
                     <!-- End Tab Player Wrapper  -->
                </div>
                
                <div id="tab2AllContent" class="tabscontainer" >
                    
                  <div class="PlayerInfoWrapperSeason DetailStatSubHeader">
										<div class="PlayerInfoStat">
											
                        	<p style="font-size: 24px;margin-bottom: 5px;margin-top: 5px;">
                        	    <?php //echo $this->playername; ?>&nbsp;Current Season Stats
                        	</p>
      										<div class="PersonalInfoStat">
      											<ul>
                                <li>
                                	<span>Games</span>
                                </li>
                                     <li>
                                	<span><?php if (empty($this->season_appearences)){echo "0"; } else {echo $this->season_appearences;}?></span>
                                </li>
                            </ul>
      										</div>
      										<div class="PersonalInfoStat">
      											<ul>
                                <li>
                                	<span>Mins</span>
                                </li>
                                     <li>
                                	<span><?php if (empty($this->season_minutes)){echo "0"; } else {echo $this->season_minutes;}?></span>
                                </li>
                            </ul>
      										</div>
      										<div class="PersonalInfoStat">
      											<ul>
                                <li>
                                 <?php if ($this->playerpos == 'Goalkeeper') {  ?>
                                		<span>GA</span>
                                	<?php } else {  ?>
                                		<span>Goals</span>
                                	<?php }  ?>
                                	
                                </li>
                                 <li>
                                    	<?php if ($this->playerpos == 'Goalkeeper') {  ?>
                                    		<span><?php if (empty($this->season_goals_allowed)){echo "0"; } else {echo $this->season_goals_allowed;}?></span>
                                    	<?php } else {  ?>
                                    		<span><?php if (empty($this->season_goals)){echo "0"; } else {echo $this->season_goals;}?></span>
                                    	<?php }  ?>
                                	</li>
                            </ul>                         
      										</div> 
      										
      											<?php if ($this->playerpos == 'Goalkeeper') {  ?>
                            	<div class="PersonalInfoStat">
                            		<ul>
                                	 <li>
                                    	<span>CS</span>
                                    </li>
                                    	<li><span><?php if (empty($this->season_clean_sheets)){echo "0"; } else {echo $this->season_clean_sheets;}?></span></li>
                                   
                                   </ul>
                                </div>
                             <?php }  ?>
      										
      										
      										<div class="PersonalInfoStat">
      											<ul>
                                <li>
                                	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/stat_yc.gif">
                                </li>
                                     <li>
                                	<span><?php if (empty($this->season_yellowcards)){echo "0"; } else {echo $this->season_yellowcards;}?></span>
                                </li>
                            </ul>
      										</div> 
      										<div class="PersonalInfoStat">
      											<ul>
                                <li>
                                	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/stat_rc.gif">
                                </li>
                                     <li>
                                	<span><?php if (empty($this->season_redcards)){echo "0"; } else {echo $this->season_redcards;}?></span>
                                </li>
                            </ul>
      										</div>                      
                    </div>
                  </div>
                    
                    <span class="show">Show:&nbsp;&nbsp;</span>
             					<p class="all">

                         <select id="typeComp" class="slct" name="typeComp" onchange="javascript:findPlayerSeasonStats(this.value);">
                             <option selected="selected" value="1">League</option>
                             <option value="2">Regional Competition</option>
                             <option value="3">National Team</option>
                         </select>
                        
                     </p>
                   
                   
                   <div id="ajaxdata" class="statdata">

                 	</div>
                   
                </div>

         </div>           
      </div><!--/SecondColumn--> 
</div>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeplayer.js" type="text/javascript"></script>