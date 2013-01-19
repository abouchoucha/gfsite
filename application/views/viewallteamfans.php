<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>  
     <?php require_once 'Common.php'; ?>
      <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>




<script type="text/JavaScript">

         jQuery(document).ready(function() {

	       	  jQuery('#addtofavoriteteamtrigger').click(function(){
	              addfavoriteteam();
	          });

             
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

              callTeamFans();
            	
            	jQuery('ul>li#listView').click(function(){
            		jQuery('#ajaxdata').html("<div class='ajaxload widgetlong'></div>");
            		jQuery.ajax({
            	        method: 'get',
            	        url : '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamfansresult/type/list/id/<?echo $this->teamid;?>',
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
            	        url : '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamfansresult/type/grid/id/<?echo $this->teamid;?>',
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


            function callTeamFans(){
                jQuery('#ajaxdata').html("<div class='ajaxload widgetlong'></div>");
            	jQuery.ajax({
                    method: 'get',
                    url : '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamfansresult/id/<?echo $this->teamid;?>',
                    dataType : 'text',
                    success: function (text) {
                        jQuery('#ajaxdata').html(text);
                     }
                 });
            }

        			

        	<?php
          	        $config = Zend_Registry::get ( 'config' );
          	        $path_team_logos = $config->path->images->teamlogos . $this->teamid .".gif" ;
          		?>    
          		
          function addfavoriteteam(){

              jQuery('#modalBodyId').show();
              jQuery('#modalBodyResponseId').hide();
              jQuery('#acceptFavoriteModalButtonId').show();
              jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel');
              jQuery('#modalFavoriteTitleId').html('Add to Favorities');
              jQuery('#dataText1').html('Add <?php echo $this->teamname;?> as your favorite teams?');
              jQuery('#title1Id').html('Country:');
              jQuery('#dataText2').html("<?php echo $this->countryname; ?>");
              jQuery('#dataText2').attr('href','<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->countryname,$this->countryid, true); ?>');

              jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoriteteamtrigger', onHide: closeModal });
              jQuery('#addFavoriteModal').jqmShow();

              var favoriteImage = null;

      <?php if (file_exists($path_team_logos))
      { ?>
              favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/teamlogos/<?php echo $this->teamid ; ?>.gif';
          <?php } else {  ?>
                  favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/TeamText.gif';
          <?php }  ?>

                  jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

                  var teamId = '<?php echo $this->teamId; ?>';

                  jQuery("#acceptFavoriteModalButtonId").unbind();
                  jQuery('#acceptFavoriteModalButtonId').click(function(){
                      jQuery.ajax({
                          type: 'POST',
                          url :  '<?php echo Zend_Registry::get("contextPath"); ?>/team/addfavorite',
                          data : ({teamId: teamId}),
                          success: function(data){
                              jQuery('#modalBodyResponseId').html('Team <?php echo $this->teamname;?> has been added to your favorites.');
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
       					top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamfans/id/<?php echo $this->team[0]["team_id"];?>';
       					
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
				
               	<!--Team Profile Badge-->
                    <div class="img-shadow">
                        <?php echo $this->render('include/badgeTeamNew.php');?>
                    </div>

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


          </div><!--end FirstColumnOfThree-->
					

          
              
              
              <div id="SecondColumnPlayerProfile" class="SecondColumnOfTwo">
				<h1><?php echo $this->team[0]['team_name']; ?> Fans</h1>
          		<div class="img-shadow">
          			<div class="WrapperForDropShadow">
                		<div class="SecondColumnProfile">
      				      <div id="FriendsWrapper" class="FriendsWrapperTopAlign">
      				      	
                				<?php if ($this->totalteamfans > 0) { ?>
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
	    					     		Be the first to <a id="addtofavoriteteamtrigger" href="javascript:void(0);">add <?php echo $this->teamname; ?></a>  as your favorite team.
	    					     	</div>
								
	    					    <?php } ?>
                			
                		  </div>
                		</div>	
                	</div>
                </div>
            </div>

    </div> <!--end ContentWrapper-->