<form>
    <select name="Country">
    	<option value="">--- Select a Country ---
		<?php foreach($this->countries as $rows) { ?>
			<option value="<?php echo $rows["country_id"]; ?>"><?php echo $rows["country_name"]; ?></option>
        <? } ?>
	</select>
</form>
