<?php 
    require_once 'seourlgen.php';
    $urlGen = new SeoUrlGen();
    $session = new Zend_Session_Namespace('userSession');
?>

<script type="text/JavaScript">

    jQuery(document).ready(function(){
      callHomeTeamGallery();
      callRandonProfiles();
      <?php if ($this->multiNatSeason == 'yes') { ?>
    	showNationalTeamSquad()
      <?php } ?>	
      
      
    });

    //Load Team Pictures
    function callHomeTeamGallery()
    {
        jQuery('#PhotoGalleryList').html("<div class='ajaxload widget'></div>");
        jQuery.ajax({
            method: 'get',
            url : '<?php echo Zend_Registry::get("contextPath"); ?>/photo/showhomepagesphotos/numphotos/4/typeid/1/id/<?php echo $this->teamId;?>',
            dataType : 'text',
            success: function (text) {
                jQuery('#tab1content').html(text);
            }
         });
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
    
    //Goooalshouts
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
    
    
</script>

<div id="ContentWrapper">

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
              
         <?php } ?>
         
        <!--Team Profile Badge-->
        <div class="img-shadow">
            <?php echo $this->render('include/badgeTeamNew.php');?>
        </div>
        
        <!--Team Profile left Menu-->
        <div class="img-shadow">
            <?php echo $this->render('include/navigationTeam.php');?>
        </div>
         
     </div><!--/FirstColumnOfThree-->
     
     <div class="SecondColumn">
     
     	  <div class="proff">
             <div class="prof1">
                <h1><?php echo $this->teamname; ?></h1>
                      <?php if ($this->teamtype != 'national') { ?>
                        <span class="age1"><b>Full Name: </b><?php echo $this->team[0]['team_name_official']; ?></span>
                        <span class="age1"><b>Location: </b><?php  if (empty($this->team[0]['team_city'])) {echo "&nbsp;Unavailable"; } else {echo $this->team[0]['team_city'];} ?> </span>
                        <span class="age1"><b>Founded: </b><?php  if  (empty($this->team[0]['team_founded']))  {echo "&nbsp;Unavailable"; } else {echo $this->team[0]['team_founded'];} ?></span>
                        <span class="age1"><b>Nickname: </b><?php  if (empty($this->team[0]['team_nickname'])){echo "&nbsp;Unavailable"; } else {echo $this->team[0]['team_nickname'];} ?></span>
                        <span class="age1"><b>Chairman: </b><?php  if (empty($this->team[0]['team_chairman'])){echo "&nbsp;Unavailable"; } else {echo $this->team[0]['team_chairman'];} ?></span>
                        <span class="age1"><b>Manager: </b><?php  if (empty($this->team[0]['team_manager'])){echo "&nbsp;Unavailable"; } else {echo $this->team[0]['team_manager'];} ?></span>
                      <?php } else { ?>
                      
                      <?php } ?>
                      
              </div>
              
              <span class="favor">
                <input type="button" value="Add to Favorites" class="submit"/>
              </span>
                
                <span class="age2"><b>Profile</b><br/>Up to # lines/## characters of bio text displays here if entered by user and set to public. If not entered or set to private it would not display here. </span>
                <span class="full"><a href="#">View Full Profile &gt;</a></span>
                <span class="report"><span class="report1">Contribute or report an error on this page</span></span>
            </div>
				
        <div class="prof">
          <p class="mblack">
            <span class="black"><?php echo $this->teamname; ?>'s 2010-2011 Overview</span>
          </p>
          <p class="wter">
          <span class="wter1">
            <?php
            $config = Zend_Registry::get ( 'config' );
            $path_team_logos = $config->path->images->teamlogos . $this->teamid .".gif" ;
            if (file_exists($path_team_logos))
           { ?>
            <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=81&h=81&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $this->teamid ; ?>.gif"/>
        <?php } else {  ?>
            <img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=81&h=81&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText.gif" align="center"/>
        <?php }  ?>
          </span>
          <span class="wter2">
              <span class="chel"><?php echo $this->teamname; ?></span>
              <span class="lea">League: <a href="#">English Premier League</a></span>
              <span class="lea">Position:<a href="#"> 2nd</a></span>
              <span class="lea">Country: <a href="#">England</a></span>
          </span>
          </p>
          <!--/use scores and scores1 for zebra-->
            <div class="scores">
                <ul>
                    <li class="name">League</li>
                    <li class="cont">Position</li>
                    <li class="score">GP</li>
                    <li class="score">W</li>
                    <li class="score">L</li>
                    <li class="score">D</li>
                    <li class="score">Pts</li>
                </ul>
            </div>

          <div class="pre1">
                <ul>
                    <li class="pree"><?php $this->league; ?></li>
                    <li class="no1"><?php $this->position; ?></li>
                    <li class="no1"><?php $this->gamesp; ?></li>
                    <li class="no1"><?php $this->wins; ?></li>
                    <li class="no1"><?php $this->losses; ?></li>
                    <li class="no1"><?php $this->draws; ?></li>
                    <li class="no1"><?php $this->points; ?></li>
                </ul>
           </div>
           <div class="nxt">
                <ul>
                        <li class="active" id="menu4" onclick="return showTab('4')"><a href="#">Next Match</a></li>
                        <li id="menu5" onclick="return showTab('5')"><a href="#">Latest Match</a></li>
                </ul>
           </div>
           <div class="nmatch" id="menu4content">
                <p class="sfull">See Full Schedule &gt;</p>
                <p class="champ">Competition: <a href="#">Champions League</a> <br/> Date: <a href="#">Wednesday - February 24, 2010</a></p>
                <div class="vs">
                        <p class="chelsa"><a href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/chelsa.jpg" alt=""/></a></p>
                        <p class="vs1">Vs</p>
                        <p class="wterr"><a href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/wter.jpg" alt=""/></a></p>
                </div>
                <p class="smatch">See Match Preview &gt;</p>
            </div>
            <div class="nmatch" id="menu5content" style="display:none;">
                <p class="sfull">See Full Schedule &gt;</p>
                <p class="smatch">See Match Preview &gt;</p>
	   </div>
      </div>
        
        
     <?php if ($this->multiNatSeason == 'yes') { ?>
      	<!--/Team Squad National Team-->
     	<div class="featured1">
				<p class="mblack">
		           <span class="black"><?php echo $this->team[0]['team_name']; ?> Players</span>
		           <span class="sm"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamsquad/id/<?php echo $this->teamId;?>" title="<?php echo $this->teamname;?> Players">More &raquo;</a></span>
		        </p>
		        <p class="show" id="seasons" style="display: block;">
		                    <select id="season_selected" class="all" name="seasons_selected">
		                    <?php $i = 0; ?>
		                     <?php foreach($this->natseasons as $seasons) { ?>
		                        <option <?php if($i == 0){ ?>selected<?php } ?> value="<?php echo $seasons["season_id"]; ?>"><?php echo $seasons["competition_name"]; ?>&nbsp;<?php echo $seasons["title"]; ?></option>
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
			             		<?php if ($data['imagefilename']!=null || $data['imagefilename']!='') { ?>
				                	<li class="silueta"><img alt="<?php echo $data["player_common_name"]; ?>"
				                    src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $data["imagefilename"] ?>" /></li>
				                <?php } else { ?>
				                	<li class="silueta"><img alt="<?php echo $data["player_common_name"]; ?>"
				                    src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif" /></li>
				                <?php } ?>
	
			                       <li class="name">
			                       	<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>"><?php echo $data["player_name_short"]; ?></a>
			                       </li>
			                       <li class="position"><?php echo $data["player_position"]; ?></li>
			                       <li class="team" style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $data["player_country"]; ?>.png') no-repeat scroll left center transparent;">
			                            <?php echo $data["country_name"]; ?>
			                       </li>
			        
			                   </ul>
			             </div>
		        
		          <?php $i++; } ?>

	          </div>
	          

	          <span class="seemore">
	          	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammates/id/<?php echo $this->playerid; ?>">See More Player Teammates</a>
	          </span>
        </div>
     
     <?php }  ?> 
  


    <!--/Team League Performance-->
        <div class="prof2">
             <p class="mblack">
                <span class="black"><?php echo $this->teamname; ?>'s League Performance</span>
            </p>
            <div class="ballnm">
                <ul>
                	<li class=silueta"></li>
                    <li class="name">Season</li>
                    <li class="team">League</li>
                    <li class="cont">Position</li>
                </ul>
            </div>
            <div class="ballnm1">
               <ul>
               	<li class=silueta"></li>
                <li class="name" style="background: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/siloute_18x18.gif) no-repeat scroll left center transparent;">Player Name</li>
                <li class="team">Team Name</li>
                <li class="country" style="background: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/176.png) no-repeat scroll left center transparent;">Spain</li>
               </ul>
            </div>
            <div class="ballnm2">
                            <ul>
                            <li class=silueta"></li>
                                            <li class="name" style="background: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/siloute_18x18.gif) no-repeat scroll left center transparent;">Player Name</li>
                                            <li class="team">Team Name</li>
                                            <li class="country" style="background: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/176.png) no-repeat scroll left center transparent;">Spain</li>
                            </ul>
            </div>
           <div class="ballnm1">
                            <ul>
                            <li class=silueta"></li>
                                            <li class="name" style="background: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/siloute_18x18.gif) no-repeat scroll left center transparent;">Player Name</li>
                                            <li class="team">Team Name</li>
                                            <li class="country" style="background: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/176.png) no-repeat scroll left center transparent;">Spain</li>
                            </ul>
            </div>
            <div class="ballnm2">
                            <ul>
                            <li class=silueta"></li>
                                            <li class="name" style="background: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/siloute_18x18.gif) no-repeat scroll left center transparent;">Player Name</li>
                                            <li class="team">Team Name</li>
                                            <li class="country" style="background: url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/176.png) no-repeat scroll left center transparent;">Spain</li>
                            </ul>
            </div>
            
         
            <span class="thread3"><a href="#">See More Teammates &gt;</a></span>
	 </div>
        
    <div class="prof2">
             <p class="mblack">
                   <span class="black"><?php echo $this->teamname; ?>'s Honors &amp; Distinctions</span>
                </p>
            <div class="ballyr">
                            <ul>
                                            <li class="year">Year</li>
                                            <li class="comp">Competition</li>
                                            <li class="honor"> Honor</li>
                            </ul>
            </div>
            <div class="ballyr1">
                            <ul>
                                            <li class="year">'07-'08</li>
                                            <li class="comp">Ligue 1</li>
                                            <li class="honor"> Top Scorer <br/>Winners Medal</li>
                            </ul>
            </div>
            <div class="ballyr2">
                            <ul>
                                            <li class="year">'07</li>
                                            <li class="comp">FIFA </li>
                                            <li class="honor"> Player of the Year</li>
                            </ul>
            </div>
            <div class="ballyr1">
                            <ul>
                                            <li class="year">'05-'06</li>
                                            <li class="comp">Coupe de la Ligue </li>
                                            <li class="honor">Runners Up Medal</li>
                            </ul>
            </div>
            <div class="ballyr2">
                            <ul>
                                            <li class="year">'03</li>
                                            <li class="comp">Ligue 1 </li>
                                            <li class="honor">Winners Medal</li>
                            </ul>
            </div>
        </div>


     </div><!--/SecondColumn--> 

     <div class="ThirdColumn">
     
        <div class="featured1">
              <p class="black"><?php echo $this->teamname; ?>'s Media</p>
              <div class="picture" id="mediaTab">
                      <ul>
                            <li id="tab1" class="active"><a href="javascript:void(0);">Photos</a></li>
                            <li id="tab2"><a href="javascript:void(0);">News</a></li>
                      </ul>
              </div>
              <div id="tab1content" class="cont">
        							
        			 </div>
        			 
              <div id="tab2content" class="cont" display="none;">
              
              </div>
              
              
        </div>
        
        <!-- Goooalshouts -->
        <div id="goalshoutId" class="img-shadow">
            <?php echo $this->render('goalshoutteam.php');?>
        </div>
        
        <!-- Profiles -->
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                    <div id="teamfansid" class="DownArrow"></div>
                    <h4 class="WithArrowToLeft"><?php echo $this->team[0]['team_name']; ?> Fans</h4>
                <span> <a href="<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamfans/id/<?php echo $this->team[0]["team_id"];?>">More Profiles&raquo;</a> </span></div>

		          <div id="teamfansBodyid">
                    <div id="randomprofiles">Loading...</div>

                    <div class="HomeSecondColumnBottomOfProfile">
                        <a 	href="<?php 	echo $urlGen->getMainProfilesPage ( True ); 	?>">Browse</a>|
                        <a 	href="<?php 	echo $urlGen->getSearchProfilesPage ( True ); 	?>">Search</a>
                    </div>
                </div>
            </div>
        </div> 
            
            
     </div><!--/ThirdColumn--> 

</div> <!--end ContentWrapper-->
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
           
