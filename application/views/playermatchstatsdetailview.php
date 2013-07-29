<?php require_once 'seourlgen.php';  $urlGen = new SeoUrlGen(); ?>
<!-- League Match Season Stats -->
<?php //echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_box.phtml'); ?>
 <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_full_matchstats.phtml'); ?>

<div class="scores">
	<ul>
		<li class="name">Date</li>
		<li class="comp">Competition</li>
		<li class="team">Opponent</li>

		<?php if ($this->playerpos != 'Goalkeeper') {  ?>
    	   	<li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt="Goals Scored"/></li>
    	<?php } else { ?>
            <li class="score">GA</li>
    	<?php } ?>
            <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score2.jpg" alt="Yellow Cards"/></li>
            <li class="score"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score3.jpg" alt="Red Cards"/></li>
	</ul>
</div>
<?php $i = 1; ?>
<?php foreach ($this->paginator as $data) {  ?>
    <?php  if($i % 2 == 1) { $style = "scores1"; }else{ $style = "scores2";} ?>
    <div class="<?php echo $style ?>">
    	<ul>
    		<li class="name"><?php echo date('M j, Y', strtotime ($data['match_date'])) ?></li>
    		<li class="comp">
		   		<a href="<?php echo $urlGen->getShowDomesticCompetitionUrl($data['competition_name'], $data["competition_id"], True); ?>">
		   	    <?php echo $data['competition_name'] ?>
		   	 </a>
		   </li>
		   <li class="teamlogo" style="background: url('<?php echo Zend_Registry::get("contextPath");?>/utility/imagecrop?w=18&h=18&zc=1&src=<?php echo Zend_Registry::get("contextPath");?><?php echo $this->root_crop;?>/teamlogos/<?php echo $data['team_other_id']; ?>.gif') no-repeat scroll left center transparent;">
		   	<a href="<?php echo $urlGen->getMatchPageUrl($data['competition_name'],$data['team_current_name'], $data['team_other_name'], $data['match_id'], true);?>">
		   	    <?php echo $data['team_other_name'] ?>
		   	 </a>
		   </li>

		   <?php if ($this->playerpos != 'Goalkeeper') {  ?>
    			<li class="score"><?php echo $data['gl'] ?></li>
    	   <?php } else { ?>
    	   		<li class="score"><?php echo $data['gl'] ?></li>
    	   <?php } ?>
    		<li class="score"><?php echo $data['yc'] ?></li>
    		<li class="score"><?php echo $data['rc'] ?></li>
    	</ul>
	</div>
<?php $i++; } ?>
 <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_full_matchstats.phtml'); ?>

