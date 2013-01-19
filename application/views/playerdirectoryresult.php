<?php require_once 'seourlgen.php'; ?>
<?php $urlGen = new SeoUrlGen(); ?>
<?php	for($i = 0 ; $i < sizeOf($this->alphabetArray); $i++) {

	if ($i==0){
		echo "<div>";
	}

	if ($i==sizeOf($this->alphabetArray)/2){
		echo "</div><div>";
	}


?>
      <h4>
        <a href="javascript:showPlayersByUniqueLetter('<?php echo $this->alphabetArray[$i]; ?>')">
          <span class="DirectoyLetter"><?php echo $this->alphabetArray[$i]; ?></span> (<?php echo $this->playerByLetterTotal[$i]; ?>)
        </a>
      </h4>
      <ul>
        <li>
          <?php foreach ($this->playersByLetter[$i] as $letra) { ?>
          <a title="<?php echo $letra["player_firstname"] . " " . $letra["player_lastname"]; ?>" href="<?php echo $urlGen->getPlayerMasterProfileUrl($letra["player_nickname"], $letra["player_firstname"], $letra["player_lastname"], $letra["player_id"], true ,$letra["player_common_name"]); ?>">
                <?php //echo $letra["player_lastname"]." ". $letra["player_firstname"]; ?>
                <?php echo $letra["player_name_short"]; ?>
          </a> |
          <?php } ?>
          
          <a title="View All Players" href="javascript:showPlayersByUniqueLetter('<?php echo $this->alphabetArray[$i]; ?>')">See All &raquo;</a>
        </li>
      </ul>
<?php } ?>

</div>
<input type="hidden" id="posType" value="<?php echo $this->position;?>">
<input type="hidden" id="countryId" value="<?php echo $this->countryId;?>">

