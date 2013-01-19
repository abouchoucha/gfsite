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

	function searchPlayers(){
		var criteria = jQuery('#searchtext').val();
		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/searchplayer/t/n/q/'+criteria+'/';
		jQuery('playerresults').innerHTML='Searching...'; 
		jQuery('#playerresults').load(url);
		jQuery('#playerresults').show();
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
	
	function searchPlayersByTeam(){
		var criteria = jQuery('#team_search').val();
		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/searchplayer/t/t/q/'+criteria +'/';
		jQuery('playerresults').innerHTML='Searching...'; 
		jQuery('#playerresults').load(url);
		jQuery('#playerresults').show();
	}

	function doAddPlayer(playerId , playerName){

		var modal = jQuery('#modalforPlayer').val();
		if(modal != ''){ //adding players for the register process
			var modal =  $('modalforPlayer').value;
			var newVar1 = 'autocomplete' + modal; 
			var newVar2 = 'playerId' + modal;
			var newVar3 = 'favalltime' + modal;
			$(newVar1).value = playerName;
			$(newVar2).value = playerId;
			$(newVar3).value = playerId;
			jQuery('#ex2').jqmHide();
		}else { //adding players for the edit favorites
			var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/player/addfavorite';
	        jQuery('#data').html('Loading...');
		 	
			jQuery.ajax({
		           type: 'POST',
		           url : url,
		           data : "fromPage=edit&playerId=" + playerId ,
		           success: function (text) {
		 					jQuery('#data').html(text);
		 					if(jQuery('#MessageFavoritePlayerAdded').is(":hidden")){
								   //alert("small hidden");
								   jQuery('#MessageFavoritePlayerAdded').show("slow");
								   jQuery('#MessageFavoritePlayerAdded').animate({opacity: '+=0'}, 2000).slideUp('slow'); 
							}
		 			}
		        });	
		 	
		 	jQuery('#AddFavPlayersModal').jqmHide();
		}	
	}


</script>

 <?php require_once 'seourlgen.php';	$urlGen = new SeoUrlGen();?>
      <div id="PopupFavorites">
          <div class="SecondColumnBlueBackground">
              <h4>Show Players By:</h4>
                <input type="radio" id="Radio1" name="radioTeam" class="radio" value="name" checked />Name
                <input type="radio" id="Radio2" name="radioTeam" class="radio" value="club" />Club  
                <p></p>
                
                    <input id="searchtext" type="text" name="searchtext" />
                    <input id="searchButtonId" type="button" class="submitmodal"  value="Search" onclick="searchPlayers()" />     
               
                <div id="searchbyClub">
                    <div id="RegionList" class="AlphaListing">
                        <a class="First" href="javascript:findCountriesByContinentGroup('2')">Americas</a> 
                        <a href="javascript:findCountriesByContinentGroup('1')">Europe</a> 
                        <a href="javascript:findCountriesByContinentGroup('3')">Africa</a> 
                        <a href="javascript:findCountriesByContinentGroup('4')">Asia</a> 
                        <a href="javascript:findCountriesByContinentGroup('4')">Oceania</a>
                  	</div>
              	    </br>
									 <select id="country_search" name="country_search" onchange="populateTeamsCombo('team_search',this.id)">
										  <option value="Select Country" selected>Select Country</option>	
									 </select>
  			 					 <select id="team_search" name="team_search">
    									<option value="0">Select Team</option>
    							 </select>
    							 <input id="displayTeamsId" type="button" class="submitmodal"  value="Display Players" onclick="searchPlayersByTeam()"/>
                </div>
              <p />
              <input type="hidden" name="modalforPlayer" id="modalforPlayer" value="<?php echo $this->modal;?>">
              <div id="playerresults">
              </div>
          </div>
      </div>
