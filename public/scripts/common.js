var DL_bIE4Mac = false;
var ajaxobj = null;
var hoverobj = null;

function objGetRealLeft(oElement)
{
	var nXPos = oElement.offsetLeft;
	var oParentEl = (DL_bIE4Mac) ? oElement.parentElement : oElement.offsetParent;

	while (oParentEl != null)
	{
		if(DL_bIE4Mac)
		{
			if(oParentEl.tagName=="SPAN")
			{
				oParentEl = oParentEl.parentElement;
			}
			if(oParentEl.tagName=="HTML")
			{
				break;
			}
		}

		nXPos += oParentEl.offsetLeft;
		oParentEl = (DL_bIE4Mac) ? oParentEl.parentElement : oParentEl.offsetParent;
	}
	return nXPos;

}


function objGetScreenTop(oElement) 
{
	var  tA = window.screenY? window.screenY: window.screenTop;
	return objGetRealTop(oElement) + tA; 
}
function objGetRealTop(oElement)
{
	var nYPos = oElement.offsetTop;
	var oParentEl = (DL_bIE4Mac) ? oElement.parentElement : oElement.offsetParent;
	while (oParentEl != null)
	{
		if(DL_bIE4Mac)
		{
			if(oParentEl.tagName=="SPAN")
			{
				oParentEl = oParentEl.parentElement;
			}
			if(oParentEl.tagName=="HTML")
			{
				break;
			}
		}
		nYPos += oParentEl.offsetTop;
		oParentEl = (DL_bIE4Mac) ? oParentEl.parentElement : oParentEl.offsetParent;
	}
	return nYPos;
}

function hoverbookoff(obj) {
	var popup = document.getElementById('bookpopup');
	popup.style.display = 'none';
	var popuparrow = document.getElementById('bookpopuparrow');
	popuparrow.style.display = 'none';
	try {
		popup.style.visibility = false;
	} catch (e) {}
	hoverobj = null;
}

function hoverbook(bookid, obj) {
	hoverobj = obj;
	setTimeout("dohoverbook(" + bookid + ")", 500);
}

function dohoverbook(bookid) {
	try {
		if (hoverobj == null) return;
		obj = hoverobj;
		
//		document.getElementById('bookpopup_description').innerHTML = '';
		document.getElementById('joined').innerHTML = 'Loading...';
//		document.getElementById('bookpopup_cover').src = null;

		var left = objGetRealLeft(obj);
///		var top = objGetRealTop(obj);
		var popup = document.getElementById('bookpopup');
		popup.style.position = 'absolute';

		var popuparrow = document.getElementById('bookpopuparrow');
		popuparrow.style.position = 'absolute';

		if (left - getScrollLeft() > 150 + 60) {
			// put it to left screen
			popup.style.left = (left - 150 - 40) + 'px';
			popuparrow.style.left = (left - 40) + 'px';
			popuparrow.src = '/goalface/public/images/tooltip_arrowr.gif';
		} else {
			popup.style.left = (left + 100) + 'px';
			popuparrow.style.left = (left + 79) + 'px';
			popuparrow.src = '/goalface/public/images/tooltip_arrow.gif';
		}
/*
		if ((top - window.pageYOffset) > (windowHeight() - 400)) {
			popup.style.top = (top + 30) + 'px';
		} else {
			popup.style.top = (top + 30) + 'px';
		}
*/
		bookquery(bookid);
	} catch (e) {
		alert('alert ' + e);
	}
}

function windowHeight() {
	if (window.innerHeight != null){ return window.innerHeight; }
	if (document.documentElement.clientHeight != null) { return document.documentElement.clientHeight;  } 
}

function windowWidth() {
	if (window.innerWidth != null){ return window.innerWidth; }    
	if (document.documentElement.clientWidth != null) { return document.documentElement.clientWidth;  }    
}

function getScrollTop() {
	if (document.documentElement.scrollTop != 0) return document.documentElement.scrollTop;
	return document.body.scrollTop;
}

function getScrollLeft() {
	if (document.documentElement.scrollLeft != 0) return document.documentElement.scrollLeft;
	return document.body.scrollLeft;
}

function callback_bookhover() {
	
	if (ajaxobj.readyState == 4 && ajaxobj.status == 200) {
		try {
		if (!hoverobj) return;
		var text = ajaxobj.responseText;
		var arr = text.split('*');
		//document.getElementById('bookpopup_cover').src = '/ApressCorporate/bookdata/' + arr[0] + '/bcm.gif';
		document.getElementById('header').innerHTML = arr[0];
		
		document.getElementById('joined').innerHTML = arr[1];
		document.getElementById('bookpopup_misc').innerHTML = arr[2];

		
		var top = objGetRealTop(hoverobj);
		var popupobj = document.getElementById('bookpopup');
		popupobj.style.display = 'block';
		popupobj.style.top = '-600px';

		var popuparrow = document.getElementById('bookpopuparrow');
		popuparrow.style.display = 'block';
		var popuptop = popupobj.style.top;

		// if the object is on the lower half of the browser
		if (top - getScrollTop() > windowHeight() / 2) {
			popuptop = top -(document.getElementById('bookpopup_misc').offsetTop - 120);
			popupobj.style.top = (popuptop - 55) + 'px';
		} else {
			popupobj.style.top = (top - 20) + 'px';
		}
		popuparrow.style.top = (top+5) + 'px';

		try {
			popupobj.style.visibility = true;
			popupobj.style.opacity = 1.0;
//			setTimeout("fadein()", 100);
		} catch (e) {}

		} catch (e) {
			alert('bookhover ' + e);
		}
	}
}

function fadein() {
	var p = document.getElementById('bookpopup');
	p.style.opacity = 1.0;
}

