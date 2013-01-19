 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 


<script>
function rate( value ) {
	new Ajax.Updater( 'rateprofile', '<?php echo Zend_Registry::get("contextPath"); ?>/profile/rateProfile/v/'+value );
}

function addGoalShout(){
	var pars = Form.serialize("comment_form");
	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/addGoulShout';
	var myAjax = new Ajax.Updater(
			'boxComments', 
					url,
		     {
		      method: 'post',
		      parameters: pars,
		      insertion: Insertion.Top,
		      onSuccess: function() {
		      //$('result').innerHTML = 'pq aca si funca';
			   //new Effect.Opacity('result',
			   // { duration: 2.0, from: 0.0, to: 1.0 } );
//			   new Effect.Opacity('result',
//			    { delay: 10.0, duration: 2.0, from: 1.0, to: 0.0 } );
			  }	
			 });
}

</script>
<script>

      function textCounter(textarea, countdown, maxlimit)
      {
        textareaid = document.getElementById(textarea);
        if (textareaid.value.length > maxlimit)
          textareaid.value = textareaid.value.substring(0, maxlimit);
        else
          document.getElementById(countdown).value = '('+(maxlimit-textareaid.value.length)+' characters available)';
      }

    </script>

<style>
      #ta1count { border: 0; }
</style>

      <div id="ContentWrapper" class="TwoColumnLayout">

        <div class="FirstColumnOfThree">
          <?php 
              $session = new Zend_Session_Namespace('userSession');
              if($session->email != null){
          ?> 
          <!--Me box Non-authenticated and Left menu -->
				     <div class="img-shadow">
                <div class="WrapperForDropShadow">
                    <?php include 'include/loginbox.php';?>
                </div>
             </div>
					   <div class="img-shadow-gray">
                <div class="WrapperForDropShadow-gray">
                    <div id="Feedback">
                    	<div class="OnlineNow">
                            Online Now
                        </div>
                        <img width="180" height="180" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/photos/<?php echo $this->currentUser->main_photo; ?>" />
                        <br />
                        
				
                        <ul class="ulGradientBoxes GradientBlue">
                            <li><div id="rating_two" class="rating_container"></div><div id="msgRate"></div>
									<script>
									   var friend = '<?php echo $this->currentUser->user_id ; ?>';
									   var rating_two = new Control.Rating('rating_two',{
									        value : <?php printf ("%01.2f",$this->avgrating) ; ?>,
									        max: 5,
									        updateUrl: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/rateProfile/friendId/'+friend,
									        afterChange: function(){
									            $('msgRate').innerHTML = 'Thanks for voting';
									        }
									    });
									    //rating_two.setValue(2.1);
									</script>
									
									</li>
                                    
                                    <li>Give a High Five</li>
                                    <li>Send a Gooal Shout</li>
                                    <li class="last">Send Private Message</li>
                                    
                                 </ul> 
                                <ul class="ulGradientBoxes GradientGray">       
                                  <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/message/sendRequest" id="modal_link_one" name="<?php echo $this->currentUser->user_id ; ?>">Add to Friends</a><div id="test_one_contents"></div></li>
                                   
                                  <li>Add to Favorities</li>
								  <li>Invite to Group</li>
								  <li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/profile/forwardToFriend" id="modal_link_two" name="<?php echo $this->currentUser->user_id ; ?>">Forward to Friend</a><div id="test_one_contents2"></div></li>
                                	   
                                    <li>Block this User</li>
                                    <li class="last">Report this user</li>
                            </ul>                      
                        </div>
                    </div>
               </div>
					
                <?php }else { ?>
                 <!--Me box Non-authenticated and left menu-->
				 
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>
					           <div class="img-shadow-gray">
                        <div class="WrapperForDropShadow-gray">
                            <div id="Feedback">
                                <strong>Views</strong>
                                <br />

                                High Fives<strong class="Quantity">25</strong>Gooal Shouts<strong class="Quantity">12</strong>
                            
                                <div class="OnlineNow">
                                    Online Now
                                </div>
                                <img width="180" height="180" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/photos/<?php echo $this->currentUser->main_photo; ?>" />
                                <br />
                                
                                <ul class="ulGradientBoxes GradientBlue">
                									<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/login">Add to Friends</a></li>
                									<li class="last"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/login">Send Private Message</a></li>                                   
                                </ul>
                            </div>    
                        </div>
                    </div>
				 <!-- Non-authenticated Profile Left Menu-->
					
					
                <?php } ?> <!-- End - Non-authenticated Profile Left Menu-->
                         
            
                </div><!--end FirstColumnOfThree-->
                
                <div class="SecondColumn" id="SecondColumnHighlightBox">
                    
                      <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                                <h4>Goal Shouts</h4>
                            </div>
                            <div class="PlayerInfoWrapper">
                                <div class="PlayerInfo">
                                    <div class="HighFivePost">
                                        <p>
                                            Text
                                        </p>
                                        <ul>
                                            <li>Type: High Five</li>
                                            <li class="Last"><input id="ta1count" type="text" size="30" style="border: 0pt none ;" readonly="readonly"/></li>                                            
                                        </ul>
                                        <form id="comment_form" action="post" name="comment_form" action="<?php echo Zend_Registry::get("contextPath"); ?>/profile/addGoulShout">
                                            <textarea id="ta1" name="ta1" rows=5 cols=20
                                            	onKeyDown="textCounter('ta1','ta1count',100);"
    											onKeyUp="textCounter('ta1','ta1count',100);" >
                                            </textarea>
                                            <input type="hidden" name="idtocomment" value="<?php echo $this->currentUser->user_id;?>">
                                            <input type="submit" class="submit" value="Post" onclick="addGoalShout()" />
                                        </form> 
                                        <script type="text/javascript">
											textCounter('ta1','ta1count',100);
										</script>
                                    </div>
                                    
                                    <ul class="BluePager">
                                        <li>Showing ######</li>
                                        <li class="ResultsLinks">
                                            <a href="#">1</a>|
                                            <a href="#">2</a>|
                                            <a href="#">3</a>|
                                            <a href="#">4</a>|
                                            <a href="#" class="Next">Next</a>                                        
                                        </li>                                    
                                    </ul>
                                    <div class="SearchResults HighFivePostDisplay">
                                     <?php foreach($this->comments as $uc) { ?>
                                        <ul style="background-image:url('<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=48&h=48&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $this->root_crop;?>/photos/<?php echo ($uc["main_photo"] !=null?$uc["main_photo"] :'silueta.gif');?>');background-repeat: no-repeat">
                                            <li class="Username"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $uc["screen_name"];?>" class="First"><?php echo $uc["screen_name"];?></a>|<a href="#">See All Comments</a>
                                               <?php  if($session->email == $this->currentUser->email){ ?>
                                                | <a href="#" class="Edit">Edit</a></li>
                                              <?php } ?>
                                             <!-- To Implement new functionality
                                             <input type="submit" class="submit" value="Accept" />
                                              <input type="submit" class="submit red" value="Decline" />-->
                                            </li>
                                            <li>(<?php echo date(' F j , Y' , strtotime($uc["comment_date"])) ;?>&nbsp;<?php echo date(' g:i a' , strtotime($uc["comment_date"])) ;?>)</li>
                                            <li class="Description"><?php echo $uc["comment"];?></li>
                                        </ul>

                                    <?php } ?>
                                                                       
                                    </div>
                                </div><!-- /PlayerInfo-->
                            </div><!-- /PlayerInfoWrapper-->
                            
                        </div>
                    </div>
                                               
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
          
          
           </div> <!--end ContentWrapper-->
