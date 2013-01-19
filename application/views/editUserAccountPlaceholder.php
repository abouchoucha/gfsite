<div>
    Display:
				<select id="FriendFeedselect" class="slct" name="FriendFeed1select" onchange="javascript:findComplaints(this.value)">
				    <option value="0" <?php if ($this->type==0) {echo 'selected';}?>>All Records</option>
				    <option value="1" <?php if ($this->type==1) {echo 'selected';}?>>Complaints Against User</option>
				    <option value="2" <?php if ($this->type==2) {echo 'selected';}?>>By This User</option>
				</select>
</div>

<div class="tabsheader">
    <ul>
    	<li class="type">Type</li>
       <li class="name">Created by</li>
       <li class="status">Status</li>
       <li class="notes">Notes</li>
     </ul>
</div>

<?php
       $i = 1;
	  // foreach($this->paginator as $rpt) {
       if($i % 2 == 1)
        {
          $style = "pre";
          }else{
           $style = "pre1";
         }
?>

<div class="<?php echo $style;?>">
      <ul>
         <li class="type"><?php //echo $rpt["report_type"];?>Warning</li>
         <li class="name"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/admin/usernamerecord/id/<?php //echo $rpt["user_id"]; ?>"><?php //echo $rpt["screen_name"];?>Chocheraz</a></li>
         <li class="status">New</li>
         <li class="notes"><a href="">Add Note</a></li>
      </ul>
 </div>
<?php
   //  $i++;
   //  }
?>
