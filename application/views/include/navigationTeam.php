<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>
<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/popup.js" type="text/javascript"></script>
<script type="text/JavaScript">
	jQuery(document).ready(function() {	
		jQuery("input[name='rbTeamType']").change(function(){
			var countryida = jQuery("#countryteama").val();
			var countryidb = jQuery("#countryteamb").val();
			var rbTeamType = jQuery("input[name='rbTeamType']:checked").val();
			if(rbTeamType == '1') {
				jQuery('.ErrorMessageIndividualDisplay').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
				jQuery('#cluberrorA').text("Select a Team A");
				populateCombo('teamselectida', countryida, 'club');
				jQuery('#cluberrorA').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
				jQuery('#cluberrorB').text("Select a Team B");
				populateCombo('teamselectidb', countryidb, 'club');
				jQuery('#cluberrorB').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
			} else {
				jQuery('.ErrorMessageIndividualDisplay').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
				jQuery('#cluberrorA').text("Select a National Team A");
				populateCombo('teamselectida', countryida, 'national');
				jQuery('#cluberrorA').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
				jQuery('#cluberrorB').text("Select a National Team B");
				populateCombo('teamselectidb', countryidb, 'national');
				jQuery('#cluberrorB').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
			}	
		});
		
		jQuery('#countryteama').change(function(){
	    	var countryid = jQuery("#countryteama").val();
	    	var rbTeamType = jQuery("input[name='rbTeamType']:checked").val();
	    	if(rbTeamType == '1') {	    		
	    		populateCombo('teamselectida', countryid, 'club');
	    	} else {
	    		populateCombo('teamselectida', countryid, 'national');
	    	}
	    }); 
	    
		jQuery('#countryteamb').change(function(){
	    	var countryid = jQuery("#countryteamb").val();
	    	var rbTeamType = jQuery("input[name='rbTeamType']:checked").val();
	    	if(rbTeamType == '1') {
	    		populateCombo('teamselectidb', countryid, 'club');
	    	} else {
	    		populateCombo('teamselectidb', countryid, 'national');
	    	}	    	
	    }); 

		//Click the button event!
		jQuery('#slcTeam1').click(function(){
			validateHead2HeadSelectTeams();
			var countryteama = jQuery("#countryteama").val();
			var countryteamb = jQuery("#countryteamb").val();
			var teama = jQuery("#teamselectida").val();
			var teamb = jQuery("#teamselectidb").val();
			if((countryteama != '0' && teama != '0') && 
					(countryteamb != '0' && teamb != '0')) {
				var url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/findhead2headmatches/teama/'+teama+'/teamb/'+teamb;
				top.location.href = url;
				//jQuery('#SecondColumnHeadtoHead').html("<div class='ajaxloadscores'></div>");
				//jQuery('#SecondColumnHeadtoHead').load(url);
				disablePopup1();
			}
		});		
	
	});	

	function validateHead2HeadSelectTeams() {
		var countryteama = jQuery("#countryteama").val();
		var countryteamb = jQuery("#countryteamb").val();
		var rbTeamType = jQuery("input[name='rbTeamType']:checked").val();
		var teama = jQuery("#teamselectida").val();
		var teamb = jQuery("#teamselectidb").val();
		if(countryteama == '0') {
			jQuery('#countryerrorA').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');				
		} else {
			jQuery('#countryerrorA').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');				
		}
		if(countryteamb == '0') {
			jQuery('#countryerrorB').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
		} else {
			jQuery('#countryerrorB').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
		}
		if(teama == '0') {
			jQuery('#cluberrorA').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');					
		} else {
			jQuery('#cluberrorA').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
		}
		if(teamb == '0') {
			jQuery('#cluberrorB').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');					
		} else {
			jQuery('#cluberrorB').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
		}
		
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
</script>

<div class="WrapperForDropShadow">
     <ul class="leftnavlist">
         <li class="<?php echo($this->teamMenuSelected == 'profile'?'active':'noactive'); ?>">
            <a href="<?php echo $urlGen->getClubMasterProfileUrl($this->teamId, $this->teamseoname, true); ?>">Profile</a>
         </li>
            <li class="<?php echo($this->teamMenuSelected == 'activity'?'active':'noactive'); ?>">
                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamactivity/id/<?php echo $this->teamId;?>">Team Activity</a>
            </li>
            
            
         <!--<li class="<php echo($this->teamMenuSelected == 'pics'?'active':'noactive'); ?>">
            <a href="<php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogallery/id/<php echo $this->teamId; ?>/type/1">Pictures</a>
         </li>-->

         <!--Team Performance here /team/showteamthrophycase/id/-->
         <li class="<?php echo($this->teamMenuSelected == 'scores'?'active':'noactive'); ?>">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamscoresschedules/id/<?php echo $this->teamId;?>">Scores &amp; Schedules</a>
         </li>
         <li class="<?php echo($this->teamMenuSelected == 'squad'?'active':'noactive'); ?>">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamsquad/id/<?php echo $this->teamId;?>">Squad</a>
         </li>
         <?php if($this->teamtype=='club') {  ?> 
         
	         <li class="<?php echo($this->teamMenuSelected == 'performance'?'active':'noactive'); ?>">
	           <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamleagueperformance/id/<?php echo $this->teamId; ?>">League Performance</a>
	         </li>
	         
	         <li class="<?php echo($this->teamMenuSelected == 'playerstats'?'active':'noactive'); ?>">
	            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamstats/id/<?php echo $this->teamId; ?>">Player Statistics</a>
	         </li>
         <?php } ?>
         
         <li class="<?php echo($this->teamMenuSelected == 'fans'?'active':'noactive'); ?>">
          <?php if ($session->email != null) { ?>
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamfans/id/<?php echo $this->teamId;?>">Fans</a>
          <?php } else { ?>
           	<a href="javascript:loginModal();">Fans</a>
         <?php }  ?> 
         </li>
 
         <li class="<?php echo($this->teamMenuSelected == 'shouts'?'active':'noactive'); ?>">
           <?php if ($session->email != null) { ?>
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamgoalshouts/id/<?php echo $this->teamId;?>">Goooal Shouts</a>
          <?php } else { ?>
           	<a href="javascript:loginModal();">Goooal Shouts </a>
         <?php }  ?> 
         </li>

     </ul>
</div>
