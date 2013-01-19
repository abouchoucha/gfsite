<div>
    Display:
				<select id="FriendFeedselect" class="slct" name="FriendFeed1select" onchange="javascript:findComplaints(this.value)">
				    <option value="0" <?php if ($this->type==0) {echo 'selected';}?>>All Records</option>
				    <option value="1" <?php if ($this->type==1) {echo 'selected';}?>>Complaints Against User</option>
				    <option value="2" <?php if ($this->type==2) {echo 'selected';}?>>By This User</option>
				</select>
				<a href="javascript:addComplaint('1','1')">Add Complaint</a>
</div>

<?php
     $tempDate = '1234';
     $today = date ( "m-d-Y" ) ;
     $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
     if (sizeOf($this->paginator) > 0) {
?>

<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control_div_box.phtml'); ?>


<?php /*?><div id="boxComments">
	<dl class="comment">
		<dt class="shout">
			
			<?php echo $rpt["report_type"];?>
			</dt>
			<dd>
				<?php if($tempDate != date('m-d-Y' , strtotime($rpt["report_time"])) ){ ?>
				<span class="date">
					<?php
					     if($today == date('m-d-Y' , strtotime($rpt["report_time"]))){
					     echo 'Today at '. date(' g:i a' , strtotime($rpt["report_time"]));
					     }else if($yesterday == date('m-d-Y' , strtotime($rpt["report_time"]))){
					     echo 'Yesterday at ' .date(' g:i a' , strtotime($rpt["report_time"]));
					     }else {
					     echo date(' F j' , strtotime($rpt["report_time"]))." - " .date(' g:i a' , strtotime($rpt["report_time"]))   ;
					     }?> by <a href="<?php echo Zend_Registry::get("contextPath"); ?>/admin/usernamerecord/id/<?php echo $rpt["user_id"]; ?>"><?php echo $rpt["screen_name"];?></a>
				</span>
				<?php } ?>
				<p class="shoutp"><?php echo $rpt["report_text"];?></p>
			</dd>
		</dl>
</div>
<? */?>
<div class="tabsheader">
    <ul>
       <li class="name">User Name</li>
       <li class="status">Status</li>
       <li class="type">Type</li>
       <li class="notes">Notes</li>
     </ul>
</div>
<?php
       $i = 1;
	   foreach($this->paginator as $rpt) {
       if($i % 2 == 1)
        {
          $style = "pre";
          }else{
           $style = "pre1";
         }
?>

<div class="<?php echo $style;?>">
      <ul>
         <li class="name"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/admin/usernamerecord/id/<?php echo $rpt["user_id"]; ?>"><?php echo $rpt["screen_name"];?></a></li>
         <li class="status">New</li>
         <li class="type"><?php echo $rpt["report_type"];?></li>
         <li class="notes"><a href="">Add Note</a></li>
      </ul>
 </div>
<?php
     $i++;
     }
?>
	<?php }else { ?>
	No complaints at this time
<?php   } ?>
                        
 
<HR></HR>                 
                        
                        
                        