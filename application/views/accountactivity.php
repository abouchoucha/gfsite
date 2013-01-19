<?php
     $tempDate = '1234';
     $today = date ( "m-d-Y" ) ;
     $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
     if (sizeOf($this->paginator) > 0) {
?>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_box.phtml'); ?>

<?php
     foreach($this->paginator as $apu) {
?>
<div id="boxComments">
	<dl class="comment">
		<dt class="shout">
			
			<img title="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/<?php echo $apu["activity_icon"];?>">
			</dt>
			<dd>
				<?php if($tempDate != date('m-d-Y' , strtotime($apu["activity_date"])) ){ ?>
				<span class="date">
					<?php
					     if($today == date('m-d-Y' , strtotime($apu["activity_date"]))){
					     echo 'Today at '. date(' g:i a' , strtotime($apu["activity_date"]));
					     }else if($yesterday == date('m-d-Y' , strtotime($apu["activity_date"]))){
					     echo 'Yesterday at ' .date(' g:i a' , strtotime($apu["activity_date"]));
					     }else {
					     echo date(' F j' , strtotime($apu["activity_date"]))." - " .date(' g:i a' , strtotime($apu["activity_date"]))   ;
					     }?>
				</span>
				<?php } ?>
				<p class="shoutp"><?php echo $apu["activity_text"];?></p>
			</dd>
		</dl>
	</div>
	
	<?php
	     //$tempDate =  date('m-d-Y' , strtotime($apu["activity_date"]));
	     }
	     ?>
	<?php }else { ?>
	No Activity at this time
                        <?php   } ?>