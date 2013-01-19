     <?php require_once 'Common.php'; ?>
     <?php require_once 'seourlgen.php'; ?> 
  	<?php $urlGen = new SeoUrlGen ( );  ?>
     <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>

     <script type="text/JavaScript">
     
     jQuery(document).ready(function() {

		    	 jQuery('#removeFriendsId').click(function(){
		 			clickUnClickCheckBoxes(this.checked);
		 		 });

		    	 jQuery('#removeButtonId').click(function(){
		    		 removeFriends();
			 	 });
		    	 jQuery('#addtofriendfeedButtonId').click(function(){
		    		 addToFriendFeed();
			 	 });
		    	 jQuery('#removefromfriendfeedButtonId').click(function(){
		    		 removeFromFriendFeed();
			 	 }); 
		    	 	
		    	 
		    	jQuery('#all').addClass('filterSelected');
			 	var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallfriends';
			 	<?php if($this->userId != null){ ?>
					urlBase =  urlBase  + '/id/' + <?php echo $this->userId; ?>;
			 	<?php } ?>
				
				urlBase =  urlBase  + '/search/';
			 	//load the first list by default	
			 	jQuery('#newboxresult').html('Loading...');
			 	url = urlBase + 'all/page/'; 
				jQuery('#newboxresult').load(url);
				
				jQuery('#refresh').click(function(){
					var typeOfSearch = jQuery("#typeOfSearch").val();
					jQuery('#newboxresult').html('Loading...'); 
					jQuery('#newboxresult').load(url , {search :typeOfSearch});
			     });

				searchFriends('all' , urlBase , 'all/page/');
				searchFriends('mp' , urlBase , 'popular/page/');
				searchFriends('ma' , urlBase , 'active/page/');	
				searchFriends('ru' , urlBase , 'recently/page/');	
				searchFriends('onl' , urlBase , 'online/page/');	
				searchFriends('ff' , urlBase , 'infriendfeed/page/');	
				searchFriendsSubmit('searchprofilesform', urlBase ,'');
			      
	});

	function removeFriends(){
				
				jQuery('#messageConfirmationId').jqm({trigger: '#removeButtonId', onHide: closeModal });
				var cont = 0;
				var query_string = '';

				jQuery("input[name='fanprofilesId']").each(			
						function() 
						    { 
						        if(jQuery(this).is(':checked')){
						        	query_string += "&fanprofilesId[]=" + jQuery(this).val();
									cont++;
								}	 
						         
						}
					);
				
				jQuery('#modalTitleConfirmationId').html('REMOVE FRIEND?');
				if(cont == 0){
					jQuery('#messageConfirmationTextId').html('You do not have any Friends selected.  Select the Friend(s) you want to remove');
					jQuery('#acceptModalButtonId').addClass('jqmClose');
					
					jQuery('#messageConfirmationId').jqmShow();
					return;
				}
				jQuery('#messageConfirmationTextId').html('Are you sure you want to delete the friend(s) selected');
				jQuery('#messageConfirmationId').jqmShow();
				jQuery('#acceptModalButtonId').unbind();
				jQuery('#acceptModalButtonId').click(function(){
					url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/removefriends';
					jQuery.ajax({
		                type: 'POST',
		                url : url,
		                data : "id=1" + query_string ,
		                success: function (text) {
		      					jQuery('#data').html(text);
		      					if(jQuery('#MainErrorMessage').is(":hidden")){
		 						   jQuery('#MainErrorMessage').show("slow");
		 						   jQuery('#MainErrorMessage').animate({opacity: '+=0'}, 2000).slideUp('slow'); 
		 						}
		      					jQuery('#messageConfirmationId').jqmHide();		
		      			}
		             });
					
										
				});	
			
	}

	function addToFriendFeed(){
		
			jQuery('#messageConfirmationId').jqm({trigger: '#addtofriendfeedButtonId', onHide: closeModal });
			var cont = 0;
			var query_string = '';
			jQuery("input[name='fanprofilesId']").each(			
					function() 
					    { 
					        if(jQuery(this).is(':checked')){
					        	query_string += "&fanprofilesId[]=" + jQuery(this).val();
								cont++;
							}	 
					         
					}
				);
			
			jQuery('#modalTitleConfirmationId').html('ADD TO FRIEND FEED?');
			if(cont == 0){
				jQuery('#messageConfirmationTextId').html('You do not have any Friends selected.  Select the Friend(s) you want to add to your Friend Feed');
				jQuery('#acceptModalButtonId').addClass('jqmClose');
				jQuery('#messageConfirmationId').jqmShow();
				return;
			}
			
			jQuery('#messageConfirmationTextId').html('Are you sure you want to add the selected friend(s) to your feed?');
			jQuery('#messageConfirmationId').jqmShow();
			
			url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/addfriendsfeed';
			jQuery('#acceptModalButtonId').unbind();				
			jQuery('#acceptModalButtonId').click(function(){
				jQuery.ajax({
	                type: 'POST',
	                url : url,
	                data : "id=1" + query_string ,
	                success: function (text) {
	      				top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallmyfriends';
	      			}
	             });
			});						
		
		

	}
	function removeFromFriendFeed(){

		jQuery('#messageConfirmationId').jqm({trigger: '#removefromfriendfeedButtonId', onHide: closeModal });
		var cont = 0;
		var query_string = '';
		jQuery("input[name='fanprofilesId']").each(			
				function() 
				    { 
				        if(jQuery(this).is(':checked')){
				        	query_string += "&fanprofilesId[]=" + jQuery(this).val();
							cont++;
						}	 
				         
				}
			);
		jQuery('#modalTitleConfirmationId').html('REMOVE FROM FRIEND FEED?');
		if(cont == 0){
			jQuery('#messageConfirmationTextId').html('You do not have any Friends selected.  Select the Friend(s) you want to remove from your Friend Feed');
			jQuery('#acceptModalButtonId').addClass('jqmClose');
			jQuery('#messageConfirmationId').jqmShow();
			return;
		}
		
		jQuery('#messageConfirmationTextId').html('Are you sure you want to remove the selected friend(s) from your feed?');
		jQuery('#messageConfirmationId').jqmShow();
		
		url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/removefromfriendsfeed';
		jQuery('#acceptModalButtonId').unbind();				
		jQuery('#acceptModalButtonId').click(function(){
			jQuery.ajax({
                type: 'POST',
                url : url,
                data : "id=1" + query_string ,
                success: function (text) {
      				top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showallmyfriends';
      			}
             });
		});	

	}

	function searchFriendsSubmit( id , urlBase ,type){

		jQuery('#'+id).submit(function(){
			jQuery('#FanProfilesCriteria a').removeClass('filterSelected');
	     	jQuery('#'+id).addClass('filterSelected');
	     	var type = jQuery('#search-profiles').val();
			jQuery('#newboxresult').html('Loading...');
			url = urlBase + type;
			jQuery('#newboxresult').load(url);
			return false;
				
	     });

	}
     
	function searchFriends( id , urlBase ,type){

		jQuery('#'+id).click(function(){
			jQuery('#FanProfilesCriteria a').removeClass('filterSelected');
	     	jQuery('#'+id).addClass('filterSelected');
			jQuery('#newboxresult').html('Loading...');
			url = urlBase + type;
			jQuery('#newboxresult').load(url);
			
	     });

	}

	function clickUnClickCheckBoxes(checked){
		jQuery("input[name='fanprofilesId']").each(		
				function() 
				{ 
					jQuery(this).attr('checked',checked); 
			   	}
		);

	}

</script>







      <div id="ContentWrapper" class="TwoColumnLayout">

          <div class="FirstColumnOfThree">
          <?php 
              $session = new Zend_Session_Namespace('userSession');
              
          ?> 
          	<!-- START Profile Box Include -->
                	<?php echo $this->render('include/miniProfilePlusLoginBox.php'); ?>
              <!-- END Profile Box Include -->	
          
           </div><!--end FirstColumnOfThree-->
		  

            <div class="SecondColumn" id="SecondColumnPlayerProfile">
                <h1><?php echo $session->currentUser->screen_name; ?> Friends</h1>
          					<div class="img-shadow">
          						<div class="WrapperForDropShadow">
          							<div class="SecondColumnProfile">
          							  
          							  <ul class="FriendSearch">
                                          <li class="Search">
                                            <form id="searchprofilesform" method="post">
                                                <label>Search Friends</label>
                                              <input id="search-profiles" type="text" class="text" name="searchtext"/>
                                              <input type="submit" class="Submit" value="Search"/>
                                            </form>
                                          </li>
                              <li class="PopularSearchesFriends">
                              		Popular Searches:  <?php $cont = 1; 
                              								foreach ($this->searchTerms as $data) { ?>
                              							<a href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data['nickname'];?>">
                           								<?php echo $data['nickname'];
                           									if($cont < 3){
				                                            echo ",";
				                                         }
                                       					$cont++;
                           								?>
                          								</a> 
                              							<?php } ?>
                              							
                              </li>
                            </ul><!-- /SearchSelections--> 
      								
      					          <div id="FriendsWrapper">
      					          	<div id="FanProfilesCriteria" class="FriendLinks">
      					          		 View: 						<a id="all" href="javascript:void(0)">All</a>|
      					          									<a id="mp" href="javascript:void(0)">Most Popular</a>|
      					          									<a id="ma" href="javascript:void(0)">Most Active</a>|
      					          									<a id="ru" href="javascript:void(0)">Recently Updated</a>|
      					          									<a id="onl" href="javascript:void(0)">Online Now</a>|
      					          									<a id="ff" href="javascript:void(0)">In Friend Feed</a>
      					          									
      					          	</div>
      					          	<ul class="Friendtoolbar">
      					          		<li class="Buttons">
      					          			  <input type="checkbox" id="removeFriendsId" class="checkbox"><!--input type="submit" id="refresh" value="Refresh" class="submit blue">-->
      					          			  <input type="button" id="removeButtonId" value="Remove" class="submit blue">
      					          			  <input type="button" id="addtofriendfeedButtonId" value="Add to Friend Feed" class="submit blue">
      					          			  <input type="button" id="removefromfriendfeedButtonId" value="Remove from Friend Feed" class="submit blue">
      					          		</li>
      					          		<li id="gridView" class="GridViewOff"/>
                                		<li id="listView" class="ListViewOn"/>	
      					          	</ul>
      					          	
		                    		 <div id="MainErrorMessage" class="ErrorMessages closeDiv">The selected Friend(s) have been removed.</div>
			                     	
    					             <div id ="newboxresult" class="ProfilesResult">	
    					          	
    							         </div>
    							     </div>
                        	</div>
                        </div>
                    </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->

              </div><!--end Second Column-->

    </div> <!--end ContentWrapper-->
