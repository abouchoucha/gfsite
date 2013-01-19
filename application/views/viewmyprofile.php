<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/rating.css' rel="stylesheet" type="text/css" media="screen"/>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.rating.js" type="text/javascript"></script> 
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.charcounter.js" type="text/javascript"></script>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php $session = new Zend_Session_Namespace('userSession');?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $common = new Common();
 ?>


<script language="javascript">

jQuery(document).ready(function() {


<?php if($this->firsttimeviewprofile =='true') { ?>
		jQuery('#inlineProfileWelcomeMessage').show();
	<?php } ?>

    jQuery(".inlineMessageWide .closemessage").click(function(){
		jQuery(this).parents(".inlineMessageWide").animate({ opacity: 'hide' }, "slow");
	 });

	jQuery('a#addToFriendTriggerGoalShout,a#addToFriendTriggerGoalShout2').click(function(){
		 addToFriends();
   	});
	
	findMyFavorities();

	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/findactivities/username/<?php echo urlencode($this->currentUser->screen_name);?>/type/1';
	jQuery('#friendActivity').html('Loading...'); 
	jQuery('#friendActivity').load(url);
	

   /*** End Star Rating */
   jQuery('#showDetail').click(function(){

	   if(jQuery('#small').is(":hidden")){
		   //alert("small hidden");
		   jQuery('#expanded').slideUp();
		   jQuery('#small').show("slow");
		   jQuery('#showDetail').html('+ Show Details');
	   }else{
		   //alert("small open");
		   jQuery('#expanded').show("slow");
		   jQuery('#small').slideUp();
		   jQuery('#showDetail').html('- Hide Details');
		}
	   return;	
    });	

   jQuery('#buttonMyActivity').click(function(){ //
	  	 findUserActivity();
	 });
   jQuery('#buttonMyFavorities').click(function(){ //
	  	 findMyFavorities();
	 });  	  

   	

});		



function findMyFavorities(){

	var type = jQuery("#myFavoritesId").val();
	if(type == '0'){
		type = 'p';
	}	
	var currentUserId = '<?php echo $this->currentUser->user_id; ?>'; 
	var url
	if(type == 'p'){
		url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findfavoriteuserplayer/u/'+currentUserId;
	}else if(type == 'l'){
		url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/findfavoriteuserleagues/u/'+currentUserId;
	}else if(type == 'nt'){
		url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/findfavoriteteams/t/national/u/'+currentUserId;
	}else if(type == 'ct'){
		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/findfavoriteteams/t/club/u/'+currentUserId;
	}else if(type == 'ga'){
		url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/findfavoriteusergames/u/'+currentUserId;
	}
	jQuery.ajax({
        method: 'get',
        url : url,
        dataType : 'text',
        success: function (text) {
			if(jQuery.trim(text) !=''){
		  		  jQuery('#data').html('Searching...');
	              jQuery('#data').html(text);
            }else{
                top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/login';  	
	          }    
         }
     });
}


function findUserActivity(){
	var type = jQuery("#myactivityId").val();
	var url = null;
	if(type == '0'){
		url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/findactivities/username/<?php echo $this->currentUser->screen_name; ?>';
	}else if(type == '1'){
		url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/findactivities/username/<?php echo $this->currentUser->screen_name; ?>/type/1';
	}
	

	jQuery('#friendActivity').html('Loading...'); 
	jQuery('#friendActivity').load(url);

}

</script>



<!--
<div id="inlineProfileWelcomeMessage" class="inline">Welcome to GoalFace! Remember <a href="echo $urlGen->getClubsMainUrl(true);" title="Football Teams">to find your favorite teams</a> and <a href="$urlGen->getPlayersMainUrl(true);" title="Football Players">your favorite players</a> and add them to your profile.
<span class="closemessage"></span></div>-->

<div style="width:917px; margin-left: 0px;" class="inlineMessageWide alertSucess closeDiv" id="inlineProfileWelcomeMessage">
    <p id="successMessageId" style="width:750px;">Welcome to GoalFace!&nbsp;Remember <a href="<?php echo $urlGen->getClubsMainUrl(true); ?>" title="Football Teams">to find find
    your favorite teams</a> and <a href="<?php echo $urlGen->getPlayersMainUrl(true); ?>" title="Football Players">your favorite players</a> so you can add them to your profile</p>
 <span class="closemessage"></span>
</div>


<div id="ContentWrapper">


		<div class="FirstColumnOfThree">
          <?php 
              $session = new Zend_Session_Namespace('userSession');
          ?> 
               		<!-- START Profile Box Include -->
                	<?php echo $this->render('include/miniProfilePlusLoginBox.php'); ?>
                	<!-- END Profile Box Include -->
                
                
                </div><!--end FirstColumnOfThree-->

                <div class="SecondColumnOfThree">
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
         
                            <div id="ProfileInfoContainer">
				                 <h1><?php echo $this->currentUser->screen_name; ?> </h1>
				                   <?php if($this->lastBroadcastTime != ''){?>
				                    <div class="profilebroadcast">
				                     <span><?php echo $this->lastBroadcast?></span>
									 <span class="date"><?php echo $common->convertDates($this->lastBroadcastTime);?></span>
									</div>
						            <?php }?>
				                    
				                   <div id="ProfileInfo">                      
				                        <span class="ProfileLabel">Member Since: </span>[<? echo date ('M Y' , strtotime($this->currentUser->registration_date)) ?>]<br>
				                        <span class="ProfileLabel" style="float:left;">Avg. Rating: </span>   
				                        	<div class="statVal" style="width:250px;float:left;padding-left:10px;margin-top:0px;margin-bottom:5px;">
                                    			<span class="ui-rater">
                                        		<span class="ui-rater-starsOff" style="width:90px;"><span class="ui-rater-starsOn" style="width:<?php echo (18*$this->rating);?>px"></span></span>
                                        		<span class="ui-rater-rating"><?php echo $this->rating;?></span>&#160;(<span class="ui-rater-rateCount"><?php echo $this->totalVotes;?></span>)
                                    			</span>
                                			</div> <br>
										<span class="ProfileLabel">BASIC PROFILE</span><?php if ($this->isMyProfile == 'y'){ ?>
                                        <a class="OrangeLink" href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"profileinfo");?>">edit</a>
                                   			<?php }?></a><br>
				                        <span class="ProfileLabel">First Name: </span>
				                         <?php echo $this->currentUser->first_name; ?> 
		                                  <?php if ($this->isMyProfile == 'y'){ ?>
		                                  	(<?php echo $this->privateText[$this->privateFirstName]; ?>)
		                                  <?php } ?>
				                        <br>
				                        <?php if($this->isLastNameVisible == 'true' and $this->currentUser->last_name !=''){ ?>
				                        	<span class="ProfileLabel">Last Name: </span>
						                        <?php echo $this->currentUser->last_name; ?>
		                                    <?php if ($this->isMyProfile == 'y'){ ?>
		                                  	(<?php echo $this->privateText[$this->privateLastName]; ?>)
		                                  <?php } ?>
				                        	<br>
				                         <?php }?>
				                        <span class="ProfileLabel">Lives: </span><?php echo $this->countryLive; ?>
		                                   <?php if($this->isCityLiveVisible == 'true' and $this->currentUser->city_live != ''){ ?>
		                                    , <?php echo $this->currentUser->city_live; ?>
		                                    <?php if ($this->isMyProfile == 'y'){ ?>
		                                  	(<?php echo $this->privateText[$this->privateCountryLives]; ?>)
		                                   <?php } ?>
		                                   <?php }?><br>
				                        <span class="ProfileLabel">From: </span><?php echo $this->countryFrom; ?>
		                                   <?php if($this->isCityBirthVisible == 'true' and $this->currentUser->city_birth != ''){ ?>
		                                   , <?php echo $this->currentUser->city_birth; ?>
		                                   <?php }?>
		                                    <?php if ($this->isMyProfile == 'y'){ ?>
		                                  	(<?php echo $this->privateText[$this->privateCountryFrom]; ?>)
		                                   <?php } ?><br>
		                                   <?php $numLang = sizeof($this->spokenLanguages);
		                                    if($numLang > 0){ ?>
		                                		<span class="ProfileLabel">Speaks: </span>
		                                    <?php
			                                    $cont = 1;
			                                    foreach($this->spokenLanguages as $language) { ?>
			                                      <?php echo $language["language_name"];
			                                         if($cont < $numLang){
			                                            echo ",";
			                                         }
			                                       $cont++;
			                                    }
			                                  }
                                  			?>
                                  			<br><br>
                                  		<?php if($this->currentUser->aboutme_text != ''){ ?>
                                      		<span class="ProfileLabel">About Me:</span>
										                 
            							      <div id="small">    
            							      <?php if(strlen($this->currentUser->aboutme_text) >= 204 ){?>              
	                                    		<?php echo substr($this->currentUser->aboutme_text, 0, stripos($this->currentUser->aboutme_text, " ", 204)); ?>...
	                                    		<?php } else {?>
	                                    			<?php echo $this->currentUser->aboutme_text; ?>
	                                    		<?php }?>
                                    		</div>
                                    		<div id="expanded" class="closeDiv">                  
                                    			<?php echo $this->currentUser->aboutme_text; ?>
                                    		</div>
                        	    	 <?php }?>
		                                   
				                  </div>
				            </div>
				                            
                            
                        </div>
                    </div>


                  <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                                <h4 class="NoArrowLeft">
                                    <?php if ($this->isMyProfile == 'y'){ ?>
                                        My Favorites
                                    <?php } else { ?>
                                        <?php echo $this->currentUser->screen_name; ?> Favorites
                                     <?php }  ?>
                                </h4>
                                <span>
                                  <?php if ($this->isMyProfile == 'y'){ ?>
                                  <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities">See More &gt;</a>
                                  <?php } else { ?>
                                  <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/editfavorities/username/<?php echo $this->currentUser->screen_name; ?>">See More &gt;</a>
                                  <?php }  ?>
                                  
                                  
                                </span>
                            </div>

                            <div id="HisFriendFeed">
                              <div class="BlueShaded DisplayDropdown">
                                Show:
                                <select id="myFavoritesId" class="slct" name="Favorites1select">
                                    <option value="0">--Select--</option>
                                    <option value="p">Players</option>
                                    <option value="l">Leagues &amp; Competitions</option>
                                    <option value="nt">National Teams</option>
                                    <option value="ct">Club Teams</option>
                                    <option value="ga">Games</option>

                                </select>
                                <input style="display:inline;" type="submit" id="buttonMyFavorities" value="Ok" class="submit">
                              </div>
                              <div id="data">

							  </div>
                            </div>
                       </div>
                  </div>

                </div><!--end SecondColumnOfThree-->
                
                <div class="ThirdColumn">
                
                    
                    <!-- Profiles -->
                    <div class="prof">
                      <p class="mblack">
                       <span class="black">
                            <?php if ($this->isMyProfile == 'y'){ ?>
                                    My Friends (<?php echo $this->totalUserFriends;?>)
                             <?php } else { ?>
                                    <?php echo $this->currentUser->screen_name; ?> friends (<?php echo $this->totalUserFriends;?>)
                             <?php }  ?>
                       
                       </span>
                       <span class="sm" id="menu6_more">
                              <?php if ($this->isMyProfile == 'y'){?>
                               <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallmyfriends">See More &raquo;</a>
                               <?php } else {?>
                               <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallhisfriends/id/<?php echo $this->currentUser->user_id;?>">See More &raquo;</a>
                               <?php }?>
                       </span>
                      </p>
            
                    <?php echo $this->render("include/hisfriends.php"); ?>
            
                       <p class="modfooter">
                        <?php if ($this->isMyProfile == 'y'){?>
                            <a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallmyfriends">See All Friends</a>
                          <?php } else { ?>
                            <a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallhisfriends/id/<?php echo $this->currentUser->user_id;?>">See More <?php echo $this->currentUser->screen_name; ?> Friends</a>
                          <?php }?>
                       
                       </p>
            
                   </div>
                    
                    
                    
                    

                    <div class="img-shadow">
                      <div class="WrapperForDropShadow">
                        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                              <h4 class="NoArrowLeft">
                               <?php if ($this->isMyProfile == 'y'){ ?>
                                        My Fan Feed (<?php echo $this->totalFriendActivities;?>)
                                 <?php } else { ?>
                                        <?php echo $this->currentUser->screen_name; ?> Fan Feed (<?php echo $this->totalFriendActivities;?>)
                               <?php }  ?>
                              </h4>
                               <span>
                                   <a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showalluseractivity<?php echo($this->isMyProfile == 'n'?'/username/'.$this->currentUser->screen_name:'');?>">See More &gt;</a>
                               </span>
                        </div>
                        <div id="HisFriendFeed">
                            <div class="BlueShaded DisplayDropdown">
	                          <?php if ($this->isMyProfile == 'y'){ ?>
									Show:
								<select id="myactivityId" class="slct" name="FriendFeed1select">
								    <option value="1">My Activity</option>
								    <option value="0">My Friends' Activity</option>
								</select>
								 <input style="display:inline;" type="submit" id="buttonMyActivity" value="Ok" class="submit">
							<?php 	} ?>
	                        <?php if (sizeOf($this->totalFriendActivities) > 0) {?>
						        <?php if ($this->isMyProfile == 'n'){?>
						          <div class="JoinedDate">
						              <a class="OrangeLink" style="padding-right:20px;" href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/rss<?php echo($this->isMyProfile == 'n'?'/username/'.$this->currentUser->screen_name:'');?>">Subscribe</a>
						          </div>
						        <?php } ?>
						    <?php } ?>
                         	</div>
							

          							<div id="friendActivity">
          
                                       
          
          							</div>
							
							
						      </div>
                        <?php if (sizeOf($this->totalFriendActivities) > 0) { ?>
                            <div id="SeeAllActivity" class="SeeMoreNews MorePadding">
                                <a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showalluseractivity<?php echo($this->isMyProfile == 'n'?'/username/'.$this->currentUser->screen_name:'');?>">See More Activity</a>
                            </div>
                            <?  }?>
                      </div>
                    </div>

                    <div id="goalshoutId" class="img-shadow">
                         <?php echo $this->render('goalshoutprofile.php');?>
                    </div>
                </div><!--end ThirdColumnOfThree-->
 
           </div> <!--end ContentWrapper-->
