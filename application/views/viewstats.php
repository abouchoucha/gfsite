<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();
	$session = new Zend_Session_Namespace('userSession');
?>

<script type="text/JavaScript">

jQuery(document).ready(function() {

	jQuery('#wcdropdown').val('<?php echo ($this->seasonId!=null?$this->seasonId:'all') ?>');
	
	jQuery('#wcdropdown').change(function(){
		var seasonVal = jQuery('#wcdropdown').val();
		top.location.href = "<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showstats/compid/72/seasonid/"+seasonVal
     });

	
      jQuery('.tabscontainer').hide();
      jQuery('div.cont').hide();
      jQuery('#tab1AllContent').show();
      jQuery('#tab1content').show();
    	
       jQuery('#tab1').addClass('active');
       var urlBase = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/showstatsresults/seasonid/<?php echo $this->seasonId?>';
       jQuery('#ajaxdata').html("<div class='ajaxload widget'></div>");
       jQuery('#statTab ul li').click(function(){
    	   tab_id = jQuery(this).attr('id');	
    	   if(tab_id == 'tab1'){
	      	  var statsVal = jQuery('#statsdropdown1').val();
	      	  url = urlBase + '/typestat/'+statsVal;
	      	  jQuery('#ajaxdata').load(url);	
	       }else if(tab_id == 'tab2'){
	      	  var statsVal = jQuery('#statsdropdown2').val();
	      	  url = urlBase + '/typestat/'+statsVal;
	      	  jQuery('#ajaxdata2').load(url);	
	       }   
          jQuery('.tabscontainer').hide();
          jQuery('div.cont').hide();
          
          //show div content
          jQuery('#' + tab_id + 'AllContent').show();
          jQuery('#' + tab_id + 'content').show();
          jQuery('#tab1,#tab2').removeClass('active');
          jQuery(this).addClass('active');
          
           
          
    	  
          
      });
	 	 
      
      var statsVal = jQuery('#statsdropdown1').val();	
      jQuery('#ajaxdata').html("<div class='ajaxload widget'></div>");
	  url = urlBase + '/typestat/'+statsVal;
	  jQuery('#ajaxdata').load(url);

      searchStats('statsdropdown1' ,urlBase , '/typestat/','ajaxdata');
      searchStats('statsdropdown2' ,urlBase , '/typestat/','ajaxdata2');
      
});

function searchStats( id , urlBase ,type , div){

	jQuery('#'+id).change(function(){
		var statsVal = jQuery('#'+id).val();
		jQuery('#'+div).html("<div class='ajaxload widget'></div>");
		url = urlBase + "/typestat/" + statsVal;
		jQuery('#'+div).load(url);
		
     });

}


 
</script>


<div id="ContentWrapper">

<p class="flags">
    <span style="background-image: url(<?php echo Zend_Registry::get ( "contextPath" ); ?>/public/images/flags/32x32/<?php echo $this->countryCodeIso; ?>.png);" class="flagtitle">
         <?php if ($this->compFormat == 'International cup') { ?>
            <?php echo $this->regionNameTitle; ?> - <?php echo $this->compName; ?>
            <?php } else { ?>
            <?php echo $this->countryName; ?> - <?php echo $this->compName; ?>
         <?php } ?>
    </span>

      <?php if($this->isFavorite == 'false') { ?>
           <?php if($session->email != null){ ?>
            <span id="favorite" class="add">
                <a id="addtofavoritecompetitiontrigger" href="#">Add to Favorites</a>
            </span>
             <span id="remove" class="remove" style="display:none">
                <a id="removefromfavoritecompetitiontrigger" href="#">Remove from Favorites</a>
            </span>
           <?php } else {?>
             <span id="favorite" class="add">
                <a id="addtofavoritecompetitionNonLoggedtrigger" onclick="loginModal()" href="#">Add to Favorites</a>
            </span>
           <?php } ?>
       <?php }else {?>
           <span id="favorite" class="add" style="display:none">
                <a id="addtofavoritecompetitiontrigger" href="#">Add to Favorites</a>
            </span>
             <span id="remove" class="remove">
                <a id="removefromfavoritecompetitiontrigger" href="#">Remove from Favorites</a>
            </span>
       <?php }?>

       <span class="pre">
 
       </span>
</p>


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
              
       
                    <?php } else {?>


                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>
                      
                <?php } ?>

          			<div id="leftnav" class="img-shadow">
                    <?php if ($this->leagueId == 25){ ?>
                         <?php echo $this->render('include/navigationCompetitionNew3.php');?>
                    <?php } else { ?>
                     	<?php echo $this->render('include/navigationCompetitionNew2.php');?>
                    <?php } ?>
                </div>
                    
        </div><!--/FirstColumn-->
                
        <div id="SecondColumnStats" class="SecondColumn">
            <div class="ammid">
                <h1 style="float:left;"><?php echo $this->compName; ?> Statistics</h1>
                <select id="wcdropdown" style="float:left;">
                    <option value = "all">All Tournaments</option>
                    <?php foreach ($this->allworldcupseasons as $wcseason) { ?>
                         <option value="<?php echo $wcseason["season_id"];?>"><?php echo $wcseason["title"];?></option>
                    <?php } ?>

                 </select>
                <div id="statTab" class="tabs">
					<ul>
						<li id="tab1"><a href="javascript:void(0);">Player</a></li>
						<li id="tab2"><a href="javascript:void(0);">Team</a></li>
					</ul>
				</div>
                
                <div id="tab1AllContent" class="tabscontainer">
                    <span class="show">Show:&nbsp;&nbsp;</span>
                    <p class="all">
                        <select id="statsdropdown1">
                            <option value="tab1" >&nbsp;All time goals </option>
                            <option value="tab11">&nbsp;All time assists </option>
                            <option value="tab12">&nbsp;Alll time minutes </option>

                        </select>
                   </p>
								<div id="ajaxdata" class="statdata">

                 	</div>
                     <!-- End Tab Player Wrapper  -->
                </div>
                
                <div id="tab2AllContent" class="tabscontainer">
                    <span class="show">Show:&nbsp;&nbsp;</span>
                    <p class="all">
                        <select id="statsdropdown2">
                            <option value="tab2">&nbsp;All time goals </option>
                            <option value="tab21">&nbsp;All time clean sheets </option>
                        </select>
                   </p>
                   <div id="ajaxdata2" class="statdata">

                 	</div>
                   
                </div>

           		
            
         </div>
            
      </div><!--/SecondColumn--> 
  
</div>
