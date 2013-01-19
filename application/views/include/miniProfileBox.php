<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/rating.css' rel="stylesheet" type="text/css" media="screen"/>

<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.rating.js" type="text/javascript"></script>

<?php $session = new Zend_Session_Namespace('userSession');?>

<?php require_once 'seourlgen.php';require_once 'Common.php'; ?>

<?php $urlGen = new SeoUrlGen();

	$common = new Common();

    $config = Zend_Registry::get ( 'config' );

    $root_crop = $config->path->crop;

 ?>


<script language="javascript">



jQuery(document).ready(function() {



	<?php if ($session->isMyProfile == 'n'){ ?>

	

	 jQuery('a#highFiveButtonTrigger').click(function(){

		 giveHighFive();

 	 });

	 jQuery('a#addToFriendTrigger').click(function(){

		 addToFriends();

     });

	 jQuery('#removeFromFriendsTrigger').click(function(){

		 removeFromFriends();

     });

	 jQuery('#sendGoalShoutTrigger').click(function(){

		 sendGoalShout();

     });

	 jQuery('#blockUserTrigger').click(function(){

		 blockThisUser();

     });

	 jQuery('#reportUserTrigger').click(function(){

		 reportUser();

     });

	 jQuery('#reportAbuseUserTrigger').click(function(){

		 reportUserAbuse();

     });

	 jQuery('#forwardToFriendTrigger').click(function(){

		 forwardToFriend();

	 });

	 jQuery('#sendPrivateMessageTrigger').click(function(){

		sendPrivateMessage();

	});

	 



	



		

	 <?php } ?>	

			 

	 jQuery('#reportTypeId').change(function(){

		 var selectValue = jQuery('#reportTypeId').val();

		 if(selectValue == 0){

			 jQuery('#textReportAbuseId').attr('disabled','disabled'); 

			 jQuery('#acceptReportAbuseButtonId').attr('disabled','disabled'); 

		 }else {

		 	jQuery('#textReportAbuseId').removeAttr('disabled');

		 	jQuery('#acceptReportAbuseButtonId').removeAttr('disabled');

		 }	 

    }); 

	 	

	

	 

	 /**

 	* Star Rating 

 	*/	 

   jQuery(function() {

 		jQuery('#newsRater2').rater({ rateResult:'#newsRater2' , postHref: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/rateprofile/friendId/<?php echo $session->currentUser->user_id ; ?>'});

 	});

  		 

 });





//validate signup form on keyup and submit



function validateSendMessageForm (){

	frmSendMessageForm = jQuery('#sendEmailModalForm').validate({

		  rules: {

			sendEmailsubject: {required : true},

	 		sendEmailtextForwardFriend: {required : true}

		  },

	 messages: {

		  sendEmailsubject: "Please enter a subject",

		  sendEmailtextForwardFriend : "Please enter a text message for your friend"

		}

	});

return frmEmailForm;

}







  <?php if ($session->isMyProfile == 'n'){ ?>

    

function giveHighFive(){



	 jQuery('#acceptModalButtonId').show();

	 jQuery('#cancelModalButtonId').attr('value','Cancel'); 	

	 jQuery('#modalTitleConfirmationId').html('GIVE HIGH FIVE?');

	 jQuery('#messageConfirmationTextId').html('Are you sure you want to give <?php echo $session->currentUser->screen_name;?> a high five?');	

	 jQuery('#messageConfirmationId').jqm({trigger: '#highFiveButtonTrigger', onHide: closeModal,modal:true });

	 jQuery('#messageConfirmationId').jqmShow();

	 

	 jQuery("#acceptModalButtonId").unbind();

		

	 jQuery('#acceptModalButtonId').click(function(){

			

			jQuery.ajax({

				type: 'GET',

				url :  '<?php echo Zend_Registry::get("contextPath"); ?>/profile/givehighfive/userid/<?php echo $session->currentUser->user_id;?>',

				success: function(data){

					jQuery('#messageConfirmationTextId').html('You gave <?php echo $session->currentUser->screen_name;?> a high five');

					jQuery('#acceptModalButtonId').hide();

					jQuery('#cancelModalButtonId').attr('value','Close');

					jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 2500).jqmHide();

				}	

			})

			 

	  });	

}



function sendPrivateMessage(){



	var k = jQuery('div.ErrorMessageIndividualDisplay');

    for(var i=0;i < k.length;i++ ){ 

       k[i].className ='ErrorMessageIndividual';

    }

	jQuery("#sendEmailto").hide();

	jQuery('#labeltoId').show();

	jQuery('#labeltoId').html('<?php echo $session->currentUser->screen_name;?>');

	jQuery('#sendEmailModalForm')[0].reset();  // this resets form variables to their initial values (must be set in form fields)...

	jQuery('#sendEmailto').attr('required','');

	jQuery('#sendEmailModal').jqm({trigger: '#sendPrivateMessageTrigger', onHide: closeModal ,modal:true});

	jQuery('#sendEmailModal').jqmShow();

	jQuery('#sendEmailTitleId').text('SEND PRIVATE MESSAGE');

	jQuery('#sendEmailsubject').val('');

	jQuery('#sendEmailtextForwardFriend').val('');

	

	

	jQuery("#acceptSendEmailButtonId").unbind();

	jQuery('#acceptSendEmailButtonId').click(function(){



	valid = validaNewForm('sendEmailModalForm');

	if(valid){

			

		var subject = jQuery('#sendEmailsubject').val();

		var content = jQuery('#sendEmailtextForwardFriend').val();

		var idarray = '<?php echo $session->currentUser->user_id;?>,';

		jQuery.ajax({

			type: 'POST',

			url :  '<?php echo Zend_Registry::get("contextPath"); ?>/message/addmessage',

			data : ({subject: subject , content:content , idarray : idarray}),

			success: function(data){



				jQuery('#sendEmailModal').jqmHide();

				jQuery('#addFavoriteModal').jqm({trigger: '#removefavoriteplayertrigger', onHide: closeModal });

				jQuery('#addFavoriteModal').jqmShow();

				jQuery('#modalFavoriteTitleId').html('SEND PRIVATE MESSAGE');

				jQuery('#modalBodyResponseId').html('Your message has been sent to <?php echo $session->currentUser->screen_name;?>');

				jQuery('#modalBodyId').hide();

				jQuery('#modalBodyResponseId').show();

				jQuery('#acceptFavoriteModalButtonId').hide();

				jQuery('#cancelFavoriteModalButtonId').attr('value','Close');

				jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();

				

			}	

		})

	

	}

	});

	

}





function sendGoalShout(){



	 jQuery('#modalFormBodyId').show();

	 jQuery('#modalFormBodyResponseId').hide(); 

	 jQuery('#acceptFormModalButtonId').show();

	 jQuery('#messageerrorId').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');

	 jQuery('#cancelFormModalButtonId').attr('value','Cancel'); 	

	 jQuery('#modalFormTitleId').html('SEND GOALSHOUT?');

	 jQuery('#formModalTextId').html('Do you want to send <?php echo $session->currentUser->screen_name;?> a Goalshout?');	

	 

	 jQuery('#messageFormModalId').jqm({trigger: '#sendGoalShoutTrigger', onHide: closeModal ,modal:true });

	 jQuery('#messageFormModalId').jqmShow();

	 

	 jQuery("#acceptFormModalButtonId").unbind();

		

	 jQuery('#acceptFormModalButtonId').click(function(){



		 var commentText = jQuery('#textGoalShoutId').val();

		 if(commentText == ''){

			jQuery('#messageerrorId').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');

			return;

		 }

		 var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/profile/addgoalshout';

		 var commentType = '1';

	     var idtocomment = '<?php echo $session->currentUser->user_id;?>';

	     var screennametocomment = '<?php echo $session->currentUser->screen_name;?>';

			

			jQuery.ajax({

				type: 'POST',

				data: ({commentType:commentType , idtocomment : idtocomment ,screennametocomment : screennametocomment , comment : commentText}),

				url :  '<?php echo Zend_Registry::get ( "contextPath" );?>/profile/addgoalshout',

				success: function(data){

					jQuery('#modalFormBodyResponseId').html('Your goalshout to <?php echo $session->currentUser->screen_name;?> has been sent.');

					jQuery('#modalFormBodyId').hide();

					jQuery('#modalFormBodyResponseId').show();

					jQuery('#acceptFormModalButtonId').hide();

					jQuery('#cancelFormModalButtonId').attr('value','Close');

					jQuery('#textGoalShoutId').val('');

					jQuery('#messageFormModalId').animate({opacity: '+=0'}, 2500).jqmHide();

				}	

			})

			 

	  });	



	

}



function addToFriends(){



	 jQuery('#modalBodyId').show();

	 jQuery('#modalBodyResponseId').hide();

	 jQuery('#acceptFavoriteModalButtonId').show();

	 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	

	 jQuery('#modalFavoriteTitleId').html('Add <?php echo $session->currentUser->screen_name; ?> as your friend?');

	 jQuery('#dataText1').html('<?php echo $session->currentUser->screen_name; ?>');

	 jQuery('#dataText1').attr('href','<?php echo $urlGen->getUserProfilePage($session->currentUser->screen_name,True);?>');



	 jQuery('#title1Id').html('Joined:');

	 jQuery('#dataText2').html("<?php echo date( 'M Y', strtotime ( $session->currentUser->registration_date ) );?>");

	 jQuery('#title2Id').html('Country:');

	 jQuery('#dataText3').html("<?php echo $this->countryFrom; ?>");

	 jQuery('#title3Id').html('City:');

	 jQuery('#dataText4').html("<?php echo $session->currentUser->city_live;?>");

	 

	 jQuery('#addFavoriteModal').jqm({trigger: '#addToFriendTrigger', onHide: closeModal ,modal:true});

	 jQuery('#addFavoriteModal').jqmShow();

	 

	  var favoriteImage = null;

	  <?php if ($session->currentUser->main_photo != null or $session->currentUser->main_photo != '') { ?>

 	    favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop; ?>/photos/<?php echo $session->currentUser->main_photo; ?>';

   	<?php  } else { ?>

   		favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop; ?>/ProfileMale.gif';

   	<?php  } ?>	

	 

	 

   

   jQuery('#favoriteImageSrcId').attr('src',favoriteImage);



	 jQuery("#acceptFavoriteModalButtonId").unbind();

	 jQuery('#acceptFavoriteModalButtonId').click(function(){

	 jQuery.ajax({

			type: 'GET',

			url :  '<?php echo Zend_Registry::get("contextPath"); ?>/message/addfriendrequest/friend/<?php echo $session->currentUser->user_id;?>',

			success: function(data){

				jQuery('#modalBodyResponseId').html('Your friend request to <?php echo $session->currentUser->screen_name;?> has been sent.');

				jQuery('#modalBodyId').hide();

				jQuery('#modalBodyResponseId').show();

				jQuery('#acceptFavoriteModalButtonId').hide();

				jQuery('#cancelFavoriteModalButtonId').attr('value','Close');

				jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();

			}	

		})

		

	 });		

}



function removeFromFriends(){



	 jQuery('#modalBodyId').show();

	 jQuery('#modalBodyResponseId').hide();

	 jQuery('#acceptFavoriteModalButtonId').show();

	 jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel'); 	

	 jQuery('#modalFavoriteTitleId').html('Remove <?php echo $session->currentUser->screen_name; ?> as your friend?');

	 jQuery('#dataText1').html('<?php echo $session->currentUser->screen_name; ?>');

	 jQuery('#dataText1').attr('href','<?php echo $urlGen->getUserProfilePage($session->currentUser->screen_name,True);?>');



	 jQuery('#title1Id').html('Joined:');

	 jQuery('#dataText2').html("<?php echo date( 'M Y', strtotime ( $session->currentUser->registration_date ) );?>");

	 jQuery('#title2Id').html('Country:');

	 jQuery('#dataText3').html("<?php echo $this->countryFrom; ?>");

	 jQuery('#title3Id').html('City:');

	 jQuery('#dataText4').html("<?php echo $session->currentUser->city_live;?>");

	 

	 var favoriteImage = '<?php echo Zend_Registry::get("contextPath"); ?>/utility/imageCrop?w=60&h=60&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop; ?>/photos/<?php echo $session->currentUser->main_photo; ?>';

	 jQuery('#favoriteImageSrcId').attr('src',favoriteImage);



	 jQuery('#addFavoriteModal').jqm({trigger: '#removeFromFriendTrigger', onHide: closeModal ,modal:true});

	 jQuery('#addFavoriteModal').jqmShow();



	 jQuery("#acceptFavoriteModalButtonId").unbind();

	 jQuery('#acceptFavoriteModalButtonId').click(function(){

	 jQuery.ajax({

			type: 'GET',

			url :  '<?php echo Zend_Registry::get("contextPath"); ?>/message/removefromfriends/friend/<?php echo $session->currentUser->user_id;?>',

			success: function(data){

				jQuery('#modalBodyResponseId').html('<?php echo $session->currentUser->screen_name;?> has been removed from your friends.');

				jQuery('#modalBodyId').hide();

				jQuery('#modalBodyResponseId').show();

				jQuery('#acceptFavoriteModalButtonId').hide();

				jQuery('#cancelFavoriteModalButtonId').attr('value','Close');

				jQuery('#add').show();

				jQuery('#remove').hide();

				jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();

			}	

		})

		

	 });		



}





function blockThisUser(){



	 jQuery('#acceptModalButtonId').show();

	 jQuery('#cancelModalButtonId').attr('value','Cancel'); 	

	 jQuery('#modalTitleConfirmationId').html('BLOCK USER?');

	 jQuery('#messageConfirmationTextId').html('Are you sure you want to block <?php echo $session->currentUser->screen_name;?> from your friends?');	

	 jQuery('#messageConfirmationId').jqm({trigger: '#blockUserTrigger' , onHide: closeModal ,modal:true});

	 jQuery('#messageConfirmationId').jqmShow();

	 

	 jQuery("#acceptModalButtonId").unbind();

		

	 jQuery('#acceptModalButtonId').click(function(){

			

			jQuery.ajax({

				type: 'GET',

				url :  '<?php echo Zend_Registry::get("contextPath"); ?>/profile/blockuser/userid/<?php echo $session->currentUser->user_id;?>',

				success: function(data){

					jQuery('#messageConfirmationTextId').html('You blocked <?php echo $session->currentUser->screen_name;?> from seeing all your activity.');

					jQuery('#acceptModalButtonId').hide();

					jQuery('#cancelModalButtonId').attr('value','Close');

					jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 2500).jqmHide();

				}	

			})

		});	

}







function reportUserAbuse(){



	

	jQuery('#reportTypeId').val('0');

	jQuery('#textReportAbuseId').val(''); 

	jQuery('#textReportAbuseId').attr('disabled','disabled');

	jQuery('#reportAbuseBodyId').show();

	jQuery('#reportAbuseBodyResponseId').hide();

	jQuery('#acceptReportAbuseButtonId').show();

	jQuery('#cancelReportAbuseButtonId').attr('value','Cancel'); 	

	jQuery('#reportAbuseTitleId').html('REPORT USER?');

	jQuery('#reportAbuseTextId').html('Are you sure you want to report <?php echo $session->currentUser->screen_name;?> ?');	



	jQuery('#reportAbuseModal').jqm({trigger: '#reportAbuseUserTrigger', onHide: closeModal ,modal:true});

	jQuery('#reportAbuseModal').jqmShow();

	

	jQuery("#acceptReportAbuseButtonId").unbind();

	jQuery('#acceptReportAbuseButtonId').click(function() {



		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/reportabuse';

		var userid = '<?php echo $session->currentUser->user_id;?>';

		var dataReport = jQuery('#textReportAbuseId').val();

		var reportType = jQuery('#reportTypeId').val();

	

		jQuery.ajax({

			type: 'POST',

			data: {userid :userid , id : userid , dataReport : dataReport ,reportType:reportType},

			url : '<?php echo Zend_Registry::get("contextPath"); ?>/profile/reportuserabuse',

			success: function(data){

				jQuery('#reportAbuseBodyResponseId').html('Your report will be reviewed by our administrators soon.');

				jQuery('#reportAbuseBodyId').hide();

				jQuery('#reportAbuseBodyResponseId').show();

				jQuery('#acceptReportAbuseButtonId').hide();

				jQuery('#cancelReportAbuseButtonId').attr('value','Close');

				jQuery('#reportAbuseModal').animate({opacity: '+=0'}, 2500).jqmHide();

			}	

		})

	});

	

}

<?php } ?>	





function addGoalShout(){

	 var commentText = jQuery('#commentGoalShoutId').val();

	 if(jQuery.trim(commentText) == ''){

		jQuery('#comment_formerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');

		return;

	 }

	var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/profile/addgoalshout';

	var commentType = jQuery('#commentTypeId').val();

    var idtocomment = jQuery('#idtocommentId').val();

    var screennametocomment = jQuery('#screennametocommentId').val();

    jQuery('#goalshoutId').load(url ,{commentType: commentType , idtocomment : idtocomment ,screennametocomment : screennametocomment , comment : commentText});

    jQuery('#commentGoalShoutId').val('');



}







function editGoalShout(id){



		jQuery('#editGoalShoutModal').jqm({trigger: '#editGoalShout', onHide: closeModal ,modal:true});

		jQuery('#editGoalShoutModal').jqmShow();

		var dataEdit = jQuery('#goalshout'+id).html();

		jQuery('#textgoalshoutEdit').val(jQuery.trim(dataEdit));

		

			jQuery('#acceptEditGoalShoutButtonId').click(function() {

				var commentText = jQuery('#textgoalshoutEdit').val();

				if(jQuery.trim(commentText) == ''){

					jQuery('#commentediterrorId').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');

		 			return;

		 		 }

				var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/editprofilegoalshout';

				var userid = '<?php echo $session->currentUser->user_id;?>';

				var dataEditted = jQuery('#textgoalshoutEdit').val();

				jQuery('#editGoalShoutModal').jqmHide();

				jQuery('#goalshoutId').html('Loading...'); 

				jQuery('#goalshoutId').load(url , {userid :userid , id : id , dataEditted : dataEditted});

				

			});

}



function deleteGoalShout(id){



	 jQuery('#acceptModalButtonId').show();

	 jQuery('#cancelModalButtonId').attr('value','Cancel'); 	

	 jQuery('#modalTitleConfirmationId').html('DELETE GOOOALSHOUT?');

	 jQuery('#messageConfirmationTextId').html('Are you sure you want to delete a goalshout');	

	 

			    

	 jQuery('#messageConfirmationId').jqm({ trigger: '#deleteGoalShout' , onHide: closeModal ,modal:true});

	 jQuery('#messageConfirmationId').jqmShow();

	 

	 jQuery("#acceptModalButtonId").unbind();

		

	 jQuery('#acceptModalButtonId').click(function(){

			

		 var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/removeprofilegoalshout/userid/<?php echo $session->currentUser->user_id;?>/id/'+id;

			jQuery('#goalshoutId').html('Loading...'); 

			jQuery('#goalshoutId').load(url ,'' , function (){

				jQuery('#messageConfirmationTextId').html('Your goalshout has been deleted.');

				jQuery('#acceptModalButtonId').hide();

				jQuery('#cancelModalButtonId').attr('value','Close');

				jQuery('#messageConfirmationId').animate({opacity: '+=0'}, 2500).jqmHide();

			});

			 

	  });	

}



function reportAbuse(id , reportTo){

	

	jQuery('#reportTypeId').val('0');

	jQuery('#textReportAbuseId').val(''); 

	jQuery('#textReportAbuseId').attr('disabled','disabled');

	jQuery('#reportAbuseBodyId').show();

	jQuery('#reportAbuseBodyResponseId').hide();

	jQuery('#acceptReportAbuseButtonId').show(); 

	jQuery('#cancelReportAbuseButtonId').attr('value','Cancel'); 	

	jQuery('#reportAbuseTitleId').html('REPORT GOALSHOUT ABUSE?');

	jQuery('#reportAbuseTextId').html('Are you sure you want to report abuse in this goalshout?');	



	jQuery('#reportAbuseModal').jqm({trigger: '#reportAbuseUserTrigger', onHide: closeModal ,modal:true});

	jQuery('#reportAbuseModal').jqmShow();

	

	jQuery("#acceptReportAbuseButtonId").unbind();

	jQuery('#acceptReportAbuseButtonId').click(function() {



		var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/reportabuse';

		var userid = '<?php echo $session->currentUser->user_id;?>';

		var dataReport = jQuery('#textReportAbuseId').val();

		var reportType = jQuery('#reportTypeId').val();



		jQuery('#goalshoutId').load(url ,{userid :userid , id : id ,reportTo : reportTo, dataReport : dataReport ,reportType:reportType} , function (){

			jQuery('#reportAbuseBodyResponseId').html('Your report will be reviewed by our administrators soon.');

			jQuery('#reportAbuseBodyId').hide();

			jQuery('#reportAbuseBodyResponseId').show();

			jQuery('#acceptReportAbuseButtonId').hide();

			jQuery('#cancelReportAbuseButtonId').attr('value','Close');

			jQuery('#reportAbuseModal').animate({opacity: '+=0'}, 2500).jqmHide();

		});

	});	

 }

function forwardToFriend(){



		var k = jQuery('div.ErrorMessageIndividualDisplay');

	    for(var i=0;i < k.length;i++ ){ 

	       k[i].className ='ErrorMessageIndividual';

	    }	

		jQuery('#sendEmailModalForm')[0].reset();             // this resets form variables to their initial values (must be set in form fields)...

		

		

		jQuery('#labeltoId').hide();

		jQuery('#sendEmailto').show();

		jQuery('#sendEmailBodyId').show();

		jQuery('#sendEmailBodyResponseId').hide();

		jQuery('#acceptSendEmailButtonId').show(); 	

		jQuery('#sendEmailTitleId').text('Forward to a Friend');

		jQuery('#sendEmailto').val("");

		jQuery('#sendEmailto').attr('required','email');

		jQuery('#sendEmailtextForwardFriend').html("Check this link: http://<?php echo ($_SERVER['SERVER_NAME'] . Zend_Registry::get ( 'contextPath' ) ."/profiles/" . urlencode($session->currentUser->screen_name))?>");

		

		jQuery('#sendEmailModal').jqm({trigger: '#forwardToFriendTrigger', onHide: closeModal ,modal:true});

		jQuery('#sendEmailModal').jqmShow();

		

		jQuery('#acceptSendEmailButtonId').click(function() {



			valid = validaNewForm('sendEmailModalForm');

			if(valid){

		 		

				var to = jQuery('#sendEmailto').val();

				var subject = jQuery('#sendEmailsubject').val();

				var message = jQuery('#sendEmailtextForwardFriend').val();

				

				jQuery.ajax({

					type: 'POST',

					data: ({to :to , subject : subject , message : message }),

					url : '<?php echo Zend_Registry::get("contextPath"); ?>/profile/forwardtofriend',

					success: function(data){

						jQuery('#sendEmailBodyResponseId').html('<?php echo $session->currentUser->screen_name;?> profile has been forwarded to your friend(s).');

						jQuery('#sendEmailBodyId').hide();

						jQuery('#sendEmailBodyResponseId').show();

						jQuery('#acceptSendEmailButtonId').hide();

						jQuery('#cancelSendEmailButtonId').attr('value','Close');

						jQuery('#sendEmailModal').animate({opacity: '+=0'}, 2500).jqmHide();

					}	

				})

			}

		});

		



	}



</script>



	<div class="img-shadow-gray">

                        <div class="WrapperForDropShadow-gray">

                            <div id="Feedback" class="MiniProfile">

                            	<?php if ($session->isMyProfile == 'y'){ ?>

                            	<h5><a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"profileinfo");?>"> Edit Profile </a></h5>

                            	

                                Views <strong><?php echo $session->currentUser->views; ?></strong>

                                |

                                High Fives <strong><?php echo $session->currentUser->highfives; ?></strong>

                                <br>

                                <?php } ?>

                                <!--|

                                Gooal Shouts <strong>12</strong> -->



                                <?php if ($session->fbuser == null) { ?>

										<?php if ($session->isMyProfile == 'y'){?>

											<?php if($common->startsWith($session->mainPhoto , '<img')){ ?>

													<?php echo $session->mainPhoto;?>

													<img style="width:9px; height:9px; position: absolute; bottom: 0px; right: 0px" src="http://cdn.tripadvisor.com/img2/facebook/f_21x21.gif">

											<?php }else {?>

													<img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/utility/imagecrop?w=180&h=180&zc=1&src=<?php echo Zend_Registry::get("contextPath"); ?><?php echo $root_crop; ?>/photos/<?php echo ($session->mainPhoto !=null?$session->mainPhoto :'ProfileMale.gif');?>" />

											<?php }?>
											
											<ul class="ulGradientBoxes GradientBlue">
	                        					<li><a href="<?php echo $urlGen->getEditProfilePage($session->screenName,True,"photo");?>">Manage Profile Photo</a></li>
	                        				</ul>

               								<h5>Message Center</h5>

										<?php }else { ?>

											<?php if ($session->currentUser->main_photo != null){?>

												<img width="180" height="180" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/photos/<?php echo $session->currentUser->main_photo; ?>" />

											<?php } else {?>

												<img width="180" height="180" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ProfileMale.gif" />      

											<?php } ?>
											
										<?php } ?>

								<?php } else {  ?>
								  		
										<?php $result = $session->fbuser; ?>
										
										<img width="50" height="50" src="https://graph.facebook.com/<?php echo $result['id'];?>/picture" style="position:relative; left:60px; top:7px;">
											
								<?php } ?>

                       			<?php if ($session->isMyProfile == 'n'){ ?>				

                       			<div class="OnlineNow">

		                                   <?php if ($this->isOnline == 'true'){?>

		                                    Online Now

		                                    <?php }else {?>

		                                    Offline

		                                    <?php }?>

                                </div>

                                <?php } ?>				

                                <ul class="ulGradientBoxes GradientBlue">

                									<?php if ($session->isMyProfile == 'y'){?>

                									<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/compose">Compose Message</a></li>

                									<li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter/">Inbox</a><span><?php echo ($session->newMessages > 0? '<span style="color: red; font-size: 9px; text-decoration: none; vertical-align: top;"> New!</span>':''); ?></span></li>

                									<?php /*?><li><a href="<?php echo Zend_Registry::get("contextPath"); ?>/messagecenter/request">Invites &amp; Requests</a><span><?php echo($this->cantReq >0 ?'( '.$this->cantReq.' )':""); ?></span></li>  */?>

                									<?php } ?>

                									<?php if ($session->isMyProfile == 'n'){?>

                									<li>Rate Profile:

                									<div id="newsRater2" class="stat">

						                    			<div class="statVal">

						                    			<span class="ui-rater">

						                    				<span class="ui-rater-starsOff" style="width:90px;"><span class="ui-rater-starsOn" style="width:<?php echo (18*$session->rating);?>px"></span></span>

						                    				<span class="ui-rater-rating"><?php echo $session->rating;?></span>&#160;(<span class="ui-rater-rateCount"><?php echo $session->totalVotes;?></span>)

						                    			</span>

						                            	</div>

						                       		 </div>

				                                    </li>

                									<li><a id="highFiveButtonTrigger" href="#">Give a High Five</a></li>

                									<!--li><a id="sendGoalShoutTrigger" class="jqmDialog" href="#">Send a Goooal Shout</a></li-->

                									<li class="last"><a href="<?php echo Zend_Registry::get("contextPath"); ?>/compose/<?php echo $this->currentUser->screen_name; ?>">Send Private Message</a></li>

													</ul>									

													<ul class="ulGradientBoxes GradientGray">	

														<?php if ($session->isMyFriend == 'false'){?>

                									<li id="add"><a id="addToFriendTrigger"  href="#">Add to Friends</a></li>

                									<li id="remove" class="closeDiv"><a id="removeFromFriendsTrigger" href="#" >Remove from Friends</a></li>        	 

            										<?php }else { ?>

            										<li id="add" class="closeDiv"><a id="addToFriendTrigger"  href="#">Add to Friends</a></li>

                									<li id="remove"><a id="removeFromFriendsTrigger" href="#" >Remove from Friends</a></li>

                									<?php } ?>

														<!--li>Add to Favorities</li-->

														<!-- li>Invite to Group</li-->

														<li><a id="forwardToFriendTrigger" href="#">Forward to Friend</a></li>

														<li><a id="blockUserTrigger" href="#">Block This User</a></li>

														<li><a id="reportAbuseUserTrigger" href="#">Report This User</a></li>

													</ul>				                									

                								<?php } ?>

                                

                                    <?php if ($session->isMyProfile == 'y'){?>

                                    <p>

                                    	<strong>Share your Goalface profile                                	

                                    	WebAddress</strong>

                                    	

                                    	<input type="text" readonly value="http://<?php echo $_SERVER['SERVER_NAME']; ?>/profiles/<?php echo $session->currentUser->screen_name; ?>">

                                    </p>

                                    <?php } ?>

                                <!-- Phase II  <p>

                								<	<strong>RSS</strong>

                                	

                                	[URL]

                                </p>-->

                            </div>

                        </div>

                    </div>

			

                    <div class="img-shadow-gray">

                        <div class="WrapperForDropShadow-gray">

                            <?php echo $this->render('include/navigationProfile.php');?>

                        </div>

                    </div>
