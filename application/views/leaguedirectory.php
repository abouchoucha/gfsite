<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 
 
 <script type="text/JavaScript">
   jQuery(document).ready(function() {         
        /*jQuery('#searchCompetitionsIdButton').click(function(){
            search('leagues');
		});*/
 	jQuery(document).keydown(function(event) {
            if (event.keyCode == 13) {
                    search('leagues');
            }
 	});	
  });

  function search(category){
   	
   	var searchText = jQuery('#search-competitions').val();
   	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/search/index/q/'+searchText;
    	if(category != ''){
    		url = url + "/t/"+category;
       }
       //alert(url);
       window.location = url;
   } 	    

</script>	 	

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
               <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" />
            </a>
            </div>
        </div>
       <?php } ?>
       <div class="img-shadow">
           <div class="WrapperForDropShadow" style="border:none;">
         	<a href="<?php echo Zend_Registry::get("contextPath"); ?>/subscribe#leagueSection" title="Subscriptions and Alers">                          
             	<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/banner_generic_league.png" style="margin-top:10px;margin-bottom:0px;"/>
      		</a>
    	  </div>
    	</div>	
		<!--Facebook Like Module-->
    <?php echo $this->render('include/navigationfacebook.php')?>
                   

    </div><!--end FirstColumnOfThree-->

    <div id="SecondColumnPlayerProfile" class="SecondColumn">
      <h1>Leagues &amp; Tournaments</h1>
        <div class="img-shadow">
            <div class="WrapperForDropShadow">
                <div class="SecondColumnProfile">

                    <ul class="FriendSearch">
                      <li class="Search">
                        <form id="searchplayersform" method="get" action="<?php echo Zend_Registry::get("contextPath"); ?>/search/">
                            <label>Search</label>
                          <input id="search-competitions" type="text" class="text"  name="q" />
                          <input id="t" type="hidden" value="leagues"  name="t"/>
                          <input id="searchCompetitionsIdButton" type="submit" class="Submit" value="Search"/>
                        </form>
                      </li>
                      <li class="PopularSearches">
                        Popular Leagues:
                        <a title="La Liga" href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/primera%20division_7/">La liga</a>
                        ,
                        <a title="Serie A" href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/serie%20a_13/">Serie A</a>
                        ,
                        <a title="Ligue 1" href="<?php echo Zend_Registry::get("contextPath"); ?>/tournaments/ligue%201_16/">Ligue1</a>
                        </li>
                     </ul>
                     <div id="FriendsWrapper">
                         <div class="FriendLinks">
                              <span>Featured Leagues &amp; Tournaments</span>&nbsp;<a href="<?php echo $urlGen->getShowFeaturedCompetitionsUrl(true) ?>" title="Featured Tourmaments & Leagues">See More &raquo;</a>
                         </div>
                        <?php
                        // Retrive data from featured leagues as an array
                            $leagueCounter = 0;
                            foreach ($this->featuredLeaguesFour as $data) {
                              $leagueCounter++;
                                  if($leagueCounter==1){
                                 ?>
                                    <ul class="LayoutFourPicturesBig">
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
                                          <strong>Region: </strong><a href="<?php echo $urlGen->getShowRegionUrl(strval($regionName[0]), True); ?>" title="<?php echo $data['region_group_name'];?>"><?php echo $data['region_group_name'];?></a>

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

                    <br class="clearleft"/>

                    <div class="FriendLinks FriendLinksTitle">
                     <span>Leagues &amp; Tournaments Directory</span>
                    </div>

                    <div class="RegionContentListContainer">

      
                        <!--Europe Region -->
                        <?php $euroData = $this->regionGroupNames["europe"]; ?>

                        <ul class="RegionContentList" style="background-image:url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/regions/1.gif);">
                             <li>
                                 <h4>
                                    <a class="regionTitle" href="<?php echo $urlGen->getShowRegionUrl(strval($euroData[0]), True); ?>" title="<?php echo $euroData[1]; ?>">
                                        <?php echo $euroData[1]; ?>&nbsp;(UEFA)
                                    </a>
                                 </h4>
                                <span>Regional Leagues &amp; Tournaments:</span>

                                <?php foreach($this->reurope as $reurope) { ?>
                                    <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($reurope["competition_name"], $reurope["competition_id"], True); ?>" title="<?php echo $reurope["competition_name"]; ?>"><?php echo $reurope["competition_name"]; ?></a> |
                                <?php } ?>
                             </li>

                             <li class="title">
                                <strong>Domestic Leagues &amp; Tournaments:</strong>
                             </li>
                       </ul>

                       <div id="CountryListTable">
                            <?php
                                $contextPath = Zend_Registry::get ( 'contextPath' );
                                $left_column_europe = "";
                                $middle_column_europe = "";
                                $right_column_europe = "";
                                $countryrows = $this->totalEurope;
                                $percolumn = ceil($countryrows / 3 );
                                $i = 0;
                                foreach($this->deurope as $deurope) {
                                    $i++;
                                    if ($i <= $percolumn ) {
                                        $left_column_europe .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $deurope["country_id"].".png)'>".
                                                                    "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($deurope["country_name"], $deurope["country_id"], True)."'>".$deurope["country_name"]."&nbsp;(".$deurope["num_of_leagues"].")</a>" .
                                                                "</li>". "\n";
                                    } elseif ($i > $percolumn && $i <= $percolumn*2) {
                                        $middle_column_europe .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $deurope["country_id"].".png)'>".
                                                                    "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($deurope["country_name"], $deurope["country_id"], True)."'>" .$deurope["country_name"]."&nbsp;(".$deurope["num_of_leagues"].")</a>" .
                                                                "</li>". "\n";
                                    } elseif ($i >= $percolumn*2) {
                                        $right_column_europe .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $deurope["country_id"].".png)'>".
                                                                    "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($deurope["country_name"], $deurope["country_id"], True)."'>" .$deurope["country_name"]."&nbsp;(".$deurope["num_of_leagues"].")</a>" .
                                                                "</li>". "\n";
                                  }

                                }
                            ?>
                            <ul class="First">
                                <?php echo $left_column_europe; ?>
                            </ul>
                            <ul>
                                <?php echo $middle_column_europe; ?>
                            </ul>
                            <ul>
                                <?php echo $right_column_europe; ?>
                            </ul>


                              <a class="SeeAll" href="<?php echo $urlGen->getShowRegionUrl(strval($euroData[0]), True); ?>" title="View All">See All &raquo;</a>

                          </div>


                            <?php $americasData = $this->regionGroupNames["americas"]; ?>

                              <ul class="RegionContentList" style="background-image:url(<?php echo Zend_Registry::get("contextPath"); ?>/public/images/regions/2.gif);">
                                 <li>
                                    <h4>
                                        <a class="regionTitle" href="<?php echo $urlGen->getShowRegionUrl(strval($americasData[0]), True); ?>" title="<?php echo $americasData[1]; ?>">
                                            <?php echo $americasData[1]; ?>&nbsp;(CONMEBOL &amp; CONCACAF)
                                        </a>
                                    </h4>

                                    <span>Regional Leagues &amp; Tournaments:</span>

                                                          <?php foreach($this->ramericas as $ramericas) { ?>
                                        <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($ramericas["competition_name"], $ramericas["competition_id"], True); ?>" title="<?php echo $ramericas["competition_name"]; ?>">
                                            <?php echo $ramericas["competition_name"]; ?>
                                        </a> |
                                        <?php } ?>
                                  </li>

                                 <li class="title">
                                     <strong>Domestic Leagues &amp; Tournaments:</strong>
                                 </li>
                             </ul>

                             <div id="CountryListTable">
                                <?php

                                    $left_column = "";
                                    $middle_column = "";
                                    $right_column = "";
                                    $countryrows = $this->totalAmericas;
                                    $percolumn = ceil($countryrows / 3 );
                                    $i = 0;
                                    foreach($this->damericas as $damericas) {
                                        $i++;
                                        if ($i <= $percolumn ) {
                                            $left_column .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $damericas["country_id"].".png)'>".
                                                               "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($damericas["country_name"], $damericas["country_id"], True)."'>".$damericas["country_name"]."&nbsp;(".$damericas["num_of_leagues"].")</a>" .
                                                            "</li>". "\n";
                                        } elseif ($i > $percolumn && $i <= $percolumn*2) {
                                            $middle_column .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $damericas["country_id"].".png)'>".
                                                                "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($damericas["country_name"], $damericas["country_id"], True)."'>" .$damericas["country_name"]."&nbsp;(".$damericas["num_of_leagues"].")</a>" .
                                                              "</li>". "\n";
                                        } elseif ($i >= $percolumn*2) {
                                            $right_column .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $damericas["country_id"].".png)'>".
                                                                "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($damericas["country_name"], $damericas["country_id"], True)."'>" .$damericas["country_name"]."&nbsp;(".$damericas["num_of_leagues"].")</a>" .
                                                             "</li>". "\n";
                                      }

                                    }
                                ?>
                                <ul class="First">
                                    <?php echo $left_column; ?>
                                </ul>
                                <ul>
                                    <?php echo $middle_column; ?>
                                </ul>
                                <ul>
                                    <?php echo $right_column; ?>
                                </ul>

                                <a class="SeeAll" href="<?php echo $urlGen->getShowRegionUrl(strval($americasData[0]), True); ?>" title="View All">See All &raquo;</a>

                              </div>

                                                <?php $africaData = $this->regionGroupNames["africa"]; ?>

                                                           <ul class="RegionContentList" style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/regions/5.gif');" >
                                                <li><h4><a class="regionTitle" href="<?php echo $urlGen->getShowRegionUrl($africaData[0], True); ?>" title="<?php echo $africaData[1]; ?>"><?php echo $africaData[1]; ?>&nbsp;(CAF)</a></h4>

                                <span>Regional Leagues &amp; Tournaments:</span>

                                  <?php foreach($this->rafrica as $rafrica) { ?>
                                  <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($rafrica["competition_name"], $rafrica["competition_id"], True); ?>" title="<?php echo $rafrica["competition_name"]; ?>">
                                    <?php echo $rafrica["competition_name"]; ?>
                                  </a> |
                                  <?php } ?>
                                </li>
                               <li class="title"><strong>Domestic Leagues &amp; Tournaments:</strong></li>
                            </ul>

                       <div id="CountryListTable">
                                <?php

                                    $left_column = "";
                                    $middle_column = "";
                                    $right_column = "";
                                    $countryrows = $this->totalAfrica;
                                    $percolumn = ceil($countryrows / 3 );
                                    $i = 0;
                                    foreach($this->dafrica as $dafrica) {
                                        $i++;
                                        if ($i <= $percolumn ) {
                                            $left_column .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $dafrica["country_id"].".png)'>".
                                                                        "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($dafrica["country_name"], $dafrica["country_id"], True)."'>".$dafrica["country_name"]."&nbsp;(".$dafrica["num_of_leagues"].")</a>" .
                                                                    "</li>". "\n";
                                        } elseif ($i > $percolumn && $i <= $percolumn*2) {
                                            $middle_column .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $dafrica["country_id"].".png)'>".
                                                                        "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($dafrica["country_name"], $dafrica["country_id"], True)."'>" .$dafrica["country_name"]."&nbsp;(".$dafrica["num_of_leagues"].")</a>" .
                                                                    "</li>". "\n";
                                        } elseif ($i >= $percolumn*2) {
                                            $right_column .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $dafrica["country_id"].".png)'>".
                                                                        "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($dafrica["country_name"], $dafrica["country_id"], True)."'>" .$dafrica["country_name"]."&nbsp;(".$dafrica["num_of_leagues"].")</a>" .
                                                                    "</li>". "\n";
                                      }

                                    }
                                ?>
                                <ul class="First">
                                    <?php echo $left_column; ?>
                                </ul>
                                <ul>
                                    <?php echo $middle_column; ?>
                                </ul>
                                <ul>
                                    <?php echo $right_column; ?>
                                </ul>

                                <a class="SeeAll" href="<?php echo $urlGen->getShowRegionUrl(strval($africaData[0]), True); ?>" title="View All">See All &raquo;</a>

                              </div>
                              
                              

                                <?php $asiaData = $this->regionGroupNames["asia"]; ?>

                                <ul class="RegionContentList" style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/regions/6.gif');">
                                    <li>
                                        <h4><a class="regionTitle" href="<?php echo $urlGen->getShowRegionUrl(strval($asiaData[0]), True); ?>" title="<?php echo $asiaData[1]; ?>"><?php echo $asiaData[1]; ?>&nbsp;(AFC &amp; OCEANIA )</a></h4>
                                        <span>Regional Leagues &amp; Tournaments:</span>

                                        <?php foreach($this->rasiapacific as $rasiapacific) { ?>
                                        <a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($rasiapacific["competition_name"], $rasiapacific["competition_id"], True); ?>" title="<?php echo $rasiapacific["competition_name"]; ?>"><?php echo $rasiapacific["competition_name"]; ?></a> |
                                        <?php } ?>

                                   </li>
                                   <li class="title"><strong>Domestic Leagues &amp; Tournaments:</strong></li>
                                </ul>

                               <div id="CountryListTable">
                                <?php

                                    $left_column = "";
                                    $middle_column = "";
                                    $right_column = "";
                                    $countryrows = $this->totalAsia;
                                    $percolumn = ceil($countryrows / 3 );
                                    $i = 0;
                                    foreach($this->dasiapacific as $dasia) {
                                        $i++;
                                        if ($i <= $percolumn ) {
                                            $left_column .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $dasia["country_id"].".png)'>".
                                                                        "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($dasia["country_name"], $dasia["country_id"], True)."'>".$dasia["country_name"]."&nbsp;(".$dasia["num_of_leagues"].")</a>" .
                                                                    "</li>". "\n";
                                        } elseif ($i > $percolumn && $i <= $percolumn*2) {
                                            $middle_column .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $dasia["country_id"].".png)'>".
                                                                        "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($dasia["country_name"], $dasia["country_id"], True)."'>" .$dasia["country_name"]."&nbsp;(".$dasia["num_of_leagues"].")</a>" .
                                                                    "</li>". "\n";
                                        } elseif ($i >= $percolumn*2) {
                                            $right_column .= "<li style='background-image:url(" .$contextPath. "/public/images/flags/". $dasia["country_id"].".png)'>".
                                                                        "<a href='".$urlGen->getShowDomesticCompetitionsByCountryUrl($dasia["country_name"], $dasia["country_id"], True)."'>" .$dasia["country_name"]."&nbsp;(".$dasia["num_of_leagues"].")</a>" .
                                                                    "</li>". "\n";
                                      }

                                    }
                                ?>
                                <ul class="First">
                                    <?php echo $left_column; ?>
                                </ul>
                                <ul>
                                    <?php echo $middle_column; ?>
                                </ul>
                                <ul>
                                    <?php echo $right_column; ?>
                                </ul>

                                <a class="SeeAll" href="<?php echo $urlGen->getShowRegionUrl(strval($asiaData[0]), True); ?>" title="View All">See All &raquo;</a>

                              </div>
                              
                               <!--FIFA  -->
                        <?php $internationalData = $this->regionGroupNames["international"]; ?>

                        <ul class="RegionContentList" style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/regions/8.gif');padding-bottom:25px;">
                                <li>
                                     <h4><a class="regionTitle" href="<?php echo $urlGen->getShowRegionUrl(strval($internationalData[0]), True); ?>" title="<?php echo $internationalData[1]; ?>"><?php echo $internationalData[1]; ?></a></h4>
                                        <span>National Teams Tournaments:</span>

                                        <?php foreach($this->rfifa as $rfifa) { ?>
                                                <a href="<?php echo $urlGen->getShowNationalTeamCompetitionsUrl($rfifa["competition_name"], $rfifa["competition_id"], True); ?>" title="<?php echo $rfifa["competition_name"]; ?>"><?php echo $rfifa["competition_name"]; ?></a> |
                                        <?php } ?>
                                 </li>
                                   <li class="title">
                                        <strong>Club Team Tournament:</strong>
                                        <a href="<?php echo $urlGen->getShowNationalTeamCompetitionsUrl('World Club Championship', 284, True); ?>" title="World Club Championship">World Club Championship</a>
                                  </li>
                      </ul>





                         </div>

                      </div>
                </div>
              </div>
        </div>
    </div>

</div><!--end ContentWrapper-->
