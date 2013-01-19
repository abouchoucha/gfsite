<?php $urlGen = new SeoUrlGen(); 
require_once 'Common.php'; 
$common = new Common();
$session = new Zend_Session_Namespace('userSession');?>  

<script type="text/JavaScript">

         jQuery(document).ready(function() {
                       
              jQuery('a.userTrigger').cluetip({         
                  waitImage: true,
                  local: false,
                  cluetipClass: 'jtip', 
                  arrows: true, 
                  sticky: false,
                  mouseOutClose: true,
                  closePosition: 'title',
                  width: 200,
                  height : '220'
               });

          	jQuery('ul>li#listView').click(function(){
              	if(jQuery('#listDisplayFriends').is(":hidden")){
              		 jQuery('#gridDisplayFriends').hide();
    			     jQuery('#listDisplayFriends').show();
	      			 jQuery('ul>li#listView').removeClass('ListViewOff');
	          		 jQuery('ul>li#listView').addClass('ListViewOn');
        			 jQuery('ul>li#gridView').removeClass('GridViewOn');
          		     jQuery('ul>li#gridView').addClass('GridViewOff');
          		 }
          	});
          	jQuery('ul>li#gridView').click(function(){
          		if(jQuery('#gridDisplayFriends').is(":hidden")){
          			 jQuery('#listDisplayFriends').hide();
        			 jQuery('#gridDisplayFriends').show();
          		     jQuery('ul>li#listView').removeClass('ListViewOn');
              	  	 jQuery('ul>li#listView').addClass('ListViewOff');
              	   	 jQuery('ul>li#gridView').removeClass('GridViewOff');
              	   	 jQuery('ul>li#gridView').addClass('GridViewOn');
          		}
          	});		

			jQuery('input:button#refresh').click(function(){
				var typeOfSearch = jQuery("#typeOfSearch").val();
				jQuery('#ajaxdata').html('Loading...'); 
				jQuery('#ajaxdata').load(url , {search :typeOfSearch});
		     });

            
         });


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

        	 var favoriteImage = null;
			 if(imageName != ''){
        	 	 favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/'+imageName;
			 }else {
				 favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif';
			 }	
        	 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

        	 jQuery("#acceptFavoriteModalButtonId").unbind();
        	 jQuery('#acceptFavoriteModalButtonId').click(function(){
        	 jQuery.ajax({
        			type: 'GET',
        			url :  '<?php echo Zend_Registry::get("contextPath"); ?>/message/addfriendrequest/friend/'+friendid,
        			success: function(data){
        				jQuery('#modalBodyResponseId').html('A Friend Request has been sent to  '+ name +'.');
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
        	 
        	 var favoriteImage = null;	
        	 if(imageName != ''){
        	 	 favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/'+imageName;
			 }else {
				 favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif';
			 }	
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

<?php $urlGen = new SeoUrlGen(); ?>

<?php if (count($this->paginator) < 1){
					echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>No profiles found.</div>";
					echo "<br>";
  }else{ ?>
  
<ul class="Friendtoolbar">
		 <li class="Buttons">
		    <!--<input type="checkbox" class="checkbox">--> 
                       <input type="button" id="refresh" value="Refresh" class="submit blue">
                 
                    <li id="listView" class="ListViewOff" />
                    <li id="gridView" class="GridViewOn"/>
</ul>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>
      					          	<!--Previous Paginatio -->

<input type="hidden" name="typeOfSearch" id="typeOfSearch" value="<?php echo $this->typeOfSearch; ?>">



<div id="gridDisplayFriends">	    					          
<?php  
                      // Retrive data from teams as a normal array
                      $userCounter = 0;
                      foreach ($this->paginator as $data) {
                        $userCounter++;
                        if($userCounter==1){
                            ?>
                            <ul class="LayoutFourPictures">
                            <?php } ?>
                            
                        <li>
                         <a href="<?php echo $urlGen->getUserProfilePage($data['screen_name'],True);?>" title="<?php echo $data['screen_name'];?>">
                            <?php echo $data['screen_name'];?>
                          </a>
                            <br/>
                          <a class="userTrigger" rel="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofiletip/username/<?php echo $data['screen_name'];?>" href="<?php echo $urlGen->getUserProfilePage($data['screen_name'],True);?>" title="<?php echo $data['screen_name'];?>">
                            <?php if ($data['main_photo']!=null || $data['main_photo']!='') { ?>		
                                <img class="logo120" border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php  echo $data['main_photo']; ?>" />
                            <?php } else { ?>
                                <img class="logo120" border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif">
                            <?php } ?>
                          </a>
                            <br/>
                              <?php if($this->typeOfSearch == 'active'){?>
    				                    <?php echo $data['totalActivities'];?> Activities
    				             <?php } else {?>
                                  		<?php echo $data['numfriends'];?> Friends
                               <?php } ?> 
                               
					          <br/>
                          <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($data["country_name"], $data["country_id"], True); ?>" title="<?php echo $data["country_name"]?>">
                              <?php echo $data["country_name"]; ?>
                           </a>
                        </li>
                                        
                          <?php 
                              if($userCounter==4){
                                $userCounter = 0;
                                echo '</ul>';
                              }
                          ?>
<?php } ?>

</div>

<div id="listDisplayFriends" class="closeDiv">	
	<?php   $cont = 1;
			if($this->paginator != null){
				
	            foreach ($this->paginator as $data) { 

	            	
            ?> 	

	  <ul class="FavoritePlayers">
	  	<li>
	  		<a class="userTrigger" rel="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofiletip/username/<?php echo $data['screen_name'];?>" href="<?php echo $urlGen->getUserProfilePage($data['screen_name'],True);?>" title="<?php echo $data['screen_name'];?>">
    			<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php  echo ($data['main_photo']!=null || $data['main_photo']!=''? $data['main_photo'] :"ProfileMale.gif"); ?>" />
    		</a>
		  </li>
		  <li>
		  	<h3><a href="<?php echo $urlGen->getUserProfilePage($data['screen_name'],True);?>" title="<?php echo $data["screen_name"];?>"><?php echo $data["screen_name"];?></a></h3>
	
		   <?php echo $data['country_name'];?><br>
		   <?php echo $data['numfriends'];?> Friend(s)<br> 
		    Member Since: <?php echo date ('M Y' , strtotime($data['registration_date']));?><br>
		    Last Updated: <?php echo $common->convertDates($data['date_update'])?><br>
		    Rating: <?php printf ("%01.2f", $data['rating']!=null?$data['rating']:'0') ;?> (<?php echo $data['total_votes'];?>)<br>
            
		 <li class="ViewProfile">
		   <a href="<?php echo $urlGen->getUserProfilePage($data['screen_name'],True);?>" title="<?php echo $data["screen_name"];?>">
		   View Profile
		   </a>
		       <br>
		       <?php if ($session->userId != $data["user_id"]){ ?>
												      <br> 
				<?php if($data["isfriend"] == 'n'){
	                           					 		if ($session->email == null) { ?>
			       			<input id="addtofriendsNotLoggedtrigger" onclick="loginModal()" class="submit" type="button" value="Add as a friend" style="display: inline;"/>
	              <?php } else { ?>
	                         <input id="addtofriendsLoggedtrigger" onclick="addFriends('<?php echo $data["user_id"];?>' ,'<?php echo $data["screen_name"];?>' , '<?php echo $data["main_photo"];?>')" class="submit" type="button" value="Add as a friend" style="display: inline;"/>
	                 <?php  } ?>
						
		       	<?php } else { ?>
		       			<input id="removefromfriendsLoggedtrigger" onclick="removeFriends('<?php echo $data["user_id"];?>' ,'<?php echo $data["screen_name"];?>' , '<?php echo $data["main_photo"];?>')" class="submit" type="button" value="Remove from friends" style="display: inline;"/>
	                   </br>
		       	<?php } ?>
	       	  
	       	   
	       		
	       		
	       		<?php }?>
	
	 	</li>
	</ul>
	<?php }
			}else {?>
        <label>No Profile Fans.</label>
     <?php }?>


</div>		

<ul class="Friendtoolbar">
		 <li class="Buttons">
		          			<!--<input type="checkbox" class="checkbox">--> 
                       <input type="button" id="refresh" value="Refresh" class="submit blue">
                       
		            <li id="listView" class="ListViewOff" />
                    <li id="gridView" class="GridViewOn"/>
</ul>
<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml'); ?>

<?php } ?>
