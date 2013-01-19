<script language = "javascript">

	function nextpage(url){

		jQuery('#newboxresult').html('Loading...'); 
		jQuery('#newboxresult').load(url);
	
	}

</script>


<ul class="PreviousNextStory PreviousNextFeaturedStory">
	<li class="Quantity">
		<?php 
        	$pagination = $this->pagination;
        	echo $pagination->getFrom();?> - <?php echo $pagination->getTo()?> of <?php echo $pagination->getTotalRows()
         ?>
	</li>
	<li class="PreviousNextStory">
	
		<?php if ($pagination->getPreviouspage() > 0) {?>
                <a href="javascript:nextpage('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?>')">&lt;&lt; First</a>
              <?php if ($pagination->getPreviouspage() != 1) {?>
							   <a href="javascript:nextpage('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?><?php echo $pagination->getPreviouspage()?>/')">&lt;&lt; Previous</a>
						  <?php  }else {?>
									<a href="javascript:nextpage('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?>')">&lt;&lt; Previous</a>
						  <?php  }?>
            <?php  }?>
							
             <?php if ($pagination->getNextpage() <= $pagination->getNumPages()) {?>
                 <a href="javascript:nextpage('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?><?php echo $pagination->getNextpage()?>/')">Next &gt;&gt;</a>
       	        <a href="javascript:nextpage('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?><?php echo $pagination->getNumPages()?>/')">Last &gt;&gt;</a>
            <?php  }?>
 	
	
	</li>
	<li class="Show"><!--Show English only Showed at the right corner of the blue bar--></li>
</ul>



