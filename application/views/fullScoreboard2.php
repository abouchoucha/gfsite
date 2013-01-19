<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
<script type="text/JavaScript">
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
<!--
                    <div class="img-shadow">
                      <div class="WrapperForDropShadow">
                        <b>league Id</b>..#..<?php //echo $this->leagueId;?>..#<BR>
                         <b>season Id</b>..#..<?php //echo $this->seasonId;?>..#<BR>
                         <b>season Active</b>..#..<?php //echo $this->seasonActive;?>..#<BR>
                          <b>round Id</b>..#..<?php //echo $this->roundId;?>..#<BR>
                          <b>Active round Id</b>..#..<?php //echo $this->currentActiveRound;?>..#<BR>
                          <b>submenuSelected</b>..#..<?php //echo $this->submenuSelected;?>..#
                          <b>competition type</b>..#..<?php //echo $this->compType;?>..#<br>
                          <b>competition format</b>..#..<?php //echo $this->compFormat;?>..# <br>
                        </div>
                    </div>
-->

                   
                    <!--List of Country competitions-->

                    <div id="leftnav" class="img-shadow">
                         <?php echo $this->render('include/navigationCompetitionNew2.php');?>
                    </div>


                <?php if ($server != 'production') { ?>
                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/mls_gear_com_ad.jpg" />
                    <div id="AdsByGoogle">
                        <u>Ads by Goooooogle</u>
                        
                        <h6><strong>Soccer</strong> News</h6>
                        Always on the go? Get your news fix via the mobile web - Learn how.
                        <br />
                        <a href="#">mobile.google.com/news</a>

                        <h6><strong>Soccer</strong> In The Streets</h6>
                        Changing lives....
                        <br />
                        <a href="#">www.soccerstreets.org</a>     
                    </div>
                <?php } ?>
                </div><!--end FirstColumnOfThree-->

     <div id="SecondColumnFullScore" class="SecondColumnOfTwo">

                <p class="flags">
                    <span class="flagtitle">
                        <a href="#"><?php echo $this->compName; ?> <?php echo $this->seasonTitle; ?></a>
                    </span>
                    <span class="add">
                        <a href="#">Add to My Favorites</a>
                    </span>
                    <span class="pre">
                    <span class="lea">Premier League Archive</span>
                        <select class="sel">
                            <option>2009/2010</option>
                        </select>
                    </span>
                </p>

        <!-- mmid -->
		<div class="mmid">			
			<div class="march">
				<p style="" class="featurebg">FULL SCOREBOARD 2009/2010</p>
				<div class="previous">
					<ul>
						<li class="pre"><a href="#">&lt;&nbsp;&nbsp;Previous</a></li>
						<li><a href="#">1</a></li>
						<li>|</li>
						<li><a href="#">2</a></li>
						<li>|</li>
						<li><a href="#">3</a></li>
						<li>|</li>
						<li><a href="#">4</a></li>
						<li>|</li>
						<li><a href="#">5</a></li>
						<li>|</li>
						<li><a href="#">6</a></li>
						<li>....</li>				 
						<li><a href="#">115</a></li>
						<li>|</li>
						<li class="one"><a href="#">116</a></li>				 
						<li class="next"><a href="#">Next &gt;</a></li>
					</ul>		    
				</div>	   
				<div class="mar15">
					<ul>
						<li class="mar">Mar.  15 2009</li>
						<li class="mar1">West Bromwich</li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar13">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar15">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar13">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar15">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar13">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar15">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar13">
					<ul>			
						<li class="mar">Mar. 14 2009</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar15">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar13">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>          
				<div class="mar15">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar13">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>     
				<div class="mar15">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="mar13">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>     
				<div class="mar15">
					<ul>			
						<li class="mar">&nbsp;</li>
						<li class="mar1">West Bromwich></li>
						<li class="mar2">3 - 2</li>
						<li class="mar3">Reading</li>
						<li class="mar4"><a href="#">Details &gt;</a></li>
					</ul>
				</div>
				<div class="previous">
					<ul>
						<li class="pre"><a href="#">&lt;&nbsp;&nbsp;Previous</a></li>
						<li><a href="#">1</a></li>
						<li>|</li>
						<li><a href="#">2</a></li>
						<li>|</li>
						<li><a href="#">3</a></li>
						<li>|</li>
						<li><a href="#">4</a></li>
						<li>|</li>
						<li><a href="#">5</a></li>
						<li>|</li>
						<li><a href="#">6</a></li>
						<li>....</li>				 
						<li><a href="#">115</a></li>
						<li>|</li>
						<li class="one"><a href="#">116</a></li>				 
						<li class="next"><a href="#">Next &gt;</a></li>
					</ul>		  
				</div> 
			</div>
		</div>	
	<!-- /mmid -->
	<div class="ads"><a href="#"><img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/hadds.jpg" alt="" /></a></div>


















                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <div class="PlayerInfoWrapper">
                                <div class="PlayerInfo">
                                   
                                     <div class="ModuleTabs">
                                        <ul>
                                            <li id="scoresTabLi" class="selected">
                                              <a id="scoresTab" class="selected" href="#">Scores</a>
                                            </li>
                                            <?php if ($this->totalFixtureMatches == 'y'){?>
                                            <li id="schedulesTabLi">
                                              <a id="schedulesTab" href="#">Schedules</a>
                                            </li>
                                            <?php } ?>
                                        </ul>                                       
                                    </div><!-- /ModuleTabs-->
                                    
                                    <div id="data" class="ModuleContent">
                                      
                                      
                                     
                                    </div> 
                                    
                                </div>
                              </div>
                        </div>
                    </div>
                            
                </div>
                  
                <!--end SecondColumnOfTwo -->
                
                
 
             </div> <!--end ContentWrapper-->
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
           
