<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $root_crop = $config->path->crop;
 ?> 
 
<script type="text/JavaScript"> 

 var contextPath = "<?php echo Zend_Registry::get("contextPath"); ?>";
 var root_crop = "<?php echo $root_crop;?>";
 </script>
 <script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/popup.js" type="text/javascript"></script>


<script type="text/JavaScript"><!--
	jQuery(document).ready(function() {
	
		var defaulttab = '<?php echo $this->default; ?>';
		if(defaulttab == 'scores'){
			showScoresScheduleTab('scoresTab');
		}else if(defaulttab == 'schedules'){
			showScoresScheduleTab('schedulesTab');
		}
		jQuery('#scoresTab').click(function() {
			showScoresScheduleTab('scoresTab');
		  });
		jQuery('#schedulesTab').click(function() {
			showScoresScheduleTab('schedulesTab');
		}); 
		jQuery('#seasonId').change(function() {
			var page = 'scores';
			var seasonId = jQuery("#seasonId").val();	
			var leagueid = '<?php echo $this->leagueId; ?>';
			top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showfullscoreboard/leagueid/'+leagueid+'/seasonid/'+seasonId +'/sm/fs';
		}); 

		

	function showScoresScheduleTab(tab){
		var page = null;
		var date = null;
		if(tab == ''){ 
			tab = 'scoresTab';
			page = 'scores';
		}
		if(tab == 'scoresTab'){
			page = 'scores';
		}else if(tab == 'schedulesTab'){
			page = 'schedules';
			
		}
	
		 var seasonId = '<?php echo $this->seasonId; ?>';
		 var roundid = '<?php echo $this->roundId; ?>';
		 var url = null;
		 
	     if(roundid != ''){
			 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showfullmatchesbyseason/roundid/'+roundid+'/type/'+page;	
		 }else {
			 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showfullmatchesbyseason/id/'+seasonId+'/type/'+page;
		 }
	     
	     jQuery('#data').html('Loading...'); 
	     jQuery('#data').load(url);
		 jQuery('a.selected').removeClass('selected');
	     jQuery('li.selected').removeClass('selected');
	     jQuery('#' + tab).addClass('selected');
	     jQuery('#' + tab + 'Li').addClass('selected');
	
	}

	function commonScoreBoardLoad(seasonId  , page){
	
		  var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showfullmatchesbyseason/id/'+seasonId+'/type/'+page;
	     
	     jQuery('#data').html('Loading...'); 
	     jQuery('#data').load(url);
		 jQuery('a.selected').removeClass('selected');
	     jQuery('li.selected').removeClass('selected');
	     jQuery('#' + tab).addClass('selected');
	     jQuery('#' + tab + 'Li').addClass('selected');
	}

	function populateCombo(dtarget , id , data){
		 var url = null;
		 var ajaxload = null;
		 if(data == 'player'){
	     	url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findplayersbycountry';
	     	ajaxload = 'ajaxloaderTeamPlayer';
		 }else if(data == 'club' || data == 'national'){
			url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/findteamsbycountry';
			ajaxload = 'ajaxloaderTeam';
		 }else if(data == 'teamplayer'){
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findplayersbyteam';
			 ajaxload = 'ajaxloaderPlayer';
		 }else if(data == 'league'){
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/searchcompetitionsselect';
		 }	 	
		 jQuery('#'+ajaxload).show();
		 jQuery('#'+dtarget).load(url , {id : id , t : data} ,function(){
			 jQuery('#'+ajaxload).hide();
		 });
	} 
	});
</script>
      
      <?php //require_once 'Common.php'; ?>
            
<div id="ContentWrapper" class="TwoColumnLayout">
      <div class="FirstColumn">
           <?php
                $session = new Zend_Session_Namespace ( 'userSession' );
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
                <!--Teams Left Menu-->
                 <div class="img-shadow">
                         <?php echo $this->render('include/navigationteamsdirectory.php');?>
                 </div>
            
                <!--List of Country competitions-->


            </div><!--end FirstColumnOfThree-->

        <div id="SecondColumnHeadtoHead" class="SecondColumnOfTwo" >
            <!-- munch -->
                <div class="munch">
      
                        <p class="burg"><?php echo $this->match[0]["t1"];?> vs <?php echo $this->match[0]["t2"];?> Head - to - Head Match Ups</p>                       


                        <div class="munch1">
                                <p class="mleft">
                                        <span class="mleft1">
                                                <span class="image1">
                                                        <a href="#">
                                                                <?php
                                                                        $config = Zend_Registry::get ( 'config' );
                                                                        $path_team_logos = $config->path->images->teamlogos . $this->match[0]["team_a"].".gif" ;
                                                                        if (file_exists($path_team_logos))
                                                                        {  ?>
                                                                                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->match[0]["team_a"]?>.gif"/>
                                                                        <?php } else {  ?>
                                                                                <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText120.gif"/>
                                                                <?php } ?>
                                                        </a>
                                                </span>
                                                <span class="change">
                                                        <a href="<?php 
                                                        		$seoLinkA = (!empty($this->match[0]))?$urlGen->getClubMasterProfileUrl($this->match[0]["team_a"], $this->match[0]["t1seoname"], True):'#';
                                                        		echo $seoLinkA; ?>">
                                                                <?php echo $this->match[0]["t1"];?>
                                                        </a>
                                                </span>
                                                <span class="change1">
                                                        <?php if($this->competitionType == 'club') { ?>
                                                               <a class="openItem" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->competitionName, $this->competitionId, True); ?>">
                                                            <?php echo $this->competitionName; ?>
                                                       </a>
                                                        <?php } else {?>
                                                               <a  class="openItem" href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($this->competitionName, $this->competitionId, True); ?>">
                                                            <?php echo $this->competitionName; ?>
                                                       </a>
                                                        <?php } ?>
                                                            <br/>
                                                            <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->competitionCountry,$this->countryId,true);?>">
                                                        <?php echo $this->competitionCountry;?>
                                                    </a>
                                                </span>
                                                
												<span class="team">
													<a id="selectTeamA" href="#"  onclick="changeTeamSelection('left');">Change Team</a>
												</span>

                                        </span>
                                        <span class="mleft1">
                                                <span class="vs">VS</span>
                                        </span>
                                        <span class="mleft1">

                                              <span class="image1">
                                                <a href="#">
                                                        <?php
                                                                $config = Zend_Registry::get ( 'config' );
                                                                $path_team_logos = $config->path->images->teamlogos . $this->match[0]["team_b"].".gif" ;
                                                                if (file_exists($path_team_logos))
                                                                {  ?>
                                                                        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->match[0]["team_b"]?>.gif"/>
                                                                <?php } else {  ?>
                                                                        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/TeamText120.gif"/>
                                                        <?php } ?>
                                                </a>
                                                </span>

                                                <span class="change">
                                                        <a href="<?php 
                                                        	$seoLinkB = (!empty($this->match[0]))?$urlGen->getClubMasterProfileUrl($this->match[0]["team_b"], $this->match[0]["t2seoname"], True):'#';
                                                        		echo $seoLinkB;  ?>">
                                                                <?php echo $this->match[0]["t2"];?>
                                                        </a>
                                                </span>
                                                <span class="change1">
                                                        <?php if($this->competitionType == 'club') { ?>
                                                       <a class="openItem" href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->competitionName, $this->competitionId, True); ?>">
                                                    <?php echo $this->competitionName; ?>
                                               </a>
                                                <?php } else {?>
                                                       <a  class="openItem" href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($this->competitionName, $this->competitionId, True); ?>">
                                                    <?php echo $this->competitionName; ?>
                                               </a>
                                                <?php } ?>
                                                    <br/>
                                                   <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->competitionCountry,$this->countryId,true);?>">
                                                <?php echo $this->competitionCountry;?>
                                            </a>
                                        </span>
                                        <span class="team">
											<a id="selectTeamB" href="#"  onclick="changeTeamSelection('right');">Change Team</a>
										</span>
                                    </span>

                                    
                                </p>
                                
                                
                               <!-- <div class="ButtonWrapper" style="margin-left: 215px;">
                 				<span class="team"><a href="#" id="selectButton">Change Teams</a></span>
              				   </div> -->
                                

                                <div class="win">
                                        <div class="win1">
                                                <ul>
                                                        <li class="winleft"><?php echo $this->teamAwins;?></li>
                                                        <li class="winmid">Wins</li>

                                                        <li class="winright"><?php echo $this->teamBwins;?></li>
                                                </ul>
                                        </div>
                                        <div class="win2">
                                                <ul>
                                                        <li class="winleft"><?php echo $this->teamAlosses;?></li>
                                                        <li class="winmid">Losses</li>

                                                        <li class="winright"><?php echo $this->teamBlosses;?></li>
                                                </ul>
                                        </div>
                                        <div class="win1">
                                                <ul>
                                                        <li class="winleft"><?php echo $this->teamties;?></li>
                                                        <li class="winmid">Draws</li>
                                                        <li class="winright"><?php echo $this->teamties;?></li>

                                                </ul>
                                        </div>
                                        <div class="win2">
                                                <ul>
                                                        <li class="winleft"><?php echo $this->teamAclean;?></li>
                                                        <li class="winmid">Clean Sheets</li>
                                                        <li class="winright"><?php echo $this->teamBclean;?></li>
                                                </ul>
                                        </div>
                                        <div id="head2headmatches">
                                                <?php if(sizeof($this->paginator) > 0 ) {
                                                              $temp1 = 'league';
                                                              $temp2 = 'date';
                                                ?>
                                                <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_head_2_head_matches.phtml', array('yearFirstHead2Head'=>$this->yearFirstHead2Head)); ?>
                                                <?php
                                                        $i = 1;
                                                                        foreach($this->paginator as $match) {
                                                                                if($i % 2 == 1) {
                                                                                $style = "mar";
                                                                                } else{
                                                                                $style = "feb";
                                                                                }
                                                ?>
                                                                                        <div class="<?php echo $style;?>">
                                                                                                <ul>
                                                                                                        <li class="mar1">
                                                                                                                <?php if(trim($temp2) != trim($match["seasonId"]) . trim($match["mdate"])) {
                                                                                        echo date ('M d, Y' , strtotime($match['mdate']));?>
                                                                                    <?php } else { ?>&nbsp;<?php } ?>
                                                                                                        </li>
                                                                                                        <li class="mar3"><?php echo $this->escape($match["competition_name"]);?></li>
                                                                                                        <li class="mar2">
                                                                                                                <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                                                                                                                        <?php echo $this->escape($match["teama"]) ;?>
                                                                                                                </a>
                                                                                                                <?php if($match["status"] == 'Played' || $match["status"] == 'Playing'){ ?>
                                                                                &nbsp;<?php echo $this->escape($match["fs_team_a"]);?> - <?php echo $this->escape($match["fs_team_b"]);?>&nbsp;
                                                                                <?php } else { ?>
                                                                                &nbsp; vs.
                                                                                <?php } ?>
                                                                                <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                                                                                                                        <?php echo $this->escape($match["teamb"]);?>
                                                                                                                </a>
                                                                                                        </li>
                                                                                                        <li class="mar1">
                                                                                                                <?php if($match["status"] == 'Played'){ ?>
                                                                                          <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Details</a>
                                                                                        <?php } else if($match["status"] == 'Playing'){  ?>
                                                                                              <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                                                                                                  <?php echo $match["match_minute"];?>'
                                                                                              </a>
                                                                                        <?php } else if($match["status"] == 'Suspended'){  ?>
                                                                                                Suspended
                                                                                        <?php } else if($match["status"] == 'Fixture'){  ?>
                                                                                                     <a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                                                                                                        <?php echo date("H:i",strtotime($match["time"]));?>
                                                                                                 </a>
                                                                                        <?php } ?>
                                                                                                        </li>
                                                                                                </ul>
                                                                                        </div>
                                                                         <?php
                                                                                    $temp1 = $match["league"] . $match["seasonId"] . $match["mdate"];
                                                                                    $temp2 = $match["seasonId"] . $match["mdate"];
                                                                                    $i++;
                                                                                }
                                                                        ?>
                                                                        <div class="btm">
                                                                                <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_head_2_head_matches.phtml', array('yearFirstHead2Head'=>$this->yearFirstHead2Head)); ?>
                                                                        </div>
                                                <?php } else { ?>
                                                	<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_head_2_head_matches.phtml', array('yearFirstHead2Head'=>$this->yearFirstHead2Head)); ?>
	                                                <div class="mar">
                                                            <ul>
	                                                    	<li class="mar2" style="width:390px;">There are no matches played by the selected teams.</li>
	                                                    </ul>
	                                                </div>    
	                                                <div class="btm">
	                                                	<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_head_2_head_matches.phtml', array('yearFirstHead2Head'=>$this->yearFirstHead2Head)); ?>
	                                                </div>
                                                <?php } ?>
                                        </div>
                                </div>
                        </div>
                </div>
             <!-- munch -->
            <div class="ads">
                &nbsp;
            </div>
        </div><!--end SecondColumnOfTwo -->
 </div> <!--end ContentWrapper-->
 <script>
	 //Preloaded variables for this page
	//The values here will be used to preninitialize the player or team Id and Name (AutoSuggest)
	//You can set server side variables here as shown and they will be use for preinitialization

	var teamAInitId=  "<?php echo $this->teama; ?>";
	var teamAInitName = "<?php echo $this->teamnamea;?>";
	var teamAInitTeamId="";
	var teamAInitTeamName="";
	var teamAInitCountryId="";

	var teamBInitId=  "<?php echo $this->teamb; ?>";
	var teamBInitName = "<?php echo $this->teamnameb;?>";
	var teamBInitTeamId="";
	var teamBInitTeamName="";
	var teamBInitCountryId="";
	
	
</script>
<?php include 'include/teamh2h.php';?>


