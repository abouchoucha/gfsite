<script type="text/javascript">
jQuery(function() {
//load goalshouts
		 	var urlAccountNotes = '<?php echo Zend_Registry::get("contextPath"); ?>/admin/showaccountnotes/id/<?php echo $this->uid; ?>';
			//load the first list by default in scores	
		 	jQuery('#subdata').html('Loading...');
		 	jQuery('#subdata').load(urlAccountNotes);
});
</script>
<p>
	<div id="ErrorMessages" style='display:none'></div>


					<div id="goalshoutId" class="img-shadow">
					  <?php 
					  echo $this->render('accountnotedetail.php');?>
				    </div>
</form>

