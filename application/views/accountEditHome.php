<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.multiSelect.js" type="text/javascript"></script>
<script src="<?php echo Zend_Registry::get("contextPath"); ?>/public/scripts/jquery.bgiframe.min.js" type="text/javascript"></script>
<link href='<?php echo Zend_Registry::get("contextPath"); ?>/public/styles/jquery.multiSelect.css' rel="stylesheet" type="text/css" media="screen"/>
<?php $session = new Zend_Session_Namespace("userSession"); ?>
<?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>
  <script type="text/JavaScript">
 
  jQuery(document).ready(function() {
	  
		//Suspend Account
		jQuery('#suspendAccountbuttonid').click(function(){
			popUpSuspendAccount('Suspend your account','Remember, you can reactive at any time by logging in with your email and password, but to do so you must have access to your current login email address.')
		});
		
		//Acordeon My Account
		$.each($('#tab1content>.divSlide'), function() {
			$(this).click(function(){
				if($("#"+$(this).attr('id')+"+div").css('display')=='none'){
					$($(this).find('img')[0]).removeClass('rot180');
					$($(this).find('img')[0]).addClass('rot0');
				}else{
					$($(this).find('img')[0]).removeClass('rot0');
					$($(this).find('img')[0]).addClass('rot180');
				}
				$("#"+$(this).attr('id')+"+div").slideToggle("slow");
			});
		});

		//Dirty Form
		/*----------------------------------------*/
		initFrmDirty("frm_gfnotifications","input[type='text'],#frm_gfnotifications input[type='checkbox'],#frm_gfnotifications input[type='radio'],#frm_gfnotifications select");
		
		function initFrmDirty(idElemento,selectores){
			$("#"+idElemento).addClass("FrmValidarDirty");
			$.each($("#"+idElemento+" "+selectores), function() {
				if($(this).attr("type")=="text" || this.tagName=="SELECT"){
					$(this).data('initial_value', $(this).attr("value"));
					$(this).change(function(){
						validarDirty(this);
					})
				}else if($(this).attr("type")=="checkbox" || $(this).attr("type")=="radio"){
					$(this).data('initial_value', $(this).is(':checked'));
					$(this).click(function(){
						validarDirty(this);
					})
				}else if(this.tagName=="SELECT"){
					$(this).data('initial_value', $(this).val())
					$(this).change(function(){
						validarDirty(this);
					})
				}			
			});
			
			$.each($("a:not(a.multiSelect, #pop-up-Alternatives a)"), function() {
				$(this).click(function(){
					return frmIsDirty(this)
				})
			});
			
		}
		
		function validarDirty(elemento){
			var valor
			if($(elemento).attr("type")=="text"){
				valor=$(elemento).attr("value");
			}else if($(elemento).attr("type")=="checkbox"){
				valor=$(elemento).is(':checked');
			}else if ($(elemento).attr("type")=="radio"){
				valor=$(elemento).is(':checked');
				var nombre=$(elemento).attr("name");
				$.each($("input[name='"+nombre+"']"), function() {
					$(this).removeClass("isDirty");
				})
			}else if(elemento.tagName=="SELECT"){
				valor=$(elemento).val();
			}
			if($(elemento).data('initial_value')!=valor){
				$(elemento).addClass("isDirty");
			}else{
				$(elemento).removeClass("isDirty");
			}
		}

		function frmRefreshDiry(){
			$.each($(".FrmValidarDirty input[type='text'].isDirty,.FrmValidarDirty input[type='checkbox'].isDirty,.FrmValidarDirty input[type='radio'].isDirty,.FrmValidarDirty select.isDirty"), function() {
				$(this).removeClass("isDirty");
			});
			initFrmDirty("frm_gfnotifications","input[type='text'],input[type='checkbox'],input[type='radio'],select");
		}
		
		function frmIsDirty(elemento){
			var cantDirty=$(".FrmValidarDirty input[type='text'].isDirty,.FrmValidarDirty input[type='checkbox'].isDirty,.FrmValidarDirty input[type='radio'].isDirty,.FrmValidarDirty select.isDirty")
			if(cantDirty.length>0){
				/*Show Pop-up*/
				popUpConfirmationSaveAlerts(elemento);
				return false;
				/*-----------*/
			}else{
				return true;
			}
		}		
		
		//multiselect combos
	   jQuery("select[name^='playerUpdateType']").each(	
			   function() 
				{ 
				   jQuery(this).multiSelect({ oneOrMoreSelected: '*' });
				}
			);	   
	  		
	  //Tabs - When page loads...
		$(".regional").hide(); 
		//Hide all content
		if(document.location.hash!='') {
            $("#tab2").addClass("active").show();
            $("#tab2content").show();
        } else {
			<?php if($session->fbuser==null){?>
            $(".tabs ul li:first").addClass("active").show(); //Activate first tab
            $(".regional:first").show(); //Show first tab content
			<?php } else{ ?>
			$("#tab2").addClass("active").show();
            $("#tab2content").show();
			<?php } ?>
        }
		
		//Tabs - On Click Event
		$(".tabs ul li").click(function() {
			//alert('hello');
			$(".tabs ul li").removeClass("active"); //Remove any "active" class
			$(this).addClass("active"); //Add "active" class to selected tab
			$(".regional").hide(); //Hide all tab content

			//Find the id attribute value to identify the active tab 
			activeTab = jQuery(this).attr('id');
	        //show div content
	        jQuery('#' + activeTab + 'content').fadeIn();
			return false;
		});

	   jQuery('#cancelbuttonid').click(function(){
			top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/index';
		});		

	   

		
		jQuery('#savebuttonid').click(function(){

			valid = validaNewForm('join');
		    if(!valid){
		    	jQuery('#ErrorMessagesAccountSettings').removeClass('ErrorMessages').addClass('ErrorMessagesDisplay');
			    jQuery('#MainErrorMessageAccountSettings').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
		    }


			var pass1 =  jQuery.trim(jQuery('#newpasswordid').val()); 
			var pass2 =  jQuery.trim(jQuery('#confirmnewpasswordId').val()); 
		  	var valid2 = true;
			var email1 = jQuery.trim(jQuery('#emailaddressid').val()); 
			var email2 = jQuery.trim(jQuery('#confirmemailaddressid').val()); 
			
		
		  	
		  	if(email1!= email2){
				jQuery('#cemailerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
			    jQuery('#cemailerror').html('The emails entered do not match. Please try again.');
		    	valid2 = false;
		  	}
		  	if(pass1!= pass2){
		    	jQuery('#confirmnewpassworderror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
			    jQuery('#confirmnewpassworderror').html('The passwords you entered do not match. Please try again..');
		    	valid2 = false;
		  	}
		  	if(!valid2){
		    	jQuery('#ErrorMessagesAccountSettings').removeClass('ErrorMessages').addClass('ErrorMessagesDisplay');
			    jQuery('#MainErrorMessageAccountSettings').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
		    	return;
		  	}

			
			jQuery.ajax({
		           type: 'POST',
		           url : '<?php echo Zend_Registry::get("contextPath"); ?>/profile/updateaccountsettings',
		           data: jQuery("#join").serialize(),
		           success: function (text) {
		           		    if(text != ''){
			           		    if(text!= 'ok'){
			           		    	jQuery('#ErrorMessagesAccountSettings').removeClass('ErrorMessages').addClass('ErrorMessagesDisplay');
			           		    	jQuery('#MainErrorMessageAccountSettings').html(text);
			           		    }else {
									jQuery('#ErrorMessagesAccountSettings').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplay').addClass('SuccessMessagesDisplay');
			           		    	jQuery('#MainErrorMessageAccountSettings').html('Your Account Settings were updated successfully');
								}
		 					}
		 			}
		     });	

			
		});

        jQuery('#cancelAlertsButtonId').click(function(){
			if(isMessageConfirmation==false){
				var cantDirty=$(".FrmValidarDirty input[type='text'].isDirty,.FrmValidarDirty input[type='checkbox'].isDirty,.FrmValidarDirty input[type='radio'].isDirty,.FrmValidarDirty select.isDirty")
				if(cantDirty.length==0){
					/*Show Pop-up*/
					popUpMessageInformation('You have not made changes.');
				}else{
					popUpMessageConfirmation('#cancelAlertsButtonId','Confirmation','Are you sure you want to cancel the changes you have made to your Updates & Alerts Settings?','Yes, Cancel',"No, Don't Cancel")
				}
			}else{
				isMessageConfirmation=false;
				top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/editprofile/<?php echo $session->screenName; ?>/settings'
			}
        });

        
        //SAVE ALERTS

        jQuery('#saveAlertsButtonId').click(function(){
			//var isAlertPlayerValido=valOptionsAlertsPlayers();
			if(valOptionsAlertsPlayers()){			
				var cantDirty=$(".FrmValidarDirty input[type='text'].isDirty,.FrmValidarDirty input[type='checkbox'].isDirty,.FrmValidarDirty input[type='radio'].isDirty,.FrmValidarDirty select.isDirty")
				if(cantDirty.length==0){
					/*Show Pop-up*/
					popUpMessageInformation('You have not made changes.');
				}else{
					if(isConfirmationSaveAlerts==false){
						//popUpMessageInformation('Successful recording.');
						popUpLoading('Loading...');
					}
					
					var query_string = '';
					
					//GoalFace User Alerts
					if(jQuery("input[name='checkEmailPrivateMessagesAlert']").is(':checked')){
						var emailPrivateMessagesFrecuency = jQuery("input[name='radioPrivateMessages']:checked").val();
						query_string +="&emailPrivateMessages=1&emailPrivateMessagesFrecuency="+emailPrivateMessagesFrecuency;
					}
					if(jQuery("input[name='checkEmailFriendInvitesAlert']").is(':checked')){
						var emailFriendInvitesFrecuency = jQuery("input[name='radioFriendInvites']:checked").val();
						query_string +="&emailFriendInvites=1&emailFriendInvitesFrecuency="+emailFriendInvitesFrecuency;
					}
					if(jQuery("input[name='checkEmailGoalShoutsAlert']").is(':checked')){
						var emailGoalShoutsFrecuency = jQuery("input[name='radioGoalShouts']:checked").val();
						query_string +="&emailGoalShouts=1&emailGoalShoutsFrecuency="+emailGoalShoutsFrecuency;
					}
					if(jQuery("input[name='checkPostCommentsAlert']").is(':checked')){
						var emailPostCommentsFrecuency = jQuery("input[name='radioComments']:checked").val();
						query_string +="&emailPostComments=1&emailPostCommentsFrecuency="+emailPostCommentsFrecuency;
					}
					if(jQuery("input[name='checkEmailFriendActivitiesAlert']").is(':checked')){
						var emailFriendActivitiesFrecuency = jQuery("input[name='radioFriendActivities']:checked").val();
						query_string +="&emailFriendActivities=1&emailFriendActivitiesFrecuency="+emailFriendActivitiesFrecuency;
					}
					//alert(query_string);

					//-----------------------//
					//--    mvillanuevaa   --//
					//-----------------------//
					var temp = '';
					//competitions alert email
					jQuery("input[name='leagueSendByEmailFlag']").each(
						function()
						{
							//if(jQuery(this).is(':checked')){
								temp = jQuery('#leagueUpdateType'+this.value).val();
								query_string += "&leagueSendByEmailFlag[]=" + this.value + "_" + temp + "_" + (jQuery(this).is(':checked')?'1':'0');
							//}
						}
					);

					//competitions alert facebook
					jQuery("input[name='leagueSendByFaceBookFlag']").each(
						function()
						{
							//if(jQuery(this).is(':checked')){
								temp = jQuery('#leagueUpdateType'+this.value).val();
								query_string += "&leagueSendByFaceBookFlag[]=" + this.value + "_" + temp + "_" + (jQuery(this).is(':checked')?'1':'0');
							//}
						}
					);

					//teams alert email
					jQuery("input[name='teamSendByEmailFlag']").each(
						function()
						{
							//if(jQuery(this).is(':checked')){
								temp = jQuery('#teamUpdateType'+this.value).val();
								query_string += "&teamSendByEmailFlag[]=" + this.value + "_" + temp + "_" + (jQuery(this).is(':checked')?'1':'0');
							//}
						}
					);

					//teams alert FaceBook
					jQuery("input[name='teamSendByFaceBookFlag']").each(
						function()
						{
							//if(jQuery(this).is(':checked')){
								temp = jQuery('#teamUpdateType'+this.value).val();
								query_string += "&teamSendByFaceBookFlag[]=" + this.value + "_" + temp + "_" + (jQuery(this).is(':checked')?'1':'0');
							//}
						}
					);
					
					//player alert email
					jQuery("input[name='playerSendByEmailFlag']").each(
						function()
						{
							//if(jQuery(this).is(':checked')){

								var arrayId = 'playerUpdateType'+this.value+'[]';
								var selectedGroups  = new Array();
								jQuery("input[name='"+arrayId+"']:checked").each(function() {
									selectedGroups.push(jQuery(this).val());
								});

								//alert(selectedGroups);
								query_string += "&playerSendByEmailFlag[]=" + this.value + "_" + selectedGroups + "_" + (jQuery(this).is(':checked')?'1':'0');
							//}
						}
					);

					//player alert facebook
					jQuery("input[name='playerSendByFaceBookFlag']").each(
						function()
						{
							//if(jQuery(this).is(':checked')){

								var arrayId = 'playerUpdateType'+this.value+'[]';
								var selectedGroups  = new Array();
								jQuery("input[name='"+arrayId+"']:checked").each(function() {
									selectedGroups.push(jQuery(this).val());
								});
								query_string += "&playerSendByFaceBookFlag[]=" + this.value + "_" + selectedGroups + "_" + (jQuery(this).is(':checked')?'1':'0');
							//}
						}
					);

					var url = '<?php echo Zend_Registry::get("contextPath"); ?>/profile/savealerts';
					
					jQuery.ajax({
						type: 'POST',
						url : url,
						data : "id=1" + query_string ,
						success: function (text) {
							//alert("isConfirmationSaveAlert:"+isConfirmationSaveAlerts)
							if(isConfirmationSaveAlerts==false){
								frmRefreshDiry();
								//popUpMessageInformation('Successful recording.');
								jQuery('#imageLoadingId').css('display','none');
								jQuery('#messageLoadingTextId').html('Successful recording.');
								jQuery("#acceptModalButtonLoadingId").unbind();

								jQuery('#acceptModalButtonLoadingId').click(function(){
									jQuery('#closeModalLoadingId').click();
								});

							}else{
								isConfirmationSaveAlerts=false;
								if(href_==""){
									var searchText = escape(jQuery('#search-query').val());
									window.location = "<?php echo Zend_Registry::get('contextPath'); ?>/search/?q="+searchText;
								}else{
									window.location = href_;
									href_="";
								}
							}
						}
					});
				}
			
			}else{
				popUpMessageInformation('You can not save until you have selected an update type.');
			}
        });

		valOptionsAlertsPlayers();

		
    });//END Jquery Body

	function updateBasicInfo(){
		  valid = validateForm('BasicInformation');
		  if(!valid){
		    return;
		  }

		  jQuery.ajax({
				type: 'POST',
				data: jQuery("#BasicInformation").serialize(),
				url: '<?php echo Zend_Registry::get("contextPath"); ?>/profile/updateuserbasicinfo',
				success: function(data){
					 jQuery('#ErrorMessages').html("Update was successfull");
		    		 jQuery('#ErrorMessages').addClass('ErrorMessagesDisplay ErrorMessagesDisplayBlue');
		    		 //jQuery('#ErrorMessages').show("slow");
					 //jQuery('#ErrorMessages').animate({opacity: '+=0'}, 2000).slideUp('slow');
		    		 top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/profiles/<?php echo $session->screenName?>';
		    		 
				}	
			});
		}

    //Remove Competitions

	function removefavorite(name,id ,type){
		
        jQuery('#modalBodyId').show();
        jQuery('#modalBodyResponseId').hide();
        jQuery('#acceptFavoriteModalButtonId').show();
        jQuery('#cancelFavoriteModalButtonId').attr('value','Cancel');
		var url = null; 
		
        if(type == 'comp'){
        	jQuery('#modalFavoriteTitleId').html('Remove '+ name +' as your favorite Competition?');
	        jQuery('#dataText1').html('Remove Competition');
	        url = '<?php echo Zend_Registry::get("contextPath"); ?>/competitions/removefavorite' ;
		    htmlResponse = 'Competition '+ name + ' has been removed from your favorite competitions.';
		}else if(type == 'team'){
        	jQuery('#modalFavoriteTitleId').html('Remove '+ name +' as your favorite Team?');
	        jQuery('#dataText1').html('Remove Team');
	        url = '<?php echo Zend_Registry::get("contextPath"); ?>/team/removefavorite' ;
		    htmlResponse = 'Team '+ name + ' has been removed from your favorite teams.';
        }else if(type == 'player'){
        	jQuery('#modalFavoriteTitleId').html('Remove '+ name +' as your favorite Player?');
	        jQuery('#dataText1').html('Remove Player');
	        url = '<?php echo Zend_Registry::get("contextPath"); ?>/player/removefavorite' ;
		    htmlResponse = 'Player '+ name + ' has been removed from your favorite players.';
        }        
        jQuery('#addFavoriteModal').jqm({trigger: '#removefromfavoritecompetitiontrigger', onHide: closeModal });
        
        jQuery('#addFavoriteModal').jqmShow();

		//jQuery('#favoriteImageSrcId').attr('src',favoriteImage);

		 var leagueId = +id+'*';
		 jQuery("#acceptFavoriteModalButtonId").unbind();
		 jQuery('#acceptFavoriteModalButtonId').click(function(){
		 if(type == 'comp'){
			 jQuery.ajax({
					type: 'POST',
					url :  url,
					data : ({leagueId:leagueId }),
					success: function(data){
						jQuery('#modalBodyResponseId').html(htmlResponse);
						jQuery('#modalBodyId').hide();
						jQuery('#modalBodyResponseId').show();
						jQuery('#acceptFavoriteModalButtonId').hide();
						jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
						jQuery('#remove').hide();
					 	jQuery('#favorite').show();
					 	jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
					 	top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/editprofile/<?php echo $session->screenName?>/settings';
					}	
				});
		 }else if(type == 'team'){
			 jQuery.ajax({
					type: 'POST',
					url :  url,
					data : ({teamId:id }),
					success: function(data){
						jQuery('#modalBodyResponseId').html(htmlResponse);
						jQuery('#modalBodyId').hide();
						jQuery('#modalBodyResponseId').show();
						jQuery('#acceptFavoriteModalButtonId').hide();
						jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
						jQuery('#remove').hide();
					 	jQuery('#favorite').show();
					 	jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
					 	top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/editprofile/<?php echo $session->screenName?>/settings';
					}	
				});
		 }else if(type == 'player'){
			 jQuery.ajax({
					type: 'POST',
					url :  url,
					data : ({id:id }),
					success: function(data){
						jQuery('#modalBodyResponseId').html(htmlResponse);
						jQuery('#modalBodyId').hide();
						jQuery('#modalBodyResponseId').show();
						jQuery('#acceptFavoriteModalButtonId').hide();
						jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
						jQuery('#remove').hide();
					 	jQuery('#favorite').show();
					 	jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
					 	top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/editprofile/<?php echo $session->screenName?>/settings';
					}	
				});
		 }

		});	
	}	

	function valCheckAlerts(checkbox){
		var opcionesAlertas=((checkbox.parentNode.parentNode.childNodes[13]).childNodes[1]).childNodes[1];
		//alert("cant ob:"+((checkbox.parentNode.parentNode.childNodes[13]).childNodes[1]).childNodes[1].length)
		
		var validacion=true
		if((checkbox.parentNode.parentNode.childNodes[7]).childNodes.length>0){
			validacion=$((checkbox.parentNode.parentNode.childNodes[7]).childNodes[1]).is(':checked')
		}
		//alert($((checkbox.parentNode.parentNode.childNodes[3]).childNodes[2]).is(':checked')+"-"+validacion)
		if($((checkbox.parentNode.parentNode.childNodes[3]).childNodes[1]).is(':checked') || 
			$((checkbox.parentNode.parentNode.childNodes[3]).childNodes[2]).is(':checked') || validacion){
			$(opcionesAlertas).removeAttr("disabled")
		}else{
			$(opcionesAlertas).attr("disabled",true)
			$(opcionesAlertas).removeClass("isDirty")
			$(((checkbox.parentNode.parentNode.childNodes[13]).childNodes[1]).childNodes[1].childNodes[1]).attr("selected",true)
		}
	}
	
	function valCheckAlertsPlayers(checkbox){
		var opcionesAlertas=(checkbox.parentNode.parentNode.childNodes[13]).childNodes[2];
		
		var validacion=true
		if((checkbox.parentNode.parentNode.childNodes[7]).childNodes.length>0){
			validacion=$((checkbox.parentNode.parentNode.childNodes[7]).childNodes[1]).is(':checked')
		}
		
		if($((checkbox.parentNode.parentNode.childNodes[3]).childNodes[1]).is(':checked') || validacion){
			$(opcionesAlertas).children().each(function(){
				$(this).children().each(function(){
					$(this).attr("disabled",false)
				});
			});
			$((checkbox.parentNode.parentNode.childNodes[13]).childNodes[1]).css("color","01395B")
		}else{
			$(opcionesAlertas).children().each(function(){
				
				$(this).children().each(function(){
					$(this).removeAttr("checked")
					$(this).attr("disabled",true)
				});
			});
			$((checkbox.parentNode.parentNode.childNodes[13]).childNodes[1]).html("<span style='width:129px; display: inline-block; margin: 1px 0 1px 3px;overflow: hidden;white-space: nowrap;'>Select options</span>")
			$((checkbox.parentNode.parentNode.childNodes[13]).childNodes[1]).css({"color":"#01395B","width":"132px"})
		}
	}
	
	function valOptionsAlertsPlayers(){
		var isOk=true
		var isOkAux=true
		var checks=false
		var cant=0
		var cantTD=0;
		//alert($('div.multiSelectOptions').parent().parent().children('tr').length)
		$('div.multiSelectOptions').parent().parent().children().each(function(){
			checks=false
			cantTD++
			//alert("ab"+this.tagName)
			if($(this).children("input[type='checkbox']").length>0){
				$(this).children("input[type='checkbox']").each(function(){
					cant+=$(this).is(':checked')?1:0
				});
			}
			if(cantTD==6 && cant>0){
				isOk=false
				$(this).children('div').children().each(function(){
					//alert($(this).is(':checked')?1:0)
					$(this).children().each(function(){
						//alert(this.tagName)
						if($(this).is(':checked')){
							isOk=true
						}
					});
				});
				
				//alert(this.parentNode.tagName+"-"+isOk+"-"+cant)
				if(isOk){
					$(this.parentNode.childNodes[13].childNodes[1]).css('color','#01395B')
				}else{
					$(this.parentNode.childNodes[13].childNodes[1]).css('color','red')
				}
				
				isOkAux=(isOkAux && isOk)
			}else if(cantTD==6 && cant==0){
				//alert("->"+this.parentNode.tagName+"-"+isOk+"-"+cant)
				$(this.parentNode.childNodes[13].childNodes[1]).css('color','#01395B')
				$(this).children('div').children().each(function(){
					//alert($(this).is(':checked')?1:0)
					$(this).children().each(function(){
						//alert(this.tagName)
						$(this).removeAttr("checked")
						$(this).attr("disabled",true)
					});
				});
			}
			if(cantTD==6){
				cant=0
				cantTD=0
			}
		});
		//alert("IsOk"+isOkAux)
		return isOkAux
	}
	
   
 </script> 
 <style>
	.rot0 {
		-webkit-transform: rotate(0deg);
		-moz-transform: rotate(0deg);
		rotation: 0deg;
		filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=0);
	}
	.rot180 {
		-webkit-transform: rotate(180deg);
		-moz-transform: rotate(180deg);
		rotation: 180deg;
		filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
	}
 </style>
  
      <div id="ContentWrapper" class="TwoColumnLayout">

          <div class="FirstColumnOfThree">
          <?php 
              $session = new Zend_Session_Namespace('userSession');
          ?> 
          	<!-- START Profile Box Include -->
                <?php echo $this->render('include/miniProfilePlusLoginBox.php'); ?>
              <!-- END Profile Box Include -->	
              
             
            </div><!--/FirstColumn-->
           
           
           <div id="SecondColumnFullHome" class="SecondColumnOfTwo">
	           	<div class="ammid">
	           		<div class="aleft">
						<h1>Account &amp; Settings </h1>
					
						<div class="tabs" id="tab_scores">
		                            <ul>
		                              	<?php if($session->fbuser==null){?>
				                        <li id="tab1" class=""><a href="javascript:void(0);">My Account</a></li>
										<?php } ?>
				                        <li id="tab2" class=""><a href="javascript:void(0);">Updates</a></li>
		                            </ul>
		                </div>
		                
		                <?php if($session->fbuser==null){?>
		                <div class="regional" id="tab1content">
							<div id="divPassword" class="divSlide" style="background: #729BB9; color: #FFFFFF; height: 20px; width: 98.7%; position:relative; top:-1px; padding: 4px 0px 0pt 10px; font-family: arial; font-weight: bold;">
								Password <img class="rot0" style="float:right; padding: 0 5px;" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/arrow_up.png">
							</div>
							<div style="background:#ffffff; border:1px solid #6F9BBC; border-top-width: 0;">
								<form id="join" name="register_form" method="POST" action="<?php echo Zend_Registry::get("contextPath"); ?>/profile/updateaccountsettings"> 
                    
		                    		<br>
		                    		<div id="systemWorking" style="display: none;" align="center"><img src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif">Logging...</div>
									<div id="ErrorMessagesAccountSettings" class="ErrorMessages">                   		
		                    		    <div id="MainErrorMessageAccountSettings">All Fields are marked with (*) are required.Missing Fields are highlighted below.</div>
			                     	</div>
			
						                  <fieldset class="FirstColumnOfTwo" id="joinFieldset">
						                  <div id="emailerror" class="ErrorMessageIndividual">You must enter an Email Address</div>
													    <label for="emailaddress">
															Email Address:
														  </label>
														  <input class="text" id="emailaddressid" name="email" value="<?php echo $session->email;?>" required="email" type="text">
														  <p></p>
														  
										  <div id="cemailerror" class="ErrorMessageIndividual">You must re-enter your Email Address</div>
						                  <label for="confirmemailaddress">
															
						                  Confirm Email:
														  </label>
														  <input class="text" id="confirmemailaddressid" name="cemail" value="<?php echo $session->email;?>" required="email" type="text">
														  <p></p>
										<div id="oldpassworderror" class="ErrorMessageIndividual">You must enter your current password</div>
														  <label for="password">
															
						                  Enter Current Password:
														  </label>
														  <input class="text" id="oldpasswordid" name="oldpassword" size="18" value="" required="min:6" type="password">
														   <p></p>
										  <div id="newpassworderror" class="ErrorMessageIndividual">You must enter your new password</div>
														  <label for="newpassword">
															
						                  Choose a New Password:
														  </label>
														   <input class="text" id="newpasswordid" name="newpassword" size="18" value="" required="min:6" type="password">
														   <br>
														  <p>Must have a minimum of 6 characters</p>
															<p></p>
											<div id="confirmnewpassworderror" class="ErrorMessageIndividual">You must re-enter your new password</div>				
										  				<label for="confirmnewpassword">
											Confirm New Password:
														  </label>
														   <input class="text" id="confirmnewpasswordId" name="confirmnewpassword" size="18" value="" required="min:6" type="password">
															<br>
														  <p>Must have a minimum of 6 characters</p>
						
						                  			<p></p>
						                  				<fieldset class="AddToPhotoButtonWrapper">
															<input class="submit GreenGradient" name="Register" id="savebuttonid" value="Save" type="button">
															<input class="submit GreenGradient" name="Register" id="cancelbuttonid" value="Cancel" type="button">
														</fieldset>		
													</fieldset>
													
										
										<br class="clearleft">
									
			                    </form>
							</div>	
								
							<div id="divSuspendAccount" class="divSlide" style="background: #729BB9; color: #FFFFFF; height: 20px; width: 98.7%; position:relative; top:-1px; padding: 4px 0px 0pt 10px; font-family: arial; font-weight: bold;">
								Suspend Account <img class="rot180" style="float:right; padding: 0 5px;" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/arrow_up.png">
							</div>
							<div style="background:#ffffff; border:1px solid #6F9BBC; border-top-width: 0; display:none; height:50px; padding: 15px 15px 20px">
								Suspending your account will disable your profile and remove your profile and all your activity on GoalFace.
								<fieldset class="AddToPhotoButtonWrapper">
									<input class="submit GreenGradient" name="Registerx" id="suspendAccountbuttonid" value="Suspend Account" type="button">
								</fieldset>		
							</div>
		                </div>
		                <?php } ?>
		                
		                <div class="regional" id="tab2content">
						  <div id="frm_gfnotifications">
					          <table cellspacing="0" cellpadding="5" border="0" class="table1">
							
								<tr bgcolor="#729BB9" style="color: #FFFFFF;">
									<td width="447"><strong>GoalFace Notifications</strong></td>
									<td width="41"><div align="center"><strong>Email </strong></div></td>
									<td width="82">&nbsp;</td>
									<td width="91">&nbsp;</td>
									<td width="89">&nbsp;</td>
									<td width="79"><div align="center"><strong>Real Time </strong></div></td>
									<td width="93"><div align="center"><strong>Daily</strong></div></td>
								</tr> 
								<tr bgcolor="#ffffff">
									<td width="447">Private Messages</td>
									<td width="41">
										<div align="center">
											<input type="checkbox" name="checkEmailPrivateMessagesAlert" <?php echo ($this->checkEmailPrivateMessagesAlert==1?'checked':'');?> id="checkPrivateMessagesAlert" value="1">
										</div>
									</td>
									<td width="82">&nbsp;</td>
									<td width="91">&nbsp;</td>
									<td width="89">&nbsp;</td>
									<td width="79"><div align="center">
									  <input type="radio" <?php echo ($this->emailPrivateMessagesFrecuency==1?'checked':'');?> name="radioPrivateMessages" value="1">
									</div></td>
									<td width="93"><div align="center">
									  <input type="radio" <?php echo ($this->emailPrivateMessagesFrecuency==2?'checked':'');?> name="radioPrivateMessages" value="2">
									</div></td>
								</tr>                                                        
								<tr bgcolor="#e6eff4">
									<td>Friend Invites</td>
									<td><div align="center">
										<input type="checkbox" name="checkEmailFriendInvitesAlert" <?php echo ($this->checkEmailFriendInvitesAlert==1?'checked':'');?> id="checkFriendInvitesAlert" value="1">
									</div></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><div align="center">
									  <input type="radio" <?php echo ($this->emailFriendInvitesFrecuency==1?'checked':'');?> name="radioFriendInvites" value="1">
							   </div></td>
									<td><div align="center">
									  <input type="radio" <?php echo ($this->emailFriendInvitesFrecuency==2?'checked':'');?> name="radioFriendInvites" value="2">
									</div></td>
								</tr>
								<tr bgcolor="#ffffff">
									<td width="447">Goooal Shouts</td>
									<td width="41"><div align="center">
										<input type="checkbox" name="checkEmailGoalShoutsAlert" <?php echo ($this->checkEmailGoalShoutsAlert==1?'checked':'');?> id="checkGoalShoutsAlert" value="1">
								  </div></td>
									<td width="82">&nbsp;</td>
									<td width="91">&nbsp;</td>
									<td width="89">&nbsp;</td>
									<td width="79"><div align="center">
									  <input type="radio" <?php echo ($this->emailGoalShoutsFrecuency==1?'checked':'');?> name="radioGoalShouts" value="1">
									</div></td>
									<td width="93"><div align="center">
									  <input type="radio" <?php echo ($this->emailGoalShoutsFrecuency==2?'checked':'');?> name="radioGoalShouts" value="2">
									</div></td>
								</tr>                                                        
								<tr bgcolor="#e6eff4">
									<td>Comments on your posts</td>
									<td><div align="center">
										<input type="checkbox" name="checkPostCommentsAlert" <?php echo ($this->checkPostCommentsAlert==1?'checked':'');?> id="checkPostCommentsAlert" value="1">
									</div></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><div align="center">
									  <input type="radio" <?php echo ($this->emailPostCommentsFrecuency==1?'checked':'');?> name="radioComments" value="1">
									</div></td>
									<td><div align="center">
									  <input type="radio" <?php echo ($this->emailPostCommentsFrecuency==2?'checked':'');?> name="radioComments" value="2">
									</div></td>
								</tr>
							<tr bgcolor="#ffffff">
									<td width="447">Friends Activity & Broadcasts</td>
									<td width="41"><div align="center">
										<input type="checkbox" name="checkEmailFriendActivitiesAlert" <?php echo ($this->checkEmailFriendActivitiesAlert==1?'checked':'');?> id="checkFriendActivitiesAlert" value="1">
								</div></td>
									<td width="82">&nbsp;</td>
									<td width="91">&nbsp;</td>
									<td width="89">&nbsp;</td>
									<td width="79"><div align="center">
									  <input type="radio" <?php echo ($this->emailFriendActivitiesFrecuency==1?'checked':'');?> name="radioFriendActivities" value="1">
								</div></td>
									<td width="93"><div align="center">
									  <input type="radio" <?php echo ($this->emailFriendActivitiesFrecuency==2?'checked':'');?> name="radioFriendActivities" value="2">
								</div></td>
							<tr bgcolor="#729BB9" style="color: #FFFFFF;">
	                            <td colspan="7"><strong>Favorities Updates</strong></td>
	                        </tr>
	                        <tr bgcolor="#777777" style="color: #FFFFFF;">
	                            <td width="447"><strong>Leagues And Tournaments </strong></td>
	                            <td width="41"><div align="center"><strong>Email </strong></div></td>
								 <!--  Testing only in PROD for Gakau, chocheraz, kokus and Miguel -->           
								 <?php 
									$idFB=cFacebook::getIDUserFacebook();
									$enabledFacebook=(($session->screenName == 'JohnGakau' OR $idFB='1478354679' OR $session->screenName == 'FCkokus' OR $idFB='500033921' OR $session->screenName == 'chocheraz' OR $idFB='513690455' OR $session->screenName == 'publicm27a' OR $idFB='100003739305174')?true:false);
									
									if ($enabledFacebook) { ?>           
										<td width="82">
										<?php if ($session->user->facebookid != null) { ?>
											<div align="center"><strong>FaceBook </strong></div>
										<?php } ?>
											</td>
								<?php } ?>	
	                            <td width="91">&nbsp;</td>
	                            <td width="89">&nbsp;</td>
	                            <td colspan="2"><div align="center"><strong>Update Type </strong></div>
	                                <div align="center"></div></td>
	                        </tr>

                        <?php
                        $i = 1;
                        foreach ($this->userleague as $data) {
							$isDisabled=($data['alert_email'] != 0 || $data['alert_facebook'] != 0?'':'disabled');
                            if ($i % 2 == 1) {
                                $style = "#ffffff";
                            } else {
                                $style = "#e6eff4";
                            }
                        ?>
                            <tr bgcolor="<?php echo $style; ?>">
                                <td width="447"><?php echo $data['competition_name'] ?>
                                    <img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/12-em-cross.png"/>&nbsp;
                                    <input type="hidden" name="compid" value="<?php echo $data['competition_id'] ?>"/>
                                    <a href="javascript:removefavorite('<?php echo $data['competition_name'] ?>',<?php echo $data['competition_id'] ?>,'comp')">Remove</a>
                                </td>
                                <td width="41" style="text-align:center;"><!-- habilitarOpcionesAlertas(this) -->
                                    <input onclick="valCheckAlerts(this)" type="checkbox" name="leagueSendByEmailFlag" <?php echo ($data['alert_email'] != 0 ? 'checked' : ''); ?> value="<?php echo $data['competition_id'] ?>">
                                </td>
								
                            <!--  Testing only in PROD for Gakau, chocheraz, kokus and Miguel -->   
                                <td id="Ok" width="82" style="text-align:center;">
									<?php if ($enabledFacebook) { ?>
										<input onclick="valCheckAlerts(this)" type="checkbox" name="leagueSendByFaceBookFlag" <?php echo ($data['alert_facebook'] != 0 ? 'checked' : ''); ?> value="<?php echo $data['competition_id'] ?>">
									<?php } ?> 
								</td>                                
                                
                                <td width="91">&nbsp;</td>
                                <td width="89">&nbsp;</td>
                                <td id="encot" colspan="2">
                                    <div align="center">
                                        <select name="leagueUpdateType" id="leagueUpdateType<?php echo $data['competition_id'] ?>" <?php echo $isDisabled?> class="sell">
                                        <?php
											$inicial=0;
											foreach ($this->frequencyLeagues as $data1) {	$inicial++;?>
                                            <option value="<?php echo $data1['alert_frecuency_id'] ?>" <?php echo (($data['alert_frecuency_type'] == $data1['alert_frecuency_id'] && ($data['alert_facebook'] != 0 || $data['alert_email'] != 0)) || $inicial==1 ? 'selected' : ''); ?> ><?php echo $data1['alert_frecuency_description'] ?></option>
                                        <?php } ?>
										</select>
									</div>
									<div align="center"></div>
								</td>
                        	</tr>
                        <?php
                            $i++;
                        }
                        ?>

                        <tr bgcolor="#777777" style="color: #FFFFFF;">
                            <td width="447"><strong>Teams </strong></td>
                            
                            <td width="41"><div align="center"><strong>Email </strong></div></td>
                            
                            <?php if ($enabledFacebook) { ?> 
                            	<td width="82"><div align="center"><strong>FaceBook </strong></div></td>
                            <?php } ?> 
                            
                            <td width="91">&nbsp;</td>
                            <td width="89">&nbsp;</td>
                            <td colspan="2"><div align="center"><strong>Update Type </strong></div>
                                <div align="center"></div></td>
                        </tr>             
                        <?php
                        $i = 1;
                        foreach ($this->userteam as $data) {
							$isDisabled=($data['alert_email'] != 0 || $data['alert_facebook'] != 0?'':'disabled');
                            if ($i % 2 == 1) {
                                $style = "#ffffff";
                            } else {
                                $style = "#e6eff4";
                            }
                        ?>
                        <tr bgcolor="<?php echo $style; ?>">
                            <td width="447"><?php echo $data['team_name'] ?>
                                <img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/12-em-cross.png"/>&nbsp;
                                <input type="hidden" name="teamid" value="<?php echo $data['team_id'] ?>"/>
                                <a href="javascript:removefavorite('<?php echo $data['team_name'] ?>',<?php echo $data['team_id'] ?>,'team')">Remove</a>
                            </td>
                            <td width="41" style="text-align:center">
                                <input onclick="valCheckAlerts(this)" type="checkbox" name="teamSendByEmailFlag" <?php echo ($data['alert_email'] != 0 ? 'checked' : ''); ?> value="<?php echo $data['team_id'] ?>">
                            </td>
							<!--  Testing only in PROD for Gakau, chocheraz, kokus and Miguel -->   
                            <td width="82" style="text-align:center;">
								<?php if ($enabledFacebook) { ?>   
									<input onclick="valCheckAlerts(this)" type="checkbox" name="teamSendByFaceBookFlag" <?php echo ($data['alert_facebook'] != 0 ? 'checked' : ''); ?> value="<?php echo $data['team_id'] ?>"/>
								<?php } ?>  
                            </td>
                            
                            <td width="91">&nbsp;</td>
                            <td width="89">&nbsp;</td>
                            <td colspan="2">
                                <div align="center">
									<select name="teamUpdateType" id="teamUpdateType<?php echo $data['team_id'] ?>" <?php echo $isDisabled?> class="sell">
									<?php 
										$iniciarl=0;
										foreach ($this->frequencyTeams as $data1) { $inicial++;?>
											<option value="<?php echo $data1['alert_frecuency_id'] ?>" <?php echo (($data['alert_frecuency_type'] == $data1['alert_frecuency_id']  && ($data['alert_facebook'] != 0 || $data['alert_email'] != 0)) || $inicial==1 ? 'selected' : ''); ?>><?php echo $data1['alert_frecuency_description'] ?></option>
									<?php } ?>
									</select>
                                </div>
                                <div align="center"></div>
                            </td>
                        </tr>
                        <?php
                            $i++;
                        } ?>

                        <tr bgcolor="#777777" style="color: #FFFFFF;">
                            <td width="447"><strong>Players </strong></td>
                            <td width="41"><div align="center"><strong>Email </strong></div></td>
                         
                         <!--  Testing only in PROD for Gakau, chocheraz, kokus and Miguel -->   
                         <?php if ($enabledFacebook) { ?>   
                            <td width="82"><div align="center"><strong>FaceBook </strong></div></td>
                         <?php } ?> 
                         
                         
                            <td width="91">&nbsp;</td>
                            <td width="89">&nbsp;</td>
                            <td colspan="2"><div align="center"><strong>Update Type </strong></div>
                                <div align="center"></div></td>
                        </tr>
                        <?php
                        $i = 1;
                        foreach ($this->userplayer as $data) {
							
                            if ($i % 2 == 1) {
                                $style = "#ffffff";
                            } else {
                                $style = "#e6eff4";
                            }
                        ?>
                        <tr bgcolor="<?php echo $style; ?>">
                            <td width="447"> <?php echo $data['player_common_name'] ?>
                                <img alt="" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/icons/12-em-cross.png"/>&nbsp;
                                <input type="hidden" name="playerid" value="<?php echo $data['player_id'] ?>"/>
                                <a href="javascript:removefavorite('<?php echo $data['player_common_name'] ?>',<?php echo $data['player_id'] ?>,'player')">Remove</a>
                            </td>
                            <td width="41" style="text-align:center">
                                <input onclick="valOptionsAlertsPlayers();valCheckAlertsPlayers(this);" type="checkbox" name="playerSendByEmailFlag" <?php echo ($data['alert_email'] != 0 ? 'checked' : ''); ?> value="<?php echo $data['player_id'] ?>">
                            </td>
                            
                         <!--  Testing only in PROD for Gakau, chocheraz, kokus and Miguel -->   
                            <td width="82" style="text-align:center">
								<?php if ($enabledFacebook) { ?>   
									<input onclick="valOptionsAlertsPlayers();valCheckAlertsPlayers(this);" type="checkbox" name="playerSendByFaceBookFlag" <?php echo ($data['alert_facebook'] != 0 ? 'checked' : ''); ?> value="<?php echo $data['player_id'] ?>">
								<?php } ?>   
                            </td>
                            
                            <td width="91">&nbsp;</td>
                            <td width="89">&nbsp;</td>
                            <td colspan="2" style="padding-left:18px"><?php $playerAlertsSelected = explode(',', $data['alert_frecuency_type']);?>
                                <select name="playerUpdateType<?php echo $data['player_id'] ?>" id="playerUpdateType<?php echo $data['player_id'] ?>" multiple>
                                    <?php
                                    foreach ($this->frequencyPlayers as $data1) {
                                    ?>
                                        <option value="<?php echo $data1['alert_frecuency_id'] ?>"
                                        <?php
										if($data['alert_email'] != 0 || $data['alert_facebook'] != 0){
											if (in_array($data1['alert_frecuency_id'], $playerAlertsSelected)) {
												echo "selected";
											}
										}
                                        ?>><?php echo $data1['alert_frecuency_description'] ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <?php
                        $i++;
                        } ?>
                    </table>  
		               <!-- <input id="saveAlertsButtonId" class="submit blue" type="button" value="Save">-->
						</div>
		                	<fieldset class="AddToPhotoButtonWrapper">
								<input type="button" value="Save" id="saveAlertsButtonId" name="Save" class="submit GreenGradient">
								<input type="button" value="Cancel" id="cancelAlertsButtonId" name="Cancel" class="submit GreenGradient">
						</fieldset>
		                
						</div>		                
					
					</div>
					
	           	</div>
           
           </div>
           

       </div><!--//ContentWrapper-->