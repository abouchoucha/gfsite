<div style="float: left; padding: 8px 0px;" class="WrapperForDropShadow">
				        <ul>
				            <li class="<?php echo($this->dashboardMenuSelected == 'home'?'active':'noactive'); ?>">
		                      <a href="<?php echo Zend_Registry::get("contextPath"); ?>/myhome" class="">
		                        My Home
		                      </a>
				            </li>
				            <li class="<?php echo($this->dashboardMenuSelected == 'scores'?'active':'noactive'); ?>">
				                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/index/morescores/type/scores">My Scores & Schedules</a>
				            </li>
				             <li class="<?php echo($this->dashboardMenuSelected == 'updates'?'active':'noactive'); ?>">
				                <a href="<?php echo Zend_Registry::get("contextPath"); ?>/index/moreupdates">Updates <!-- & Alerts --></a>
				            </li>
				             <li class="<?php echo($this->dashboardMenuSelected == 'friends'?'active':'noactive'); ?>">
								<a href="<?php echo Zend_Registry::get("contextPath"); ?>/index/moreactivities">Activity & Broadcasts</a>
				            </li>

				
				         </ul>
</div>