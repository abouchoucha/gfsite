<?php 
    require_once 'seourlgen.php';
    require_once 'Common.php';
    $urlGen = new SeoUrlGen();
	$session = new Zend_Session_Namespace('userSession');
	$common = new Common();
?>
<table border="0" class="table" cellpadding="5" cellspacing="0">
                                <?php 
                                $i = 1;
                                	if(sizeOf($this->paginator) == 0){
                                		echo '<tr><td>You are not currently following any players , teams, or leagues. Simply add one or more to your
                                		favorites and you will ve able to follow them here.</td></tr>';
                                	}else {
                                	foreach($this->paginator as $update){
                                	if($i % 2 == 1)
			                        {
			                            $style = "#ffffff";
			                        }else{
			                            $style = "#e6eff4";
			                        }
                                	?>
                                
                                <tr bgcolor="<?php echo $style;?>">
                                        <td>
                                        	<img src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=40&h=40&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?><?php echo $update['activity_image'];?>"
                                        	 alt=""/></td><td><?php echo $update['activity_text'];?> 
                                        	<span class="date"><?php  
                                        	date_default_timezone_set('Europe/Copenhagen');
	                                        $dateEvent = new Zend_Date($update["activity_date"],'yyyy-MM-dd HH:mm:ss', null, new Zend_Locale('en_US'));
											$dateEvent->setTimezone('America/New_York');
	                                        $dateEvent = $dateEvent->toString ( 'yyyy-MM-dd HH:mm:ss' );
	                                        echo $common->convertDates($dateEvent);?></span>
                                        	
                                        </td>
                                </tr>
                                <?php $i++;
                                	} 
                                	}/*?>
                                <!--tr bgcolor="#e6eff4">
                                        <td><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/image2.jpg" alt=""/></td><td><a href="#">&lt;Team Name&gt;</a> <font style="color:#000;">&lt;won,lost&gt; &lt;league name&gt; match vs. &lt;Team Name&gt; &lt;result&gt;,</font> 4:55 PM</td>
                                </tr>
                                <tr bgcolor="#ffffff">
                                        <td><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/image3.jpg" alt=""/></td><td><a href="#">&lt;Player Name&gt;</a> <font style="color:#000;">appeared as a &lt;starter, substitute&gt; in the &lt;Team&gt; vs. &lt;Team&gt; match,</font> 4:55 PM</td>
                                </tr>
                                <tr bgcolor="#e6eff4">
                                        <td><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/image4.jpg" alt=""/></td><td><a href="#">&lt;League/Tournament&gt;</a> <font style="color:#000;">match &lt;Team Name&gt; vs. &lt;Team Name&gt; kicked off,</font> 4:55 PM</td>
                                </tr>
                                <!--tr bgcolor="#ffffff">
                                    <td style="float:left;"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/image3.jpg" alt="" width="40" height="40"/></td>
                                        <td class="ballack"><a href="#">A. Iniesta</a> <font style="color:#000;"> has a new picture in their profile, 3:10 PM</font><br/>
                                            <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/image5.jpg" alt=""/>
                                            <p>Picture caption would display here, wrapping as needed and truncating after
                                                ## characters...</p><span><a href="#">See Detailed View &raquo;</a></span></td>
                                </tr>
                                <tr bgcolor="#e6eff4">
                                        <td style="float:left;"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/image3.jpg" alt=""/></td>
                                        <td class="ballack"><a href="#">D. Forlan</a> <font style="color:#000;"> has a new picture in their profile, 3:10 PM</font><br/>
                                            <img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/image10.jpg" alt=""/>
                                            <p>Picture caption would display here, wrapping as needed and truncating after
                                        ## characters...</p><span><a href="#">See Detailed View &raquo;</a></span></td>
                                </tr-->} <?php */ ?>
                                <tr>
                               <?php if($this->limit == 10){?>
                                <tr>
                                    <td colspan="4" class="see"><a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/index/moreupdates">See More Updates &raquo;</a></td>
                                </tr>
                                <?php }else {?>
                                <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div_simple.phtml',array('ajaxdata'=>'data_updates'));  ?>
                                <?php }?>
                                        
</table>