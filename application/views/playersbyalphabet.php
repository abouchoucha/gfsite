
<?php require_once 'Common.php'; ?>
<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 
      
      <div id="ContentWrapper" class="TwoColumnLayout">
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
                     <!--Players Directory left Menu-->
                    <div class="img-shadow">
                        <?php echo $this->render('include/navigationplayersdirectory.php');?>
                    </div>
                    <?php //echo $this->render('include/feedbackbadge.php')?>
                    <?php }else { ?>
                    
                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>

                        <!--Players Directory left Menu-->
                    <div class="img-shadow">
                        <?php echo $this->render('include/navigationplayersdirectory.php');?>
                    </div>


                    <!--Goalface Register Ad-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register">
                              <img class="JoinNowHome" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" /> 
                             </a>  
                        </div>
                    </div>
                    <?php } ?>

              

                </div><!--end FirstColumnOfThree-->

                <div id="SecondColumnPlayerProfile" class="SecondColumn">
                     <div id="SecondColumnPlayerProfile" class="SecondColumn">
                            <h1>Player Names Starting with '<?php echo $this->letter ?>'</h1>
                                <div class="img-shadow">
                                    <div class="WrapperForDropShadow">
                                        <div class="SecondColumnProfile">
                                            <ul class="FriendSearch">
                                              <li class="Search">
                                                <form id="searchplayersform" method="get" action="<?php echo Zend_Registry::get("contextPath"); ?>/search/">
                                                    <label>Search Players</label>
                                                    <input id="search-players" type="text" class="text"  name="q"/>
                                                    <input id="t" type="hidden" value="players" class="hidden"  name="t"/>
                                                    <input type="submit" class="Submit" value="Search"/>
                                                </form>
                                              </li>
                                              <li class="PopularSearches searchPlayers">
                                                    Popular Players:
                                                        <?php $i = 0; foreach($this->popularPlayers as $pp) { if($i<3){?>
                                                        <a href="<?php echo $urlGen->getPlayerMasterProfileUrl($pp["player_nickname"], $pp["player_firstname"], $pp["player_lastname"], $pp["player_id"], true ,$pp["player_common_name"]); ?>" title="<?php echo $pp["player_firstname"].' '.$pp["player_lastname"] ?>">
                                                                  <?php echo $pp["player_firstname"].' '.$pp["player_lastname"] ?>
                                                            </a>
                                                  <?php if ($i != 2){echo ",";} ?>
                                                    <?php $i= $i+1;}} ?>
                                              </li>
                                            </ul><!-- /SearchSelections-->
                                            <div id="FriendsWrapper">
                                                    <ul id="SecondColumnHighlightBoxContentNavTwo">
                                                        <li class="PaginationAlphabet">
                                                            <?php $urlGen = new SeoUrlGen(); ?>
                                                                <a href="<?php echo $urlGen->getPlayersMainUrl(True); ?>">All</a>
                                                        <?php	for($i = 0 ; $i < 25; $i++) {
                                                            if($this->letter != $this->alphabetArray[$i]) {   ?>
                                                            <a href="<?php echo $urlGen->getPlayersStartingWithAlphabetUrl($this->alphabetArray[$i], 0, True , $this->position, $this->countryId); ?>"><?php echo $this->alphabetArray[$i]; ?></a>
                                                               <?php } else {
                                                                   echo $this->alphabetArray[$i];
                                                                  }
                                                            }
                                                             if($this->letter != 'Z'){
                                                                ?>
                                                                <a href="<?php echo $urlGen->getPlayersStartingWithAlphabetUrl('Z',0,True , $this->position, $this->countryId); ?>" class="last">Z</a>
                                                            <?php } else {
                                                                   echo 'Z';
                                                                  }

                                                            ?>
                                                        </li>
                                                    </ul>


                                                <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml'); ?>

                                                  	<?php {
                                                        $rowcolor1 = '#F0F0F2';
                                                        $rowcolor2 = '#FFFFFF';
                                                        // the background colors on mouseover
                                                        $hovercolor1 = '#BAD4EB';
                                                        $hovercolor2 = '#DCE9F4';
                                                        } ?>

                                                    <table class="small"  width="100%" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF" style="padding-bottom:15px;">
                                                        <tr>
                                                                <td bgcolor="#CCCCCC" align="center"><b>No.</b></td>
                                                            <td bgcolor="#CCCCCC"><b>Player name</b></td>
                                                            <td bgcolor="#CCCCCC"><b>Club</b></td>
                                                            <td bgcolor="#CCCCCC"><b>Country</b></td>
                                                            <td bgcolor="#CCCCCC"><b>Position</b></td>
                                                            <td bgcolor="#CCCCCC" align="center"><b>Age</b></td>
                                                       </tr>
                                                     <?php $i = 1;
                                                           $common = new Common();
                                                           $cont = $this->itemNumber;
                                                     ?>

                                                     <?php foreach ($this->paginator as $data)
                                                        {
                                                              if($i % 2 == 1)
                                                                  {
                                                                    $style = $rowcolor1;
                                                                    $hoverstyle = $hovercolor1;
                                                                  }else{
                                                                    $style = $rowcolor2;
                                                                    $hoverstyle = $hovercolor2;
                                                                  }
                                                     ?>
                                                                <tr>
                                                            <td align="center" bgcolor="<?php echo $style; ?>"><?php echo $cont; ?></td>
                                                            <td bgcolor="<?php echo $style; ?>"><a href="<?php echo $urlGen->getPlayerMasterProfileUrl($data["player_nickname"], $data["player_firstname"], $data["player_lastname"], $data["player_id"], true ,$data["player_common_name"]); ?>"><?php echo $data["player_name_short"]; ?></a></td>
                                                            <td bgcolor="<?php echo $style; ?>">&nbsp;&nbsp;&nbsp;<a href="<?php echo $urlGen->getClubMasterProfileUrl($data["team_id"], $data["team_seoname"], true)?>" title="<?php echo $data["team_name"] ?>"><?php echo $data["team_name"]; ?></a></td>
                                                            <td bgcolor="<?php echo $style; ?>"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $data["player_country"]; ?>.png">&nbsp;<?php echo $data["country_name"]; ?></td>
                                                            <td bgcolor="<?php echo $style; ?>"><?php echo $data["player_position"]; ?></td>
                                                            <td align="center" bgcolor="<?php echo $style; ?>"><?php echo $common->GetAge($data["player_dob"]); ?></td>
                                                       </tr>
                                                      <?php $i++;
                                                            $cont++; } ?>
                                                    </table>
                                                    
                                                    <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml'); ?>

                                            </div>
                                            



                                        </div>
                                    </div>
                                 </div>
                    </div>
                </div><!--end SecondColumnPlayerProfile-->

    </div> <!--end ContentWrapper-->
<?php include 'include/playerh2h.php';?>