

<script type="text/JavaScript">
    jQuery(document).ready(function(){
    	jQuery('#searchUsersIdButton').click(function(){ //
    		var q = jQuery("#search-players").val();
    		jQuery.ajax({
		            method: 'post',
		            url : '<?php echo Zend_Registry::get("contextPath"); ?>/admin/findusers',
		            data: {q :q},
		            dataType : 'text',
		            success: function (text) {
		                jQuery('#data').html(text);
		             }
		         });
    	});
    });	
</script>

<?php echo $this->form ?>
<?php if(is_null($this->showFilters)){?>
<h1>Manage Users</h1>
<li class="Search">
              <form id="searchplayersform" method="post" action="">
				<label>Find Users</label>
				<input id="search-players" class="text" name="q" type="text">
				<input id="t" value="players" class="hidden" name="t" type="hidden">
				<input id="searchUsersIdButton" class="Submit" value="Search" type="button">
              </form>
</li>
Display:
<select name="userStatus" id="dateId">
         <option selected value="today">Flagged - New</option>
         <option value="yesterday">--Red Card</option>
         <option value="-3">--Complaint</option>
         <option value="last">Type</option>
         <option value="last">Flagged(*)</option>
         <option value="last">Rights Suspended</option>
         <option value="last">Acct Suspended</option>
         <option value="last">All - A-Z</option>
</select>
		                  
<?php } ?>		          
                            
<div id="data">
<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml'); ?>
<table class="PlayerData" style="float:left;">
	<tr>
            <th class="NoLeftBorder">User Name</th>
            <th class="LeftAlign">E-mail</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Status</th>
            <th>Acct Since</th>
            <th># Cards/History</th>
</tr>
<?php $i = 1; ?>
 
<?php foreach ($this->paginator as $details)  {
		if($i % 2 == 1)
	   		{
	              		$style = "";
	         	}else{
	              		$style = "Even"; 
	         	}
?>
<tr class="<?php echo $style; ?>"> 
	<td><a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/admin/usernamerecord/id/<?php echo $details["user_id"]; ?>"><?php echo $details["screen_name"]; ?></a></td>
	<td><?php echo $details["email"]; ?></td>
	<td><?php echo $details["first_name"]; ?></td>
	<td><?php echo $details["last_name"]; ?></td>
	<td></td>
	<td><? echo date ('M d, Y' , strtotime($details["registration_date"])); ?></td>
	<td></td>
</tr>
<?php $i++; } ?>	     
</table>
</div>