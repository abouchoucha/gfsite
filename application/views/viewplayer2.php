<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php require_once 'Team.php'; $equipo = new Team(); ?>

<?php $urlGen = new SeoUrlGen ( );
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $offset = $config->time->offset->daylight;
    $path_player_photos = $config->path->images->players . $this->playerid .".jpg" ;
?>

<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>

<script type="text/JavaScript">

var initPlayerASelection = "<?php echo $this->playerid;?>";
var initPlayerANameSelection = "<?php echo $this->playercommonname;?>";

  jQuery(document).ready(function(){

        //load players stats default view
        //showPlayerStats();

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


		 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findactivities/id/<?php echo $this->playerid; ?>';
			 jQuery('#playerFeed').html('Loading...');
			 jQuery('#playerFeed').load(url);



 	    //assign show player stats function to event click "ok" button in dropdown
      	jQuery('#buttonStats').click(function(){ //
       		showPlayerStats();
      	});

    	//Initial load all matches
      	findMatchPlayerStats(0);

        //load players photo
	  	//callHomePlayerGallery();



		//Media tabs
        jQuery('#menu14content,#menu16content').hide();
        jQuery('#menu16content').show();
        jQuery('#menu16').addClass('active');
        jQuery('#mediaTabs ul li').click(function(){
            jQuery('#menu14content,#menu16content').hide();
            tab_id = jQuery(this).attr('id');
            //show div content
            jQuery('#' + tab_id + 'content').show();
            jQuery('#menu14,#menu16').removeClass('active');
            jQuery(this).addClass('active');

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
        });


       //Player's Stats
       jQuery('#menu24').addClass('active');
       jQuery('#playerStatsTab ul li').click(function(){
           jQuery('#menu24content,#menu25content').hide();
           tab_id = jQuery(this).attr('id');
           //show div content
           jQuery('#' + tab_id + 'content').show();
           jQuery('#menu24,#menu25').removeClass('active');
           jQuery(this).addClass('active');
        });

       jQuery('#menu6content,#menu7content,#menu8content,#menu9content').hide();
       jQuery('#menu6content').show();
       jQuery('#playerstatsdropdown').change(function() {
    	   jQuery('#menu6content,#menu7content,#menu8content,#menu9content').hide();
           tab_id = jQuery(this).val();
           //show div content
           jQuery('#' + tab_id + 'content').show();
       });

  });



  	function findMatchPlayerStats(teamid) {
  		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showmatchplayerstats/id/<?php echo $this->playerid;?>/team/'+teamid;
		jQuery('#data').html("<div class='ajaxloadmodule'></div>");
		jQuery('#data').load(url);
  	}

	function findUserActivity(type){

 		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findactivities/id/<?php echo $this->playerid;?>/type/'+type;
		jQuery('#playerFeed').html('Loading...');
		jQuery('#playerFeed').load(url);

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
         var countryid = jQuery('#countryid').val();
         var playerId = jQuery('#playerId').val();
         jQuery('#goalshoutId').load(url ,{countryid : countryid ,commentType: commentType , idtocomment : idtocomment ,screennametocomment : screennametocomment ,playerId :playerId, comment : commentText});
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

		 jQuery('#messageConfirmationId').jqm({ trigger: '#deleteGoalShout' , onHide: closeModal });
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


	 function reportAbuse(id){

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

				var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/reportabuse';
				var playerid = '<?php echo $this->playerid;?>';
				var dataReport = jQuery('#textReportAbuseId').val();
				var reportType = jQuery('#reportTypeId').val();

				jQuery('#goalshoutId').load(url ,{playerid :playerid , id : id , dataReport : dataReport ,reportType:reportType} , function (){
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
 		<?php } ?>

         <?php echo $this->render('include/badgePlayerNew.php');?>

        <div class="img-shadow" style="margin-left:2px;margin-top:10px;">
          <?php echo $this->render('include/navigationPlayerNew.php');?>
        </div>

     </div><!--/FirstColumnOfThree-->

     <div class="SecondColumn">
     	  <div class="proff">
             <div class="prof1">
                <h1><?php echo $this->playername;?></h1>
                  
            <?php if($this->playeractualteam == 1) {?>       
                  <span class="subscribe">
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
          					    <a id="btn_playerid_<?php echo $this->playerid;?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $this->playerid;?>);">
          							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribeprofile.png" alt="Subscribe to <?php echo $this->playername;?>'s updates">
          						</a>
              				<?php }  ?>
                    </span>
                <?php } ?>     

	               	<span class="twitter">
	                	<a href="http://twitter.com/share" class="twitter-share-button" style="padding-bottom:5px;width:100px;" data-count="horizontal">Tweet</a>
	              	</span>

	              	<span class="facebook">
		              	<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>&layout=button_count&show_faces=false&width=80&action=like&font=verdana&colorscheme=light" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:22px;padding-bottom:3px;" allowTransparency="true"></iframe>
	              	</span>

	              	<span class="gplus">
	              		<!-- Place this tag in your head or just before your close body tag -->
						<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
						<!-- Place this tag where you want the +1 button to render -->
						<g:plusone size="medium"></g:plusone>
	              	</span>

	              	<span class="twitter">
                        <!--START PIN BUTTON-->
							<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ); ?>&media=<?php echo urlencode($this->imagefacebook ); ?>&description=<?php echo $this->title;?>" class="pin-it-button" count-layout="none"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
                        <!--END PIN BUTTON-->
					</span>


                <span class="age1 age1first"><b>Player Full Name:</b>&nbsp;<?php echo $this->playerfname;?>&nbsp;<?php echo $this->playerlname;?></span>
                <span class="age1"><b>Age:</b>&nbsp;<?php if (empty($this->playerage)) {echo "&nbsp;Unavailable"; } else {echo $this->playerage;}?></span>
                <span class="age1"><b>Birthdate: </b>&nbsp;<?php if (empty($this->playerdob))  {echo "&nbsp;Unavailable"; } else {echo $this->playerdob;} ; ?></span>
                <span class="age1"><b>Place of Birth:</b>&nbsp;<?php if (empty($this->playerdobcity)){echo "&nbsp;Unavailable"; } else {echo $this->playerdobcity;}?></span>
                <span class="age1"><b>Height: </b>&nbsp;<?php if (empty($this->playerheight)){echo "&nbsp;Unavailable"; } else {echo $this->playerheight . "&nbsp;cm";}?></span>
                <span class="age1"><b>Weight:</b>&nbsp;<?php if (empty($this->playerweight)){echo "&nbsp;Unavailable"; } else {echo $this->playerweight . "&nbsp;kg" ;}?></span>
            </div>

		<!--/ TO DO LIST

                <span class="age2"><b>Profile</b><br/>Up to # lines/## characters of bio text displays here if entered by user and set to public. If not entered or set to private it would not display here. </span>
                <span class="full"><a href="#">View Full Profile &gt;</a></span>
                <span class="report"><span class="report1">Contribute or report an error on this page</span></span>
       -->
            </div>

 <?php if($this->playeractualteam == 1) {?>

        <div class="prof">
          <p class="mblack">
            <span class="black"><?php echo $this->playername;?>'s Current Season Overview</span>
          </p>
          <p class="wter">
                <span class="wter1">
                  <a href="<?php echo $urlGen->getClubMasterProfileUrl ( $this->playerteamid, $this->playerteamseoclub, True ); ?>">
	              	<?php
                  		$config = Zend_Registry::get ( 'config' );
                  		$path_team_logos = $config->path->images->teamlogos . $this->playerteamid .".gif" ;
                  		if (file_exists($path_team_logos))
                 		{ ?>
                  	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=81&h=81&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $this->playerteamid ; ?>.gif"/>
              			<?php } else {  ?>
                  	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=81&h=81&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText.gif"/>
              		<?php }  ?>
              	  </a>
                </span>

                <span class="wter2">
                    <span class="chel"><a href="<?php echo $urlGen->getClubMasterProfileUrl ( $this->playerteamid, $this->playerteamseoclub, True ); ?>"><?php echo $this->playerteamclub; ?></a></span>
                    <span class="lea">League: <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->playerteamleague, $this->playerteamleagueid, True); ?>"><?php echo $this->playerteamleague; ?></a></span>
                    <span class="lea">Position: <?php echo $this->teamposition; ?></span>
                    <span class="lea">Country: <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->playerteamcountry,$this->playerteamcountryid,true);?>"><?php echo $this->playerteamcountry; ?></a></span>
                </span>
          </p>
            <div class="scores">
                <ul>

                    <?php if ($this->playerpos == 'Goalkeeper') {  ?>
                    	<li class="name" style="width: 140px;">League</li>
                    	<li class="score">GP</li>
                    	<li class="score">Min</li>
                    	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/hand_for_high_five.gif" alt="Clean Sheets"/></li>
                    	<li class="score">GA</li>
                    <?php } else { ?>
                      <li class="name" style="width: 167px;">League</li>
                    	<li class="score">GP</li>
                    	<li class="score">Min</li>
                    	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt="Goals Scored"/></li>
                    <?php }  ?>
                      <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="Yellow Cards"/></li>
                    	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt="Red Cards"/></li>
                </ul>
            </div>

           	<?php $i = 0;

		          	foreach ($this->stats as $stat) {

		                      if(($i+1) % 2 == 1) { $style = "pre1"; }else{ $style = "pre";}
	              ?>
					          <div class="<?php echo $style; ?>">

					                <ul>


					                    <?php if ($this->playerpos == 'Goalkeeper') {  ?>
    					                    	<li class="pree" style="width: 150px;">
    					                    	<a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($stat[0]['league'], $stat[0]['league_id'], True); ?>">
    					                    		<?php echo mb_convert_encoding($stat[0]['league'], "ISO-8859-1", "UTF-8");  ?>
    					                    	</a>
    					                    </li>
    					                    <li class="no1" style="width: 25px;">
    					                    	<?php if (empty($stat[0]['total_appear'])){echo "--"; } else {echo $stat[0]['total_appear'];}?>
    					                    </li>
    					                    <li class="no1" style="width: 35px;">
    					                    	<?php if (empty($stat[0]['total_full_minutes'])){echo "--"; } else {echo $stat[0]['total_full_minutes'];}?>
    					                    </li>
					                       <li class="no1" style="width: 30px;">
    					                    	<?php if (empty($stat[0]['total_clean_sheets'])){echo "0"; } else {echo $stat[0]['total_clean_sheets'];}?>
    					                    </li>
    					                     <li class="no1" style="width: 30px;">
    					                    	<?php if (empty($stat[0]['total_goals_allowed'])){echo "0"; } else {echo $stat[0]['total_goals_allowed'];}?>
    					                    </li>

					                    <?php } else { ?>
    					                    	<li class="pree" style="width: 178px;">
    					                    	<a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($stat[0]['league'], $stat[0]['league_id'], True); ?>">
    					                    		<?php echo mb_convert_encoding($stat[0]['league'], "ISO-8859-1", "UTF-8");  ?>
    					                    	</a>
    					                    </li>
    					                    <li class="no1" style="width: 23px;">
    					                    	<?php if (empty($stat[0]['total_appear'])){echo "--"; } else {echo $stat[0]['total_appear'];}?>
    					                    </li>
    					                    <li class="no1" style="width: 40px;">
    					                    	<?php if (empty($stat[0]['total_full_minutes'])){echo "--"; } else {echo $stat[0]['total_full_minutes'];}?>
    					                    </li>
    					                     <li class="no1" style="width: 26px;">
    					                    	<?php if (empty($stat[0]['total_goals'])){echo "0"; } else {echo $stat[0]['total_goals'];}?>
    					                    </li>
					                    <?php }  ?>

  														<li class="no1">
    					                    	<?php if (empty($stat[0]['total_yellow_cards'])){echo "0"; } else {echo $stat[0]['total_yellow_cards'];}?>
    					                    </li>
					                    <li class="no1">
					                    	<?php if (empty($stat[0]['total_red_cards'])){echo "0"; } else {echo $stat[0]['total_red_cards'];}?>
					                    </li>

					                </ul>
					           </div>

		         <?php $i++; } ?>


           <div id="matchTab" class="nxt">
                <ul>
                        <li id="menu4"><a href="javascript:void(0);">Next Match</a></li>
                        <li id="menu5"><a href="javascript:void(0);">Latest Match</a></li>
                </ul>
           </div>


	          <div class="nmatch" id="menu4content">
	          	<?php if($this->nextMatch != null) {?>
	                <p class="sfull"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamscoresschedules/id/<?php echo $this->playerteamid;?>">See Full Schedules &raquo;</a></p>
	                <p class="champ">Competition: <a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($this->nextMatch[0]['competition'], $this->nextMatch[0]["league"], True); ?>"><?php echo $this->nextMatch[0]['competition']; ?></a>
	                <br/> Date:<span id="feature_matchDate_nxt">
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
	                <p class="sfull"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamscoresschedules/id/<?php echo $this->playerteamid;?>">See Full Scores &raquo;</a></p>

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

                        <p class="vs1">Vs</p>

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
      <?php } ?>


      <?php //if($this->total_club_stats > 0) {?>


      <!-- Season and Match Stats -->

      <div class="prof2" style="margin-top: 0px;">
            <p class="mblack">
               <span class="black"><?php echo $this->playername;?>'s Career Statistics</span>
            </p>
            <div id="playerStatsTab" class="nxt">
                <ul>
                  <li id="menu24"><a href="javascript:void(0);">Match Statistics</a></li>
                  <li id="menu25"><a href="javascript:void(0);">Season Statistics</a></li>
                </ul>
            </div>



        <div class="nmatch" id="menu24content">
               <p class="show">
                    <label>Show:</label>
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

             <div id="data" class="modulo_container">
             	<!-- League Match Season Stats -->
  					</div>

  					<span class="thread3">
            	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerstatsdetail/id/<?php echo $this->playerid; ?>">See Detailed Match Statistics &raquo;</a>
          	</span>
         </div>


    <div class="nmatch" id="menu25content" style="display:none;">
        <p class="show">
          <label>Show:</label>
          <select id="playerstatsdropdown" class="all">
            <option value="menu6">League</option>
            <option value="menu9">Cup</option>
            <option value="menu7">Regional Competition</option>
            <option value="menu8">National Team</option>
          </select>
        </p>

			  <!-- Club Season Stats -->
        <div id="menu6content" class="cont">

          <div class="scores">
  					<ul>
  						<li class="name">Season</li>
  						<li class="team">Team</li>
  						<li class="score">GP</li>
              <?php if ($this->playerpos == 'Goalkeeper') {  ?>
              		<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/hand_for_high_five.gif" alt="Clean Sheets"/></li>
                	<li class="score">GA</li>
                	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="Yellow Cards"/></li>
                	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt="Red Cards"/></li>
                <?php } else { ?>
                  <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/minutes.jpg" alt="Minutes Played"/></li>
                	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt="Goals Scored"/></li>
                	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="Yellow Cards"/></li>
                	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt="Red Cards"/></li>
                <?php }  ?>
  					</ul>
           </div>

		      <div class="scores scoresTotals">
  					<ul>
							<li class="name">Totals</li>
							<li class="team">&nbsp;</li>
							<li class="score"><?php echo $this->total_stats_league['total_gp'] ?></li>
							<?php if ($this->playerpos == 'Goalkeeper') {  ?>
								    <li class="score"><?php if (empty($this->total_stats_league['total_cs'])){echo "--"; } else {echo $this->total_stats_league['total_cs'];}?></li>
                    <li class="score"><?php if (empty($this->total_stats_league['total_ga'])){echo "--"; } else {echo $this->total_stats_league['total_ga'];}?></li>
                  	<li class="score"><?php echo $this->total_stats_league['total_yc'] ?></li>
                  	<li class="score"><?php echo $this->total_stats_league['total_rc'] ?></li>

                <?php } else { ?>
                  	<li class="score"><?php echo $this->total_stats_league['total_min'] ?></li>
                    <li class="score"><?php echo $this->total_stats_league['total_gl'] ?></li>
                  	<li class="score"><?php echo $this->total_stats_league['total_yc'] ?></li>
                  	<li class="score"><?php echo $this->total_stats_league['total_rc'] ?></li>
                 <?php }  ?>
  						</ul>
	         </div>



	         <?php $i = 1;
  		      foreach ($this->stats_league as $data) {
  		          if($i % 2 == 1) { $style = "scores1"; }else{ $style = "scores2";}
  	           ?>
  		         <div class="<?php echo $style ?>">
  							<ul>
  									<li class="name"><?php echo $data['season'] ?></li>
                     <?php  $teamRow = $equipo->fetchRow( 'team_gs_id = ' . $data['id'] ); ?>
  									<li class="team">
  										<a href="<?php echo $urlGen->getClubMasterProfileUrl ( $teamRow['team_id'], $teamRow['team_seoname'], True ); ?>">
                            <?php echo mb_convert_encoding($data['name'], "ISO-8859-1", "UTF-8"); ?>
  									  </a>
  									</li>

  									<li class="score"><?php echo $data['appearences'] ?></li>
  									<?php if ($this->playerpos != 'Goalkeeper'){ ?>
                          <li class="score"><?php if (empty($data['minutes'])){echo "--"; } else {echo $data['minutes'];}?></li>
                          <li class="score"><?php if (empty($data['goals'])){echo "--"; } else {echo $data['goals'];}?></li>
                          <li class="score"><?php if (empty($data['yellowcards'])){echo "--"; } else {echo $data['yellowcards'];}?></li>
                          <li class="score"><?php if (empty($data['redcards'])){echo "--"; } else {echo $data['redcards'];}?></li>
  	                    <?php } else { ?>
  	                       <li class="score"><?php if (empty($data['cs'])){echo "--"; } else {echo $data['cs'];}?></li>
                          <li class="score"><?php if (empty($data['ga'])){echo "--"; } else {echo $data['ga'];}?></li>
  	                      <li class="score"><?php if (empty($data['yellowcards'])){echo "--"; } else {echo $data['yellowcards'];}?></li>
                          <li class="score"><?php if (empty($data['redcards'])){echo "--"; } else {echo $data['redcards'];}?></li>
  				          <?php } ?>
  							</ul>
  					   </div>
  				    <?php $i++; } ?>
          </div>

          <!-- Cup Season Stats -->
          <div id="menu9content" class="cont">
            <?php  if (sizeOf($this->stats_cup) > 0) { ?>
              <div class="scores">
                <ul>
                  <li class="name">Season</li>
                  <li class="team">Team</li>
                  <li class="score">GP</li>
                       <?php if ($this->playerpos == 'Goalkeeper') {  ?>
                            <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/hand_for_high_five.gif" alt="Clean Sheets"/></li>
                            <li class="score">GA</li>
                            <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="Yellow Cards"/></li>

                          <?php } else { ?>
                            <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/minutes.jpg" alt="Minutes Played"/></li>
                            <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt="Goals Scored"/></li>
                            <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="Yellow Cards"/></li>
                            <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt="Red Cards"/></li>
                          <?php }  ?>

                  </ul>
              </div>

              <div class="scores scoresTotals">
                <ul>
                  <li class="name">Totals</li>
                  <li class="team">&nbsp;</li>

                  <li class="score"><?php echo $this->total_stats_cup['total_gp'] ?></li>
                  <?php if ($this->playerpos == 'Goalkeeper') {  ?>
                    <li class="score"><?php if (empty($this->total_stats_cup['total_cs'])){echo "--"; } else {echo $this->total_stats_cup['total_cs'];}?></li>
                    <li class="score"><?php if (empty($this->total_stats_cup['total_ga'])){echo "--"; } else {echo $this->total_stats_cup['total_ga'];}?></li>
                    <li class="score"><?php echo $this->total_stats_cup['total_yc'] ?></li>

                  <?php } else { ?>
                   <li class="score"><?php echo $this->total_stats_cup['total_min'] ?></li>
                    <li class="score"><?php echo $this->total_stats_cup['total_gl'] ?></li>
                    <li class="score"><?php echo $this->total_stats_cup['total_yc'] ?></li>
                    <li class="score"><?php echo $this->total_stats_cup['total_rc'] ?></li>
                  <?php }  ?>
                </ul>
              </div>

              <?php $i = 1;
                  foreach ($this->stats_cup as $data) {
                            if($i % 2 == 1) { $style = "scores1"; }else{ $style = "scores2";}
                  ?>
                  <div class="<?php echo $style ?>">
                      <ul>
                          <li class="name">
                              <?php echo str_replace ( '20', '', str_replace ( '/', '-', $data['season'] ) ) ."-" .substr($data['league'], 0, 4);  ; ?>
                          </li>
                           <?php  $teamRow = $equipo->fetchRow( 'team_gs_id = ' . $data['id'] ); ?>
                          <li class="team">
                            <a href="<?php echo $urlGen->getClubMasterProfileUrl ( $teamRow['team_id'], $teamRow['team_seoname'], True ); ?>">
                              <?php echo mb_convert_encoding($data['name'], "ISO-8859-1", "UTF-8"); ?>
                            </a>
                          </li>

                          <li class="score"><?php echo $data['appearences'] ?></li>
                          <?php if ($this->playerpos != 'Goalkeeper'){ ?>
                         <li class="score"><?php if (empty($data['minutes'])){echo "--"; } else {echo $data['minutes'];}?></li>
                              <li class="score"><?php if (empty($data['goals'])){echo "--"; } else {echo $data['goals'];}?></li>
                              <li class="score"><?php if (empty($data['yellowcards'])){echo "--"; } else {echo $data['yellowcards'];}?></li>
                              <li class="score"><?php if (empty($data['redcards'])){echo "--"; } else {echo $data['redcards'];}?></li>
                         <?php } else { ?>
                                <li class="score"><?php if (empty($data['cs'])){echo "--"; } else {echo $data['cs'];}?></li>
                                <li class="score"><?php if (empty($data['ga'])){echo "--"; } else {echo $data['ga'];}?></li>
                                <li class="score"><?php if (empty($data['yellowcards'])){echo "--"; } else {echo $data['yellowcards'];}?></li>
                                <li class="score"><?php if (empty($data['redcards'])){echo "--"; } else {echo $data['redcards'];}?></li>

                         <?php } ?>
                      </ul>
                  </div>
              <?php $i++; } ?>

            <?php } else { ?>
              <span>No Data Available1</span>
            <?php }  ?>
          </div>

        <!-- Regional Season Stats -->
	        <div id="menu7content" class="cont">
	              <?php  if (sizeOf($this->stats_club_int) > 0) { ?>
		              <div class="scores">
        						<ul>
        							<li class="name">Season</li>
        							<li class="team">Team</li>
        							<li class="score">GP</li>
                           <?php if ($this->playerpos == 'Goalkeeper') {  ?>
                           			<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/hand_for_high_five.gif" alt="Clean Sheets"/></li>
                              	<li class="score">GA</li>
                              	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="Yellow Cards"/></li>

                              <?php } else { ?>
                                <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/minutes.jpg" alt="Minutes Played"/></li>
                              	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt="Goals Scored"/></li>
                              	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="Yellow Cards"/></li>
                              	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt="Red Cards"/></li>
                              <?php }  ?>

        						  </ul>
		              </div>

		            	<div class="scores scoresTotals">
      							<ul>
      								<li class="name">Totals</li>
      								<li class="team">&nbsp;</li>

      								<li class="score"><?php echo $this->total_stats_int['total_gp'] ?></li>
      								<?php if ($this->playerpos == 'Goalkeeper') {  ?>
                        <li class="score"><?php if (empty($this->total_stats_int['total_cs'])){echo "--"; } else {echo $this->total_stats_int['total_cs'];}?></li>
                        <li class="score"><?php if (empty($this->total_stats_int['total_ga'])){echo "--"; } else {echo $this->total_stats_int['total_ga'];}?></li>
                        <li class="score"><?php echo $this->total_stats_int['total_yc'] ?></li>

      								<?php } else { ?>
      					       <li class="score"><?php echo $this->total_stats_int['total_min'] ?></li>
                        <li class="score"><?php echo $this->total_stats_int['total_gl'] ?></li>
                        <li class="score"><?php echo $this->total_stats_int['total_yc'] ?></li>
                        <li class="score"><?php echo $this->total_stats_int['total_rc'] ?></li>
      								<?php }  ?>
      							</ul>
	              	</div>


		              <?php $i = 1;
			          	foreach ($this->stats_club_int as $data) {
			                      if($i % 2 == 1) { $style = "scores1"; }else{ $style = "scores2";}
		              ?>
			            <div class="<?php echo $style ?>">
      								<ul>
      										<li class="name">
                              <?php echo str_replace ( '20', '', str_replace ( '/', '-', $data['season'] ) ) ."-" .substr($data['league'], 0, 4);  ; ?>
                          </li>
                          <?php  $teamRow = $equipo->fetchRow( 'team_gs_id = ' . $data['id'] ); ?>
      										<li class="team">
                            <a href="<?php echo $urlGen->getClubMasterProfileUrl ( $teamRow['team_id'], $teamRow['team_seoname'], True ); ?>">
                              <?php echo mb_convert_encoding($data['name'], "ISO-8859-1", "UTF-8"); ?>
                            </a>
                          </li>

      										<li class="score"><?php echo $data['appearences'] ?></li>
      										<?php if ($this->playerpos != 'Goalkeeper'){ ?>
      			             <li class="score"><?php if (empty($data['minutes'])){echo "--"; } else {echo $data['minutes'];}?></li>
                              <li class="score"><?php if (empty($data['goals'])){echo "--"; } else {echo $data['goals'];}?></li>
                              <li class="score"><?php if (empty($data['yellowcards'])){echo "--"; } else {echo $data['yellowcards'];}?></li>
                              <li class="score"><?php if (empty($data['redcards'])){echo "--"; } else {echo $data['redcards'];}?></li>
      			             <?php } else { ?>
      			                    <li class="score"><?php if (empty($data['cs'])){echo "--"; } else {echo $data['cs'];}?></li>
      			                    <li class="score"><?php if (empty($data['ga'])){echo "--"; } else {echo $data['ga'];}?></li>
      			                    <li class="score"><?php if (empty($data['yellowcards'])){echo "--"; } else {echo $data['yellowcards'];}?></li>
                                <li class="score"><?php if (empty($data['redcards'])){echo "--"; } else {echo $data['redcards'];}?></li>

      			             <?php } ?>
      								</ul>
						        </div>
						        <?php $i++; } ?>


					 <?php } else { ?>
						<span>No Data Available</span>
					<?php }  ?>


          </div>



            <!-- Regional Season Stats -->
              <div id="menu8content" class="cont">
                <?php  if (sizeOf($this->stats_national) > 0) { ?>
	               <div class="scores">
        					<ul>
        						<li class="name">Season</li>
        						<li class="team">Team</li>

        						<li class="score">GP</li>
                         <?php if ($this->playerpos == 'Goalkeeper') {  ?>
                         			<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/hand_for_high_five.gif" alt="Clean Sheets"/></li>
                            	<li class="score">GA</li>
                            	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="Yellow Cards"/></li>

                            <?php } else { ?>
                              <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/minutes.jpg" alt="Minutes Played"/></li>
                            	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt="Goals Scored"/></li>
                            	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="Yellow Cards"/></li>
                            	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt="Red Cards"/></li>
                            <?php }  ?>
        					</ul>
	              </div>


  		          <div class="scores scoresTotals">
    							<ul>
    								<li class="name">Totals</li>
    								<li class="team">&nbsp;</li>

    								<li class="score"><?php echo $this->total_stats_nat['total_gp'] ?></li>
    								<?php if ($this->playerpos == 'Goalkeeper') {  ?>
    									<li class="score"><?php if (empty($this->total_stats_nat['total_cs'])){echo "--"; } else {echo $this->total_stats_nat['total_cs'];}?></li>
    									<li class="score"><?php if (empty($this->total_stats_nat['total_ga'])){echo "--"; } else {echo $this->total_stats_nat['total_ga'];}?></li>
    			            <li class="score"><?php echo $this->total_stats_nat['total_yc'] ?></li>
    								<?php } else { ?>
                      <li class="score"><?php echo $this->total_stats_nat['total_min'] ?></li>
    								  <li class="score"><?php echo $this->total_stats_nat['total_gl'] ?></li>
    			            <li class="score"><?php echo $this->total_stats_nat['total_yc'] ?></li>
    			            <li class="score"><?php echo $this->total_stats_nat['total_rc'] ?></li>
    								<?php }  ?>

    							</ul>
  	              </div>


                <?php $i = 1;
		          	foreach ($this->stats_national as $data) {
		                      if($i % 2 == 1) { $style = "scores1"; }else{ $style = "scores2";}
	              ?>
		             <div class="<?php echo $style ?>">
							<ul>
									<li class="name">
                      <?php echo str_replace ( '20', '', str_replace ( '/', '-', $data['season'] ) ) ."-" .substr($data['league'], 0, 7);  ; ?>
                  </li>
                  <?php  
                  		if ($data['id'] != '') {
                  			$teamRow = $equipo->fetchRow( 'team_gs_id = ' . $data['id'] ); 
                  ?>
                  				<li class="team">
					                     <a href="<?php echo $urlGen->getClubMasterProfileUrl ( $teamRow['team_id'], $teamRow['team_seoname'], True ); ?>">
					                    <?php echo $teamRow['team_name']; ?>
					                    </a>
					                  </li>
                  	<?php	} else {
                  			$teamRow['team_id'] = null;
                  			$teamRow['team_seoname'] = $data['name'];

                  	?>
       				<li class="team">
					                
					                    <?php echo $data['name']; ?>
					                    </a>
					                  </li>
                  	<?php
                  		}                 			
                  ?>


									<li class="score"><?php echo $data['appearences'] ?></li>
									<?php if ($this->playerpos != 'Goalkeeper'){ ?>
  			                      <li class="score"><?php if (empty($data['minutes'])){echo "--"; } else {echo $data['minutes'];}?></li>
                              <li class="score"><?php if (empty($data['goals'])){echo "--"; } else {echo $data['goals'];}?></li>
                              <li class="score"><?php if (empty($data['yellowcards'])){echo "--"; } else {echo $data['yellowcards'];}?></li>
                              <li class="score"><?php if (empty($data['redcards'])){echo "--"; } else {echo $data['redcards'];}?></li>
			                    	<?php } else { ?>
			                        <li class="score"><?php if (empty($data['cs'])){echo "--"; } else {echo $data['cs'];}?></li>
                              <li class="score"><?php if (empty($data['ga'])){echo "--"; } else {echo $data['ga'];}?></li>
                              <li class="score"><?php if (empty($data['yellowcards'])){echo "--"; } else {echo $data['yellowcards'];}?></li>
                              <li class="score"><?php if (empty($data['redcards'])){echo "--"; } else {echo $data['redcards'];}?></li>
			                     	<?php } ?>


          							</ul>
          					</div>
          					<?php $i++; } ?>

          				<?php } else { ?>
          					<span>No Data Available</span>
          				<?php }  ?>
	           </div>




	           <span class="thread3">
            	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerstatsdetail/id/<?php echo $this->playerid; ?>">See Seasom Statistics &raquo;</a>
          	   </span>

             </div>

       </div>

      <?php // } ?>

  <?php if($this->playeractualteam == 1) {?>
    <div class="featured1" style="margin-top: 12px;">
			<p class="mblack">
	           <span class="black"><?php echo $this->playername;?> Teammates</span>
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

		          	foreach ($this->playermates as $data) {
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
						          <?php } else {  ?>
						         	<li class="silueta">
						              <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale30.gif" alt="<?php echo $data["player_common_name"]; ?>"/>
						            </li>
						          <?php }  ?>

			                       <li class="name">
			                       	<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>">
			                       		<?php echo $data["player_name_short"]; ?>
			                       	</a>
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
	          	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammates/id/<?php echo $this->playerid; ?>">See More Player Details</a>
	          </span>
        </div>
        
     <?php } ?>


     </div><!--/SecondColumn-->

     <div class="ThirdColumn">

     	  <div id="banner_right_wide" class="img-shadow" style="margin-top: -10px;">
  					<div style="float: left; padding:0px;border:none;">
                 <a href="<?php echo Zend_Registry::get("contextPath"); ?>/subscribe#playerSection" title="Subscriptions and Alers">
                     <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/banners_player_wide.png" style="margin-top:10px;"/>
              	</a>
            </div>
          </div>

      <!-- Team Media Gallery -->
      <!-- END - Team Media Gallery -->

        <!--/Activity Feed-->
	    <?php if($this->playeractualteam == 1) {?> 
         <div class="img-shadow">
		      <div class="WrapperForDropShadow">
		        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
		        <h4 class="NoArrowLeft">
		            <?php echo $this->playername;?> Activity Feed (
		            <?php echo sizeOf($this->playeractivities);?>)
		        </h4>
		          <?php if (sizeOf($this->playeractivities) > 0) { ?>
		          <span>
		          	 <a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayeractivity/id/<?php echo $this->playerid?>">See more &raquo;</a>
		            </span>
		          <?php } ?>
		        </div>
		        <?php if (sizeOf($this->playeractivities) > 0) { ?>
		        <div class="BlueShaded DisplayDropdown">
		        	Show:
						<select id="FriendFeedselect" class="slct" name="FriendFeed1select" onchange="javascript:findUserActivity(this.value)">
						    <option value="0" selected>All Activity</option>
						    <option value="1">Goals </option>
						     <option value="2">Appearances</option>
						    <option value="3">Cards</option>
						    <!--<option value="4">Community</option>-->
						</select>
		          <div class="JoinedDate">

		              <a class="OrangeLink" style="padding-right:20px;" href="<?php echo Zend_Registry::get("contextPath"); ?>/player/rss/id/<?php echo $this->playerid?>">Subscribe</a>
		          </div>
		        </div>
		        <div id="playerFeed">

		        </div>
		        <?php } else { ?>
					<center><strong>No Player Activity Available</strong></center>
				<?  }?>

		       	<?php if (sizeOf($this->playeractivities) > 0) { ?>
		          <div id="SeeMoreNews" class="SeeMoreNews">
		              <a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayeractivity/id/<?php echo $this->playerid?>">See More <?php echo $this->playername?> Activity</a>
		          </div>
		         <?  }?>
		      </div>
		    </div>
		    <?php } ?> 
		    
		    
	    	 <?php if ($session->email != null) { ?>
	        <!--/Goooalshout-->
	        <div id="goalshoutId" class="img-shadow">
	          <?php echo $this->render('goalshoutplayer.php');?>
	        </div>
			<?php }  else{?>
		    <div class="img-shadow">
		            <div class="WrapperForDropShadow">
		                <div class="DropShadowHeader BlueGradientForDropShadowHeader">

		                    <h4 class="NoArrowLeft">
		                    	<?php echo $this->playername;?> Goooal Shouts
		                    </h4>
							<span class="sm" id="menu6_more">
			                   <a href="javascript:loginModal();"" title="<?php echo $this->playername;?> Fans">More »</a>
			               </span>
		                </div>
		                <div class="boxMessage">
		                    <div class="preRegMessage">
		                    You must <a href="javascript:loginModal();" title="Sign In">Sign in</a> to see <?php echo $this->playername;?>'s Goooal Shouts .
		                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">Click here to register</a> if you are not already a GoalFace member.
		                    </div>
		                    <div id="SeeMoreNews" class="SeeMoreNews">
		                        <a class="OrangeLink" onclick="openbrowserlogin();" href="javascript:void(0)">See More
		                        <?php echo $this->playername;?> Goooal Shouts </a>
		                    </div>
		                </div>
		           </div>
		    </div>
		    <?php } ?>
		   <div class="img-shadow">
		            <div class="WrapperForDropShadow">
		             <?php if ($session->email != null) { ?>
		                <div class="DropShadowHeader BlueGradientForDropShadowHeader">

		                    <h4 class="NoArrowLeft">
		                    	<?php echo $this->playername;?> Fans
		                    </h4>
							 <span class="sm" id="menu6_more">
			                   <a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerfans/id/<?php echo $this->playerid; ?>" title="<?php echo $this->playername;?> Fans">More »</a>
			               </span>
		                </div>
		               <?php } else {?>
		               <div class="boxMessage">
		                    <div class="preRegMessage">
		                    You must <a href="javascript:loginModal();" title="Sign In">Sign in</a> to see <?php echo $this->playername;?>'s Fans.
		                    <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">Click here to register</a> if you are not already a GoalFace member.
		                    </div>
		                    <div id="SeeMoreNews" class="SeeMoreNews">
		                        <a class="OrangeLink" onclick="openbrowserlogin();" href="javascript:void(0)">See More
		                        <?php echo $this->playername;?> Fans</a>
		                    </div>
		                </div>
		                <?php }?>
		           </div>
		    </div>



     </div><!--/ThirdColumn-->

</div> <!--/ContentWrapper-->

<script type="text/JavaScript">
/*The values here will be used to preninitialize the player or team Id and Name (AutoSuggest)
/You can set server side variables here as shown and they will be use for preinitialization*/
var playerAInitId="<?php echo $this->playerid;?>";
var playerAInitName = "<?php echo $this->playername;?>";
var playerAInitTeamId="";
var playerAInitTeamName="";
var playerAInitCountryId="";
var playerAInitCountryName="";
var playerBInitId="";
var playerBInitName = "";
var playerBInitTeamId="";
var playerBInitTeamName="";
var playerBInitCountryId="";
</script>

<?php include 'include/playerh2h.php';?>

<script type="text/JavaScript">
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
</script>

<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeplayer.js" type="text/javascript"></script>

