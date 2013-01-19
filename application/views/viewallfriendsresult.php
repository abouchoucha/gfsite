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


            //display views
          	jQuery('#listView').click(function(){
          		if(jQuery('#listDisplayFriends').is(":hidden")){
          			     jQuery('#gridDisplayFriends').hide();
          			     jQuery('#listDisplayFriends').show();
            			 jQuery('#listView').removeClass('ListViewOff');
                		 jQuery('#listView').addClass('ListViewOn');
              			 jQuery('#gridView').removeClass('GridViewOn');
                		 jQuery('#gridView').addClass('GridViewOff');
          		 }
          	});
          	jQuery('#gridView').click(function(){
          		if(jQuery('#gridDisplayFriends').is(":hidden")){
          			   jQuery('#listDisplayFriends').hide();
          			   jQuery('#gridDisplayFriends').show();
            		   jQuery('#listView').removeClass('ListViewOn');
                	   jQuery('#listView').addClass('ListViewOff');
                	   jQuery('#gridView').removeClass('GridViewOff');
                	   jQuery('#gridView').addClass('GridViewOn');
          		}
          	});		
               
         });
</script>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_ajax.phtml'); ?> 
  
  
  <input type="hidden" name="typeOfSearch" id="typeOfSearch" value="<?php echo $this->typeOfSearch; ?>">
    <?php if (count($this->paginator) < 1){
					echo "<div id=\"boxComments\" style=\"text-align:center\"><br/>No Friends in this view.</div>";
				}else{ ?>
    <div id="gridDisplayFriends">					          
    	
				<?php  
                    // Retrive data from teams as a normal array
                    $userCounter = 0;
                    foreach ($this->paginator as $data) {
                      $userCounter++;
                      if($userCounter==1){
                          ?>
                          * Indicates Included in Friend Feed
                          <ul class="LayoutFourPictures">
                          <?php } ?>
                          
                          <li>
                                <input type="checkbox" value="<?php echo $data['userId'];?>" name="fanprofilesId"><strong><?php echo ($data['infriendfeed']=='y'?'*':'');?></strong> <a href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data['nickname'];?>"><?php echo $data['nickname'];?></a>
            								
            								<br/>
    								          <a class="userTrigger" rel="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofiletip/username/<?php echo $data['nickname'];?>" href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data['nickname'];?>">
    								            <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php  echo ($data['main_photo']!=null || $data['main_photo']!=''? $data['main_photo'] :"ProfileMale.gif"); ?>" />
    							            </a>
                              <br/>
            							 
                            <?php echo $data['numfriends'];?> Friend(s)
                             <br>	
                            <?php echo $data['location'];?>
                          </li>
            
                      <?php 
                        if($userCounter==4){
                          $userCounter = 0;
                          echo '</ul>';
                        }
                      ?>
				<?php $userCounter++;} ?>
			
</div>				
				
				
<div id="listDisplayFriends" class="closeDiv">	
	
     <?php   $cont = 1;
			    foreach ($this->paginator as $data) { 
				if($cont==1){
				?>	
		* Indicates Included in Friend Feed
		<? }?>	
	  <ul class="FavoritePlayers">
	  	<li>
	  		<?php if($session->isMyProfile == 'y') { ?>
            <input type="checkbox" value="<?php echo $data['userId'];?>" name="fanprofilesId"> 
            <?php } ?>
		   <a class="userTrigger" rel="<?php echo Zend_Registry::get("contextPath"); ?>/profile/showprofiletip/username/<?php echo $data['nickname'];?>" href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data['nickname'];?>">
    			<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php  echo ($data['main_photo']!=null || $data['main_photo']!=''? $data['main_photo'] :"ProfileMale.gif"); ?>" />
    		</a>
		  </li>
		  <li>
		  	<h3><a href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data["nickname"];?>"><?php echo $data["nickname"];?></a></h3>
		  	<strong><?php echo ($data['infriendfeed']=='y'?'*':'');?></strong>
	
		   <?php echo $data['country_name'];?><br>
		   <?php echo $data['numfriends'];?> Friend(s)<br> 
		    Member Since: <?php echo date ('M Y' , strtotime($data['registration_date']));?><br>
		    Last Updated: <?php echo $common->convertDates($data['date_update'])?><br>
		    Rating: <?php printf ("%01.2f", $data['rating']!=null?$data['rating']:'0') ;?> (<?php echo $data['total_votes'];?>)<br>
		   
		 <li class="ViewProfile">
		   <a href="<?php echo $urlGen->getUserProfilePage($data['nickname'],True);?>" title="<?php echo $data["nickname"];?>">
		   View Profile
		   </a>
		       <br>
		       
	       	  
	
	 	</li>
	</ul>
	<?php $cont++;}?>
			
     


</div>				
<?php }?>				
