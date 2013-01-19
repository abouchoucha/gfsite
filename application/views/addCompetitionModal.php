 <script>

	jQuery(document).ready(function() {
        jQuery('#searchbyClub').hide();
        jQuery("input[@name='radioTeam']").change(function(){
            if (jQuery("input[@name='radioTeam']:checked").val() == 'name'){   
            	jQuery('#searchbyClub').hide();
            	jQuery('#playerresults').hide();
            	jQuery('#searchtext').show();
            	jQuery('#searchButtonId').show();
            }else{
                jQuery('#searchtext').hide();
                jQuery('#searchbyClub').show();
                jQuery('#searchButtonId').hide();
            }   
        });
       
	});

	function searchLeaguesByName(){
		var criteria = jQuery('#searchtext').val();
		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/searchcompetitions/criteria/'+criteria+'/';
		jQuery('results').innerHTML='Searching...'; 
		jQuery('#results').load(url);
		jQuery('#results').show();
	}

	function findCountriesByContinentGroup(groupId){

		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/findcountriesbycontinent/r/'+groupId;
		jQuery('#country_search').load(url);
	}

	function populateTeamsCombo(dtarget , countryId){
		 countryId = jQuery('#' +countryId).val();	
	     var url = '<?php echo Zend_Registry::get("contextPath"); ?>/user/addfavorities';
		 jQuery('#' +dtarget).load(url , {countryId : countryId});
	}
	
	function searchLeaguesByCountry(){
		var countryId = jQuery('#country_search').val();
		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/searchcompetitions/countryid/'+countryId +'/';
		jQuery('#results').innerHTML='Searching...'; 
		jQuery('#results').load(url);
		jQuery('#results').show();
	}

	function doAddLeague(leagueId ){

		var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/competitions/addfavorite';
        jQuery('#data').html('Loading...');
	 	
		jQuery.ajax({
	           type: 'POST',
	           url : url,
	           data : "fromPage=edit&leagueId=" + leagueId ,
	           success: function (text) {
	 					jQuery('#data').html(text);
	 					if(jQuery('#MessageFavoriteLeaguesAdded').is(":hidden")){
							   //alert("small hidden");
							   jQuery('#MessageFavoriteLeaguesAdded').show("slow");
							   jQuery('#MessageFavoriteLeaguesAdded').animate({opacity: '+=0'}, 2000).slideUp('slow'); 
						}
	 			}
	        });	
	 	
	 	jQuery('#addFavLeaguesModal').jqmHide();
		
	}


</script>

 <?php require_once 'seourlgen.php';	$urlGen = new SeoUrlGen();?>
      <div id="PopupFavorites">
          <div class="SecondColumnBlueBackground">
              <h4>Show Leagues/Competitions By:</h4>
                <input type="radio" id="Radio1" name="radioTeam" class="radio" value="name" checked />Name
                <input type="radio" id="Radio2" name="radioTeam" class="radio" value="club" />Country  
                <p></p>
                
                    <input id="searchtext" type="text" name="searchtext" />
                    <input id="searchButtonId" type="button" class="submitmodal"  value="Search" onclick="searchLeaguesByName()" />     
               
                <div id="searchbyClub">
                    <div id="RegionList" class="AlphaListing">
                        <a class="First" href="javascript:findCountriesByContinentGroup('2')">Americas</a> 
                        <a href="javascript:findCountriesByContinentGroup('1')">Europe</a> 
                        <a href="javascript:findCountriesByContinentGroup('3')">Africa</a> 
                        <a href="javascript:findCountriesByContinentGroup('4')">Asia</a> 
                        <a href="javascript:findCountriesByContinentGroup('4')">Oceana</a>
                  	</div>
              	    </br>
									 <select id="country_search" name="country_search">
										  <option value="Select Country" selected>Select Country</option>	
									 </select>
  			 					 <input id="displayTeamsId" type="button" class="submitmodal"  value="Display Leagues" onclick="searchLeaguesByCountry()"/>
                </div>
              <p />
              <input type="hidden" name="modalforPlayer" id="modalforPlayer" value="<?php echo $this->modal;?>">
              <div id="results">
              </div>
          </div>
      </div>
