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
	callPlayerTeamMates();
	
	jQuery('ul>li#listView').click(function(){
		jQuery('#ajaxdata').html("<div class='ajaxload widgetlong'></div>");
		jQuery.ajax({
	        method: 'get',
	        url : '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammatesresult/type/list/id/<?echo $this->playerid;?>',
	        dataType : 'text',
	        success: function (text) {
	            jQuery('#ajaxdata').html(text);
	            if(jQuery('#listDisplayFriends').is(":hidden")){
		   			   jQuery('#gridDisplayFriends').hide();
		   			   jQuery('#listDisplayFriends').show();
		   			   jQuery('ul>li>#listView').removeClass('ListViewOff');
		     		   jQuery('ul>li>#listView').addClass('ListViewOn');
		   			   jQuery('ul>li>#gridView').removeClass('GridViewOn');
		     		   jQuery('ul>li>#gridView').addClass('GridViewOff');
		     		   jQuery('#gridOrList').val('list');
		     		   
		   		}
	         }
	     });
  		
  	});
	jQuery('ul>li#gridView').click(function(){

		jQuery('#ajaxdata').html("<div class='ajaxload widgetlong'></div>");
		jQuery.ajax({
	        method: 'get',
	        url : '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammatesresult/type/grid/id/<?echo $this->playerid;?>',
	        dataType : 'text',
	        success: function (text) {
	            jQuery('#ajaxdata').html(text);
	            if(jQuery('#gridDisplayFriends').is(":hidden")){
		   			   jQuery('#listDisplayFriends').hide();
		   			   jQuery('#gridDisplayFriends').show();
		     		   jQuery('ul>li>#listView').removeClass('ListViewOn');
		     	  	   jQuery('ul>li>#listView').addClass('ListViewOff');
		     		   jQuery('ul>li>#gridView').removeClass('GridViewOff');
		     	  	   jQuery('ul>li>#gridView').addClass('GridViewOn');
		       	  	   jQuery('#gridOrList').val('grid');
		   		}
	         }
	     });
		
  		
  	});		
	 
     
});


function callPlayerTeamMates(){
	jQuery('#ajaxdata').html("<div class='ajaxload widgetlong'></div>");
	jQuery.ajax({
        method: 'get',
        url : '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammatesresult/id/<?echo $this->playerid;?>',
        dataType : 'text',
        success: function (text) {
            jQuery('#ajaxdata').html(text);
         }
     });
}

function addfavoriteplayer(playerid , playername , playerimage){

	 jQuery('#modalBodyId').show();
	 jQuery('#modalBodyResponseId').hide();
	 jQuery('#acceptFavoriteModalButtonId').show();
	 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
	 jQuery('#modalFavoriteTitleId').html('Add '+playername+' as your favorite player?');
	 jQuery('#dataText1').html(playername);
	 /*jQuery('#dataText1').attr('href','<?php echo $urlGen->getPlayerMasterProfileUrl($this->playernickname, $this->playerfname, $this->playerlname, $this->playerid, true);?>');
	 jQuery('#title1Id').html('Team:');
	 jQuery('#dataText2').html("<?php echo $this->playerteamclub;?>");
	 jQuery('#dataText2').attr('href','<?php echo $urlGen->getClubMasterProfileUrl ( $this->playerteamid, $this->playerteamseoclub, True ); ?>');

	 jQuery('#title2Id').html('Nationality:');
	 jQuery('#dataText3').html("<?php if (empty($this->playercountry)){echo 'Unavailable'; } else {echo  $this->playercountry;} ?>");
	 jQuery('#title3Id').html('Position:');
	 jQuery('#dataText4').html("<?php if (empty($this->playerpos)){echo 'Unavailable'; } else {echo $this->playerpos;} ?>");
	 */
	 jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteplayertrigger', onHide: closeModal });
	 jQuery('#addFavoriteModal').jqmShow();
	 
	 var favoriteImage = null;

	 if (playerimage != '') { 
	 	favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/'+playerimage;
  	 } else { 
  		favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/Player1Text.gif';
  	 } 	
	 
	 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

	 var playerId = playerid;	
	 
	 jQuery("#acceptFavoriteModalButtonId").unbind();
	 jQuery('#acceptFavoriteModalButtonId').click(function(){
	 jQuery.ajax({
			type: 'POST',
			url :  '<?php echo Zend_Registry::get("contextPath"); ?>/player/addfavorite',
			data : ({playerId: playerId}),
			success: function(data){
				jQuery('#modalBodyResponseId').html('Player '+playername+' has been added as your favorite player.');
				jQuery('#modalBodyId').hide();
				jQuery('#modalBodyResponseId').show();
				jQuery('#acceptFavoriteModalButtonId').hide();
				jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
				jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
				top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammates/id/<?php echo $this->playerid;?>';
			}	
		});
		
	 });	
}

function removefavoriteplayer(playerid , playername , playerimage){

	 jQuery('#modalBodyId').show();
	 jQuery('#modalBodyResponseId').hide();
	 jQuery('#acceptFavoriteModalButtonId').show();
	 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
	 jQuery('#modalFavoriteTitleId').html('Remove '+playername+' as your favorite player?');
	 jQuery('#dataText1').html(playername);
	 
	 /*jQuery('#dataText1').attr('href','<?php echo $urlGen->getPlayerMasterProfileUrl($this->playernickname, $this->playerfname, $this->playerlname, $this->playerid, true);?>');
	 jQuery('#title1Id').html('Team:');
	 jQuery('#dataText2').html("<?php echo $this->playerteamclub;?>");
	 jQuery('#dataText2').attr('href','<?php echo $urlGen->getClubMasterProfileUrl ( $this->playerteamid, $this->playerteamseoclub, True ); ?>');

	 jQuery('#title2Id').html('Nationality:');
	 jQuery('#dataText3').html("<?php if (empty($this->playercountry)){echo 'Unavailable'; } else {echo  $this->playercountry;} ?>");
	 jQuery('#title3Id').html('Position:');
	 jQuery('#dataText4').html("<?php if (empty($this->playerpos)){echo 'Unavailable'; } else {echo $this->playerpos;} ?>");
	 */
	 jQuery('#addFavoriteModal').jqm({trigger: '#removefavoriteplayertrigger', onHide: closeModal });
	 jQuery('#addFavoriteModal').jqmShow();
	 
	 var favoriteImage = null;

	 if (playerimage != '') { 
	 	favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/'+playerimage;
  	 } else { 
  		favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/Player1Text.gif';
  	 } 	
	 
	 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

	 var playerId = playerid;	
	 
	 jQuery("#acceptFavoriteModalButtonId").unbind();
	 jQuery('#acceptFavoriteModalButtonId').click(function(){
	 jQuery.ajax({
			type: 'POST',
			url :  '<?php echo Zend_Registry::get("contextPath"); ?>/player/removefavorite',
			data : ({playerId: playerId}),
			success: function(data){
				jQuery('#modalBodyResponseId').html('Player '+playername+' has been removed from your favorite players.');
				jQuery('#modalBodyId').hide();
				jQuery('#modalBodyResponseId').show();
				jQuery('#acceptFavoriteModalButtonId').hide();
				jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
				jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
				top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerteammates/id/<?php echo $this->playerid;?>';
			}	
		});
		
	 });	
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
					

          <div id="SecondColumnPlayerProfile" class="SecondColumnOfTwo">
              <h1><?php echo $this->playername;?> Teammates</h1>
          		<div class="img-shadow">
          			<div class="WrapperForDropShadow">
          				<div class="SecondColumnProfile">
      				      <div id="FriendsWrapper" class="FriendsWrapperTopAlign">
      				      	
      				      	<input type="hidden" name="gridOrList" value="list" id="gridOrList">
      				      
	      				      	<?php if ($this->totalteammates > 0) { ?>
								 	<ul class="Friendtoolbar FriendtoolbarTopAlign">                             
	 									<li id="gridView" class="GridViewOn"/>
	                                	<li id="listView" class="ListViewOff"/>
	    					     	</ul>
	    					     	<div class="ProfilesResult" id="ajaxdata">  
	    					    	
									</div> <!-- End ajax control -->
									<ul class="Friendtoolbar FriendtoolbarBottomAlign">
      					          		<li id="gridView" class="GridViewOn"/>
                                		<li id="listView" class="ListViewOff"/>
      					          	</ul>
      					          	
	    					     <?php } else {
	    					     	echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>No Teammates found.</div>";
									echo "<br>";
	    					     } ?>
	    					     
    					  </div>   							  	
                        </div>                          
                    </div>
                        
                </div>

         </div><!--end SecondColumnOfTwo-->

    </div> <!--end ContentWrapper-->
