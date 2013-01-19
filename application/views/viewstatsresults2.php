  <?php require_once 'seourlgen.php'; ?>
  <?php $urlGen = new SeoUrlGen(); ?>
  <?php $i = 1;?>

    <!-- Tab Team Wrapper  -->
    <div class="cont" id="tab2content">
    		 <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div.phtml',array('ajaxdata'=>'ajaxdata2')); ?>	
            <div class="scores">
                <ul>
                    <li class="team">Team</li>
                     <li class="team">Appearances</li>
                     <li class="cont">GP</li>
                    <li id="stattile" class="score">
                    <?php if($this->typestat == 'tab2'){?>
                           <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/score1.jpg" alt="">
                       <?php }else if($this->typestat == 'tab21'){?>
                       	CS
                       <?php }?>
                    </li>
                   
                    <li class="profile">&nbsp;</li>

                </ul>
            </div>
        <?php $i = 1; ?>
        <?php foreach ($this->paginator as $goals) { ?>
        <?php if($i % 2 == 1) { $style = 'scores1'; }else{ $style = 'scores2';} ?>
            <div class="<?php echo $style; ?>">
                <ul>
                    <li style="background: url('<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $goals['country_id']; ?>.png') no-repeat scroll left center transparent;" class="team">
                        <a href="<?php echo $urlGen->getClubMasterProfileUrl($goals['team_id'],$goals['team_seoname'], True); ?>">
                          <?php echo $goals['team_name']; ?>
                        </a>
                    </li>                       
                    <li class="team"><?php echo $goals['appear']; ?></li>
                    <li class="cont"><?php echo $goals['gamesplayed']; ?></li>
                    <li class="score">

                    	 <?php if($this->typestat == 'tab2'){?>
                        	<?php echo $goals['totalgoals']; ?>
                       <?php }else if($this->typestat == 'tab21'){?>
                       	<?php echo $goals['cleansheets']; ?> 
                       <?php  }?>
                        </li>
                    <li class="profile"><a href="#">Profile &raquo;</a></li>
                </ul>
            </div>
        <?php $i++; } ?>

    </div>
                      
              
