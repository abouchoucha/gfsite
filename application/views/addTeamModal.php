<?php require_once 'seourlgen.php';	$urlGen = new SeoUrlGen();?>
<script language="javascript">

function searchTeams(){

	 var criteria = $('searchtext').value;
	 var nat = $('radioTypeN').checked;
	 if(nat == true){
	 	type = $('radioTypeN').value;
	 }
	 var club = $('radioTypeC').checked;
	 if(club == true){
	 	type = $('radioTypeC').value;
	 }
	 var target = 'teamsresults';
     $('teamsresults').innerHTML='Searching...';
     var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/searchteam/q/'+criteria +'/type/'+type;
     var myAjax = new Ajax.Updater(target, url, {
                                             method: 'get'
                                           }
                                         );

}

function doAddTeam(teamId){

		var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/team/addfavorite';
        jQuery('#data').html('Loading...');
	 	
		jQuery.ajax({
	           type: 'POST',
	           url : url,
	           data : "fromPage=edit&teamId=" + teamId ,
	           success: function (text) {
	 					jQuery('#data').html(text);
	 					if(jQuery('#MessageFavoriteTeamAdded').is(":hidden")){
							   //alert("small hidden");
							   jQuery('#MessageFavoriteTeamAdded').show("slow");
							   jQuery('#MessageFavoriteTeamAdded').animate({opacity: '+=0'}, 2000).slideUp('slow'); 
						}
	 			}
	        });	
	 	
	 	jQuery('#addFavTeams').jqmHide();
	}	



</script>


<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/goalface.css' rel="stylesheet" type="text/css" media="screen"/>
<div id="PopupFavorites">
                  
                   
                        <div class="WrapperForDropShadow">
                            
                            <div class="SecondColumnBlueBackground">
                                <h4>Show teams by:</h4>
                                <!-- <input type="radio" id="Radio1" name="radioRegion" class="radio" /><a href="">Europe</a>
                                <input type="radio" id="Radio2" name="radioRegion" class="radio" /><a href="">Americas</a>
                                <input type="radio" id="Radio3" name="radioRegion" class="radio" /><a href="">Africa</a>
                                <input type="radio" id="Radio4" name="radioRegion" class="radio" /><a href="">Asia</a>
                                <p />
                                <h4>By:</h4>
                                <input type="radio" id="Radio1" name="radioRegion" class="radio" /><a href="">Name</a> 
                                <input type="radio" id="Radio1" name="radioRegion" class="radio" /><a href="">Country</a>
                                <p />
                                 -->
                                <input type="radio" id="radioTypeC" value="club" name="radioType" class="radio" checked/><a href="">Club</a>
                                <input type="radio" id="radioTypeN" value="national" name="radioType" class="radio" /><a href="">National</a> 
                                
                                <p />
                              <form id="searchplayersform" method="post">
							 	<fieldset>
									<input type="text" name="searchtext" id="searchtext"/>
									<input class="submit" type="button" value="Search" onclick="searchTeams()"/>
								</fieldset>
							  </form>
								                              
                                
                                <p />
                                <div id="teamsresults" class="TeamSearchListing">
                                    
                                </div>
                                
                                 
              
                            </div>
                           
                        </div>                   
                    
                </div>    
