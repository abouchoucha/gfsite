 <?php require_once 'Common.php'; ?>
  <?php require_once 'seourlgen.php'; ?> 
  <?php $urlGen = new SeoUrlGen ( ); 
  $session = new Zend_Session_Namespace('userSession');
  ?>
   <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
 
	 <script type="text/JavaScript">

		 jQuery(document).ready(function() {

	        jQuery('#all').addClass('filterSelected');
				 	var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showfeaturedprofiles/search/';
	
				 	//load the first list by default	
				 	jQuery('#ajaxdata').html('Loading...');
				 	url = urlBase + '<?php echo $this->typeOfSearch; ?>' +'/page/'; 
				 	jQuery('#FanProfilesCriteria a').removeClass('filterSelected');
				 	jQuery('#'+ '<?php echo $this->idToSelect; ?>').addClass('filterSelected');
					jQuery('#ajaxdata').load(url);
					
				 	
					/*jQuery('#refresh').click(function(){
						var typeOfSearch = jQuery("#typeOfSearch").val();
						jQuery('#data').html('Loading...'); 
						jQuery('#data').load(url , {search :typeOfSearch});
				     });*/
		
					searchProfilesByCriteria('all' , 'all' ,urlBase);
					searchProfilesByCriteria('mp' , 'popular' ,urlBase);
					searchProfilesByCriteria('lm' , 'likeme' ,urlBase);
					searchProfilesByCriteria('ma' , 'active' ,urlBase);
					searchProfilesByCriteria('nw' , 'newest' ,urlBase);
					searchProfilesByCriteria('ru' , 'recently' ,urlBase);
					searchProfilesByCriteria('onl' , 'online' ,urlBase);

				/*jQuery('#searchProfilesIdButton').click(function(){
				 	 	search('profiles');
					 });
				*/	 
			 	jQuery(document).keydown(function(event) {
					if (event.keyCode == 13) {
						search('profiles');
					}
			 	});	
			  
					
				
		 });

		 function search(category){
			   	
			   	var searchText = jQuery('#search-profiles').val();
			   	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/search/index/q/'+searchText;
			    	if(category != ''){
			    		url = url + "/t/"+category;
			       }
			       window.location = url;
		 } 	  
		    
		function searchProfilesByCriteria(idSelector , filter , urlBase){	
			 jQuery('#'+idSelector).click(function(){
					jQuery('#FanProfilesCriteria a').removeClass('filterSelected');
			     	jQuery('#'+idSelector).addClass('filterSelected');
					jQuery('#ajaxdata').html('Loading...'); 
					url = urlBase + filter+ '/page/'; 
					jQuery('#ajaxdata').load(url);
					
			     });
		}
		 
	</script>

    <script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/common.js" type="text/javascript"></script>

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

                   
                     <!--Goalface Join Now-->
                        <div class="img-shadow">
                            <div class="WrapperForDropShadow">
                           <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
                               <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
                            </a>
                            </div>
                        </div>
                    <?php } ?>

            </div><!--end FirstColumn-->

            <div class="SecondColumnOfTwo" id="SecondColumnPlayerProfile">
            
                <h1>Fan Profiles</h1>
                 		<div class="img-shadow">
          						<div class="WrapperForDropShadow">
          							<div class="SecondColumnProfile">
          							  
          							  <ul class="FriendSearch">
                              <li class="Search">
                                <form id="searchprofilesform" method="get" action="<?php echo Zend_Registry::get("contextPath"); ?>/search/">
                                	<label>Search Profiles</label>
                                  <input id="search-profiles" type="text" class="text"  name="q"/>
                                  <input id="t" type="hidden" value="profiles"  name="t"/>
                                  <input id="searchProfilesIdButton" type="submit" class="Submit" value="Search"/>
                                </form>
                              </li>
                              <li class="PopularSearchesProfiles">
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
      					          		View: 						<a id="all" href="javascript:void(0);">All</a>|
      					          									<a id="mp" href="javascript:void(0);">Most Popular</a>|
      					          									<?php if($session->email != null) {?>
      					          									<a id="lm" href="javascript:void(0);">Like Me</a>|
      					          									<?php } ?>
      					          									<a id="ma" href="javascript:void(0);">Most Active</a>|
      					          									<a id="ru" href="javascript:void(0);">Recently Updated</a>|
      					          									<a id="nw" href="javascript:void(0);">Newest</a>|
      					          									<a id="onl" href="javascript:void(0);">Online</a>
      					          	</div>
      					        
                                
      					    
      					    		   	
                    		<div id ="ajaxdata" class="ProfilesResult">
                    		
                              				
    						</div>
    						 
    					</div>
                        	</div>
                        </div>
                    </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
          
            </div><!--end SecondColumn-->

  </div> <!--end ContentWrapper-->
