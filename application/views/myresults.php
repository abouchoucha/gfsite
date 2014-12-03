<script type="text/JavaScript">
    jQuery(document).ready(function(){
    	jQuery('#buttonLeagues').unbind();
		jQuery('#buttonLeagues').click(function(){ //
    		var leagueIdId = jQuery("#myleaguesId").val();
    		var typeOfResults = '<?php echo $this->typeOfResults; ?>';
    		if(typeOfResults == 'fixture'){
    			callMyResults('fixture','menu2content',leagueIdId);
    		}else if(typeOfResults == 'played'){
    			callMyResults('played','menu1content',leagueIdId);
    		}
    	}); 
        
	});
</script>
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
                                if(sizeOf($this->paginator)== 0){ 
                                	echo "You do not have matches because you do not have leagues in your favorites.";
                                }else {
                                	if($this->typeOfResults == 'fixture'){
	                                	if($this->league == 8 or $this->league == 43 or $this->league ==45 or $this->league == 70 ){
	                                		echo "Schedule information is not available due to restrictions by the league";
	                                		exit;
	                                	}
                                	}
                                foreach($this->paginator as $match){
                                if($i % 2 == 1)
                        {
                            $style = "#ffffff";
                        }else{
                            $style = "#e6eff4";
                        }
                                ?>
                                <?php if($match['status'] == 'Playing' or $match['status'] == 'Played'){?>
                                <tr bgcolor="<?php echo $style;?>">
                                        <td><?php 
                                        date_default_timezone_set('Europe/Copenhagen');
                                        $dateEvent = new Zend_Date($match['mdate']." ".$match['time'],'yyyy-MM-dd HH:mm:ss', null, new Zend_Locale('en_US'));
										$dateEvent->setTimezone('America/New_York');
                                        $dateEvent = $dateEvent->toString ( 'yyyy-MM-dd HH:mm:ss' );
                                        //echo $dateEvent;
										echo $common->convertDates($dateEvent);?></td>
                                        <td>
                                        	<img style="border:0 none;" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $match["country"]; ?>.png">
                                        	<a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($match["competition_name"], $match["league"], True); ?>">
                                        		<?php echo $match['competition_name'];?>
                                        	</a>
                                        	</td>
                                        	<td>
                                        		<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>"><?php echo $match['teama'];?> <?php echo $match['fs_team_a'];?> - <?php echo $match['fs_team_b'];?> <?php echo $match['teamb'];?></a>
                                        	</td>
                                        <td align="right"><a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
										<?php if($match['status'] == 'Playing'){ ?>
											Playing  &raquo;
										<?php }else {?>
											Details &raquo;
										<?php }?>
										
								</a></td>
                                </tr>
                                <?php }else if($match['status'] == 'Fixture'){
                                	?>
                                <tr bgcolor="<?php echo $style;?>">
                                        <td><?php echo date('Y/m/d' ,strtotime($match['mdate']));?></td>
                                        <td>
                                        	<img style="border:0 none;" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/flags/<?php echo $match["country"]; ?>.png">
                                        	<a href="<?php echo $urlGen->getShowRegionalCompetitionsByRegionUrl($match["competition_name"], $match["league"], True); ?>"><?php echo $match['competition_name'];?></a>
                                        </td>
                                       <?php  if($match['league'] != 8 and $match['league'] != 43 and $match['league'] !=45 and $match['league'] != 70){ ?>
                                        <td>
                                        	<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">
                                        	<?php echo $match['teama'];?> <font style="color:#000;"> vs </font><?php echo $match['teamb'];?></a>
                                        </td>
                                        <td align="right">
                                        	<a href="<?php echo $urlGen->getMatchPageUrl($match["competition_name"], $match["teama"], $match["teamb"], $match["matchid"], true);?>">Preview &raquo;</a>
                                        </td>
                                         <?php } else { echo '<td colspan=4>Schedule information is not available due to restrictions by the league </td>';}
                                   } ?>
                                </tr>
                               
                                <?php $i++;
                                	} 
                                }
                                ?>
                                <?php if($this->limit == 5){?>
                                <tr>
                                	<?php if($this->typeOfResults == 'played'){?>
                                		<td colspan="4" class="see"><a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/index/morescores/type/scores">See More Scores &raquo;</a></td>
                                	<?php }else{ ?>
                                	<td colspan="4" class="see"><a class="OrangeLink" href="<?php echo Zend_Registry::get("contextPath"); ?>/index/morescores/type/schedules">See More Schedules &raquo;</a></td>
                                	<?php } ?>	
                                </tr>
                                <?php }else {?>
                                <?php if($this->typeOfResults == 'played'){?>
                                	<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div_simple.phtml',array('ajaxdata'=>'menu1content'));  ?>
                                	<?php }else{ ?>
                                	<?php echo $this->paginationControl($this->paginator,'Sliding','scripts/pagination_control_div_simple.phtml',array('ajaxdata'=>'menu2content'));  ?>
                        			<?php } 
                                }?>	
                        </table>