<?php require_once 'seourlgen.php'; $urlGen = new SeoUrlGen();
  require_once 'urlGenHelper.php';  
  $urlGenHelper = new UrlGenHelper();
?>

<div class="WrapperForDropShadow"> 

  <ul class="leftnavlist">

    <li class="leftmenutitle">National Team</li>

    <li><a href="<?php echo $urlGen->getClubMasterProfileUrl($this->nationalTeamId,$this->nationalTeamSeoName, True); ?>"><?php echo $this->nationalTeamName; ?>&nbsp;National Team</a></li>

    <li class="leftmenutitle">Domestic Competitions</li>

    <?php foreach($this->dcountry as $domestic) { ?>

        <li><a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($domestic["competition_name"], $domestic["competition_id"], True); ?>"><?php echo $domestic["competition_name"]; ?></a></li>

    <?php } ?>

    <li class="leftmenutitle">International Competitions</li>

    <?php foreach($this->rcountry as $international) { ?>

          <li><a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($international["competition_name"], $international["competition_id"], True); ?>"><?php echo $international["competition_name"]; ?></a></li>

    <?php } ?>

   </ul>

</div>