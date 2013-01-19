<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
    $session = new Zend_Session_Namespace('userSession');
 ?>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/popup.js" type="text/javascript"></script>

<script type="text/JavaScript">
     jQuery(document).ready(function() {
         
        jQuery('#PlayerPositions').hide();
        jQuery('#recentlyAdded').hide();
        jQuery('#countries').hide();
        jQuery('#countriesTeam').hide();
        jQuery("#AF").addClass("filterSelected");


        jQuery("#allfilter").click(function(e){
          	jQuery('#PlayerPositions').hide();
          	jQuery('#recentlyAdded').hide();
          	jQuery('#countries').hide();
          	jQuery('#countriesTeam').hide();
            jQuery("#PlayerSearchCriteria a").removeClass("filterSelected");
          	jQuery("#letterGroup a").removeClass("filterSelected");
          	jQuery("#AF").addClass("filterSelected");
          	jQuery("#allfilter").addClass("filterSelected");
          	jQuery("#letterGroup").show();
          	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory';
          	jQuery('#PlayerAlphabetList').html('Loading...');
          	jQuery('#PlayerAlphabetList').load(url);
        });

        jQuery("#byposition").click(function(e){
          	jQuery('#PlayerPositions').show();
          	jQuery('#recentlyAdded').hide();
          	jQuery('#countries').hide();
          	jQuery('#countriesTeam').hide();
          	jQuery('#PlayerAlphabetList').html('');
            jQuery("#PlayerSearchCriteria a").removeClass("filterSelected");
          	jQuery("#byposition").addClass("filterSelected");
            jQuery("#PlayerPositions a").removeClass("filterSelected");
          	jQuery("#forwards").addClass("filterSelected");
          	jQuery("#letterGroup").show();
          	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/p/AT';
          	jQuery('#PlayerAlphabetList').html('Loading...');
          	jQuery('#PlayerAlphabetList').load(url);
        });

        jQuery("#forwards").click(function(e){
          	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/p/AT';
          	jQuery("#PlayerPositions a").removeClass("filterSelected");
          	jQuery("#letterGroup").show();
          	jQuery("#letterGroup a").removeClass("filterSelected");
          	jQuery("#AF").addClass("filterSelected");
          	jQuery("#forwards").addClass("filterSelected");
          	jQuery('#PlayerAlphabetList').html('Loading...');
          	jQuery('#PlayerAlphabetList').load(url);

        });
        
        jQuery("#midfielders").click(function(e){
          	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/p/MD';
          	jQuery("#PlayerPositions a").removeClass("filterSelected");
          	jQuery("#midfielders").addClass("filterSelected");
          	jQuery("#letterGroup").show();
          	jQuery("#letterGroup a").removeClass("filterSelected");
          	jQuery("#AF").addClass("filterSelected");
          	jQuery('#PlayerAlphabetList').html('Loading...');
          	jQuery('#PlayerAlphabetList').load(url);
        });

        jQuery("#defenders").click(function(e){
          	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/p/DF';
          	jQuery("#PlayerPositions a").removeClass("filterSelected");
          	jQuery("#defenders").addClass("filterSelected");
          	jQuery("#letterGroup").show();
          	jQuery("#letterGroup a").removeClass("filterSelected");
          	jQuery("#AF").addClass("filterSelected");
          	jQuery('#PlayerAlphabetList').html('Loading...');
          	jQuery('#PlayerAlphabetList').load(url);
        });

        jQuery("#goalkeepers").click(function(e){
          	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/p/GK';
          	jQuery("#PlayerPositions a").removeClass("filterSelected");
      			jQuery("#goalkeepers").addClass("filterSelected");
      			jQuery("#letterGroup").show();
          	jQuery("#letterGroup a").removeClass("filterSelected");
          	jQuery("#AF").addClass("filterSelected");
            jQuery('#PlayerAlphabetList').html('Loading...');
          	jQuery('#PlayerAlphabetList').load(url);
        });

        jQuery("#byrecently").click(function(e){
          	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/d/today';
          	jQuery("#letterGroup").hide();
          	jQuery('#PlayerPositions').hide();
          	jQuery('#countries').hide();
          	jQuery('#countriesTeam').hide();
          	jQuery('#recentlyAdded').show();
          	jQuery("#PlayerSearchCriteria a").removeClass("filterSelected");
          	jQuery("#byrecently").addClass("filterSelected");
          	jQuery("#recentlyAdded a").removeClass("filterSelected");
          	jQuery("#today").addClass("filterSelected");
            jQuery('#PlayerAlphabetList').html('Loading...');
          	jQuery('#PlayerAlphabetList').load(url);
        });
        jQuery("#today").click(function(e){
        	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/d/today';
        	jQuery("#letterGroup").hide();
        	jQuery('#PlayerPositions').hide();
        	jQuery('#recentlyAdded').show();
        	jQuery("#recentlyAdded a").removeClass("filterSelected");
        	jQuery("#today").addClass("filterSelected");
          	jQuery('#PlayerAlphabetList').html('Loading...');
        	jQuery('#PlayerAlphabetList').load(url);
        });
        jQuery("#last3").click(function(e){
        	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/d/last3';
        	jQuery("#letterGroup").hide();
        	jQuery('#PlayerPositions').hide();
        	jQuery('#recentlyAdded').show();
        	jQuery("#recentlyAdded a").removeClass("filterSelected");
        	jQuery("#last3").addClass("filterSelected");
          	jQuery('#PlayerAlphabetList').html('Loading...');
        	jQuery('#PlayerAlphabetList').load(url);
        });
        jQuery("#last7").click(function(e){
        	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/d/last7';
        	jQuery("#letterGroup").hide();
        	jQuery('#PlayerPositions').hide();
        	jQuery('#recentlyAdded').show();
        	jQuery("#recentlyAdded a").removeClass("filterSelected");
        	jQuery("#last7").addClass("filterSelected");
          	jQuery('#PlayerAlphabetList').html('Loading...');
        	jQuery('#PlayerAlphabetList').load(url);
        });
        jQuery("#bynationality").click(function(e){
        	jQuery("#letterGroup").hide();
        	jQuery('#PlayerPositions').hide();
        	jQuery('#recentlyAdded').hide();
        	jQuery('#countriesTeam').hide();
        	jQuery('#countries').show();
        	jQuery('#country').attr('value',0);
        	jQuery('#PlayerAlphabetList').html('');
        	jQuery("#PlayerSearchCriteria a").removeClass("filterSelected");
        	jQuery("#bynationality").addClass("filterSelected");
        	jQuery("#recentlyAdded a").removeClass("filterSelected");
        	jQuery("#today").addClass("filterSelected");

        });
        jQuery("#byclub").click(function(e){
        	jQuery("#letterGroup").hide();
        	jQuery('#PlayerPositions').hide();
        	jQuery('#recentlyAdded').hide();
        	jQuery('#countries').hide();
        	jQuery('#countriesTeam').show();
        	jQuery('#countryTeam').attr('value',0);
        	jQuery('#team').attr('value',0);
        	jQuery('#PlayerAlphabetList').html('');
        	jQuery("#PlayerSearchCriteria a").removeClass("filterSelected");
        	jQuery("#byclub").addClass("filterSelected");
        	jQuery("#recentlyAdded a").removeClass("filterSelected");
        	jQuery("#today").addClass("filterSelected");

        });

        jQuery('#searchPlayersIdButton').click(function(){
	 	 	search('players');
		 });
	 	jQuery(document).keydown(function(event) {
			if (event.keyCode == 13) {
				search('players');
			}
	 	});	
        
		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory';
    	jQuery('#PlayerAlphabetList').html('Loading...');
    	jQuery('#PlayerAlphabetList').load(url);



	});

 	function search(category){
 		
 		var searchText = jQuery('#search-players').val();
 		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/search/index/q/'+searchText;
 	 	if(category != ''){
 	 		url = url + "/t/"+category;
 	    }
 	    //alert(url);
 	    window.location = url;
 	} 	

   	function showPlayersByLetter(letter){
   		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/ft/'+letter;
		var posType = jQuery('#posType').attr('value');
		var countryId = jQuery('#countryId').attr('value');
		if(posType != ''){
			url += '/p/' + posType;
		}
		if(countryId != ''){
			url += '/c/'+countryId;
		}
		jQuery('#letterGroup a').removeClass('filterSelected');
    	jQuery('#'+ letter ).addClass('filterSelected');
    	jQuery('#PlayerAlphabetList').html('Loading...');
    	jQuery('#PlayerAlphabetList').load(url);

   	}

   	function showPlayersByUniqueLetter(letter){
   		var position = jQuery('#posType').attr('value');
   		var countryId = jQuery('#countryId').attr('value');
   		url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayersbyalphabet/letter/'+letter;
   		if(position != ''){
			url += '/position/'+position;
		}
   		if(countryId != ''){
			url += '/c/'+countryId;
		}
   		url += "/page/";
		document.location.href = url;
   	}

   	function findPlayersByCountry(countryId){
   		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayerdirectory/c/'+countryId;
   		jQuery("#letterGroup").show();
    	jQuery('#PlayerAlphabetList').html('Loading...');
    	jQuery('#PlayerAlphabetList').load(url);
	}

   	function selectTeams( countryId){
        var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findteamsbycountry/c/'+countryId;
        jQuery('#team').load(url);
    }

    function selectPlayersByTeam(teamId){

    	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/showplayersbyteam/t/'+teamId;
        jQuery('#PlayerAlphabetList').load(url);

    }



</script>
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>

<div id="ContentWrapper" class="TwoColumnLayout">
    <div class="FirstColumn">
               
				<?php echo $this->render('include/topleftbanner.php')?>
				<?php
                    $session = new Zend_Session_Namespace('userSession');
                    if($session->email != null){
                ?>
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>

                        </div>
                    </div>

                    <!--Players Directory left Menu-->
                    <div class="img-shadow">
                        <?php echo $this->render('include/navigationplayersdirectory.php');?>
                    </div>
                   
					<?php }else { ?>

                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>

                     

                        <!--Players Directory left Menu-->
                        <div class="img-shadow">
                            <?php echo $this->render('include/navigationplayersdirectory.php');?>
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

								<!--Facebook Like Module-->
                   <?php echo $this->render('include/navigationfacebook.php')?>




            </div><!--end FirstColumnOfTwo-->

            <div id="SecondColumnPlayerProfile" class="SecondColumn">
              <h1>Football Player Directory</h1>
              	<div class="img-shadow">
          			<div class="WrapperForDropShadow">
          				<div class="SecondColumnProfile">
          				   	<ul class="FriendSearch">
                              <li class="Search">
                                <form id="searchplayersform" method="get" action="<?php echo Zend_Registry::get("contextPath"); ?>/search/">
									<label>Search Players</label>
									<input id="search-players" type="text" class="text"  name="q"/>
									<input id="t" type="hidden" value="players" class="hidden"  name="t"/>
									<input id="searchPlayersIdButton" type="submit" class="Submit" value="Search"/>
                                </form>
                              </li>
                              <li class="PopularSearches searchPlayers">
                              		Popular Players:
                              		    <?php $i = 0; foreach($this->popularPlayers as $data) { if($i<3){?>
                              			<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_firstname"].' '.$data["player_lastname"] ?>">
	      					          		      <?php echo $data["player_firstname"].' '.$data["player_lastname"] ?>
	      				          	  	    </a>
                                  <?php if ($i != 2){echo ",";} ?>
                              		<?php $i= $i+1;}} ?>
                              </li>
                            </ul><!-- /SearchSelections-->

                            <div id="FriendsWrapper">
	      		          	    <div class="FriendLinks">
	      		          		     <span style="font-size:16px;font-weight:bold;">Featured Players</span>&nbsp;
                                     <a id="fplayerslink" href="<?php echo $urlGen->getFeaturedPlayersUrl(TRUE) ?>" title="Featured Players">See More</a>
	      		          	    </div>
                            	<ul class="LayoutFourPicturesBig">
    			  	               <?php foreach ($this->featuredPlayers as $data) { ?>

	    			  	               <li>
  	    		  			             <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_common_name"]; ?>">
  	    		  				            <strong><?php echo $data["player_name_short"]?></strong>
  	    	  				             </a>
	    		  		    	         <br/>
	    		  		    	   <?php
						                $path_player_photos = $config->path->images->players . $data["player_id"] .".jpg" ;
						                if (file_exists($path_player_photos)) { 
						          ?>
	                                  	<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true , $data["player_common_name"]); ?>" title="<?php echo $data["player_common_name"]; ?>">
	  										<img id="player<?php echo $data["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $data["player_id"]; ?>.jpg"/>
	  	    		  			       </a>
  	    		  			      <?php } else {  ?>
	  	    		  			        <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_common_name"]; ?>">
	  										<img id="player<?php echo $data["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=120&h=120&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/> 
	  	    		  			         </a>
  	    		  			       <?php }   ?>

                                    <br/>
                                   <strong>Team: </strong><a  href="<?php echo $urlGen->getClubMasterProfileUrl($data["team_id"], $data["team_seoname"], true)?>" title="<?php echo $data["team_name"] ?>"><?php echo $data["team_name"] ?></a>
                                   <br/>
            		               <strong>Country: </strong><a  href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($data["country_name"], $data["player_country"], True); ?>" title="<?php echo $data["country_name"] ?>"><?php echo $data["country_name"] ?></a>
            		               <br/>
            		               <strong>Position: </strong><?php echo $data["player_position"] ?>
            		               <br/>
            		               <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_common_name"];?>">
            		               View Profile &raquo;
            		               </a>
            		               
            		               <?php if ($session->email != null) { ?>
                  					<?php if($session->userId == $data['user_id']) { ?>
                                          <a id="btn_player_off_<?php echo $data["player_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $data["player_id"];?>);">
                                    				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                    			</a>
                                    			<a id="btn_player_on_<?php echo $data["player_id"];?>" class="subscribe  ScoresClosed" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
                                    				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                    			</a>
                                      <?php } else { ?>
                                      	<a id="btn_player_on_<?php echo $data["player_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
                                    				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                    			</a>
                                    			<a id="btn_player_off_<?php echo $data["player_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $data["player_id"];?>);">
                                    				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                                		</a>
                                                <?php }  ?>
                    					<?php } else { ?>
                    					    <a id="btn_playerid_<?php echo $data["player_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
                    							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                    						</a>
                    			    <?php }  ?>	
            		               
          	                 	</li>
            	            	  <?php } ?>
    	                   		</ul>
	    		                  <br class="clearleft"/><br class="clearleft"/>
	    		                  <div class="FriendLinks">
	      			          		     <span style="font-size:16px;font-weight:bold;">Most Popular Players</span>&nbsp;<a id="pplayerslink"  href="<?php echo $urlGen->getPopularPlayersUrl(TRUE) ?>">See More</a>
	      			          	  </div>
      				          	  <?php $i = 1; ?>
      				          	  <ul class="LayoutSixPictures LayoutSixPicturesFirst">
      					          	  <?php foreach ($this->popularPlayers as $data) { ?>

      					          	  	<?php if ($i == 7){ ?>
      					          	  		</ul><ul class="LayoutSixPictures">
      					          	  <?php } ?>
										<li>
      					          	  		<a class="playerTitle" href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_common_name"]; ?>">
                                                <?php echo $data["player_name_short"] ?>
                                            </a>
											  <?php
										          $path_player_photos = $config->path->images->players . $data["player_id"] .".jpg" ;
										          if (file_exists($path_player_photos)) { 
										        ?>
      					          	  			<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_name_short"] ?>">
      					          	  				<img id="player<?php echo $data["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/players/<?php echo $data["player_id"]; ?>.jpg"/>
      				          	  				</a>
      				          	  			<?php } else {  ?>
      					          	  			<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_common_name"]; ?>">
  											        <img id="player<?php echo $data["player_id"];?>profileImage" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=80&h=80&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/ProfileMale.gif"/>
  	    		  			              </a>
      					          	  		<?php } ?>
      					          	  		<a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>" title="<?php echo $data["player_common_name"]; ?>">
									          View Profile &raquo;
            		               			</a>
            		               			
            		               			 <?php if ($session->email != null) { ?>
                              					<?php if($session->userId == $data['user_id']) { ?>
                                                      <a id="btn_player_off_<?php echo $data["player_id"];?>" class="unsubscribe" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $data["player_id"];?>);">
                                                				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                                			</a>
                                                			<a id="btn_player_on_<?php echo $data["player_id"];?>" class="subscribe  ScoresClosed" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
                                                				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                                			</a>
                                                  <?php } else { ?>
                                                  	<a id="btn_player_on_<?php echo $data["player_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
                                                				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                                			</a>
                                                			<a id="btn_player_off_<?php echo $data["player_id"];?>" class="unsubscribe ScoresClosed" href="javascript:" onclick="unsubscribeToPlayer(<?php echo $data["player_id"];?>);">
                                                				<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/unsubscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                                            		</a>
                                                            <?php }  ?>
                                					<?php } else { ?>
                                					    <a id="btn_playerid_<?php echo $data["player_id"];?>" class="subscribe" href="javascript:" onclick="subscribeToPlayer(<?php echo $data["player_id"];?>);">
                                							<img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/subscribe.jpg" alt="Subscribe to <?php echo $data["player_name_short"];?>'s updates">
                                						</a>
                                			    <?php }  ?>	
            		               			
      					          	  	</li>
      					          	  	<?php $i++ ?>

      					          	  <?php } ?>
      				          	  </ul>

      		          	    <br class="clearleft"/><br class="clearleft"/>
        		          		<span style="font-size:16px;font-weight:bold;">Player Directory</span>
                                <div id="PlayerSearchCriteria" class="FriendLinks">
      		          		     Show: <a href="javascript:void(0)" id="allfilter" class="filterSelected">All</a> |
                                        <a href="javascript:void(0)" id="byposition">By Position</a> |
                                       <a href="javascript:void(0)" id="byrecently">Recently Updated</a> |
                                       <a href="javascript:void(0)" id="byclub">By Club Team</a> |
                                       <a href="javascript:void(0)" id="bynationality">By Nationality</a> 
                                      
      		          	    	</div>
      			          	    <div id="PlayerPositions" class="FriendLinks">
      			          		     <a id="forwards" href="javascript:void(0)">Forwards</a>|
      			          		     <a id="midfielders" href="javascript:void(0)">Midfielders</a>|
      			          		     <a id="defenders" href="javascript:void(0)">Defenders</a>|
      			          		     <a id="goalkeepers" href="javascript:void(0)">Goalkeepers</a>
      			          	    </div>
      			          	    <div id="recentlyAdded" class="FriendLinks">
      			          		     <a id="today" href="javascript:void(0)">Today</a>|
      			          		     <a id="last3" href="javascript:void(0)">Last 3 days</a>|
      			          		     <a id="last7" href="javascript:void(0)">Last 7 days</a>
      			          		</div>

      			          		<div id="countries" class="SelectBoxWrapper">
									     <select id="country" name="country" onchange="findPlayersByCountry(this.value)">
											<option value="0" selected>Select Country</option>
												<?php foreach ($this->countries as $league) { ?>
								            <option value="<?php echo $league["country_id"];?>"><?php echo $league["country_name"];?></option>
								            <?php } ?>
										  </select>
  								</div>
  								<div id="countriesTeam" class="SelectBoxWrapper">
									     <form id="countryteamform" method="post">
									     <select id="countryTeam" name="country" onchange="selectTeams(this.value)">
											<option value="0" selected>Select Country</option>
												<?php foreach ($this->countries as $league) { ?>
								            <option value="<?php echo $league["country_id"];?>"><?php echo $league["country_name"];?></option>
								            <?php } ?>
										  </select>
										  <select id="team" name="team" onchange="selectPlayersByTeam(this.value)">
    													<option value="0">Select Team</option>
    									  </select>
    									  </form>
  								</div>

      			          	    <div id="letterGroup" class="FriendLinks">
      			          		     <a id="AF" href="javascript:showPlayersByLetter('AF')">A-F</a>|
      			          		     <a id="GL" href="javascript:showPlayersByLetter('GL')">G-L</a>|
      			          		     <a id="MR" href="javascript:showPlayersByLetter('MR')">M-R</a>|
      			          		     <a id="SZ" href="javascript:showPlayersByLetter('SZ')">S-Z</a>
      			          	    </div>

                               <div id="PlayerAlphabetList">

	                            </div>


      				          	</div>
      				          	</div><!--end SecondColumnProfile-->
                      </div><!--end wrapperForDropShadow-->
                 </div>	<!--end img-shadow-->
            </div><!--end SecondColumnPlayerProfile and SecondColumn-->
</div> <!--end ContentWrapper-->


<?php include 'include/playerh2h.php';?>


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
<script  src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/subscribeplayer.js" type="text/javascript"></script>