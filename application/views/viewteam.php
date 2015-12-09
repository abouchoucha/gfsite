<?php $session = new Zend_Session_Namespace('userSession'); ?>
<?php require_once 'Common.php'; $common = new Common();?>
<?php require_once 'seourlgen.php';
$urlGen = new SeoUrlGen();?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $offset = $config->time->offset->daylight;
    $root_crop = $config->path->crop;
    $path_team_logos = $config->path->images->teamlogos . $this->teamid .".gif" ;
 ?>

<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<script type="text/JavaScript">

var initTeamASelection = "<?php echo $this->teamId;?>";
var initTeamANameSelection = "<?php echo $this->teamname;?>";
var contextPath = "<?php echo Zend_Registry::get("contextPath"); ?>";
var root_crop = "<?php echo $root_crop;?>";

jQuery(document).ready(function() {

		 jQuery("span[id^='feature_matchDate']").each(function() {
			  var date_time = jQuery(this).text();
			  var date = new Date(date_time);
			  var date_time_os = calcTimeOffset(date,<?php echo $offset;?>);
			  jQuery(this).text(formatDate(date_time_os, 'EE, MMM dd,yyyy'));
		});
	
		jQuery("span[id^='feature_matchHour']").each(function() {
			  var date_time = jQuery(this).text();
			  var date = new Date(date_time);
			  var date_time_os = calcTimeOffset(date,<?php echo $offset;?>);
			 jQuery(this).text(formatDate(date_time_os, 'HH:mm'));
		});

        jQuery('#buttonSquads').click(function(){
        	showNationalTeamSquad()
        });

        jQuery('#menu4content,#menu5content').hide();
        <?php if($this->nextMatch != null) {?>
             jQuery('#menu4content').show();
             jQuery('#menu4').addClass('active');
        <?php } else { ?>
             jQuery('#menu5content').show();
             jQuery('#menu5').addClass('active');
        <?php }  ?>
        
        jQuery('#matchTab ul li').click(function(){
             jQuery('#menu4content,#menu5content').hide();
             tab_id = jQuery(this).attr('id');
             //show div content
             jQuery('#' + tab_id + 'content').show();
             //show see more header link
             jQuery('#menu4_more,#menu5_more').hide();
             jQuery('#' + tab_id + '_more').show();
             jQuery('#menu4,#menu5').removeClass('active');
             jQuery(this).addClass('active');
             return false;
         });

		//Media tabs 
        jQuery('#menu14content,#menu15content,#menu16content').hide();
        jQuery('#menu16content').show();
        jQuery('#menu16').addClass('active');
        jQuery('#mediaTabs ul li').click(function(){
            jQuery('#menu14content,#menu15content,#menu16content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            jQuery('#menu14,#menu15,#menu16').removeClass('active');
            jQuery(this).addClass('active');
            return false;

        });
		
		// Team Top Player Stats
		jQuery('#menu7content,#menu8content,#menu9content').hide();
        jQuery('#menu7content').show(); // goals default view
        jQuery('#menu7').addClass('active');
		jQuery('#teamStatsTab ul li').click(function(){
				jQuery('#menu7content,#menu8content,#menu9content').hide();
				tab_id = jQuery(this).attr('id');
				//show div content
				jQuery('#' + tab_id + 'content').show();
				jQuery('#menu7,#menu8,#menu9').removeClass('active');
				jQuery(this).addClass('active');
				return false;

        });
		
        //Top PlayersStats - Goals, Yellow Cards, Red Cards Stats 
        jQuery('#menu23content,#menu24content,#menu25content').hide();
        jQuery('#menu23content').show();
        jQuery('#leaguedropdown').change(function() {
             jQuery('#menu23content,#menu24content,#menu25content').hide();
             tab_id = jQuery(this).val();
             //show div content
             jQuery('#' + tab_id + 'content').show();
             return false;
        });

 		

        var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/findactivities/id/<?php echo $this->teamId; ?>';
        jQuery('#teamFeed').html("<div class='ajaxload widget'></div>");
        jQuery('#teamFeed').load(url);

        <?php if ($this->multiNatSeason == 'yes') { ?>
        	showNationalTeamSquad()
        <?php } ?>	

        var timezone = calculate_time_zone();
	    var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showmatches/timezone/'+timezone+'/id/<?php echo $this->teamId; ?>/status/';
        //load the first list by default in scores
        jQuery('#scoreboardResult').html("<div class='ajaxload widget'></div>");
        url = urlBase + 'Played' + '/type/profile';
        jQuery('#scoreboardResult').load(url );

        jQuery('#scoresTab').click(function() {
        	url = urlBase + 'Played' + '/type/profile';
    		loadScoresTab('scoresTabLi', url, 'scoreboardResult' , 'ScoresDateFilter'  , 'tab_2' );
    	 	jQuery('#tab_3').hide();
    	});
    	
    	jQuery('#schedulesTab').click(function() {
    		url = urlBase + 'Fixture' + '/type/profile';
    		loadScoresTab('schedulesTabLi', url, 'scoreboardResult2' , 'SchedulesDateFilter' , 'tab_3' );
    	 	jQuery('#tab_2').hide();
    	});
        
		  jQuery('#reportTypeId').change(function(){
            var selectValue = jQuery('#reportTypeId').val();
            if(selectValue == 0){
                jQuery('#textReportAbuseId').attr('disabled','disabled');
                jQuery('#acceptReportAbuseButtonId').attr('disabled','disabled');
            }else {
                jQuery('#textReportAbuseId').removeAttr('disabled');
                jQuery('#acceptReportAbuseButtonId').removeAttr('disabled');
            }
        });

        showHideDivBox('teamfansid','teamfansBodyid');

    });

	function showNationalTeamSquad(){
	
	    var seasonSelected = jQuery('#season_selected').val();
	    jQuery('#teamsquadcontent').html("<div class='ajaxload widget'></div>");
	    jQuery.ajax({
	        method: 'get',
	        url : '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamsquadajax/id/<?php echo $this->teamId;?>/season/'+seasonSelected,
	        dataType : 'text',
	        success: function (text) {                      
	               jQuery('#teamsquadcontent').html(text);
	        }
	     });
	
	}

    function loadScoresTab(tabid, url, container , filter  , tabtoshow ){

    	jQuery('li.Selected').removeClass('Selected');
        jQuery('#'+tabid).addClass('Selected');
        jQuery('#'+ filter+' a').removeClass('filterSelected');
     	jQuery('#'+container).html('');
        jQuery('#'+container).html("<div class='ajaxload widget'></div>");
    	jQuery('#'+tabtoshow).show();
    	jQuery('#'+container).load(url );
    	
    }

    function findTeamActivity(type){

        var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/findactivities/id/<?php echo $this->teamId;?>/type/'+type;
        jQuery('#teamFeed').html('Loading...');
        jQuery('#teamFeed').load(url);

    }
    
	//Load Random Profiles
    function callRandonProfiles()
    {
        jQuery.ajax({
            method: 'get',
            url : '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofilesrandom/teamId/<?php echo $this->teamId;?>',
            dataType : 'text',
            success: function (text) {
                jQuery('#randomprofiles').html(text);
            }
        });

    }

    function addGoalShout(){

        var commentText = jQuery('#commentGoalShoutId').val();
        if(jQuery.trim(commentText) == ''){
            jQuery('#comment_formerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
            return;
        }
        var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/profile/addgoalshout';
        var commentType = jQuery('#commentTypeId').val();
        var idtocomment = jQuery('#idtocommentId').val();
        var screennametocomment = jQuery('#screennametocommentId').val();
        var teamId = jQuery('#teamId').val();
        var countryid = jQuery('#countryid').val();
        
        jQuery('#goalshoutId').load(url ,{countryid : countryid , commentType: commentType , idtocomment : idtocomment ,screennametocomment : screennametocomment ,teamId :teamId, comment : commentText});
        jQuery('#commentGoalShoutId').val('');

    }

	function editGoalShout(id){
			
			jQuery('#editGoalShoutModal').jqm({trigger: '#editGoalShoutTrigger', onHide: closeModal });
			jQuery('#editGoalShoutModal').jqmShow();
			var dataEdit = jQuery('#goalshout'+id).html();
			jQuery('#textgoalshoutEdit').val(jQuery.trim(dataEdit));
			
				jQuery('#acceptEditGoalShoutButtonId').click(function() {
					var commentText = jQuery('#textgoalshoutEdit').val();
					if(jQuery.trim(commentText) == ''){
						jQuery('#commentediterrorId').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
			 			return;
			 		 }
					var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/editplayergoalshout';
					var playerId = '<?php echo $this->playerid;?>';	
					var dataEditted = jQuery('#textgoalshoutEdit').val();
					jQuery('#editGoalShoutModal').jqmHide();
					jQuery('#goalshoutId').html('Loading...'); 
					jQuery('#goalshoutId').load(url , {playerId :playerId , id : id , dataEditted : dataEditted});
					
				});
	}

	 function deleteGoalShout(id, isComment){

		 jQuery('#acceptModalButtonId').show();
		 jQuery('#cancelModalButtonId').attr('value','Cancel'); 	
		 var modalTitle = (isComment)?'DELETE COMMENT?':'DELETE GOOOALSHOUT?';
		 jQuery('#modalTitleConfirmationId').html(modalTitle);
		 var confirmMessage = (isComment)?'Are you sure you want to delete this comment?':'Are you sure you want to delete a goalshout';
		 var deleteMessage = (isComment)?'Your comment has been deleted.':'Your goalshout has been deleted.';
		 jQuery('#messageConfirmationTextId').text(confirmMessage);	
		 
		 jQuery('#messageConfirmationId').jqm({ trigger: '#deleteGoalShout' , onHide: closeModal});
		 jQuery('#messageConfirmationId').jqmShow();
		 
		 jQuery("#acceptModalButtonId").unbind();
			
		 jQuery('#acceptModalButtonId').click(function(){
				
			 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/removeplayergoalshout/playerid/<?php echo $this->playerid;?>/id/'+id;
				jQuery('#goalshoutId').html('Loading...'); 
				jQuery('#goalshoutId').load(url ,'' , function (){
					jQuery('#messageConfirmationTextId').html('Your goalshout has been deleted.');
					jQuery('#acceptModalButtonId').hide();
					jQuery('#cancelModalButtonId').attr('value','Close');
					jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 2500).jqmHide();
				});
				 
		  });	
	}	

	 function toggleReplyContainerDisplay(replyContainerId)
	 {
	 	if(jQuery(replyContainerId).css('display') == "none")
	 	{
	 		jQuery(replyContainerId).show('fast');
	 	}
	 	else
	 	{
	 		jQuery(replyContainerId).hide('fast');
	 	}
	 }
	 //Records a user rating on an activity
	 function rateActivity(activityId, rateValue)
	 {
	 	//console.log("activityId: " + activityId + ' rateValue: ' + rateValue);
	 	if( jQuery('#activity' + activityId + 'HasBeenRated').val())
	 	{
	 		alert("You have already rated this activity!");
	 		return;
	 	}
	 	if(rateValue > 0 )
	 	{
	 		//Increase plus rating by one
	 		jQuery('#activity' + activityId + 'PlusCount').text(parseInt(jQuery('#activity' + activityId + 'PlusCount').text()) + 1);
	 		
	 	}
	 	else
	 	{
	 		//Increase plus rating by one
	 		jQuery('#activity' + activityId + 'MinusCount').text(parseInt(jQuery('#activity' + activityId + 'MinusCount').text()) + 1);
	 	
	 	}
	 	jQuery('#activity' + activityId + 'HasBeenRated').val('1');
	 }

	 function sendReply(commentid, isActivityComment)
	 {
	    	var broadcast_reply = jQuery('#text_comment'+commentid).attr('value');
	    	if(broadcast_reply == '' || broadcast_reply == 'Write a comment...'){
				return;
	        }
	    	
			jQuery.ajax({
	    	type: "POST",
	    	url: "<?php echo Zend_Registry::get("contextPath"); ?>/index/addbroadcast",
	    	data:"message_wall="+ broadcast_reply+"&commentid="+commentid+"&isActivityComment="+isActivityComment,
	    	success: function(){
	    		callAllReplies(commentid, isActivityComment);
	    		jQuery('#text_comment'+commentid).val('Write a comment...');
	    	}
	    	});
	    	return false;

	 }

   function callAllReplies(commentid, isActivityComment)
   {
	        jQuery('#viewreplies'+commentid).html("<div class='ajaxload widgetlong'></div>");
	    	jQuery.ajax({
	            method: 'get',
	            url : '<?php echo Zend_Registry::get("contextPath"); ?>/index/showmessagereplies/commentid/'+commentid+ '/isActivityComment/' + isActivityComment,
	            dataType : 'text',
	            success: function (text) {
	                jQuery('#viewreplies'+commentid).html(text);
	             }
	         });
	}

     function reportAbuse(id , reportTo){

            jQuery('#reportTypeId').val('0');
            jQuery('#textReportAbuseId').val('');
            jQuery('#textReportAbuseId').attr('disabled','disabled');
            jQuery('#reportAbuseBodyId').show();
            jQuery('#reportAbuseBodyResponseId').hide();
            jQuery('#acceptReportAbuseButtonId').show();
            jQuery('#cancelReportAbuseButtonId').attr('value','Cancel');
            jQuery('#reportAbuseTitleId').html('REPORT GOALSHOUT ABUSE?');
            jQuery('#reportAbuseTextId').html('Are you sure you want to report abuse in this goalshout?');

            jQuery('#reportAbuseModal').jqm({trigger: '#reportAbuseUserTrigger', onHide: closeModal });
            jQuery('#reportAbuseModal').jqmShow();

            jQuery("#acceptReportAbuseButtonId").unbind();
            jQuery('#acceptReportAbuseButtonId').click(function() {

                var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/reportabuse';
                var teamId = '<?php echo $this->teamId;?>';
                var dataReport = jQuery('#textReportAbuseId').val();
                var reportType = jQuery('#reportTypeId').val();

                jQuery('#goalshoutId').load(url ,{teamId :teamId , id : id ,reportTo : reportTo, dataReport : dataReport ,reportType:reportType} , function (){
                    jQuery('#reportAbuseBodyResponseId').html('Your report will be reviewed by our administrators soon.');
                    jQuery('#reportAbuseBodyId').hide();
                    jQuery('#reportAbuseBodyResponseId').show();
                    jQuery('#acceptReportAbuseButtonId').hide();
                    jQuery('#cancelReportAbuseButtonId').attr('value','Close');
                    jQuery('#reportAbuseModal').animate({opacity: '+=0'}, 2500).jqmHide();
                });
            });
        }

</script>


<div id="ContentWrapper">
    <div class="FirstColumn">
		<?php echo $this->render('include/topleftbanner.php')?>
        <?php
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

        <!--Team Profile Badge-->
        <?php echo $this->render('include/badgeTeamNew.php');?>

        <!--Team Profile left Menu-->
        <div class="img-shadow">
            <?php echo $this->render('include/navigationTeam.php');?>
        </div>

      <?php  if($session->email == null){  ?>
    	<!--Goalface Join Now-->
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
               <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
            </a>
            </div>
    	</div>
        <?php  } ?>
   </div> <!--end FirstColumnOfThree-->

   <div class="SecondColumn">
    
      <div class="proff">
             <div class="prof1">
                <h1><?php echo $this->teamname; ?></h1>
                   <span class="subscribe">
                   	<?php if ($session->email != null) { ?>
                   		<?php if ($this->isFavorite == 'false') { ?>
							<a id="btn_team_on_<?php echo $this->teamId;?>" class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $this->teamId;?>, '<?php echo $this->teamname;?>');">
								<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribeprofile.png" alt="Subscribe to <?php echo $this->teamname;?>'s updates">
    						</a>
    						<a id="btn_team_off_<?php echo $this->teamId;?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToTeam(<?php echo $this->teamId;?>, '<?php echo $this->teamname;?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribeprofile.png" alt="Unsubscribe to <?php echo $this->teamname;?>'s updates">
    						</a>
                   		<?php }else {?>
                   			 <a id="btn_team_off_<?php echo $this->teamId;?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToTeam(<?php echo $this->teamId;?>, '<?php echo $this->teamname;?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribeprofile.png" alt="Unsubscribe to <?php echo $this->teamname;?>'s updates">
    						</a>
    						 <a id="btn_team_on_<?php echo $this->teamId;?>" class="subscribe ScoresClosed" href="javascript:" onclick="subscribeToTeam(<?php echo $this->teamId;?>, '<?php echo $this->teamname;?>');">
    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribeprofile.png" alt="Subscribe to <?php echo $this->teamname;?>'s updates">
    						</a>
                   	    <?php }?>
                   	<?php }else {?>
               	 		<a class="subscribe" href="javascript:" onclick="subscribeToTeam(<?php echo $this->teamId;?>);">
							<img class="subscribe-img-swap" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribeprofile.png" alt="Subscribe to <?php echo $this->teamname;?>'s updates">
						</a>
                   	<?php }?>
                   </span>

             	<span class="twitter">
                	<a href="http://twitter.com/share" class="twitter-share-button" style="padding-bottom:5px;width:100px;" data-count="horizontal">Tweet</a>
              </span> 

      			  <span class="facebook">
              		<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>&layout=button_count&show_faces=false&width=80&action=like&font=verdana&colorscheme=light" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:22px;padding-bottom:3px;" allowTransparency="true"></iframe>
              </span>

              <?php if ($this->teamtype != 'national') { ?>
                <span class="age1 age1first"><b>Full Name: </b><?php echo $this->team[0]['team_name_official']; ?></span>
                <span class="age1"><b>Location: </b><?php  if (empty($this->team[0]['team_city'])) {echo "&nbsp;Unavailable"; } else {echo $this->team[0]['team_city'];} ?> </span>
                <span class="age1"><b>Founded: </b><?php  if  (empty($this->team[0]['team_founded']))  {echo "&nbsp;Unavailable"; } else {echo $this->team[0]['team_founded'];} ?></span>                      
                <span class="age1"><b>Manager: </b><?php  if (empty($this->team[0]['team_manager'])){echo "&nbsp;Unavailable"; } else {echo $this->team[0]['team_manager'];} ?></span>
              <?php } else { ?>
              	 <span class="age1" style="margin-top: 20px;"><b>Federation: </b><?php echo $this->team[0]['team_federation']; ?></span>
                <span class="age1"><b>Official Website: </b><?php  if (empty($this->teamurl)){echo "&nbsp;Unavailable"; } else {echo "<a href=' ".$this->teamurl." '>".substr($this->teamurl,7)."</a>";} ?></span>
                <span class="age1"><b>Manager: </b><?php  if (empty($this->teammanager)){echo "&nbsp;Unavailable"; } else {echo $this->teammanager;} ?></span>
                <span class="age1"><b>Stadium: </b><?php  if (empty($this->teamstadium)){echo "&nbsp;Unavailable"; } else {echo $this->teamstadium;} ?></span>
              <?php } ?>
                      
              </div>

                <span class="age2"><b>Profile</b><br/><?php  if (empty($this->teamadditionalinfo)){echo "&nbsp;Unavailable"; } else {echo $this->teamadditionalinfo ."<p><strong>Source:</strong> Wikipedia</p>"; } ?></span>
  
      </div>
      
      
      <div class="prof">
          <p class="mblack">
            <span class="black"><?php echo $this->teamname;?>'s <?php echo $this->seasonleague;?> </span>
          </p>


<?php if ($this->teamtype == 'club') { ?>
           <div class="scores" style="margin-top: 0;">
                <ul>
                    <li class="name" style="width: 137px;">League</li>
                    <li>&nbsp;</li>
                    <li class="cont" style="width: 30px;">Pos</li>
                    <li class="score">GP</li>
                    <li class="score">W</li>
                    <li class="score">L</li>
                    <li class="score">D</li>
                    <li class="score">Pts</li>
                </ul>
            </div>
              <?php $i = 1;
          		//foreach ($this->competitionactive as $data) {
                      //if($i % 2 == 1) { $style = "pre"; }else{ $style = "pre1";}
              ?> 
              
             <div class="pre">
	                <ul>
	                    <li class="name">
	                    	<a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->domesticleaguename, $this->domesticleagueid, True); ?>"><?php echo $this->domesticleaguename;?></a>
	                    </li>
	                    <li style="width:20px;">
	                    	<?php if($this->teamstatus == 'up') { ?>
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/green_up_arrow.gif"/>
                            <?php  } else if ($this->teamstatus == 'down') {  ?>
                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/red_down_arrow.gif"/>
                            <?php } else { ?>
                            		<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/delta_equal.gif"/>
                            <?php }  ?>	                    
	                    </li>
	                    <li class="position"><?php echo $this->teamposition;?></li>
	                    <li class="no"><?php echo $this->gp;?></li>
	                    <li class="no"><?php echo $this->w;?></li>
	                    <li class="no"><?php echo $this->l;?></li>
	                    <li class="no"><?php echo $this->d;?></li>
	                    <li class="no"><?php echo $this->pts;?></li>
	                </ul>
           </div>
            <?php //$i = $i+1; } ?>
     <?php } ?>       
            
              
           <div id="matchTab" class="nxt">
                <ul>
                        <li id="menu4"><a href="javascript:void(0);">Next Match</a></li>
                        <li id="menu5"><a href="javascript:void(0);">Latest Match</a></li>
                </ul>
           </div>
           
           
	          <div class="nmatch" id="menu4content">
	          	<?php if($this->nextMatch != null) {?>
	                <p class="sfull"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamscoresschedules/id/<?php echo $this->teamid;?>">See Full Scores &raquo;</a></p>
	                <p class="champ">Competition: <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->nextMatch[0]['competition'], $this->nextMatch[0]["league"], True); ?>"><?php echo $this->nextMatch[0]['competition']; ?></a> <br/> 
	                
	                Date:<span id="feature_matchDate_nxt">
	                    		<?php echo date('Y/m/d H:i:s',strtotime($this->nextMatch[0]['mdate'] ." ". $this->nextMatch[0]['time'])) ?> 
	                    	</span> &nbsp;&nbsp;
	                    	 <span id="feature_matchHour_nxt">
                    	<?php echo date('Y/m/d H:i:s',strtotime($this->nextMatch[0]['mdate'] ." ". $this->nextMatch[0]['time'])) ?> 
                    	</span>
	                
	                </p>
	                
	                
	                <div class="vs">
	                
	                        <p class="chelsa">
	                        	<a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->nextMatch[0]["teama"], $this->nextMatch[0]["teamb"], $this->nextMatch[0]["matchid"], true);?>">	
		                        	<?php
		                                  $config = Zend_Registry::get ( 'config' );
		                                  $path_team_logos = $config->path->images->teamlogos . $this->nextMatch[0]["cteama"].".gif" ;
		
		                                  if (file_exists($path_team_logos))
		                                  {  ?>
		                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=64&h=64&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $this->nextMatch[0]["cteama"] ; ?>.gif"/>	
		                                  <?php } else {  ?>	
		                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=64&h=64&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText80.gif"/>
	                              	<?php }   ?>
	                        	</a>
	                        </p>
	                        
	                        <p class="vs1">Vs</p>
	                        
	                        <p class="wterr">
	                        	<a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->nextMatch[0]["teama"], $this->nextMatch[0]["teamb"], $this->nextMatch[0]["matchid"], true);?>">
	                        		<?php
		                                  $config = Zend_Registry::get ( 'config' );
		                                  $path_team_logos = $config->path->images->teamlogos . $this->nextMatch[0]["cteamb"].".gif" ;
		
		                                  if (file_exists($path_team_logos))
		                                  {  ?>
		                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=64&h=64&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $this->nextMatch[0]["cteamb"] ; ?>.gif"/>	
		                                  <?php } else {  ?>	
		                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=64&h=64&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText80.gif"/>
	                              	<?php }   ?>
	                        	</a>
	                        </p>
	                        
	                </div>
	                <p class="smatch"><a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->nextMatch[0]["teama"], $this->nextMatch[0]["teamb"], $this->nextMatch[0]["matchid"], true);?>">See Match Preview &raquo;</a></p>
	            <?php } else {  
	            	echo "<br><center><strong>No Matches played yet.</strong></center>";
	              } ?>
	            </div>  
	                  
	            <div class="nmatch" id="menu5content" style="display:none;">
	             <?php if($this->previousMatch != null) {?>
	                <p class="sfull"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamscoresschedules/id/<?php echo $this->teamid;?>">See Full Schedule &raquo;</a></p>
	               
	                <p class="champ">Competition: <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->previousMatch[0]['competition'], $this->previousMatch[0]["league"], True); ?>"><?php echo $this->previousMatch[0]['competition']; ?></a> 
	                <br/> 
	                 Date:<span id="feature_matchDate_nxt">
	                    		<?php echo date('Y/m/d H:i:s',strtotime($this->previousMatch[0]['mdate'] ." ". $this->previousMatch[0]['time'])) ?> 
	                    	</span> &nbsp;&nbsp;
	                    	 <span id="feature_matchHour_nxt">
                    	<?php echo date('Y/m/d H:i:s',strtotime($this->previousMatch[0]['mdate'] ." ". $this->previousMatch[0]['time'])) ?> 
                    	</span>
	                </p>
	                <div class="vs">
	                
                        <p class="chelsa">
                        	<a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->previousMatch[0]["teama"], $this->previousMatch[0]["teamb"], $this->previousMatch[0]["matchid"], true);?>">	
	                        	<?php
	                                  $config = Zend_Registry::get ( 'config' );
	                                  $path_team_logos = $config->path->images->teamlogos . $this->previousMatch[0]["cteama"].".gif" ;
	
	                                  if (file_exists($path_team_logos))
	                                  {  ?>
	                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=64&h=64&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $this->previousMatch[0]["cteama"] ; ?>.gif"/>	
	                                  <?php } else {  ?>	
	                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=64&h=64&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText80.gif"/>
                              	<?php }   ?>
                        	</a>
                        </p>
                        
                         <p class="vs1">
                              <?php echo $this->previousMatch[0]["fs_team_a"];?> - <?php echo $this->previousMatch[0]["fs_team_b"];?>
                              <br>Final</p>
                        
                        <p class="wterr">
                        	<a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->previousMatch[0]["teama"], $this->previousMatch[0]["teamb"], $this->previousMatch[0]["matchid"], true);?>">
                        		<?php
	                                  $config = Zend_Registry::get ( 'config' );
	                                  $path_team_logos = $config->path->images->teamlogos . $this->previousMatch[0]["cteamb"].".gif" ;
	
	                                  if (file_exists($path_team_logos))
	                                  {  ?>
	                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=64&h=64&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $this->previousMatch[0]["cteamb"] ; ?>.gif"/>	
	                                  <?php } else {  ?>	
	                                    <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=64&h=64&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText80.gif"/>
                              	<?php }   ?>
                        	</a>
                        </p>
	                        
	                </div>
	                <p class="smatch"><a href="<?php echo $urlGen->getMatchPageUrl($this->compName, $this->previousMatch[0]["teama"], $this->previousMatch[0]["teamb"], $this->previousMatch[0]["matchid"], true);?>">See Match Details &raquo;</a></p>
		           <?php } else {  
	            	echo "<br><center><strong>No Matches played yet.</strong></center>";
	              } ?>
		         
		         </div>
              
              
            
      </div>

      
   

<?php if ($this->multiNatSeason == 'yes') { ?>
 
	<div class="featured1">
		<p class="mblack">
           <span class="black"><?php echo $this->team[0]['team_name']; ?> Players</span>
           <span class="sm"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamsquad/id/<?php echo $this->teamId;?>" title="<?php echo $this->teamname;?> Players">More &raquo;</a></span>
        </p>
        <p class="show" id="seasons" style="display: block;">
                    <select id="season_selected" class="all" name="seasons_selected">
                    <?php $i = 0; ?>
                     <?php foreach($this->natseasons as $seasons) { ?>
                        <option <?php if($i == 0){ ?>selected<?php } ?> value="<?php echo $seasons["season_id"]; ?>">
                        <?php echo $seasons["competition_name"]; ?>&nbsp;<?php echo $seasons["title"]; ?></option>
					<?php $i = $i+1;
                      } ?>
                    </select>
                    <input type="submit" style="display: inline;" value="Ok" class="submit" id="buttonSquads">
               </p>
        
        <div class="cont" id="teamsquadcontent">
         
       </div>

    </div>
    
 <?php } else { ?>


          <!--/Team Squad Club-->
          <div class="featured1" style="margin-top: 12px;">
			<p class="mblack">
	           <span class="black"><?php echo $this->teamname; ?> Players</span>
	           <span class="sm">
	           	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammates/id/<?php echo $this->playerid; ?>" title="<?php echo $this->playername;?> Teammates">More &raquo;</a>
	           </span>
	        </p>

            <div class="cont" id="teamsquadcontent">
	            <div class="scores">
	                 <ul>
	                 	<li class="silueta">&nbsp;</li>
	                    <li class="name">Name</li>
	                    <li class="position">Position</li>
	                    <li class="team">Nationality</li>
	                 </ul>
	              </div>
	             <?php $i = 1;
	
		          	foreach ($this->players as $data) {
		                      if($i % 2 == 1) { $style = "scores1"; }else{ $style = "scores2";}
	              ?> 
	              
	              		<div class="<?php echo $style; ?>">
			                   <ul>
			             		 <?php
						                $path_player_photos = $config->path->images->players . $data["player_id"] .".jpg" ;
						                if (file_exists($path_player_photos)) { 
						          ?>
				                	<li class="silueta">
				                	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $data["player_id"]; ?>.jpg" alt="<?php echo $data["player_common_name"]; ?>"/>
				                	</li>
				                <?php } else { ?>
				                	<li class="silueta">
				                	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale30.gif" alt="<?php echo $data["player_common_name"]; ?>" />
				                	</li>
				                <?php } ?>
	
			                       <li class="name">
			                       	<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>"><?php echo $data["player_name_short"]; ?></a>
			                       </li>
			                       <li class="position"><?php echo $data["player_position"]; ?></li>
			                       <li class="nationality" style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $data["player_country"]; ?>.png') no-repeat scroll left center transparent;">
			                            <?php echo $data["country_name"]; ?>
			                       </li>
			        
			                   </ul>
			             </div>
		        
		          <?php $i++; } ?>

	          </div>
	          

	          <span class="seemore">
	          	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammates/id/<?php echo $this->teamid; ?>">See More Player Details</a>
	          </span>
        </div>
        
       <?php }  ?>

	<?php if ($this->teamtype == 'club') { ?>
		

		<div class="featured1">
			<p class="mblack">
			<span class="black"><?php echo $this->team[0]['team_name']; ?> League Performance</span>
			</p>
			
	        <div class="cont" id="menuXcontent" style="display: block;">                                        
			  <div class="scores">                        
			    <ul>                            
			      <li class="name" >Season</li>                            
			      <li class="name" style="width:110px">League</li>                                                   
			      <li class="name" >Position</li>                       
			    </ul>                    
			  </div>   
			   <?php $i = 1; ?>
                    <?php foreach ($this->trophydetails as $data) { ?>
                    <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?>                                                                                                  
				  <div class="<?php echo $style; ?>">                            
				    <ul>                                
				      <li class="name" >                                                                  
				         <?php echo $data["season_name"]; ?>
				      </li> 
				      <li class="name" style="width:110px">           
                          <a href="<?php echo $urlGen->getShowNationalTeamCompetitionsUrl($data["competition_name"], $data["competition_id"], True); ?>"><?php echo $data["competition_name"]; ?></a>
				      </li>                               
             
				      <li class="name">
				          <?php
                            if($data["rank"] == "1st") {
                                echo "Champions";
                            } elseif ($data["rank"] == "2nd") {
                                echo "Runner Up";
                            } else {
                                echo $data["rank"] ."&nbsp;Place";
                            }
                           ?>
				      </li>                            
				    </ul>                        
				  </div> 
				<?php $i++; } ?>
			</div> 
			   <span class="seemore">
	          	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamleagueperformance/id/<?php echo $this->teamid; ?>">See Detailed League Performance Statistics</a>
	          </span>
		</div>			

        <?php } ?>
        
    </div> <!--end SecondColumnOfThree-->
    
    <div class="ThirdColumn">
	
	     <div id="banner_right_wide" class="img-shadow" style="margin-top: -10px;">          
  			<div style="float: left; padding:0px;border:none;">
                 <a href="<?php echo Zend_Registry::get("contextPath"); ?>/subscribe#teamSection" title="Subscriptions and Alers">                          
                     <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/banners_team_wide.png" style="margin-top:10px;"/>
              	</a>
            </div>
          </div>
	
      <!-- Team Media Gallery -->
      <!-- END - Team Media Gallery -->

    <?php if ($this->teamtype == 'club') { ?>
	<!-- Team  Top Players  -->
		<div class="featured1">
                <p class="mblack">
                        <span class="black"><?php echo $this->teamname;?> Top Players</span>   
                </p>
                <div class="picture" id="teamStatsTab">
                        <ul>
                             <li id="menu7"><a href="javascript:void(0);">Top Scorers</a></li>
                             <li id="menu8"><a href="javascript:void(0);">Appereances</a></li>
							 <li id="menu9"><a href="javascript:void(0);">Discipline</a></li>
                        </ul>
                </div>  
            <p>&nbsp;</p>
            <div id="menu7content" class="cont">
				<?php if(sizeOf($this->topscorerca) == 0){ ?>
					<div class="scores"> 
			    		<ul>                            
					      <li class="name" style="width:120px">Player</li>                            
					      <li class="team" style="width:113px">Country</li>                            
					      <!-- <li class="cont">GP</li>   -->                        
					      <li class="score">                              
					        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt=""></li>                        
					    </ul> 
					 </div>
					 <div class="scores1">
					     <center><strong>No Top Scorers Available</strong></center>
					</div>
					<?php } else { ?>  
					 
					  <div class="scores">                        
					    <ul>                            
					      <li class="name" style="width:100px">Player</li>                            	                                                       
					      <li class="score">                              
					        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt="">
					      </li> 
					      <li class="score">Min</li>
					      <li class="score">GP</li>
					      <li class="team" style="width:113px">Nationality</li>                        
					    </ul>                    
					  </div>   
					   <?php $i = 1; ?>
             <?php foreach ($this->topscorerca as $item) { ?>
                <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?>                                                                                                  
						  <div class="<?php echo $style; ?>">                            
						    <ul>                                
						      <li class="name" style="width:100px">                                                                  
						        <a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $item["player_id"], true ,$item["player_common_name"]); ?>">
						        	<?php echo $item['player_name_short']; ?>
						        </a>
						      </li> 
						      <li class="score"><?php echo $item['total_goals']; ?></li> 
						      <li class="score"><?php if (empty($item['full_minutes'])){echo "0"; } else {echo $item['full_minutes'];}?></li>     
						      <li class="score"><?php  if (empty($item['total_appear'])){echo "--"; } else {echo $item['total_appear'];} ?></li>                           
						      <li class="teamflag" style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $item['player_nationality']; ?>.png') no-repeat scroll left center transparent;">                                  
						        <a href=""><?php echo $item['country_name']; ?></a>
						      </li>                                						                                                          
						    </ul>                        
						  </div> 
						<?php $i++; if($i==6) break; } ?>
				<?php }  ?> 
		</div>
	
		<div id="menu8content" class="cont">
			 <?php if(sizeOf($this->lineups) == 0){ ?>
					<div class="scores"> 
						<ul>                            
					      <li class="name" style="width:120px">Player</li>                                               
					      <li class="score">                              
					        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/sh6.jpg" alt="Appereances">
					       </li>   
					      <li class="score">                              
					        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/sh7.jpg" alt="Minutes">
					       </li>   
					       <li class="team" style="width:113px">Nationality</li>                 
					    </ul>
					 </div>
					 <div class="scores1">
					     <center><strong>No Appereances Data Available</strong></center>
					</div>
					<?php } else { ?>  
					  
					  <div class="scores">                        
					    <ul>                            
					      <li class="name" style="width:120px">Player</li>                                               
					      <li class="score">         
					      	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/appear.png" alt="">                     
					       </li>   
					      <li class="score">                              
					        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/clock.gif" alt="">
					       </li>     
					       <li class="team" style="width:113px">Nationality</li>                
					    </ul>                    
					  </div>   
					   <?php $i = 1; ?>
                    	<?php foreach ($this->lineups as $item) { ?>
                   		 <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?>                                                                                                  
						  <div class="<?php echo $style; ?>">                            
						    <ul>                                
						      <li class="name" style="width:120px">                                                                  
						        <a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $item["player_id"], true ,$item["player_common_name"]); ?>"><?php echo $item['player_name_short']; ?></a></li>                                
			                             
						      <li class="score"><?php echo $item['total_appear']; ?></li> 
						      <li class="score"><?php echo $item['total_minutes'] + $item['total_subin_minutes']; ?></li> 
						      <li class="team" style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $item['player_nationality']; ?>.png') no-repeat scroll left center transparent;">                                  
						        <a href=""><?php echo $item['country_name']; ?></a>
						      </li>                        
						    </ul>                        
						  </div> 
						<?php $i++;if($i==6) break; } ?>
				<?php }  ?>
		</div>
	
	    <!--/ Discipline Tab YC and RC and Total Cards -->
		<div id="menu9content" class="cont">
			
				<?php if(sizeOf($this->allcards) == 0){ ?>
					<div class="scores"> 
						<ul>                            
					      <li class="name" style="width:100px">Player</li>                                               
					      <li class="score">                              
					        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="">
					       </li>   
					      <li class="score">                              
					        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt="">
					       </li>   
					       <li class="score">Total</li> 
					       <li class="team" style="width:113px">Nationality</li>                   
					    </ul>
					 </div>
					 <div class="scores1">
					     <center><strong>No Disciplinary Data Available</strong></center>
					</div>
					<?php } else { ?>  
					  
					  <div class="scores">                        
					    <ul>                            
					      <li class="name" style="width:100px">Player</li>                                               
					      <li class="score">                              
					        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="">
					      </li>   
					      <li class="score">                              
					        <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt="">
					       </li>   
					       <li class="score">Total</li>  
					       <li class="team" style="width:113px">Nationality</li>                   
					    </ul>                    
					  </div>   
					   <?php $i = 1; ?>
					   
                    	<?php foreach ($this->allcards as $item) { ?>
                   		 <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?>                                                                                                  
						  <div class="<?php echo $style; ?>">                            
						    <ul>                                
						      <li class="name" style="width:100px">                                                                  
						        <a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $item["player_id"], true ,$item["player_common_name"]); ?>"><?php echo $item['player_name_short']; ?></a></li>                                
			                             
						      <li class="score"><?php echo $item['total_yellow_cards']; ?></li> 
						      <li class="score"><?php echo $item['total_red_cards']; ?></li> 
						      <li class="score"><?php echo $item['total_cards']; ?></li>
							  <li class="team" style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $item['player_nationality']; ?>.png') no-repeat scroll left center transparent;">                                  
						        <a href=""><?php echo $item['country_name']; ?></a>
						      </li>                                 
						    </ul>                        
						  </div> 
						<?php $i++;if($i==6) break; } ?>
				<?php }  ?>

		</div>
		<!-- End Team Top Players  -->
			<p style="padding-bottom:8px;" class="smatch1">
                 <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamstats/id/<?php echo $this->teamId; ?>" class="OrangeLink">See More Statistics</a>
            </p>
	
                
		</div>		
				
				
	
		<?php } ?>
		

		<!-- Scoreboard -->
        <div id="scoreboard" class="img-shadow">
                    <div class="WrapperForDropShadow">
                        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                      
                            <h2 class="NoArrowLeft">
                            	<a href="<?php echo Zend_Registry::get ( "contextPath" ); ?>/team/showteamscoresschedules/id/<?php echo $this->teamId;?>"><?php echo $this->teamname;?>Scores &amp; Schedules</a>
                            </h2>
                             <span>
                                 <a href="<?php echo Zend_Registry::get ( "contextPath" ); ?>/team/showteamscoresschedules/id/<?php echo $this->teamId;?>" title="<?php echo $this->teamname ?>&nbsp;Scores &amp; Schedules">See More &raquo;</a>
                            </span>
                        </div>
                        <div class="WrapperForScoreTab" id="competitionsScoresBodyId">
                            <ul id="main_tabs" class="TabbedHomeNav">
                                <li id="scoresTabLi"  class="Selected" style="font-size:12px;">
                                    <a id="scoresTab" class="Selected" href="javascript:void(0)">Top Scores</a>
                                </li>

                                <li id="schedulesTabLi" style="font-size:12px;">
                                    <a id="schedulesTab" href="javascript:void(0)">Schedules</a>
                                </li>
                            </ul>
                            <br class="ClearBoth"/>
                            <div id="ScoresSchedulesWrapper" style="padding-top:15px;width:325px;margin-left:2px;border-left:0px;border-right:0px;margin-right:2px;border-bottom:0px;">
                                <div id="tab_1" class="tabContent" style="display:none">             
                                     <div class="FeaturedNewsSort" id="MyScoresDateFilter">
                                           <a href="#" id="todayms" class="filterSelected">Today</a> |
                                           <a href="javascript:void(0);" id="l3ms">Last 3 Days</a> |
                                           <a href="javascript:void(0);" id="l7ms">Last 7 Days</a> |
                                    </div>
                                    <div id="scoreboardResult0" class="Scores">
                                           Loading..
                                    </div>
                                </div>
                                <div id="tab_2" class="tabContent" style="">
	                            		<div id="scoreboardResult" class="Scores">
	                            			Loading...            		
	                            		</div>   
                                </div>
                                   <div id="tab_3" class="tabContent" style="display:none;">
                                      <div id="scoreboardResult2" class="Scores">
                                          Loading..
                                      </div>                           
                                </div>
                            </div>
                        </div>
                        <div class="SeeMoreNews">
                                   <a class="OrangeLink" href="<?php echo Zend_Registry::get ( "contextPath" ); ?>/team/showteamscoresschedules/id/<?php echo $this->teamId;?>">See more scores and schedules</a></div>
                            </div>    
                
            </div>        
        <!-- End Scoreboard -->


  
  
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                    <h2 class="WithArrowToLeft">
                    	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamactivity/id/<?php echo $this->team[0]['team_id']?>"></a>
                        <?php echo $this->team[0]['team_name'];?> Activity (
                        <?php echo sizeOf($this->teamActivities);?>)
                    </h2>
                        <?php if (sizeOf($this->teamActivities) > 0) { ?>
	                        <span>
	                            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamactivity/id/<?php echo $this->team[0]['team_id']?>">See More</a>
	                        </span>
                        <?php } ?>
                </div>
                <?php if (sizeOf($this->teamActivities) > 0) { ?>
                <div class="BlueShaded DisplayDropdown">Show: <select
                        id="FriendFeedselect" class="slct" name="FriendFeed1select"
                        onchange="javascript:findTeamActivity(this.value);">
                        <option value="0">All Activity</option>
                        <option value="1">Match Results</option>
                        <option value="2">News</option>
                        <option value="3">Pictures</option>
                        <option value="4">Community</option>
                    </select>
                    <div class="JoinedDate">
                    <a class="OrangeLink" style="padding-right: 20px;" href="<?php echo Zend_Registry::get("contextPath"); ?>/team/rss/id/<?php echo $this->team[0]['team_id']?>">Subscribe</a>
                    </div>
                </div>
                <?php } ?>
                <div id="teamFeed"></div>
                <?php if (sizeOf($this->teamActivities) > 0) { ?>
                <div class="SeeMoreNews">
                    <a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamactivity/id/<?php echo $this->team[0]['team_id']?>">See More <?php echo $this->team[0]['team_name'];?> activity</a>
                </div>
                <? }?>
            </div>
        </div>

<?php if ($session->email != null) { ?>

        <div id="goalshoutId" class="img-shadow">
            <?php echo $this->render('goalshoutteam.php');?>
        </div>
 
        
      
     <!-- Profiles -->
        <div class="prof">
              <p class="mblack">
               <span class="black"><?php echo $this->teamname;?> Fans</span>
               <span class="sm" id="menu6_more">
                   <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamfans/id/<?php echo $this->teamid;?>" title="<?php echo $this->teamname;?> Fans">More &raquo;</a>
               </span>
              </p>

              <div id="randomprofiles" class="nmatch">

			</div>

           <p class="modfooter"><a class="orangelink" href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamfans/id/<?php echo $this->teamid;?>">See More <?php echo $this->teamname;?> Fans &raquo;</a></p>

       </div>
      
       <?php } else { ?>
            
    <div class="img-shadow">   
            <div class="WrapperForDropShadow">
                <div class="DropShadowHeader BlueGradientForDropShadowHeader">
              
                    <h2 class="NoArrowLeft">
                    	<a href="javascript:loginModal();"><?php echo $this->teamname;?> Goooal Shouts</a>
                    </h2>
                    <span class="sm" id="menu6_more">
                   <a href="javascript:loginModal();" title="<?php echo $this->teamname;?> Fans">More &raquo;</a>
               </span>

                </div>
                <div class="boxMessage">
                    <div class="preRegMessage">
                    You must <a href="javascript:loginModal();" title="Sign In" >Sign in</a> to see <?php echo $this->teamname;?>'s Goooal Shouts .
                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">Click here to register</a> if you are not already a GoalFace member.
                    </div>
                    <div class="SeeMoreNews">
                        <a class="OrangeLink" onclick="openbrowserlogin();" href="javascript:void(0)">See More
                        <?php echo $this->teamname;?>'s Goooal Shouts </a>
                    </div>
                </div>
           </div>
    </div>
    
   <div class="img-shadow">   
            <div class="WrapperForDropShadow">
                <div class="DropShadowHeader BlueGradientForDropShadowHeader">
              
                    <h2 class="NoArrowLeft">
                    	<a href="javascript:loginModal();"><?php echo $this->teamname;?> Fans</a>
                    </h2>
					<span class="sm" id="menu6_more">
                   <a href="javascript:loginModal();" title="<?php echo $this->teamname;?> Fans">More &raquo;</a>
               		</span>
                </div>
                  <div class="boxMessage">
                    <div class="preRegMessage">
                    You must <a href="javascript:loginModal();" title="Sign In">Sign in</a> to see <?php echo $this->teamname;?>'s Fans.
                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">Click here to register</a> if you are not already a GoalFace member.
                    </div>
                    <div class="SeeMoreNews">
                        <a class="OrangeLink" onclick="openbrowserlogin();" href="javascript:void(0)">See More &raquo;
                        <?php echo $this->teamname;?>'s Fans</a>
                    </div>
                </div>
           </div>
    </div>
   
        
        <?php } ?>
        
    </div><!--end ThirdColumnOfThree-->
</div><!--end ContentWrapper-->
<script>
//The values here will be used to preninitialize the team or team Id and Name (AutoSuggest)
//You can set server side variables here as shown and they will be use for preinitialization
var teamAInitId="<?php echo $this->teamId;?>";
var teamAInitName = "<?php echo $this->teamname;?>";
var teamAInitTeamId="";
var teamAInitTeamName="";
var teamAInitCountryId="";
var teamAInitCountryName="";
var teamBInitId="";
var teamBInitName = "";
var teamBInitTeamId="";
var teamBInitTeamName="";
var teamBInitCountryId="";
</script>
<?php include 'include/teamh2h.php';?>

<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeteam.js" type="text/javascript"></script>
