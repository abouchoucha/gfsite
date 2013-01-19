<ul id="SecondColumnHighlightBoxContentNavTwo">
    	<li class="PaginationOne">
      		<?php 
      		  $pagination = $this->pagination;
      		  echo $pagination->getFrom();?> - <?php echo $pagination->getTo()?> of <?php echo $pagination->getTotalRows()
          ?>
    	</li>
    	<li class="PaginationTwo">
          <? for ($i= $pagination->getFromPage(); $i<=$pagination->getToPage(); $i++) 
          { 
								if ($i==1)
								{
									if($pagination->getNumPage() != 1)
									{
									?>
										<a href="javascript:paginateUrl('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?>')"><?php echo $i;?></a>
									<? 
                  } else {
										  echo $i;
									}
								} else {
                    if($pagination->getNumPage() != $i) 
                    {           
	                ?>   
									   <a href="javascript:paginateUrl('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?>page/<?php echo $i;?>')"><?php echo $i;?></a>
	               <? } else {
	                    echo $i;
	                  } 
                } 
						}?>                            
        </li>
        <li class="PaginationThree">
            <?if ($pagination->getPreviouspage() > 0) {?>
                <a href="javascript:paginateUrl('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?>')">&lt;&lt; First</a>
              <?if ($pagination->getPreviouspage() != 1) {?>
							   <a href="javascript:paginateUrl('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?>page/<?php echo $pagination->getPreviouspage()?>')">&lt;&lt; Previous</a>
							<? }else {?>
									<a href="javascript:paginateUrl('<?php echo Zend_Registry::get("contextPath"); ?>page/<?php echo $pagination->getPaginationUrl()?>')">&lt;&lt; Previous</a>
						  <? }?>
            <? }?>
							
                  <?if ($pagination->getNextpage() <= $pagination->getNumPages()) {?>
                      <a href="javascript:paginateUrl('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?>page/<?php echo $pagination->getNextpage()?>/')">Next &gt;&gt;</a>
            	        <a href="javascript:paginateUrl('<?php echo Zend_Registry::get("contextPath"); ?><?php echo $pagination->getPaginationUrl()?>page/<?php echo $pagination->getNumPages()?>/')">Last &gt;&gt;</a>
                 <? }?>
        </li>
</ul>