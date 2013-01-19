<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>  
     <?php require_once 'Common.php'; ?>
      <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>




<script type="text/JavaScript">

         jQuery(document).ready(function() {

        	 jQuery('#addtofavoritecompetitiontrigger').click(function(){
        		 addremovefavoritecompetition('add');
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

              callLeagueFans();
            	
            	jQuery('ul>li#listView').click(function(){
            		jQuery('#ajaxdata').html("<div class='ajaxload widgetlong'></div>");
            		jQuery.ajax({
            	        method: 'get',
            	        url : '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionfansresult/type/list/id/<?echo $this->leagueId;?>',
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
            	        url : '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionfansresult/type/grid/id/<?echo $this->leagueId;?>',
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


            function callLeagueFans(){
                jQuery('#ajaxdata').html("<div class='ajaxload widgetlong'></div>");
            	jQuery.ajax({
                    method: 'get',
                    url : '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showcompetitionfansresult/id/<?echo $this->leagueId;?>',
                    dataType : 'text',
                    success: function (text) {
                        jQuery('#ajaxdata').html(text);
                     }
                 });
            }


            function addremovefavoritecompetition(type){

                jQuery('#modalBodyId').show();
                jQuery('#modalBodyResponseId').hide();
                jQuery('#acceptFavoriteModalButtonId').show();
                jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel');
                if(type == 'add'){
                       jQuery('#modalFavoriteTitleId').html('Add <?php echo $this->compName;?> as your favorite competition?');
                }else if(type == 'remove'){
                        jQuery('#modalFavoriteTitleId').html('Remove <?php echo $this->compName;?> as your favorite competition?');
                }	 	 
                jQuery('#dataText1').html('<?php echo $this->compName;?>');
                jQuery('#title1Id').html('Country:');
                jQuery('#dataText2').html("<?php echo $this->countryName; ?>");
                jQuery('#dataText2').attr('href','<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->countryName,$this->countryId, true); ?>');

                if(type == 'add'){
                       jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoritecompetitiontrigger', onHide: closeModal });
                }else if(type == 'remove'){
                        jQuery('#addFavoriteModal').jqm({trigger: '#removefromfavoritecompetitiontrigger', onHide: closeModal });
                }
                jQuery('#addFavoriteModal').jqmShow();

                var favoriteImage = null;

               <?php
                $config = Zend_Registry::get ( 'config' );
                $path_comp_logos = $config->path->images->complogos . $this->leagueId.".gif" ;

                if (file_exists($path_comp_logos)){  ?>
              			favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/competitionlogos/<?php  echo $this->leagueId .'.gif'?>';
       		<?php } else {  ?>
       				favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/LeagueText120.gif';
           	<?php  } ?>

       		 
       		 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

       		 var leagueId = '<?php echo $this->leagueId; ?>*<?php echo $this->countryId; ?>';	
       		
       		 var url = null;
       		 var htmlResponse = null;
       		 if(type == 'add'){ 
       			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/addfavorite' ;
       			 htmlResponse = 'Competition <?php echo $this->compName;?> has been added to your favorite competitions.';
       		 }else if(type == 'remove'){
       			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/removefavorite' ;
       			 htmlResponse = 'Competition <?php echo $this->compName;?> has been removed from your favorite competitions.';
       		 }	 	 
       		 


       		 jQuery("#acceptFavoriteModalButtonId").unbind();
       		 jQuery('#acceptFavoriteModalButtonId').click(function(){
       		 jQuery.ajax({
       				type: 'POST',
       				url :  url,
       				data : ({leagueId:leagueId }),
       				success: function(data){
       					jQuery('#modalBodyResponseId').html(htmlResponse);
       					jQuery('#modalBodyId').hide();
       					jQuery('#modalBodyResponseId').show();
       					jQuery('#acceptFavoriteModalButtonId').hide();
       					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
       					if(type == 'add'){ 
       						jQuery('#favorite').hide();
       					 	jQuery('#remove').show()
       					}else if(type == 'remove'){
       				 		jQuery('#remove').hide();
       				 		jQuery('#favorite').show();
       				 	}		
       				 	 
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
          <div class="FirstColumnOfThree">
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
                        <?php //echo $this->render('include/badgeTeam.php');?>
                    </div>

                  <!--Team Profile left Menu-->
                    <div class="img-shadow">
                       <?php //echo $this->render('include/navigationTeam.php');?>
                    </div>

          </div><!--end FirstColumnOfThree-->
					

            <div class="SecondColumn" id="SecondColumnPlayerProfile">
                <h1><?php echo $this->competition['competition_name']; ?> Fans</h1>
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
	    					     		Be the first to <a id="addtofavoritecompetitiontrigger" href="javascript:void(0);">add <?php echo $this->competition['competition_name']; ?></a>  as your favorite competition.
	    					     	</div>
								
	    					    <?php } ?>
                			
                		  </div>
                                
                          </div>
                        </div>
                    </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->

              </div><!--end Second Column-->

    </div> <!--end ContentWrapper-->