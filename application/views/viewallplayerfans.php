<?php $urlGen = new SeoUrlGen(); 

$session = new Zend_Session_Namespace('userSession');?>   
 
<script type="text/JavaScript">

    jQuery(document).ready(function() {
                       
              jQuery('a.userTrigger').cluetip({         
                  waitImage: true,
                  local: false,
                  cluetipClass: 'jtip', 
                  arrows: true, 
                  sticky: true,
                  mouseOutClose: true,
                  closePosition: 'title',
                  width: 200,
                  height : '220'
               });


              jQuery('#addtofavoriteplayertrigger').click(function(){
          		 addfavoriteplayer();
           	 });

              
              callPlayerFans();
            	
            	jQuery('ul>li#listView').click(function(){
            		jQuery('#ajaxdata').html("<div class='ajaxload widgetlong'></div>");
            		jQuery.ajax({
            	        method: 'get',
            	        url : '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerfansresult/type/list/id/<?echo $this->playerid;?>',
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
            	        url : '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerfansresult/type/grid/id/<?echo $this->playerid;?>',
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


            function callPlayerFans(){
                jQuery('#ajaxdata').html("<div class='ajaxload widgetlong'></div>");
            	jQuery.ajax({
                    method: 'get',
                    url : '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerfansresult/id/<?echo $this->playerid;?>',
                    dataType : 'text',
                    success: function (text) {
                        jQuery('#ajaxdata').html(text);
                     }
                 });
            }

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

         function addFriends(friendid ,name , imageName){

        	 jQuery('#modalBodyId').show();
        	 jQuery('#modalBodyResponseId').hide();
        	 jQuery('#acceptFavoriteModalButtonId').show();
        	 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
        	 jQuery('#modalFavoriteTitleId').html('Add '+ name +' as your friend?');

        	 jQuery('#dataText1').html(name);
			 jQuery('#title1Id').html('Joined:');
        	 jQuery('#dataText2').html(jQuery('#joinedfan'+friendid).val());
        	 /*jQuery('#title2Id').html('Country:');
        	 jQuery('#dataText3').html("<?php echo $this->countryFrom; ?>");
			 */	
        	 jQuery('#title3Id').html('City:');
        	 jQuery('#dataText4').html(jQuery('#cityfan'+friendid).val());
        	 
        	 jQuery('#addFavoriteModal').jqm({trigger: '#addToFriendTrigger', onHide: closeModal });
        	 jQuery('#addFavoriteModal').jqmShow();
        	 
        	 var favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/'+imageName;
        	 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

        	 jQuery("#acceptFavoriteModalButtonId").unbind();
        	 jQuery('#acceptFavoriteModalButtonId').click(function(){
        	 jQuery.ajax({
        			type: 'GET',
        			url :  '<?php echo Zend_Registry::get("contextPath"); ?>/message/addfriendrequest/friend/'+friendid,
        			success: function(data){
        				jQuery('#modalBodyResponseId').html('Your friend request to '+ name +'  has been sent.');
        				jQuery('#modalBodyId').hide();
        				jQuery('#modalBodyResponseId').show();
        				jQuery('#acceptFavoriteModalButtonId').hide();
        				jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
        				jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
        			}	
        		})
        		
        	 });		
        }

         function removeFriends(friendid ,name , imageName){

        	 jQuery('#modalBodyId').show();
        	 jQuery('#modalBodyResponseId').hide();
        	 jQuery('#acceptFavoriteModalButtonId').show();
        	 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	
        	 jQuery('#modalFavoriteTitleId').html('Remove '+ name +' from your friends?');
        	 jQuery('#dataText1').html(name);
        	 
			 jQuery('#dataText1').html(name);
			 jQuery('#title1Id').html('Joined:');
        	 jQuery('#dataText2').html(jQuery('#joinedfan'+friendid).val());
        	 /*jQuery('#title2Id').html('Country:');
        	 jQuery('#dataText3').html("<?php echo $this->countryFrom; ?>");
			 */	
        	 jQuery('#title3Id').html('City:');
        	 jQuery('#dataText4').html(jQuery('#cityfan'+friendid).val());
        	 
        	 jQuery('#addFavoriteModal').jqm({trigger: '#addToFriendTrigger', onHide: closeModal });
        	 jQuery('#addFavoriteModal').jqmShow();
        	 
        	 var favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/'+imageName;
        	 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

        	 jQuery("#acceptFavoriteModalButtonId").unbind();
        	 jQuery('#acceptFavoriteModalButtonId').click(function(){
       		 jQuery.ajax({
       				type: 'GET',
       				url :  '<?php echo Zend_Registry::get("contextPath"); ?>/message/removefromfriends/friend/'+friendid,
       				success: function(data){
       					jQuery('#modalBodyResponseId').html(name +' has been removed from your friends.');
       					jQuery('#modalBodyId').hide();
       					jQuery('#modalBodyResponseId').show();
       					jQuery('#acceptFavoriteModalButtonId').hide();
       					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
       					jQuery('#add').show();
       					jQuery('#remove').hide();
       					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
       					top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerfans/id/<?php echo $this->playerid;?>';
       				}	
       			})
        		
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

          </div><!--end FirstColumnOfThree-->

            <div id="SecondColumnPlayerProfile" class="SecondColumnOfTwo">
				<h1><?php echo	$this->playername;?> Fans</h1>
          		<div class="img-shadow">
          			<div class="WrapperForDropShadow">
                		<div class="SecondColumnProfile">
      				      <div id="FriendsWrapper" class="FriendsWrapperTopAlign">
      				      	
                				<?php if ($this->totalplayerfans > 0) { ?>
                					<ul class="Friendtoolbar FriendtoolbarTopAlign">                             
	 									<li id="gridView" class="GridViewOn"/></li>
	 									<li id="listView" class="ListViewOff"/></li>
	                              
	    					     	</ul>

	    					    	<div class="ProfilesResult" id="ajaxdata">  
	    					    	
									</div> <!-- End ajax control -->	
	
							
									<ul class="Friendtoolbar FriendtoolbarBottomAlign">
      					          		<li id="gridView" class="GridViewOn"/>
                                		<li id="listView" class="ListViewOff"/>
      					          	</ul>
                				
                			
                				<?php } else { ?>
	    					     	<div id="boxComments" style="text-align:center;height:55px;padding-top:30px;">
	    					     		Be the first to <a id="addtofavoriteplayertrigger" href="javascript:void(0);">add <?php echo $this->playername; ?></a>  as your favorite player.
	    					     	</div>
								
	    					    <?php } ?>
                			
                		  </div>
                		</div>	
                	</div>
                </div>
            </div><!--end Second Column-->

    </div> <!--end ContentWrapper-->
