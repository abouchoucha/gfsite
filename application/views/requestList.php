 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 
<script type="text/JavaScript">

jQuery(document).ready(function() {
	
});


function acceptFriendRequest(messageRequestId , newFriendId , newUserFriendName){

	var url = '<?php echo Zend_Registry::get ( "contextPath" );?>/message/acceptfriendrequest';
	
	jQuery.ajax({
        type: 'POST',
        url : url,
        data : {newFriendId: newFriendId ,messageRequestId: messageRequestId ,newUserFriendName:newUserFriendName} ,
        
 		success: function (text) {
					jQuery('#messageDetail').jqmHide();
					jQuery('#addFavoriteModal').jqm({trigger: '#removefavoriteplayertrigger', onHide: closeModal });
					jQuery('#addFavoriteModal').jqmShow();
					jQuery('#modalFavoriteTitleId').html('Friend Request');
 					jQuery('#modalBodyResponseId').html(newUserFriendName+ ' is now your friend.');
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
					top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/messagecenter/request';
 				}	
	});	
}

function sendSingleMessage(obj , id , newUserFriendName){

	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/message/messageupdate';
	var lir = id;
	lir += ",";
	var message_stat;
	var typo = "request";
	if(obj.id == "acceptMess"){
		message_stat = 10;
	}else{
		message_stat = 11;
	}
	jQuery.ajax({
        type: 'POST',
        url : url,
        data : {ids: lir, status: message_stat, type: typo} ,
        
 		success: function (text) {
					jQuery('#messageDetail').jqmHide();
					jQuery('#addFavoriteModal').jqm({trigger: '#removefavoriteplayertrigger', onHide: closeModal });
					jQuery('#addFavoriteModal').jqmShow();
					jQuery('#modalFavoriteTitleId').html('Friend Request');
 					jQuery('#modalBodyResponseId').html('You rejected a friend request from' +newUserFriendName);
					jQuery('#modalBodyId').hide();
					jQuery('#modalBodyResponseId').show();
					jQuery('#acceptFavoriteModalButtonId').hide();
					jQuery('#cancelFavoriteModalButtonId').attr('value','Close');
					jQuery('#addFavoriteModal').animate({opacity: '+=0'}, 2500).jqmHide();
					top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/messagecenter/request';
 		}	
	});
} 


function selectAll(pObj, pFlag) {

 var pForm = $("messageForm");
	for (var iCount=0;;iCount++) {
		if (!pForm.elements[pObj + iCount]) break;
			pForm.elements[pObj + iCount ].checked = pFlag;
	}
}
	
function sendMessage(obj){

	var url = '<?php echo Zend_Registry::get("contextPath"); ?>/message/messageupdate';
	var target = 'error';
	var lir = lit();
	var message_stat;
	var typo = "request";
	if(obj.id == "accept"){
		message_stat = 10;
	}else{
		message_stat = 11;
	}
    var myAjax = new Ajax.Updater(target, url, {
                                                method: 'post', 
                                                asynchronous:true,
											    parameters: {ids: lir, status: message_stat, type: typo}, 
                                                evalScripts:true,
												onSuccess: refreshAll
											 }
                                            );
			$('messageForm').reset();
		return false;                                            
    } 

	


	function lit(){
		var dom = document.getElementById('messageForm');
			//var arrayt = new Array();
			//alert(dom.checkarray.size);
			var arrayt ="";
			if(dom.checkarray.size == 0){
				arrayt += dom.checkarray.value+","; 
			}else{
				for(i=0;i<dom.checkarray.length;i++){
				//alert("this: "+dom.checkarray[i].checked);
					if(dom.checkarray[i].checked == true){
						//alert(dom.checkarray[i].value);
						//arrayt[i] = dom.checkarray[i].value;
						arrayt += dom.checkarray[i].value+",";
					}
				}
			}
			return arrayt;
		}

function refreshAll(){
	//location.reload(true);
}

function replyMessageFormShow(){
	var dest = $('dest');
	var str = "<table width='130'  bgcolor='#E4EDF2' style='padding:5px 0px 10px 5px'><tr><td style='padding: 10px 0px 0px 0px;'><form name='replyForm'><table width='390' border='0'><tr><td><h3>Message Reply</h3></td></tr><tr><td>To :</td><td><input type='text' id='to' name='to' value='"+screen_name+"'/></td></tr><tr><td>Subject :</td><td><input type='text' id='subject' name='subject' value='re:'></td></tr><tr><td style='vertical-align:top'>Message :</td><td><textarea name='content' id='content' style='width: 200px; height:100px;' height='50'></textarea ><input type='hidden' id='dest1' name='dest1' value='"+dest.value+"' /></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td width='30%'>&nbsp;</td><td><table><tr><td style='padding-right:30 px;'><input type='button' name='replyMess' onclick='replyMessage()' id='replyMess' value='Send' class='buttonCustom'/></td><td><input type='reset' name='reset' id='resetMessage' value='Cancel' class='buttonCustom' onclick='m.close();'/></td></tr></table></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table></form></td></tr></table>";
$('modal_container').innerHTML = str;
var form = $('modal_container').getElementsByTagName('form')[0];
replyableform = form;
}

function replyMessage(){
     var url = '<?php echo Zend_Registry::get("contextPath"); ?>/message/replyMessage';
     var target = 'modal_container';
	 var pars = Form.serialize(replyableform);
     var myAjax = new Ajax.Updater(target, url, {
                                                method: 'post', 
                                                asynchronous:true,
                                                parameters: pars,
                                                evalScripts:true
                                                }
                                            );
}

function orderByDate(){
	if(dateOrder == 1){
	  //alert("we do the asc");
 		dateOrder = 0;
 	}else if(dateOrder == 0){
	 //alert("we do the desc");
 	 dateOrder = 1;
 	}
	
}
function callOrder(obj){
	var message_stat = $('combo').value;
	window.location = "<?php echo Zend_Registry::get("contextPath"); ?>/message/requestorder/type/<?php echo $this->messageType;?>/status/"+message_stat;
}



function showMessageDetail(id){
	
	 jQuery('#messageDetail').jqm({
        ajax: '<?php echo Zend_Registry::get("contextPath"); ?>/message/showmessagedetail/type/request/message/'+id
    });
  	 jQuery('#messageDetail').jqmShow();
  	 top.location.href = '<?php echo Zend_Registry::get ( "contextPath" );?>/messagecenter/inbox';
             
}

</script>


    <div class="addFavoriteWindow" id="messageDetail">
      <a href="#" class="jqmClose">Close</a>                Please wait...
      <img src="inc/busy.gif" alt="loading" />
    </div>


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


                      <?php }else { ?>
                       <!--Me box Non-authenticated and left menu-->

                          <div class="img-shadow">
                              <div class="WrapperForDropShadow">
                                  <?php include 'include/loginNonAuthBox.php';?>
                              </div>
                          </div>

                     <!-- Non-authenticated Profile Left Menu-->


                      <?php } ?> <!-- End - Non-authenticated Profile Left Menu-->

               
            </div><!--end FirstColumnOfThree-->

        <div class="SecondColumnOfTwo" id="SecondColumnPlayerProfile">
                <div class="img-shadow">
                    <div class="WrapperForDropShadow">
                        <div class="DropShadowHeader BlueGradientForDropShadowHeader">
                            <h4>Messages</h4>
                        </div>
                        <form id="messageForm" name="messageForm" method="post" >
                        <div class="PlayerInfoWrapper">
                            <div class="PlayerInfo MessageWrapper">

                                <div class="ModuleTabs Messages">
                                    <ul>
                                        <li id="inbox" class="<?php echo ($this->messageType =="inbox"?"selected":"")?>"><a class="<?php echo ($this->messageType =="inbox"?"selected":"")?>" href="<?php echo Zend_Registry::get("contextPath"); ?>/message">INBOX (<?php echo($this->cants["cantMess"]); ?>)</a></li>
                                        <li id="sent" class="<?php echo ($this->messageType =="sent"?"selected":"")?>"><a class="<?php echo ($this->messageType =="sent"?"selected":"")?>" href="<?php echo Zend_Registry::get("contextPath"); ?>/message/gosent">SENT (<?php echo($this->cants["cantSent"]); ?>)</a></li>
                                        <li class="<?php echo ($this->messageType =="request"?"selected Requests":"")?>"><a class="<?php echo ($this->messageType =="request"?"selected":"")?>" href="<?php echo Zend_Registry::get("contextPath"); ?>/message/gorequest">REQUESTS &amp; INVITES (<?php echo($this->cants["cantReq"]); ?>)</a></li>
                                        <li class="<?php echo ($this->messageType =="deleted"?"selected":"")?>"><a class="<?php echo ($this->messageType =="deleted"?"selected":"")?>" href="<?php echo Zend_Registry::get("contextPath"); ?>/message/delete">DELETED (<?php echo($this->cants["cantDel"]); ?>)</a></li>
                                    </ul>
                                </div><!-- /ModuleTabs-->
                                <ul class="MessagePager">
                                    <li>View: <select style=" width:100px;" id="combo" ><option value="0" onclick="callOrder(this)">All</option>
                                    <?php
                                  $res = $this->states;
                                   for($i=0;$i<count($res);$i++){?>
                                     <option value="<?php echo($res[$i]["id_description"]); ?>" onclick="callOrder(this)" <?php echo ($res[$i]["id_description"] == $this->status?'selected':'')?>><?php echo($res[$i]["description"]);?></option>
                                    <?php }?>
                                    </select></li>
                                    <li class="Pager"><?php if($this->paginate["current_page"] != 1){
                                ?><a href="<?php echo Zend_Registry::get("contextPath"); ?>/message/paginateViews/page/<?php echo($this->paginate["current_page"] - 1); ?>"  id="<?php echo($this->paginate["current_page"] - 1); ?>"> Previous
                                <?php echo($this->paginate["current_page"] - 1);?></a><?php }?><span><?php echo($this->paginate["current_page"]);?></span>
                                <?php if(($this->paginate["current_page"] + 1) <= $this->paginate["page_total"]){?>
                                    |&nbsp;<a href="<?php echo Zend_Registry::get("contextPath"); ?>/message/paginateViews/page/<?php echo($this->paginate["current_page"] + 1); ?>" id="<?php echo($this->paginate["current_page"] + 1); ?>"><?php echo($this->paginate["current_page"] + 1);?>&nbsp; Next &gt; </a>
                                    <?php }?>

                                </li>
                                </ul>
                                <ul class="MessageToolbar">
                                    <li>
                                        <input type="button" name="accept" id="accept" value="Accept" class="submit" onclick="sendMessage(this)"/>
                                        <input type="button" name="reject" id="reject" value="Reject" class="submit red" onclick="sendMessage(this)"/>
                                    </li>
                                </ul>

                                <table class="Messages">
                                    <?php if(count($this->results) > 0){?>
                                    <tr>
                                        <th><input type="checkbox" class="checkbox" value="all" id="all" onclick="selectAll('chk',checked)"/></th>
                                        <th class="NoLeftBorder">From</th>
                                        <th class="NoLeftBorder">Subject</th>
                                        <th class="NoLeftBorder">Date</th>
                                        <?php if($this->messageType == 'sent'){ ?>
                                        <th class="NoLeftBorder">To</th>
                                        <?php }?>
                                    </tr>

                                    <?php
                                        $res = $this->results;
                                        $colcont = true;
                                        $color = "";
                                        for($i=0;$i<count($res);$i++){
                                            if($colcont == true){
                                                $color = "";
                                            }else{
                                                $color = "Even";
                                            }

                                  ?>
                                    <tr class="<?=  $color?>">
                                        <td><input type="checkbox" value="<?php echo($res[$i]["id"]);?>" name="chk<?php echo($i); ?>" id="checkarray" onclick="lit()" /></td>
                                        <td class="NoLeftBorder"><?php echo($res[$i]["screen_name"]);?></td>
                                        <td class="NoLeftBorder"><a href="javascript:showMessageDetail('<?php echo($res[$i]["id"]);?>')" ><?php echo($res[$i]["subject"]);?></a></td>
                                        <td class="NoLeftBorder"><?php echo($res[$i]["date"]);?></td>
                                        <?php if($this->messageType == 'sent'){ ?>
                                        <td class="NoLeftBorder"><?php echo($res[$i]["tu"]);?></td>
                                        <?php }?>
                                    </tr>
                                    <?php
                                    if($colcont == true){$colcont = false;}else{$colcont = true;}
                                        }
                                    }else{ ?>
                                      <h4>You don't have messages.</h4>
                                    <?php }?>

                                </table>

                                 <ul class="MessageToolbar">
                                    <li>
                                        <input type="button" name="accept" id="accept" value="Accept" class="submit" onclick="sendMessage(this)"/>
                                        <input type="button" name="reject" id="reject" value="Reject" class="submit red" onclick="sendMessage(this)"/>
                                    </li>
                                </ul>

                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div><!--end SecondColumnOfTwo and #SecondColumnPlayerProfile-->
    </div> <!--end ContentWrapper-->
