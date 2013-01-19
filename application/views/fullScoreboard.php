<?php 
    require_once 'seourlgen.php';
    $urlGen = new SeoUrlGen();
	$session = new Zend_Session_Namespace('userSession');
?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>



<script type="text/JavaScript">
jQuery(document).ready(function() {

    //add remove favorires buttons
	jQuery('#addtofavoritecompetitiontrigger').click(function(){
		 addremovefavoritecompetition('add');
	});
    
	 jQuery('#removefromfavoritecompetitiontrigger').click(function(){
		 addremovefavoritecompetition('remove');
	});
    
    //scoreboard - When listing matches by season -round is '' for international cup competitions, only lists knockout stages) and for round specific as well
	var page = '<?php echo $this->default; ?>';
	var seasonId = '<?php echo $this->seasonId; ?>';
	var roundid = '<?php echo $this->roundId; ?>';
	var url = null;
	var timezone = calculate_time_zone();

 	if(roundid != ''){
		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showfullmatchesbyseason/timezone/'+timezone+'/id/'+seasonId+'/roundid/'+roundid+'/type/'+page;
   	} else {
        var url = '<?php echo Zend_Registry::get("contextPath"); ?>/scoreboard/showfullmatchesbyseason/timezone/'+timezone+'/id/'+seasonId+'/type/'+page;
    }
      
    jQuery('#fullscoreboardcontent').html("<div class='ajaxloadscores'></div>");
    jQuery('#fullscoreboardcontent').load(url);

});

function addremovefavoritecompetition(type){

         jQuery('#modalBodyId').show();
		 jQuery('#modalBodyResponseId').hide();
		 jQuery('#acceptFavoriteModalButtonId').show();
		 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel');
         if(type == 'add'){
		 	jQuery('#modalFavoriteTitleId').html('Add <?php echo $this->compName;?> to Favorites?');
		 }else if(type == 'remove'){
			 jQuery('#modalFavoriteTitleId').html('Remove <?php echo $this->compName;?> from Favorites?');
		 }
         jQuery('#dataText1').html('<?php echo $this->compName;?>');
		 jQuery('#title1Id').html('Country:');
		 jQuery('#dataText2').html("<?php echo $this->countryName; ?>");
		 jQuery('#dataText2').attr('href','<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($this->countryName,$this->countryId, true); ?>');
         if(type == 'add'){
		 	jQuery('#addFavoriteModal').jqm({trigger: '#addtofavoritecompetitiontrigger', onHide: closeModal });
		 }else if(type == 'remove'){
			 jQuery('#addFavoriteModal').jqm({trigger: '#removefromfavoritecompetitiontrigger', onHide: closeModal });
		 }
		 jQuery('#addFavoriteModal').jqmShow();

		 var favoriteImage = null;

		<?php
         $config = Zend_Registry::get ( 'config' );
         $path_comp_logos = $config->path->images->complogos . $this->leagueId.".gif" ;

         if (file_exists($path_comp_logos)){  ?>
       			favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/competitionlogos/<?php  echo $this->leagueId .'.gif'?>';
		<?php } else {  ?>
				favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/LeagueText120.gif';
    	<?php  } ?>


		 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

		 var leagueId = '<?php echo $this->leagueId; ?>*<?php echo $this->countryId; ?>';

		 var url = null;
		 var htmlResponse = null;
		 if(type == 'add'){
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/addfavorite' ;
			 htmlResponse = 'Competition <?php echo $this->compName;?> has been added to Favorites.';
		 }else if(type == 'remove'){
			 url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/removefavorite' ;
			 htmlResponse = 'Competition <?php echo $this->compName;?> has been removed from Favorites.';
		 }


		 jQuery("#acceptFavoriteModalButtonId").unbind();
		 jQuery('#acceptFavoriteModalButtonId').click(function(){
		 jQuery.ajax({
				type: 'POST',
				url :  url,
				data : ({leagueId:leagueId }),
				success: function(data){
					jQuery('#modalBodyResponseId').html(htmlResponse);
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					if(type == 'add'){
						//jQuery('#favorite').removeClass('Display').addClass('ScoresClosed');
					 	//jQuery('#remove').removeClass('ScoresClosed').addClass('Display');
                        jQuery('#favorite').css('display','none');
                        jQuery('#remove').css('display','inline');
				 	}else if(type == 'remove'){
				 		//jQuery('#remove').removeClass('Display').addClass('ScoresClosed');
				 		//jQuery('#favorite').removeClass('ScoresClosed').addClass('Display');
                        jQuery('#remove').css('display','none');
                        jQuery('#favorite').css('display','inline');
				 	}

					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
				}
			})

		 });	
         
}

</script>

<div id="ContentWrapper" class="TwoColumnLayout">
   

     <p class="flags">
        <span class="flagtitle" style="background-image:url(<?php echo Zend_Registry::get ( "contextPath" ); ?>/public/images/flags/32x32/<?php echo $this->countryCodeIso; ?>.png)">
            <a href="#">
                <?php if ($this->compFormat == 'International cup') { ?>
                    <?php echo $this->regionNameTitle; ?> - <?php echo $this->compName; ?>
                <?php } else { ?>
                    <?php echo $this->countryName; ?> - <?php echo $this->compName; ?>
                <?php } ?>
            </a>         
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

		<?php if ($server == 'local') { ?>
	      <?php if ($this->scoreTypeTitle == 'Scores') {?>
	       <span class="pre">
	         <span class="lea"><?php echo $this->compName; ?> Archive </span>
	           <form id="#seasondropdown" name="seasondropdown" method="post" action="#">
	            <select class="sel" name="seasonId" id="seasonId" onchange="document.seasondropdown.submit();">
	            <?php foreach($this->seasonList as $season) { ?>
	                <option value="<?php echo $season["season_id"]; ?>" <?php echo ($this->seasonId == $season["season_id"]?'selected':'')?>><?php echo $season['title']; ?></option>
	            <?php } ?>
	            </select>
	          </form>
	       </span>
	    	<?php }?>
	    <?php }?>
    
      </p>



      <div class="FirstColumn">
               <?php 
                    $session = new Zend_Session_Namespace ( 'userSession' );
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
                    
                    
                    <!--Goalface Register Ad-->
                    
                    <?php } ?>

                 

                    <div id="leftnav" class="img-shadow">
                    <?php if ($this->leagueId == 25){ ?>
                         <?php echo $this->render('include/navigationCompetitionNew3.php');?>
                         <div class="" style="float: left; padding:0px;border:none;">
                             <a href="<?php echo Zend_Registry::get("contextPath"); ?>/subscribe" title="Subscriptions and Alers">                          
                                 <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/BannersEuro1.png" style="margin-top:10px;"/>
                          	</a>
                    	</div>
                    <?php } else { ?>
                     	<?php echo $this->render('include/navigationCompetitionNew2.php');?>
                    <?php } ?>
                    </div>


                 
                </div><!--end FirstColumnOfThree-->

     <div id="SecondColumnFullScore" class="SecondColumnOfTwo">

       <p class="middletitle">
          <?php echo $this->compName; ?> Scoreboard <?php echo $this->seasonTitle; ?>
       </p>

        <!-- mmid -->
		<div class="mmid">			
			<div class="march">
				
                 <div id="fullscoreboardcontent">
                    <!--scoreboard content here-->
                </div>
			</div>
		</div>	
        <!-- /mmid -->
        <div class="ads">
            &nbsp;
        </div>
   </div><!--end SecondColumnOfTwo -->
 </div> <!--end ContentWrapper-->
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             
           
