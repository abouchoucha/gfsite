<?php $session = new Zend_Session_Namespace('userSession'); ?>
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
					

                    <!--Team Profile Badge-->
                    <div class="img-shadow">
                        <?php echo $this->render('include/badgeTeamNew.php');?>
                    </div>

                    <!--Team Profile left Menu-->
                    <div class="img-shadow">
                       <?php echo $this->render('include/navigationTeam.php');?>
                    </div>

    </div><!--end FirstColumnOfTwo-->

	<div class="SecondColumn" id="SecondColumnFullScore">
		<p class="middletitle"><?php echo $this->team[0]['team_name']; ?> League Performance</p>
		<div style="margin-top:5px;" class="img-shadow">
            <div class="WrapperForDropShadow">
               <div class="SecondColumnWhiteBackground">
               		<div id="leaguetables">
                         <table width="100%" cellspacing="0" cellpadding="1" border="0" id="LeagueTable">
                             <tr style="border-bottom:1px solid #D5D3D4;">
                                 <td colspan="4" class="tableTopHead"> </td>
                                 <td align="center" colspan="7" class="tableTopHead">Total</td>
                                 <td align="center" colspan="5" class="tableTopHead">Home</td>
                                 <td align="center" colspan="5" class="tableTopHead">Away</td>
                                 <td class="tableTopHead"> </td>
                              </tr>
	                          <tr class="" align="left">

                                 <th class="lheading" style="padding-left: 10px;">Team</th>
                                 <th class="lheading" style="padding-left: 10px;">League</th>
                                 <th class="lheading">Season</th>
                                 <th class="lheading">Pos</th>
                                 <th class="lheading mp">MP</th>
                                 <th class="lheading">W</th>
                                 <th class="lheading">D</th>
                                 <th class="lheading">L</th>
                                 <th class="lheading">GS</th>
                                 <th class="lheading">GA</th>
                                 <th class="lheading">+/-</th>
                                 <th class="lheading home mp">W</th>
                                 <th class="lheading home">D</th>
                                 <th class="lheading home">L</th>
                                 <th class="lheading home">GS</th>
                                 <th class="lheading home">GA</th>
                                 <th class="lheading away mp">W</th>
                                 <th class="lheading away">D</th>
                                 <th class="lheading away">L</th>
                                 <th class="lheading away">GS</th>
                                 <th class="lheading away">GA</th>
                                 <th class="lheading away">Pts</th>
                              </tr>
                              
                               <?php $i = 1; ?>
                               <?php   foreach ($this->performance as $stats ) {  ?>
	                               <?php
	                                     if($i % 2 == 1) {
	                                       $style = 'odd'; 
	                                     }else{
	                                       $style = 'even';
	                                     }
	                               ?>
                               		<tr class="<?php echo $style; ?>">

		                                 <td>
		                                 	<a href="<?php echo $urlGen->getClubMasterProfileUrl($stats['team_id'],$stats['team_seoname'], True); ?>"><?php echo $stats['team_name']; ?></a>
		                                 </td>
		                                     <td>
		                                 	<a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($stats["competition_name"], $stats["competition_id"], True); ?>"><?php echo $stats['competition_name']; ?></a>
		                                 </td>
		                                 <td><?php echo $stats['season_name']; ?></td>
		                                 <td><?php echo $stats['rank']; ?></td>                             
		                                 <td><?php echo $stats['gp']; ?></td>
		                                 <td><?php echo $stats['wn']; ?></td>
		                                 <td><?php echo $stats['dr']; ?></td>	
                                         <td><?php echo $stats['ls']; ?></td>
                                         <td><?php echo $stats['gf']; ?></td>
                                         <td><?php echo $stats['ga']; ?></td>
                                         <td><?php echo $stats['diff']; ?></td>
                                         <td><?php echo $stats['hw']; ?></td>
                                         <td><?php echo $stats['hd']; ?></td>
                                         <td><?php echo $stats['hl']; ?></td>
                                         <td><?php echo $stats['hgf']; ?></td>
                                         <td><?php echo $stats['hga']; ?></td>
                                         <td><?php echo $stats['aw']; ?></td>
                                         <td><?php echo $stats['ad']; ?></td>
                                         <td><?php echo $stats['al']; ?></td>
                                         <td><?php echo $stats['agf']; ?></td>
                                         <td><?php echo $stats['aga']; ?></td>                                          
                                        <td class="last"><?php echo $stats['pts']; ?></td>    
                            		 </tr>
                             <?php $i++; } ?>
                         </table>
                    </div>
               </div>
            </div>
        </div>
        
        <div style="width:730px;" class="StatLegend">
	        <ul class="Abbreviation">
	            <li>MP</li>
	            <li>W</li>
	        </ul>
	        <ul class="Full">
	            <li>Matches Played</li>
	            <li>Wins</li>
	        </ul>
	         <ul class="Abbreviation">
	            <li>D</li>
	            <li>L</li>
	        </ul>
	        <ul class="Full">
	            <li>Draws</li>
	            <li>Losses</li>
	        </ul>
	         <ul class="Abbreviation">
	             <li>GS</li>
	            <li>GA</li>
	        </ul>
	        <ul class="Full">
	            <li>Goals Scored</li>
	            <li>Goals Against</li>
	        </ul>
	        <ul class="Abbreviation">
	            <li>Pts</li>
	            <li>+/-</li>
	
	        </ul>
	        <ul class="Full Last">
	            <li>Goals Points</li>
	            <li>Plus/Minus</li>
	        </ul>
   		 </div>
        
        
	</div>


             
</div> <!--end ContentWrapper-->
