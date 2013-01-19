<script type="text/JavaScript">
    jQuery(document).ready(function(){
	
    	jQuery('#savebuttonid').click(function() {

    		var suspendedTo = jQuery('#suspendedTo').val();
    		var userIdToSuspend = '<?php echo $this->userId;?>';
        		
    		jQuery.ajax({
    	          method: 'post',
    	          url : '<?php echo Zend_Registry::get("contextPath"); ?>/admin/suspendaccount',
    	          dataType : 'text',
    	          data: ({userId: userIdToSuspend , suspendedTo : suspendedTo}),
    	          success: function (text) {
    		  		  alert('Account has been suspended');
    	              //jQuery('#'+div).html(text);
    	           }
    	       });
		});

});

</script>
    
<form id="UserPriveleges" name="update_user_priveleges_form"  method="POST" action="">
<?php echo $this->userId;?>
<fieldset id="joinFieldset" class="FirstColumnOfTwo" style="width:650px;padding-right:15px;">
        <div class="ErrorMessageIndividual" id="emailerror">You must enter an Email Address</div>

			<input type="checkbox" value="1" name="suspendaccount" id="suspendaccountChk" style="width:20px;">
	
			<label for="suspendaccount">
				Suspend Account
			</label>
			<span style="margin-right:60px;"><strong>As of :</strong><?php echo date ('l - F j , Y')?></span>
			<span><strong>Until :</strong>
				 <select name="profile_inactive_period" id="suspendedTo" style="margin-right:160px;width:100px;float:right;">
					<option value="0">1 week</option>
                	<option value="1">2 weeks</option>
                	<option value="2">1 month</option>
       			</select>
			</span>
		
	</fieldset>
		<fieldset class="FirstColumnOfTwo" style="width:650px;padding-right:15px;padding-left:20px;">
				<input type="button" value="Save" id="savebuttonid" name="Suspend" class="submit GreenGradient">
			</fieldset>

</form>

<!--end FirstColumnOfThree
	<div id="ErrorMessages" style='display:none'></div>

	<p>
 	Goal Shouts

        <select class="private" name="goalshout_priv">
		<option value="0">Active</option>
                <option value="1">Suspended</option>
        </select>

        <select class="private" name="goalshout_inactive_period">
		<option value="0">1 week</option>
                <option value="1">2 weeks</option>
                <option value="2">1 month</option>
        </select>
	</p>
	<p>
 	Profile Updates

        <select class="private" name="profile_priv">
				<option value="0">Active</option>
                <option value="1">Suspended</option>
        </select>

        <select class="private" name="profile_inactive_period">
				<option value="0">1 week</option>
                <option value="1">2 weeks</option>
                <option value="2">1 month</option>
        </select>
	</p>
	<p>
 	Private Messaging

        <select class="private" name="messaging_priv">
				<option value="0">Active</option>
                <option value="1">Suspended</option>
        </select>

        <select class="private" name="messaging_inactive_period">
		<option value="0">1 week</option>
                <option value="1">2 weeks</option>
                <option value="2">1 month</option>
        </select>

	</p>

 	Friend Requests

        <select class="private" name="friend_requests_priv">
		<option value="0">Active</option>
                <option value="1">Suspended</option>
        </select>

        <select class="private" name="friend_requests_inactive_period">
		<option value="0">1 week</option>
                <option value="1">2 weeks</option>
                <option value="2">1 month</option>
        </select>

	<p>

 	Hi Fives

        <select class="private" name="messaging_priv">
		<option value="0">Active</option>
                <option value="1">Suspended</option>
        </select>

        <select class="private" name="messaging_inactive_period">
		<option value="0">1 week</option>
                <option value="1">2 weeks</option>
                <option value="2">1 month</option>
        </select>

	<p>

 	Comments

        <select class="private" name="messaging_priv">
		<option value="0">Active</option>
                <option value="1">Suspended</option>
        </select>

        <select class="private" name="messaging_inactive_period">
		<option value="0">1 week</option>
                <option value="1">2 weeks</option>
                <option value="2">1 month</option>
        </select>

	<p>

 	Blogs

        <select class="private" name="messaging_priv">
		<option value="0">Active</option>
                <option value="1">Suspended</option>
        </select>

        <select class="private" name="messaging_inactive_period">
		<option value="0">1 week</option>
                <option value="1">2 weeks</option>
                <option value="2">1 month</option>
        </select>

	<p>

 	Message Boards

        <select class="private" name="messaging_priv">
		<option value="0">Active</option>
                <option value="1">Suspended</option>
        </select>

        <select class="private" name="messaging_inactive_period">
		<option value="0">1 week</option>
                <option value="1">2 weeks</option>
                <option value="2">1 month</option>
        </select>

	<p>

 	Photos

        <select class="private" name="messaging_priv">
		<option value="0">Active</option>
                <option value="1">Suspended</option>
        </select>

        <select class="private" name="messaging_inactive_period">
		<option value="0">1 week</option>
                <option value="1">2 weeks</option>
                <option value="2">1 month</option>
        </select>

	<p>

 	Video

        <select class="private" name="messaging_priv">
		<option value="0">Active</option>
                <option value="1">Suspended</option>
        </select>

        <select class="private" name="messaging_inactive_period">
		<option value="0">1 week</option>
                <option value="1">2 weeks</option>
                <option value="2">1 month</option>
        </select>
-->



