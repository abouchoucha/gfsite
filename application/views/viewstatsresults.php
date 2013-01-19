<script type="text/JavaScript">

jQuery(document).ready(function() {
      jQuery('.tabscontainer').hide();
      jQuery('div.cont').hide();
      jQuery('#tab1AllContent').show();
      jQuery('#tab1content').show();
      jQuery('#tab1').addClass('active');
 });

</script>

<!-- Tab Player Wrapper  -->
<div class="cont" id="tab1content">
         <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml',array('ajaxdata'=>'ajaxdata')); ?>
      <div class="scores">
          <ul>
              <li class="name">Player</li>
              <li class="team">Team</li>
              <li id="stattile" class="cont">GP</li>
              <li class="score">
                   <?php if($this->typestat == 'tab1'){?> <!-- goals stats  -->
                         <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt="">
                 <?php }else if($this->typestat == 'tab11'){?> <!-- assits stats -->
                        Assists
                 <?php }else if($this->typestat == 'tab12'){?> <!-- minutes stats  -->
                        Minutes
                 <?php }?>

              </li>
              <li class="profile">&nbsp;</li>

          </ul>
      </div>
  
      
  <?php require_once 'seourlgen.php'; ?>
  <?php $urlGen = new SeoUrlGen(); ?>
  <?php $i = 1;?>
  
  <?php foreach ($this->paginator as $goals) { ?>
  <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?>
      <div class="<?php echo $style; ?>">
          <ul>
              <li class="name">
              		<?php if($goals["player_common_name"] != null){?>
              		<a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $goals["player_id"], true ,$goals["player_common_name"]); ?>" title="<?php echo $goals['First_Name']; ?> <?php echo $goals['Last_Name']; ?>">
              			<?php echo $goals['First_Name']; ?> <?php echo $goals['Last_Name']; ?>
              		</a>
              		<?php }else{ ?>
              			<?php echo $goals['First_Name']; ?> <?php echo $goals['Last_Name']; ?>
              		<?php } ?>
              </li>
              <li style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $goals['country_id']; ?>.png') no-repeat scroll left center transparent;" class="team">
                  <a href="<?php echo $urlGen->getClubMasterProfileUrl($goals['team_id'],$goals['team_seoname'], True); ?>">
                    <?php echo $goals['team_name']; ?>
                  </a>
              </li>
              <li class="cont"><?php echo $goals['gamesplayed']; ?></li>
              <li class="score">
                 <?php if($this->typestat == 'tab1'){?>
                        <?php echo $goals['totalgoals']; ?>
                 <?php }else if($this->typestat == 'tab11'){?>
                        <?php echo $goals['assists']; ?>
                 <?php }else if($this->typestat == 'tab12'){?>
                        <?php echo $goals['minutes']; ?>
                  <?php }?>
                  </li>
              <li class="profile">
                <?php if($goals["player_common_name"] != null){?>
                  <a href="<?php echo $urlGen->getPlayerMasterProfileUrl(null, null, null, $goals["player_id"], true ,$goals["player_common_name"]); ?>" title="<?php echo $goals['First_Name']; ?> <?php echo $goals['Last_Name']; ?>">Profile &raquo;</a>
                <?php } ?>
              </li>
          </ul>
      </div>
  <?php $i++; } ?>

</div>
                      
              
