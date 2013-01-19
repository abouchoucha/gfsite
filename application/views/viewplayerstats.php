<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>     
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
                    
                    <!--Goalface Register Ad-->
           
                    <?php } ?>
                
             
                <!--Player Profile Badge-->
               
                    <?php echo $this->render('include/badgePlayerNew.php');?>
          


              <!--Player Profile left Menu-->
         
                <div class="img-shadow" style="margin-left:2px;margin-top:10px;">
         		 <?php echo $this->render('include/navigationPlayerNew.php');?>
        		</div>
              
                  
          </div><!--end FirstColumnOfThree-->

	         <div class="SecondColumnOfTwo" id="SecondColumnPlayerProfile">
                    <h1><?php echo $this->playername; ?> Career Statistics</h1>
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                   
                            <div class="PlayerInfoWrapper">
                                <div class="PlayerInfo">
                                   <ul>
                                        <li><a href="#">Add To Favorites</a></li>
                                        <!--<li class="Last"><a href="#">Get <?php //echo $this->playername; ?>  Widget</a></li>-->
                                    </ul>
                                    
                                    <div class="PlayerHighLevel"><!--Player high level-->
                                        <!-- set the background image to show the logo in the right corner, the image is positioned in the css-->
                                        <div style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/teamlogos/<?php echo $this->playerteamid;?>.gif')" >
                                            <h1><?php echo $this->playername; ?>&nbsp;<?php echo $this->jerseyclub; ?></h1>
                                            <ul class="PersonalInfo">
                                                <li>Position:<span><?php if (empty($this->playerpos)){echo 'Unavailable'; } else {echo $this->playerpos;} ?></span></li>
                                                <li>Height:<span><?php if (empty($this->playerheight)){echo "&nbsp;Unavailable"; } else {echo $this->playerheight . "&nbsp;cm";}?></span></li>
                                                <li>Weight:<span><?php if (empty($this->playerweight)){echo "&nbsp;Unavailable"; } else {echo $this->playerweight . "&nbsp;kg" ;}?></span></li>
                                            </ul>
                                            <ul class="PersonalInfo">
                                                <li>Club:<span><?php echo $this->playerteamclub; ?></span></li>
                                                <li>Date of Birth:<span><?php echo $this->playerdob; ?></span></li>
                                                <li>Place of Birth:<span><?php echo $this->playerdobcity; ?></span></li>
                                            </ul>                                           
                                        </div>
                                        <div class="YearStats">
                                            <?php echo $this->seasontitle; ?> League Stats
                                        </div>
                                        <ul class="StatList">
                                            <li>
                                                Goals
                                                <h1><?php echo $this->leaguestats[0]['goals']; ?></h1>
                                            </li>
                                           <li>
                                                Games Played
                                                <h1><?php echo $this->leaguestats[0]['appearences']; ?></h1>
                                            </li>
                                           <li>
                                                Yellow Cards
                                                <h1><?php echo $this->leaguestats[0]['yellowcards']; ?></h1>
                                            </li>
                                            <li>
                                                Red Cards
                                                <h1><?php echo $this->leaguestats[0]['redcards']; ?></h1>
                                            </li>
                                        </ul>    
                                    </div><!--/Player high level-->
                                    
                                  <!--  <div class="ModuleTabs">
                                        <ul>
                                            <li class="selected"><a class="selected" href="#">GOALS</a></li>
                                            <li><a href="#">ASSISTS</a></li>
                                            <li><a href="#">COMPARISONS</a></li>
                                        </ul>
                                    </div>--><!-- /ModuleTabs-->
                                   <!-- <div class="ModuleContent">
                                        <img src="<?php echo Zend_Registry::get ( "contextPath" );?>/public/images/charts.jpg" />
                                    </div>-->
                                </div><!-- /PlayerInfo-->
                            </div><!-- /PlayerInfoWrapper-->

                            <table class="PlayerData">
                                <tr>
                                    <th class="NoLeftBorder">Season</th>
                                    <th class="LeftAlign">Team</th>
                                    <th class="LeftAlign">League</th>
                                    <th>Gp</th>
                                    <th>Sb</th>
                                    <th>Min</th>
                                    <th>Gl</th>
                                    <th>Hd</th>
                                    <th>Fk</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>Pn</th>
                                    <th>Pa</th>
                                    <th>As</th>
                                    <th>Dd</th>
                                    <th>Sht</th>
                                    <th>Gw</th>
                                    <th>Fls</th>
                                    <th>Yc</th>
                                    <th>Rc</th>                                               
                                    <th class="NoRightBorder">MPG</th>
                                </tr>        
                        
                                <?php $i = 1; ?>
                            				<?php foreach ($this->playerstats as $details)  {
                                                                if($i % 2 == 1)
                                                               {
                            	              				    $style = "";
                            	         					  }else{
                            	              					$style = "Even"; 
                            	         					  }
                            				?>

	                                <tr class="<?php echo $style; ?>">
	                                    <td class="LeftAlign"><?php echo $details["season_name"]; ?></td>
	                                    <td class="LeftAlign"><a href="<?php echo $urlGen->getClubMasterProfileUrl($details["team_id"], $details["team_seoname"], true)  ?>"><?php echo $details["team_name"]; ?></a></td>
	                                    <td><?php echo $details["competition_name"]; ?></td>
	                                    <td><?php echo $details["gp"]; ?></td>
	                                    <td class="RightAlign"><?php echo $details["sb"]; ?></td>
	                                    <td><?php echo $details["minp"]; ?></td>
	                                    <td><?php echo $details["gl"]; ?></td>
	                                    <td><?php echo $details["hd"]; ?></td>
	                                    <td><?php echo $details["fk"]; ?></td>
	                                    <td><?php echo $details["gin"]; ?></td>
	                                    <td><?php echo $details["gout"]; ?></td>
	                                    <td><?php echo $details["pn"]; ?></td>
	                                    <td><?php echo $details["pa"]; ?></td>
	                                    <td><?php echo $details["ast"]; ?></td>
	                                    <td><?php echo $details["dd"]; ?></td>
	                                    <td><?php echo $details["sht"]; ?></td>
	                                    <td><?php echo $details["gw"]; ?></td>
	                                    <td><?php echo $details["fls"]; ?></td>
	                                    <td><?php echo $details["yc"]; ?></td>
	                                    <td><?php echo $details["rc"]; ?></td>                                   
	                                    <td class="NoRightBorder"><?php echo $details["mpg"]; ?></td>
	                                </tr>
								        <?php $i++; } ?>	     
                             </table>
                        </div>
                    </div>
                    
                    <div class="StatLegend">                         
                        <ul class="Abbreviation">
                            <li>Gp</li>
                            <li>Sb</li>
                            <li>Min</li>
                            <li>Gl</li>
                            <li>Hd</li>
                        </ul>
                        <ul class="Full">
                            <li>Games Played</li>
                            <li>Games as Sub</li>
                            <li>Minutes Played</li>
                            <li>Goals</li>
                            <li>Goals by Header</li>
                        </ul>               
                         <ul class="Abbreviation">
                            <li>Fk</li>
                            <li>In</li>
                            <li>Out</li>
                            <li>Pn</li>
                            <li>Pa</li>
                        </ul>
                        <ul class="Full">
                            <li>Goals by Free Kicks</li>
                            <li>Goals inside the box</li>
                            <li>Goals outside the box</li>
                            <li>Penalty Scored</li>
                            <li>Penalties Attempted</li>
                        </ul> 
                         <ul class="Abbreviation">
                             <li>As</li>
                            <li>Dd</li>
                            <li>Sht</li>
                            <li>Gw</li>
                            <li>Fls</li>
                            
                        </ul>
               		<ul class="Full">
                            <li>Assists</li>
                            <li>Dead Ball Assists</li>
                            <li>Shots on Net</li>
                            <li>Game Winning Goals</li>
                            <li>Fouls Commited</li>
                            
                            
                            
                        </ul>
                        <ul class="Abbreviation">
                            <li>Yc</li>
                            <li>Rc</li>
                            <li>MPG</li>
                            <li>&nbsp;</li>
                            <li>&nbsp;</li>
                            <!--<li>A/90</li>-->
                            <li>&nbsp;</li>
                            <!--<li>Fls/90</li>-->
                        </ul>
                        <ul class="Full Last">
                            <li>Yellow Cards</li>
                            <li>Red Cards</li>
                            <li>Minutes Per Game</li>  
                            <li>&nbsp;</li>
                            <li>&nbsp;</li>
                        </ul> 
                        
                    </div>
                    
                    <!--<div class="PhotoGallery">
                      <ul>
                			    <li>Photo Gallery</li>
                			    <li class="Last"><a href="#">More Photos</a></li>
                			</ul>
                        <ul class="Photos">
                			    <li><a href="#">/public/images/left_photo_arrow.jpg" /></a></li>
                			    <li class="Photos">/public/images/players/<?php echo $this->playerid; ?>/images.jpg" /></li>
                			    <li class="Photos">/public/images/players/<?php echo $this->playerid; ?>/mages2.jpg" /></li>
                			    <li class="Photos">/public/images/players/<?php echo $this->playerid; ?>/images3.jpg" /></li>
                			    <li class="Photos">/public/images/players/<?php echo $this->playerid; ?>/images4.jpg" /></li>
                			    <li class="Photos">/public/images/players/<?php echo $this->playerid; ?>/images5.jpg" /></li>
                			    <li class="Last">/public/images/right_photo_arrow.jpg" /></a></li>
                			</ul>
                   </div>-->
                   
        </div><!--end SecondColumnOfTwo and #SecondColumnPlayerProfile-->
                
                
 
     </div> <!--end ContentWrapper-->
