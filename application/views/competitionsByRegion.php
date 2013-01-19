<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();?>


    <span>(#) = # of Domestic Competitions</span>
    <h3>National Team Tournaments</h3>
      <ul class="compListItem">
					<?php foreach($this->regionalNatTeam as $regClubTeam) { ?>
              <li><a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($regClubTeam["competition_name"], $regClubTeam["competition_id"], True); ?>" title="<?php echo $regClubTeam["competition_name"]; ?>"><?php echo $regClubTeam["competition_name"]; ?></a></li>
          <?php } ?>
			</ul>

       
          
      <h3>Regional Club Team Tournaments</h3>

        <ul class="compListItem">
				<?php foreach($this->regionalClubTeam as $regNatTeam) { ?>
          <li><a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($regNatTeam["competition_name"], $regNatTeam["competition_id"], True); ?>" title="<?php echo $regNatTeam["competition_name"]; ?>"><?php echo $regNatTeam["competition_name"]; ?></a></li>
        <?php } ?>
        </ul>


      <h3>Domestic Leagues and Tournaments</h3>
        <ul class="TwoColumnCountryList">
				<?php foreach($this->domesticTeams as $dteams) { ?>
           <li>
            <img class="flgSmall" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $dteams["country_id"]; ?>.png">&nbsp;
            <a href="<?php echo $urlGen->getShowDomesticCompetitionsByCountryUrl($dteams["country_name"], $dteams["country_id"], True); ?>" title="<?php echo $dteams["country_name"]; ?>"><?php echo $dteams["country_name"]; ?></a> (<?php echo $dteams["num_of_leagues"]; ?>)
           </li>
        <?php } ?>              
        </ul>        
   
 

 
 
 
 
