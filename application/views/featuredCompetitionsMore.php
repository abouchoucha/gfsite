
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; 
$urlGen = new SeoUrlGen();?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>

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


</div><!--/FirstColumn-->

<div class="SecondColumn" id="SecondColumnHighlightBox">
    <h1>Featured Leagues &amp; Tournaments</h1>

    <div id="SecondColumnHighlightBoxContent">
        <?php
        // Retrive data from featured leagues as an array
        $leagueCounter = 0;
        foreach ($this->featuredLeagues as $data) {
            $leagueCounter++;
            if($leagueCounter==1){
                ?>
        <ul class="LayoutFourPictures">
            <?php } ?>

            <li>
                <a class="LeagueTitle" href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($data["competition_name"], $data["competition_id"], True); ?>" title="<?php echo $data['competition_name'];?>"><?php echo $data['competition_name'];?></a>

                    <?php
                    $config = Zend_Registry::get ( 'config' );
                    $path_comp_logos = $config->path->images->complogos . $data['competition_id'].".gif" ;

                    if (file_exists($path_comp_logos))
                    {  ?>
                <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($data["competition_name"], $data["competition_id"], True); ?>" title="<?php echo $data['competition_name'];?>">
                    <div class="LogoWrapper" style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/competitionlogos/<?php  echo $data['competition_id'].'.gif'?>')">
                    </div>
                </a>

            <?php } else {  ?>
                <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($data["competition_name"], $data["competition_id"], True); ?>" title="<?php echo $data['competition_name'];?>">
                    <div class="LogoWrapper" style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/LeagueText120.gif')">
                    </div>
                </a>
                <?php  } ?>

            <?php if ($data['regional'] == 0) { ?>
                <strong>Country: </strong>
                <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($data["country_name"], $data["country_id"], True); ?>" title="<?php echo $data["country_name"]; ?>">
                    <?php echo $data['country_name'];?>
                </a>
                <br/>
                <?php } ?>

            <?php //getShowRegionUrl(strval($regionName[0])    $continent = strval($data['region_name']);?>
            <?php //echo $continent ;?>
            <?php $regionName = $this->regionGroupNames[mb_strtolower($data['region_group_name'])]; ?>
                <strong>Region: </strong><a href="<?php echo $urlGen->getShowRegionUrl(strval($regionName[0]), True); ?>" title="<?php echo $data['region_name'];?>"><?php echo $data['region_name'];?></a>

                <br/>
                <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($data["competition_name"], $data["competition_id"], True); ?>" title="<?php echo $data['competition_name'];?>">
                    View Profile &raquo;
                </a>
            </li>

    <?php
    if($leagueCounter==4){
        $leagueCounter = 0;
        echo '</ul>';
    }
    ?>
    <?php } ?>
        </ul>
        <br class="clearleft"/>
        <!--<div id="SecondColumnHighlightBoxContentBottomImage"></div>-->
    </div><!--/SecondColumnOfTwo and #SecondColumnHighlightBox-->



</div> <!--end ContentWrapper-->
