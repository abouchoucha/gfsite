<?php $session = new Zend_Session_Namespace('userSession'); ?>
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php';
    $urlGen = new SeoUrlGen();?>

<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>

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
   <span class="pre">
     <span class="lea"><?php echo $this->compName; ?> Archive</span>
       <form id="#seasondropdown" name="seasondropdown" method="post" action="#">
        <select class="sel" name="seasonId" id="seasonId" onchange="document.seasondropdown.submit();">
        <?php foreach($this->seasonList as $season) { ?>
            <option value="<?php echo $season["season_id"]; ?>" <?php echo ($this->seasonId == $season["season_id"]?'selected':'')?>><?php echo $season['title']; ?></option>
        <?php } ?>
        </select>
      </form>
   </span>
<?php }?>

  </p>

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

        <?php } ?>

        <div id="leftnav" class="img-shadow">
            <?php if ($this->leagueId == 25){ ?>
                 <?php echo $this->render('include/navigationCompetitionNew3.php');?>
            <?php } else { ?>
             	<?php echo $this->render('include/navigationCompetitionNew2.php');?>
            <?php } ?>
        </div>
        
        <div class="" style="float: left; padding:0px;border:none;margin-top: -10px;">
             <a href="<?php echo Zend_Registry::get("contextPath"); ?>/subscribe" title="Subscriptions and Alers">                          
                 <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/banner_generic_team.png" style="margin-top:10px;"/>
          	</a>
        </div>


    </div><!--/FirstColumn-->

   <div id="SecondColumnFullScore" class="SecondColumn">

   <p class="flags">
    <span class="flagtitle">
        <a href="#"><?php echo $this->compName; ?> Teams <?php echo $this->seasonTitle; ?></a>
    </span>
  </p>

    <div id="SecondColumnHighlightBoxContent" style="border:1px solid #6F9CBD;">
      <div id="data">
         
      </div>
    </div>

   </div>


</div>
<!--/ContentWrapper-->

<script type="text/javascript">

     jQuery(document).ready(function() {
          //var url = '<?php //echo Zend_Registry::get("contextPath"); ?>/team/showteamsbycompetition/c/<?php echo $this->leagueid?>';
    	   var url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/showteamsbyseason/s/<?php echo $this->seasonId?>';
         	jQuery('#data').html('Loading...');
    	   jQuery('#data').load(url);
      });

</script>

