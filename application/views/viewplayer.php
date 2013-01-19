<?php $session = new Zend_Session_Namespace ( 'userSession' ); ?>
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen ( );
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
?>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/popup.js" type="text/javascript"></script>

<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<script type="text/JavaScript">

jQuery(document).ready(function() {
    
	callHomePlayerGallery();

	 jQuery('#addtofavoriteplayertrigger').click(function(){
		 addfavoriteplayer();
 	 });
	 jQuery('#removefromfavoriteplayertrigger').click(function(){
		 removefavoriteplayer();
 	 });

	 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findactivities/id/<?php echo $this->playerid; ?>';
	 jQuery('#playerFeed').html('Loading...'); 
	 jQuery('#playerFeed').load(url);


	 callRandonProfiles();
     //load players stats default view
     showPlayerStats();


	 jQuery('#sendShoutTrigger').click(function(){
		 sendGoalShout();
        });

        //assign show player stats function to event click "ok" button in dropdown
	jQuery('#buttonStats').click(function(){ //
 		showPlayerStats();
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

	 showHideDivBox('playerfansid','playerfansBodyid');

	//to get current url encoded
	// var currentURL = encodeURIComponent(location.href);
	 //jQuery('.facebook_button').attr('src', jQuery('.facebook_button').attr('src') + currentURL); 

});


     function showPlayerStats(){

            var formatType = jQuery('#typeComp').val();
            jQuery('#PlayerStats').html("<div class='ajaxload widget'></div>");
            jQuery.ajax({
                method: 'get',
                url : '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerministats/playerId/<?php echo $this->playerid;?>/playerpos/<?php echo $this->playerpos;?>/format/'+formatType,
                dataType : 'text',
                success: function (text) {                      
                       jQuery('#PlayerStats').html(text);
                }
             });

     }

	 function findUserActivity(type){
		  
			var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findactivities/id/<?php echo $this->playerid;?>/type/'+type;
			jQuery('#playerFeed').html('Loading...'); 
			jQuery('#playerFeed').load(url);
	
	 }

  
     //Load Player Pictures
    function callHomePlayerGallery(){
    jQuery('#PhotoGalleryList').html("<div class='ajaxload widget'></div>");
	jQuery.ajax({
        method: 'get',
        url : '<?php echo Zend_Registry::get("contextPath"); ?>/photo/showhomepagesphotos/numphotos/4/typeid/2/id/<?php echo $this->playerid;?>',
        dataType : 'text',
        success: function (text) {
			jQuery('#PhotoGalleryList').html(text);
		}
     });
    }

	//Load Random Profiles
	function callRandonProfiles()
	{
	      jQuery.ajax({
	                    method: 'get',
	                    url : '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofilesrandom/playerId/<?php echo $this->playerid;?>',
	                    dataType : 'text',
	                    success: function (text) {
	          				jQuery('#randomprofiles').html(text);
	          									 }
	                 });

	}

	//setInterval(callRandonProfiles, 60000);

	
	function addfavoriteplayer(){

		 jQuery('#modalBodyId').show();
		 jQuery('#modalBodyResponseId').hide();
		 jQuery('#acceptFavoriteModalButtonId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
		 jQuery('#modalFavoriteTitleId').html('Add to your Favorites');
		 jQuery('#dataText1').html("Add <?php echo $this->playername;?> to your favorite Players?");
		 jQuery('#dataText1').attr('href','<?php echo $urlGen->getPlayerMasterProfileUrl($this->playernickname, $this->playerfname, $this->playerlname, $this->playerid, true ,$this->playercommonname);?>');
		 jQuery('#title1Id').html('Team:');
		 jQuery('#dataText2').html("<?php echo $this->playerteamclub;?>");
		 jQuery('#dataText2').attr('href','<?php echo $urlGen->getClubMasterProfileUrl ( $this->playerteamid, $this->playerteamseoclub, True ); ?>');
	
		 jQuery('#title2Id').html('Nationality:');
		 jQuery('#dataText3').html("<?php if (empty($this->playercountry)){echo 'Unavailable'; } else {echo  $this->playercountry;} ?>");
		 jQuery('#title3Id').html('Position:');
		 jQuery('#dataText4').html("<?php if (empty($this->playerpos)){echo 'Unavailable'; } else {echo $this->playerpos;} ?>");
		 
		 jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteplayertrigger', onHide: closeModal });
		 jQuery('#addFavoriteModal').jqmShow();
		 
		 var favoriteImage = null;

		 <?php if ($this->filename!= null or $this->filename!= '') { ?>
		 	favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop; ?>/players/<?php echo $this->filename ?>';
       	<?php  } else { ?>
       		favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/Player1Text.gif';
       	<?php  } ?>	
		 
		 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

		 var playerId = '<?php echo $this->playerid;?>';	
		 
		 jQuery("#acceptFavoriteModalButtonId").unbind();
		 jQuery('#acceptFavoriteModalButtonId').click(function(){
		 jQuery.ajax({
				type: 'POST',
				url :  '<?php echo Zend_Registry::get("contextPath"); ?>/player/addfavorite',
				data : ({playerId: playerId}),
				success: function(data){
					jQuery('#modalBodyResponseId').html("<?php echo $this->playername;?> has been added to your favorites.");
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					jQuery('#favorite').removeClass('Display').addClass('ScoresClosed');
				 	jQuery('#remove').removeClass('ScoresClosed').addClass('Display');
					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
				}	
			})
			
		 });	
	}

	function removefavoriteplayer(){

		 jQuery('#modalBodyId').show();
		 jQuery('#modalBodyResponseId').hide();
		 jQuery('#acceptFavoriteModalButtonId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
		 jQuery('#modalFavoriteTitleId').html('Remove from Favorites');
		 jQuery('#dataText1').html("Remove <?php echo $this->playerfname;?>&nbsp;<?php echo $this->playerlname;?> from your Favorites?");
		 jQuery('#dataText1').attr('href','<?php echo $urlGen->getPlayerMasterProfileUrl($this->playernickname, $this->playerfname, $this->playerlname, $this->playerid, true ,$this->playercommonname);?>');
		 jQuery('#title1Id').html('Team:');
		 jQuery('#dataText2').html("<?php echo $this->playerteamclub;?>");
		 jQuery('#dataText2').attr('href','<?php echo $urlGen->getClubMasterProfileUrl ( $this->playerteamid, $this->playerteamseoclub, True ); ?>');
	
		 jQuery('#title2Id').html('Nationality:');
		 jQuery('#dataText3').html("<?php if (empty($this->playercountry)){echo 'Unavailable'; } else {echo  $this->playercountry;} ?>");
		 jQuery('#title3Id').html('Position:');
		 jQuery('#dataText4').html("<?php if (empty($this->playerpos)){echo 'Unavailable'; } else {echo $this->playerpos;} ?>");
		 
		 jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteplayertrigger', onHide: closeModal });
		 jQuery('#addFavoriteModal').jqmShow();
		 
		 var favoriteImage = null;

		 <?php if ($this->filename!= null or $this->filename!= '') { ?>
		 	favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $this->filename ?>';
      	<?php  } else { ?>
      		favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/Player1Text.gif';
      	<?php  } ?>	
		 
		 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

		 var playerId = '<?php echo $this->playerid;?>';	
		 
		 jQuery("#acceptFavoriteModalButtonId").unbind();
		 jQuery('#acceptFavoriteModalButtonId').click(function(){
		 jQuery.ajax({
				type: 'POST',
				url :  '<?php echo Zend_Registry::get("contextPath"); ?>/player/removefavorite',
				data : ({id: playerId}),
				success: function(data){
					jQuery('#modalBodyResponseId').html("<?php echo $this->playername;?> has been removed from your Favorites.");
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					jQuery('#remove').removeClass('Display').addClass('ScoresClosed');
				 	jQuery('#favorite').removeClass('ScoresClosed').addClass('Display');
					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
				}	
			})
			
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

	 function deleteGoalShout(id){

		 jQuery('#acceptModalButtonId').show();
		 jQuery('#cancelModalButtonId').attr('value','Cancel'); 	
		 jQuery('#modalTitleConfirmationId').html('DELETE GOOOALSHOUT?');
		 jQuery('#messageConfirmationTextId').html('Are you sure you want to delete a goalshout');	
		 
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

	 function reportAbuse(id, reportTo){

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

				jQuery('#goalshoutId').load(url ,{playerid :playerid , id : id ,reportTo : reportTo , dataReport : dataReport ,reportType:reportType} , function (){
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

<div class="FirstColumnOfThree">
<?php if ($session->email != null) { ?>
    <div class="img-shadow">
      <div class="WrapperForDropShadow">
<?php include 'include/loginbox.php'; ?>
      </div>
    </div>
    
<?php } else { ?>
    <!--Me box Non-authenticated-->
    <div class="img-shadow">
      <div class="WrapperForDropShadow">
<?php include 'include/loginNonAuthBox.php'; ?>
      </div>
    </div>
    <!--Goalface Register Ad-->
<?php } ?>



 <!--Player Profile Badge-->
    <div class="img-shadow">
        <?php echo $this->render('include/badgePlayer.php');?>
    </div>

    <!--Player Profile left Menu-->
    <div class="img-shadow">
       <?php echo $this->render('include/navigationPlayer.php');?>
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

  </div>
  <!--end FirstColumnOfThree-->
  <div class="SecondColumnOfThree">

    <div class="img-shadow">
        <div class="WrapperForDropShadow">
            <div id="ProfileInfoContainer">
                <h1><?php echo $this->playername;?></h1>
                   
                   <div class="profilebuttons">
                   	            
                      <?php
	                    if ($this->isFavorite == 'false') {
	                        if ($session->email != null) { ?>
	                                <li id="favorite">
	                                  <input id="addtofavoriteplayertrigger" class="submit" type="button" value="Add to favorites" style="display: inline;"/></li>
	                                <li id="remove" class="ScoresClosed"><input id="removefromfavoriteplayertrigger" class="submit" type="button" value="Remove from favorites" style="display: inline;"/></li>
	                      <?php } else { ?>
	                                <li id="favorite">
	                                  <input id="addtofavoriteplayerNonLoggedtrigger" onclick="loginModal()" class="submit" type="button" value="Add to favorites" style="display: inline;"/>
	                                </li>
	                      <?php  }
	                    } else { ?>
	                          <li id="favorite" class="ScoresClosed">
	                            <input id="addtofavoriteplayertrigger" class="submit" type="button" value="Add to favorites" style="display: inline;"/>
	                          </li>
	                          <li id="remove">
	                            <input id="removefromfavoriteplayertrigger" class="submit" type="button" value="Remove from favorites" style="display: inline;"/>
	                          </li>
	                    <?php }?>
                     
                      <?php if ($server != 'local') { ?>
                       <!--Twitter and facebook buttons here-->
                       <span class="tweeterbutton" style="padding-left:4px;">
                        <a href="http://twitter.com/share" class="twitter-share-button" style="padding-bottom:5px;width:100px;" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
                      </span>
                     <span class="fbbutton">
                        <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>&layout=button_count&show_faces=false&width=80&action=like&font=verdana&colorscheme=light" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:22px;padding-bottom:3px;" allowTransparency="true"></iframe>
                      </span>
                      <?php }?>
  
		            </div>
                    
                   <div id="ProfileInfo">                      
                        <strong>Full Name: </strong><?php echo $this->playerfname;?>&nbsp;<?php echo $this->playerlname;?><br>
                        <strong>Age: </strong><?php if (empty($this->playerage)) {echo "&nbsp;Unavailable"; } else {echo $this->playerage;}?>  <br>
                        <strong>Birthdate: </strong><?php if (empty($this->playerdob))  {echo "&nbsp;Unavailable"; } else {echo $this->playerdob;} ; ?><br>
                        <strong>Place of Birth: </strong><?php if (empty($this->playerdobcity)){echo "&nbsp;Unavailable"; } else {echo $this->playerdobcity;}?><br>
                        <strong>Height: </strong><?php if (empty($this->playerheight)){echo "&nbsp;Unavailable"; } else {echo $this->playerheight . "&nbsp;cm";}?><br>
                        <strong>Weight: </strong><?php if (empty($this->playerweight)){echo "&nbsp;Unavailable"; } else {echo $this->playerweight . "&nbsp;kg" ;}?><br>
                        <p/>
                  </div>
            </div>
        </div>
    </div>
    

    <div class="img-shadow">
      <div class="WrapperForDropShadow">
        <div class="DropShadowHeader BlueGradientForDropShadowHeader">            
            <h4 class="WithArrowToLeft"><?php echo $this->playername;?>'s Career Statistics</h4>
            <?php if ($server == 'local') { ?>
                <span>
                    <a href="<?php echo $urlGen->getPlayerMasterProfileStatsUrl($this->playernickname, $this->playerfname, $this->playerlname, $this->playerid, true); ?>">See more &raquo;</a>
                </span>
            <?php } ?>
        </div>

        <div class="BlueShaded DisplayDropdown">
            Show:
            <select id="typeComp" class="slct" name="typeComp">
                <option selected="selected" value="1">League</option>
                <option value="2">Regional Competition</option>
                <option value="3">National Team</option>
            </select>
            <input id="buttonStats" class="submit" type="submit" value="Ok" style="display: inline;"/>
        </div>

         <div id="PlayerStats" class="TeamPsuedoTables">
                <p id="NoLeagueData" style="display:none;">No league statistics available for</p>
                <p id="NoRegionalData" style="display:none;">No regional competition information available for</p>
                Loading...    
         </div>
         
      </div>
    </div>



    <div class="img-shadow">
      <div class="WrapperForDropShadow">
        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
        <h4 class="WithArrowToLeft"><?php echo $this->playername; ?>'s Teammates</h4>
           <span>
              <a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammates/id/<?php echo $this->playerid; ?>">See more &raquo;</a>
           </span>
        </div>
        <div id="TeamPlayerList" class="TeamPsuedoTables">
            <ul class="Header">
                <li class="ColumnOne"></li>
                <li class="ColumnTwo">Name</li>
                <li class="ColumnThree">Position</li>
                <li class="ColumnFour">Nationality</li>
            </ul>


            <?php $i = 1;
                if(sizeof($this->playermates) <= 0){
                	echo "<div  style=\"text-align:center\"><br/>No teammates</div>";
                }else {
            	foreach ($this->playermates as $data)
                   {
                      if($i % 2 == 1)
              {
              $style = "";
              }else{
                $style = "AltRow";
              }
            ?>

              <ul class="<?php echo $style; ?>">
              <?php if ($data['imagefilename']!=null || $data['imagefilename']!='') { ?>
                <li class="ColumnOne"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop; ?>/players/<?php echo $data["imagefilename"] ?>"/></li>
              <?php } else { ?>
                   <li class="ColumnOne"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/></li>
               <?php } ?>
                <li class="ColumnTwo">
                    <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>">
                        <?php echo $data["player_name_short"]; ?>
                    </a>
                </li>
                  <li class="ColumnThree"><?php echo $data["player_position"]; ?></li>
                  <li class="ColumnFour"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $data["player_country"]; ?>.png">&nbsp;<?php echo $data["country_name"]; ?></li>
              </ul>

             <?php $i++; } 
                }
              ?>
            </div>
            
            <div class="SeeMoreNews" id="SeeMoreNews">
          <a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammates/id/<?php echo $this->playerid; ?>"  class="OrangeLink">See More Player Details</a>
        </div>
      </div>
    </div>

  </div>
  <!--end SecondColumnOfThree-->

  <div class="ThirdColumn">
   

     <!-- Competition Gallery -->
    <div class="featured1">
        <p class="mblack">
            <span class="black"><?php echo $this->playername;?> Pictures</span>
             <span class="sm">
               <a href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogallery/id/<?php echo $this->playerid?>/type/2" title="<?php echo $this->playername;?> Pictures">More &raquo;</a>
           </span>
        </p>

            <div id="PhotoGalleryList" class="imgscontent">

            </div>

        <p class="modfooter"><a class="orangelink" href="<?php echo Zend_Registry::get("contextPath"); ?>/photo/showphotogallery/id/<?php echo $this->playerid?>/type/2">See More <?php echo $this->playername;?> Pictures &raquo;</a></p>
	 </div>





  <?php if ($session->email != null) { ?>
  
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
				    <option value="0">All Activity</option>
				    <option value="1">On The Field</option>
				    <!-- <option value="2">News</option>
				    <option value="3">Pictures</option> -->
				    <option value="4">Community</option>
				</select>
          <div class="JoinedDate">
          	  	
              <a class="OrangeLink" style="padding-right:20px;" href="<?php echo Zend_Registry::get("contextPath"); ?>/player/rss/id/<?php echo $this->playerid?>">Subscribe</a>
          </div>
        </div>
        <?php } ?>
        <div id="playerFeed">
          
        </div>
         <?php if (sizeOf($this->playeractivities) > 0) { ?>
          <div id="SeeMoreNews" class="SeeMoreNews">
              <a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayeractivity/id/<?php echo $this->playerid?>">See More <?php echo $this->playername?> Activity</a>
          </div>
          <?  }?>
      </div>  
    </div>
    
    <div id="goalshoutId" class="img-shadow">
      <?php echo $this->render('goalshoutplayer.php');?>
    </div>
    
    <!-- Profiles -->
        <div class="prof">
              <p class="mblack">
               <span class="black"><?php echo $this->playername;?> Fans</span>
               <span class="sm" id="menu6_more">
                   <a href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerfans/id/<?php echo $this->playerid; ?>" title="<?php echo $this->playername;?> Fans">More »</a>
               </span>
              </p>

              <div id="randomprofiles" class="nmatch">

			</div>

           <p class="modfooter"><a class="orangelink" href="<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerfans/id/<?php echo $this->playerid; ?>">See More <?php echo $this->playername;?> Fans &raquo;</a></p>

       </div>


	<?php } else { ?>

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
    
   <div class="img-shadow">   
            <div class="WrapperForDropShadow">
                <div class="DropShadowHeader BlueGradientForDropShadowHeader">
              
                    <h4 class="NoArrowLeft">
                    	<?php echo $this->playername;?> Fans
                    </h4>
					 <span class="sm" id="menu6_more">
	                   <a href="javascript:loginModal();" title="<?php echo $this->playername;?> Fans">More »</a>
	               </span>
                </div>
               
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
           </div>
    </div>
   
	
    <?php } ?> 



  </div>
  <!--end ThirdColumnOfThree-->
</div>
<!--end ContentWrapper-->


 <div class="steam"  id="selectteam">
	<div class="stop">
		<span class="sel">Select Players</span>
		<span class="close"  id="aboutclose1"><a href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/cross1.jpg" alt=""/></a></span>
	</div>
	<div class="smid">
		<p class="two">Compare two players based on statistics.</p>
	    <p class="two">Choose the type of team for the two players you want to compare.</p>
		<p class="team">Club:
			<input type="radio" id="rbTeamType" name="rbTeamType" checked="checked" title="Clubs" value="1">			
			&nbsp;&nbsp;&nbsp;National Team:
            <input type="radio" id="rbTeamType" name="rbTeamType" title="National Teams" value="2">
        </p>
		<div id="countryerrorA" class="ErrorMessageIndividual">Select a Country for Player A</div>
		<p class="team"><b>Player A:</b></p>
		<span class="country">
			<select class="try" id="countryteama">
				<option value="0" selected>Select Country</option>
				<?php foreach ($this->countries as $league) 
				{
					$selectedOption = "";	
					if($league["country_id"] ==  $this->countryid)
					{
						$selectedOption = "selected";
					}
					?>
					
                        <option value="<?php echo $league["country_id"];?>" <?php echo $selectedOption;?>><?php echo $league["country_name"];?></option>
                <?php } ?>
			</select>
			<br/>
			<div id="cluberrorA" class="ErrorMessageIndividual">Select a Club for Player A</div>
			<p class="team">
				<select id="teamselectida">
	                <option value="<?php echo $this->playerteamid;?>" selected><?php echo $this->playerteamclub;?></option>
	            </select>
			</p>
			<div id="playererrorA" class="ErrorMessageIndividual">Select Player A</div>
			<p class="team">
				<select id="playerselectida">
	                <option value="<?php echo $this->playerid;?>" selected><?php echo $this->playername;?></option>
	            </select>
	            <input type="text" name="playerautoinputA" id="playerautoinputA"/>
			</p>	
		</span>
		<div id="countryerrorB" class="ErrorMessageIndividual">Select a Country for Player B</div>
		<p class="team"><b>Player B:</b></p>
		<span class="country">
			<select class="try" id="countryteamb">
				<option value="0" selected>Select Country</option>
				<?php foreach ($this->countries as $league) {
					$selectedOption = "";	
					
					
					?>
                        <option value="<?php echo $league["country_id"];?>"  <?php echo $selectedOption;?>><?php echo $league["country_name"];?></option>
                <?php } ?>
			</select>
			<br/>
			<div id="cluberrorB" class="ErrorMessageIndividual">Select a Club B</div>
			<p class="team">
				<select id="teamselectidb">
	                <option value="0" selected>Select A Team</option>
	            </select>
			</p>
			<div id="playererrorB" class="ErrorMessageIndividual">Select Player B</div>
			<p class="team">
				<select id="playerselectidb">
	                <option value="0" selected>Select A Player</option>
	            </select>
			</p>				        			
		</span>
		<input type="hidden" name="competitionid" id="competitionid" />
		<p class="view"><input type="button" value="View Head To Head" name="slcPlayer1" id="slcPlayer1"/></p>
	</div>
	<div class="sbtm"></div>
</div>

<div class="steam"  id="selectteam1">
	<div class="stop">
		<span class="sel">Select Teams</span>
		<span class="close"  id="aboutclose2"><a href="#"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/cross1.jpg" alt=""/></a></span>
	</div>
	<div class="smid">
		<p class="two">Compare two players based on statistics.</p>
		<p class="team1">
			<span class="text"><b>Player A:</b></span>
			<span class="here"><input type="text" value="Type team name here..." name="" onfocus="javascript:if(this.value=='Type team name here...')this.value='';" onblur="javascript:if(this.value=='')this.value='Type team name here...';" /></span>
		</p>
		<p class="team1">
			<span class="text"><b>Player A:</b></span>
			<span class="here"><input type="text" value="Type team name here..." name="" onfocus="javascript:if(this.value=='Type team name here...')this.value='';" onblur="javascript:if(this.value=='')this.value='Type team name here...';" /></span>
		</p>
		<span class="view1"><input type="button" value="View Head To Head" name=""/></span>
		<span class="cancel"><input type="button" value="cancel" name=""/></span>
	</div>
	<div class="sbtm"></div>
</div>
<div id="backgroundPopup"></div>

<script type="text/JavaScript">

jQuery(document).ready(function() {
	
	jQuery("input[name='rbTeamType']").change(function(){
		var countryida = jQuery("#countryteama").val();
		var countryidb = jQuery("#countryteamb").val();
		var rbTeamType = $("input[name='rbTeamType']:checked").val();
		if(rbTeamType == '1') {
			jQuery('#cluberrorA').text("Select a Club A");
			populateCombo('teamselectida', countryida, 'club');
			jQuery('#cluberrorA').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
			jQuery('#cluberrorB').text("Select a Club B");
			populateCombo('teamselectidb', countryidb, 'club');
			jQuery('#cluberrorB').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
		} else {
			jQuery('#cluberrorA').text("Select a National Team A");
			populateCombo('teamselectida', countryida, 'national');
			jQuery('#cluberrorA').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
			jQuery('#cluberrorB').text("Select a National Team B");
			populateCombo('teamselectidb', countryidb, 'national');
			jQuery('#cluberrorB').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
		}
		//Clear the player select dropdpwn	
		jQuery('#playerselectida > option').remove();
		jQuery('#playerselectida')[0].options.add( new Option("Select Player A","0") )

		jQuery('#playerselectidb > option').remove();
		jQuery('#playerselectidb')[0].options.add( new Option("Select Player B","0") )
	});
	


	
	jQuery('#countryteama').change(function()
	{
		var countryid = jQuery("#countryteama").val();
		var rbTeamType = $("input[name='rbTeamType']:checked").val();
		var teamTypeCode = (rbTeamType == '1')?'club':'national';
    
    	populateCombo('teamselectida', countryid, teamTypeCode);
    }); 

	jQuery('#teamselectida').change(function(){
		var teamid = jQuery("#teamselectida").val();
    	populateCombo('playerselectida', teamid, 'teamplayer');
    }); 
    
	jQuery('#countryteamb').change(function(){
		var countryid = jQuery("#countryteamb").val();
		var rbTeamType = $("input[name='rbTeamType']:checked").val();
		var teamTypeCode = (rbTeamType == '1')?'club':'national';
    	populateCombo('teamselectidb', countryid, teamTypeCode);
    }); 

	jQuery('#teamselectidb').change(function(){
		var teamid = jQuery("#teamselectidb").val();
    	populateCombo('playerselectidb', teamid, 'teamplayer');
    });

	//Click the button event!
	jQuery('#slcPlayer1').click(function(){
		validateHead2HeadSelectTeamsAndPlayers();
		var countryteama = jQuery("#countryteama").val();
		var countryteamb = jQuery("#countryteamb").val();
		var teama = jQuery("#teamselectida").val();
		var teamb = jQuery("#teamselectidb").val();
		var playera = jQuery("#playerselectida").val();
		var playerb = jQuery("#playerselectidb").val();
		var competitionid = jQuery("#competitionid").val();
		if((countryteama != '0' && teama != '0' && playera !='0') && (countryteamb != '0' && teamb != '0' && playerb !='0')) 

		{
			var url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/findhead2headplayers/teama/'+teama+'/teamb/'+teamb+'/playera/'+playera+'/playerb/'+playerb;
			top.location.href = url;
			//jQuery('#SecondColumnHeadtoHead').html("<div class='ajaxloadscores'></div>");
			//jQuery('#SecondColumnHeadtoHead').load(url);
			disablePopup1();
		}
	});		

});	

function validateHead2HeadSelectTeamsAndPlayers() {
	var countryteama = jQuery("#countryteama").val();
	var countryteamb = jQuery("#countryteamb").val();
	var rbTeamType = $("input[name='rbTeamType']:checked").val();
	var teama = jQuery("#teamselectida").val();
	var teamb = jQuery("#teamselectidb").val();
	var playera = jQuery("#playerselectida").val();
	var playerb = jQuery("#playerselectidb").val();
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
	if(playera == '0') {
		jQuery('#playererrorA').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');					
	} else {
		jQuery('#playererrorA').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
	}
	if(playerb == '0') {
		jQuery('#playererrorB').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');					
	} else {
		jQuery('#playererrorB').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
	}
	
}

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
