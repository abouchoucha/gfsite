<?php $session = new Zend_Session_Namespace('userSession'); ?>
<?php require_once 'seourlgen.php';
$urlGen = new SeoUrlGen();?>
 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
<script language="javascript">


    jQuery(document).ready(function() {

        jQuery('.Selected').removeClass('Selected');
        jQuery('#<?php echo $this->tabSelected; ?>').addClass('Selected');


        jQuery('#mfactivity').click(function(){

            url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showalluseractivity/type/0';

            document.userActivityForm.action = url;
            document.userActivityForm.submit();

        });
        jQuery('#myactivity').click(function(){

            url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showalluseractivity/type/1';
            document.userActivityForm.action = url;
            document.userActivityForm.submit();

        });

        jQuery('#buttonActivity').click(function(){

            var activityId = jQuery('#userActivityId').val();
            var url = null;
            var type = '<?php echo $this->tabSelected; ?>';
            if(type == 'mfactivity'){
                url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showalluseractivity/type/0/activityid/'+activityId;
            }else if(type == 'myactivity'){
                url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/showalluseractivity/username/<?php echo $this->currentusername;?>/type/1/activityid/'+activityId;
            }
            document.userActivityForm.action = url;
            document.userActivityForm.submit();

        });

    });

</script>


<div id="ContentWrapper" class="TwoColumnLayout">
    <div class="FirstColumnOfThree">
        <?php
        $session = new Zend_Session_Namespace('userSession');
        ?>

        <!-- START Profile Box Include -->
        <?php echo $this->render('include/miniProfilePlusLoginBox.php'); ?>
        <!-- END Profile Box Include -->

    </div><!--end FirstColumnOfThree-->


    <div class="SecondColumn" id="SecondColumnPlayerProfile">
        <h1><?php echo ($session->isMyProfile =='y' ?"My":$session->currentUser->screen_name);?> <?php echo ($this->type =="0"?"Friend's":"");?> Activity</h1>
  
                <div class="SecondColumnSubpageProfile">
                    <?php if ($session->isMyProfile == 'y'){ ?>
                    
                        <ul class="TabbedNav" id="main_tabs">
                       		<li id="myactivity" class="Selected"><a href="#">My Activity</a></li>
                            <li id="mfactivity" ><a href="#">My Friend's</a></li>
                            
                        </ul>
                   
                    <?php 	} ?>

                    <div id="FriendsWrapper" style="padding:0px;width:730px;" >
                        <ul class="Friendtoolbar" style="padding-top:5px;margin-top:0;">
                            <li class="Buttons">
                                <span class="Label">Show:
                                    <?php //if ($session->isMyProfile == 'y'){ ?>
                                    <form name="userActivityForm" method="post">
                                        <select id="userActivityId" class="slct" name="userActivityId">
                                            <option value="0" <?php echo ($this->activityid =="0"?"selected":"");?>>--All--</option>
                                            <option value="20" <?php echo ($this->activityid =="20"?"selected":"");?>>New Friends</option>
                                            <option value="24" <?php echo ($this->activityid =="24"?"selected":"");?>>Profile Update</option>
                                            <option value="4" <?php echo ($this->activityid =="4"?"selected":"");?>>Goooal Shout Post</option>
                                            <option value="26" <?php echo ($this->activityid =="26"?"selected":"");?>>Comment Post</option>
                                        </select>
                                        <input style="display:inline;" type="submit" id="buttonActivity" value="Ok" class="submit">
                                    </form>
                                    <?php 	//} ?>
                                </span>
                            </li>
                        </ul>

                        <?php
                        $tempDate = '1234';
                        $today = date ( "m-d-Y" ) ;
                        $yesterday  = date ( "m-d-Y", (strtotime (date ("Y-m-d" )) - 1 * 24 * 60 * 60 )) ;
                        if (sizeOf($this->paginator) > 0) {
                            ?>

                            <?php echo $this->paginationControl($this->paginator,'Sliding','scripts/my_pagination_control.phtml'); ?>

                            <?php
                            foreach($this->paginator as $apu) {
                                ?>
                        <div id="boxComments">
                            <dl class="comment">
                                <dt class="shout">

                                    <img title="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/<?php echo $apu["activitytype_icon"];?>">
                                </dt>
                                <dd>
                                    <?php if($tempDate != date('m-d-Y' , strtotime($apu["activity_date"])) ){ ?>
                                    <span class="date">
                                        <?php
                                        if($today == date('m-d-Y' , strtotime($apu["activity_date"]))){
                                            echo 'Today at '. date(' g:i a' , strtotime($apu["activity_date"]));
                                        }else if($yesterday == date('m-d-Y' , strtotime($apu["activity_date"]))){
                                            echo 'Yesterday at ' .date(' g:i a' , strtotime($apu["activity_date"]));
                                        }else {
                                            echo date(' F j' , strtotime($apu["activity_date"]))." - " .date(' g:i a' , strtotime($apu["activity_date"]))   ;
                                        }?>
                                    </span>
                                    <?php } ?>
                                    <p class="shoutp"><?php echo $apu["activity_text"];?></p>
                                </dd>
                            </dl>
                        </div>

                                <?php
                                //$tempDate =  date('m-d-Y' , strtotime($apu["activity_date"]));
                            }
                            ?>
                            <?php }else { ?>
                        No Activity at this time
                        <?php   } ?>

                    </div>
                </div>
    
    </div><!--end Second Column-->
</div> <!--end ContentWrapper-->
