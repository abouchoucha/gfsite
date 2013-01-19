

//loading popup with jQuery magic!
function loadPopup(popupname){
	//loads popup only if it is disabled
	
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#" + popupname).fadeIn("slow");
		window.scroll(0,0);
	
}

function disablePopup1(){
		$("#backgroundPopup").fadeOut("slow");
		$("#selectteam").fadeOut("slow");
}
function disablePopup2(){
		$("#backgroundPopup").fadeOut("slow");
		$("#selectteam1").fadeOut("slow");
}

//centering popup
function centerPopup(popupname){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#" + popupname ).height();
	var popupWidth = $("#" + popupname).width();
	//centering
	$("#" + popupname).css({
		"position": "absolute",
		"top": 50,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	$("#select").click(function(){
		//centering with css
		centerPopup('selectteam');
		//load popup
		loadPopup('selectteam');
		return false;
	});	
				
	$("#select1").click(function(){
		//centering with css
		centerPopup('selectteam1');
		//load popup
		loadPopup('selectteam1');
		return false;
	});	
				
	//CLOSING POPUP
	//Click the x event!
	
	$("#aboutclose1").click(function(){
		disablePopup1();
	});	
	$("#aboutclose2").click(function(){
		disablePopup2();
	});	

	$(document).keypress(function(e){
		if(e.keyCode==27){
			disablePopup1();
			disablePopup2();
		}
	});
});