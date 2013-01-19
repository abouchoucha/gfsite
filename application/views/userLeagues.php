<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery-1.2.6.js" type="text/javascript"></script>

<script type="text/JavaScript">
jQuery.noConflict();
jQuery(document).ready(function() {
	<?php echo $this->leagueIds; ?>
	   
	}); 

function accordion(el,num) {
    
    var eldown = el.parentNode.id+'-body';
    //var elup = $('visible').parentNode.id+'-body';
    //alert(eldown);
    //alert(elup);
    //alert(el);
    //alert(num);
    if(el == $('visible')+num ){  
      alert(el);
    }
    if ($('visible') == el) {
        new Effect.SlideDown(elup)
        return;
    }
    if ($('visible'+num)) {
        new Effect.Parallel(
        [
            new Effect.SlideUp(eldown),
        ], {
            duration: 0.1
        });
        el.id = '';
    }else { //not visible open up
        new Effect.Parallel(
        [
            
            new Effect.SlideDown(eldown)
        ], {
            duration: 0.1
        });
        el.id = 'visible'+num;
    }
    
    
}

function init() {

    // hide all elements apart from the one with id visible
    var acc = document.getElementById('favoriteCompetitionsFieldset');
    var apanels = acc.getElementsByTagName('div');
    for (i = 0; i < apanels.length; i++) {
        if (apanels[i].className == 'CompetitionListBox') {
            apanels[i].style.display = 'none';
        }
    }
    //var avis = document.getElementById('visible1').parentNode.id+'-body';
    
    document.getElementById('panel1-body').style.display = 'block';
}
function addEvent(elm, evType, fn, useCapture) {
    elm["on"+evType]=fn;return;
}

addEvent(window, "load", init);


var url = '<?php echo Zend_Registry::get("contextPath"); ?>/user/selectcompetitions';
//jQuery('#sleagues').load(url ,  {'zone1[]': ["Nigeria*Premier League*296*144"]});
								
var myAjax = new Ajax.Updater('sleagues', url, {
    method: 'post', 
    parameters: {
				<?php echo $this->compPreSelected; ?>
			   }

 
    }
);

function addleagues(){

     
     var target = 'sleagues';
     var pars = Form.serialize("FavoriteCompetitions");
     var url = '<?php echo Zend_Registry::get("contextPath"); ?>/user/selectcompetitions';
     
     var myAjax = new Ajax.Updater(target, url, {
                                                method: 'post', 
                                                parameters: pars
                                                }
                                            );
    
}

function removeLeagues(){

     
     var target = 'sleagues';
     var pars = Form.serialize("FavoriteCompetitions");
     var url = '<?php echo Zend_Registry::get("contextPath"); ?>/user/removecompetitions';
     
     var myAjax = new Ajax.Updater(target, url, {
                                                method: 'post', 
                                                parameters: pars
                                                }
                                            );
    
}

function saveLeagues(){
	
  var oChks= document.getElementsByName("zone1s[]");
  var oChks2= document.getElementsByName("zone2s[]");
  var oChks3= document.getElementsByName("zone3s[]");
  var oChks4= document.getElementsByName("zone4s[]");
  var oChks5= document.getElementsByName("zone5s[]");
  
  for(i=0;i<oChks.length;i++)
  {
     oChks[i].checked = true;         
  }
  for(i=0;i<oChks2.length;i++)
  {
     oChks2[i].checked = true;         
  }
  for(i=0;i<oChks3.length;i++)
  {
     oChks3[i].checked = true;         
  }
  for(i=0;i<oChks4.length;i++)
  {
     oChks4[i].checked = true;         
  }
  for(i=0;i<oChks5.length;i++)
  {
     oChks5[i].checked = true;         
  }
  
  $('FavoriteCompetitions').submit();
	
	
}


</script>

<div id="FormWrapper">

                <h3>Favorites Continued</h3>
                <div id="FormWrapperForBottomBackground">
                    
                    <form id="FavoriteCompetitions" name="formLeagues" method="post" action="<?php echo Zend_Registry::get("contextPath"); ?>/user/savecompetitions">
                    	<div id="FieldsetWrapper">
                    		<h5>Favorite Leagues and Tournaments</h5>
                    		<div  id="ErrorMessages" class="ErrorMessages"></div>                    		
                    		<fieldset id="favoriteCompetitionsFieldset">                    		     
                                <p>
                                    Select your favorite leagues and tournaments below
                                </p>

                                <div id="panel1" class="DropShadowHeader BrownGradientForDropShadowHeader">
                                    <img id="visible1" onclick="javascript:accordion(this,1)" border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/DropShadowHeader/brown_down_arrow.gif" />
                                    <h4 class="WithArrowToLeft">Africa</h4><span>(<?php echo $this->nrafrica; ?>)</span> 
                                </div>
                                <div id="panel1-body" class="CompetitionListBox">
                                   <?php 
							           $tempCountry = 9999; 
							           foreach($this->africa as $afrcomp) { 
							              if(trim($tempCountry) != trim($afrcomp["country_id"])){ 
							          ?>
							          <br> 
							          <h6><?php echo $afrcomp["country_name"]; ?></h6>
							          <?php } ?>
							          
							          <input type="checkbox" class="checkbox" name="zone3[]" id="<?php echo $afrcomp["competition_id"]; ?>" value='<?php echo $afrcomp["country_name"]; ?>*<?php echo $afrcomp["competition_name"]; ?>*<?php echo $afrcomp["competition_id"]; ?>*<?php echo $afrcomp["country_id"]; ?>'/><label><?php echo $afrcomp["competition_name"]; ?></label>
							          <br />
							          <?php 
							            $tempCountry = $afrcomp["country_id"];
							          } ?>
									
									
                                 </div>

                                <div id="panel2" class="DropShadowHeader BrownGradientForDropShadowHeader">
                                    <img onclick="javascript:accordion(this,2)" border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/DropShadowHeader/brown_down_arrow.gif" />
                                    <h4 class="WithArrowToLeft">Americas</h4><span>(<?php echo $this->nramericas; ?>)</span> 
                                </div>

                                <div id="panel2-body" class="CompetitionListBox">
                                   <?php 
							           $tempCountry2 = 9999;
							           $tempCountryN = 'name'; //for the case of regional of different continents
							           foreach($this->americas as $amecomp) { 
							              if(trim($tempCountry2) != trim($amecomp["country_name"])){ 
							          ?>
							          <br> 
							          <h6><?php echo $amecomp["country_name"]; ?></h6>
									  <?php } ?>
							          <input type="checkbox" class="checkbox" name='zone1[]' id="<?php echo $amecomp["competition_id"]; ?>" value='<?php echo $amecomp["country_name"]; ?>*<?php echo $amecomp["competition_name"]; ?>*<?php echo $amecomp["competition_id"]; ?>*<?php echo $amecomp["country_id"]; ?>'/><label><?php echo $amecomp["competition_name"]; ?></label>
									  <br />
									<?php 
							            $tempCountry2 = trim($amecomp["country_name"]);
							          } ?>
                                </div>
                                 <!--  -->
                                <div id="panel3" class="DropShadowHeader BrownGradientForDropShadowHeader">
                                    <img  onclick="javascript:accordion(this,3)" border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/DropShadowHeader/brown_down_arrow.gif" />
                                    <h4 class="WithArrowToLeft">Asia & Pacific Islands</h4><span>(<?php echo $this->nrasiapacific; ?>)</span> 
                                </div>
                                <div id="panel3-body" class="CompetitionListBox">
								        <?php 
											$tempCountry3 = 9999; 
								           foreach($this->asiapacific as $asipcomp) { 
								              if(trim($tempCountry3) != trim($asipcomp["country_id"])){ 
								          ?>
							          <br> 
							          <h6><?php echo $asipcomp["country_name"]; ?></h6>
							          <?php } ?>
							          
							          <input type="checkbox" class="checkbox" name="zone4[]" id="<?php echo $asipcomp["competition_id"]; ?>" value='<?php echo $asipcomp["country_name"]; ?>*<?php echo $asipcomp["competition_name"]; ?>*<?php echo $asipcomp["competition_id"]; ?>*<?php echo $asipcomp["country_id"]; ?>'/><label><?php echo $asipcomp["competition_name"]; ?></label>
							          <br />
							          <?php 
							          $tempCountry3 = $asipcomp["country_id"];
							          } ?>
									
									
                                </div>
                                <div id="panel4" class="DropShadowHeader BrownGradientForDropShadowHeader">
                                    <img  onclick="javascript:accordion(this,4)" border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/DropShadowHeader/brown_down_arrow.gif" />
                                    <h4 class="WithArrowToLeft">Europe</h4><span>(<?php echo $this->nreurope; ?>)</span> 
                                </div>
                                <div id="panel4-body" class="CompetitionListBox">
                                   <?php 
							           $tempCountry4 = 9999; 
							           foreach($this->europe as $eurcomp) { 
							              if(trim($tempCountry4) != trim($eurcomp["country_id"])){ 
							          ?> <br> 
							          <h6><?php echo $eurcomp["country_name"]; ?></h6>
							          <?php } ?>
							          
							          <input type="checkbox" class="checkbox" name="zone2[]" id="<?php echo $eurcomp["competition_id"]; ?>" value='<?php echo $eurcomp["country_name"]; ?>*<?php echo $eurcomp["competition_name"]; ?>*<?php echo $eurcomp["competition_id"]; ?>*<?php echo $eurcomp["country_id"]; ?>'/><label><?php echo $eurcomp["competition_name"]; ?></label>
							          <br />
							          <?php 
							          $tempCountry4 = $eurcomp["country_id"];
							          } ?>
									
								</div>
								<div id="panel5" class="DropShadowHeader BrownGradientForDropShadowHeader">
                                    <img  onclick="javascript:accordion(this,5)" border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/DropShadowHeader/brown_down_arrow.gif" />
                                    <h4 class="WithArrowToLeft">FIFA Competitions</h4><span>(<?php echo $this->nrfifa; ?>)</span> 
                                </div>
                                <div id="panel5-body" class="CompetitionListBox">
                                   <?php 
							           $tempCountry5 = 9999; 
							           foreach($this->fifa as $fifacomp) { 
							              if(trim($tempCountry5) != trim($fifacomp["country_id"])){ 
							          ?><br>
									<h6><?php echo $fifacomp["country_name"]; ?></h6>
							          <?php } ?>
							          
							          <input type="checkbox" class="checkbox" name="zone5[]" id="<?php echo $fifacomp["competition_id"]; ?>" value='<?php echo $fifacomp["country_name"]; ?>*<?php echo $fifacomp["competition_name"]; ?>*<?php echo $fifacomp["competition_id"]; ?>*<?php echo $fifacomp["country_id"]; ?>'/><label><?php echo $fifacomp["competition_name"]; ?></label>
							          <br />
							          <?php 
							          $tempCountry5 = $fifacomp["country_id"];
							          } ?>
									
								</div>
</fieldset>
							<fieldset id="FavoriteCompetitionsButtons">

							    <input type="button" value="Add" class="submit" onclick="addleagues()">
							    <input type="button" value="Remove" class="submit" onclick="removeLeagues()"/>
							    <input type="button" value="View Profile" class="submit" onclick="saveLeagues()" />
							</fieldset>
							<fieldset id="FavoriteCompetitionsSelected">
                                <div class="DropShadowHeader BrownGradientForDropShadowHeader">                                    
                                    <h4>Your Favorite Selected Competitions</h4> 
                                </div>
                                <div id="sleagues" class="CompetitionListBox">

                                    
                                 </div>                                
							</fieldset>

						    <br class="clearleft"/>
						</div><!-- end of FieldSetWrapper -->
                    </form>
                    <input type="button" class="submit GreenGradient" name="Register" value="Previous" onclick="javascript:history.back(1) "/>
								<a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/skiptomyprofile">Skip to My Profile</a>
                </div> <!--end FormWrapperForBottomBackground -->
  </div><!--end FormWrapper -->





