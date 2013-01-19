<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
<script>
    jQuery(document).ready(function() {
          jQuery("#league1").addClass("filterSelected");

          var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamsbycompetition/c/<?php echo $this->defaultComp?>'; 
    	   jQuery('#data').html("<div class='ajaxload'></div>");
    	   jQuery('#data').load(url);
      });


     function findTeamsByCompetition(compId , id){

            var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamsbycompetition/c/'+compId;
                jQuery("#TeamsByCountry a").removeClass("filterSelected");
				jQuery("#"+id).addClass("filterSelected");
		   		jQuery("#allfilter").addClass("filterSelected");
                jQuery('#data').html("<div class='ajaxload'></div>");
                jQuery('#data').load(url);

     }	
</script>

      <?php require_once 'Common.php'; ?>
       <?php require_once 'seourlgen.php'; 
	 	$urlGen = new SeoUrlGen();?>
      
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
                     
                    <!--Teams Left Menu-->
                    <div class="img-shadow">
                           <?php echo $this->render('include/navigationteamsdirectory.php');?>
                    </div>
                    <?php }else { ?>
                    
                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>

                    <!--Teams Left Menu-->
                    <div class="img-shadow">
                           <?php echo $this->render('include/navigationteamsdirectory.php');?>
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


            </div><!--/FirstColumn-->

            <div class="SecondColumn" id="SecondColumnHighlightBox">
                    <h1><?php echo $this->countryName; ?> Football Clubs</h1>                   
                    
                  <!---   <ul id="SecondColumnHighlightBoxContentNav">
                        <li class="selected">Most Popular</li>
                        <li><a href="#">Newest</a></li>
                        <li><a href="#">Online Now</a></li>
                            
                    </ul> --->
      
				<div id="SecondColumnHighlightBoxContent">
            	<div id="TeamsByCountry" class="TeamLinks">
        		    Show:
        		    <?php 
        		      $i = 1;
                      foreach ($this->dCompetitions as $data) { ?> 
                        <a href="javascript:findTeamsByCompetition('<?php echo $data['competition_id'];?>','league<?php echo $i;?>')" id="league<?php echo $i;?>">
                            <?php echo $data['competition_name'];?>
                        </a>
                            <?php if($i != $this->dCompetitionsTotal){ ?>
                                <span> | </span>
                            <?php } ?>         
                        <?php  $i++;} ?>
        		</div> 
        		
        		  <div id="data">
                 
                  </div>
                    <!--<div id="SecondColumnHighlightBoxContentBottomImage"></div>-->                            
          </div><!--/SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
                
 
             </div> <!--end ContentWrapper-->
<?php include 'include/teamh2h.php';?>
