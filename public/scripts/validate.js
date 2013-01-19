// JavaScript Document

function insertAfter(newElement,targetElement) {

	//target is what you want it to go after. Look for this elements parent.
	var parent = targetElement.parentNode;
	//if the parents lastchild is the targetElement...
	
	if(parent.lastchild == targetElement) {
	//add the newElement after the target element.
	parent.appendChild(newElement);
	} else {
	// else the target has siblings, insert the new element between the target and it's next sibling.
	parent.insertBefore(newElement, targetElement.nextSibling);
	}

}


function ntx(txt){
  return document.createTextNode(txt);
 }

function ge(e) {
 return document.getElementById(e);
 }

function validaNewForm(form){

    var frm = document.getElementById(form);
    var valid = true;
    var errors = new Array();
     errors[0] = "Field required";
     errors[1] = "Does not appear to be a valid email address";
     errors[2] = "Should be at least %% characters long";
     errors[3] = "Should not be greather than %% characters";
     errors[4] = "Incorrect Date of Birth";
   
     //var k = $$("div.ErrorMessageIndividualDisplay");
     var k = jQuery('div.ErrorMessageIndividualDisplay')
     for(var i=0;i < k.length;i++ ){ 
        k[i].className ='ErrorMessageIndividual';
     }
     //validate email fields
    var ids=0;
    var valid = true;
       for(var i=0; i<frm.length; i++){
         var e = frm.elements[i];
         var a = e.getAttribute("required");
         var b = e.getAttribute("name");
         var fmin= /min/;
         var fmax= /max/;
         var femail= /email/;
         var fnn= /nn/;
         var fnlmi=/nlmi/;
         var fdob=/date/;
         var fminmax=/minmax/;  
         if(a){
           //email addresses validation
           if(femail.test(a)) {
               var v = parseInt(a.substring(6));
               var filter = /^([\sa-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9\s]{2,4})+$/;
               if(!filter.test(e.value)) {
	             	if(e.value.length > 0) {
		                var mn = document.getElementById(b+'error');
		                mn.className ='ErrorMessageIndividualDisplay';
		                mn.innerHTML = "The Email address entered is not valid.";
		                valid = false;
	                }else {
		             	var mn = document.getElementById(b+'error');
		             	mn.className ='ErrorMessageIndividualDisplay';
		             	//mn.innerHTML = "You must enter an Email Address.";
		             	valid = false;
	             	}
               }
            }
           	
           	if(fmin.test(a)) {
               var v = parseInt(a.substring(4));
               if(e.value.length < v) {
                 var etext = ntx(errors[2].replace("%%", v));
                 var mn = document.getElementById(b+'error');
                 mn.className ='ErrorMessageIndividualDisplay';
                 mn.innerHTML = "Your password must contain 6 or more characters.";
                 e.value = "";
                 valid = false;
               }
            }
            if(fdob.test(a)) {
               var v = a.substring(5);
               var day = frm.elements[i-1];
               var month = frm.elements[i-2];
               if(e.value.length == 0 || day.value.length == 0 || month.value.length == 0 ){
                 var etext = errors[4];
                 var mn = document.getElementById(b+'error');
                 mn.className ='ErrorMessageIndividualDisplay';
                 valid = false;
              } 
            }
            
             if(fminmax.test(a)) {
		         
		         var min = parseInt(a.substring(7,8));
		         var max = parseInt(a.substring(9));
		         if( (e.value.length < min) ) {
		            var mn = document.getElementById(b+'error');
		            mn.className ='ErrorMessageIndividualDisplay';
                 	mn.innerHTML = "Your display name must contain 6 or more characters.  Try again";
		          	valid = false;
		         } 
		         if( (e.value.length > max) ) {
		            var mn = document.getElementById(b+'error');
		            mn.className ='ErrorMessageIndividualDisplay';
                 	mn.innerHTML = "Your display name must contain 16 characters or less . Try again";
		          	valid = false;
		         }  
		       }
            
            if(fnn.test(a)) {
              if(e.value.length == 0) {
                var mn = document.getElementById(b+'error');
                mn.className ='ErrorMessageIndividualDisplay';
                valid = false;
             }
          }
     }
    } 
	
	if(valid==false){
		var msgbox = document.getElementById("ErrorMessages");
		msgbox.className = 'ErrorMessagesDisplay';
	}
    
	return valid;
}


function validateForm(form)
{

    var frm = document.getElementById(form);
    
    
    var ids=0;
    var valid = true;
    var errors = new Array();
     errors[0] = "Field required";
     errors[1] = "Does not appear to be a valid email address";
     errors[2] = "Should be at least %% characters long";
     errors[3] = "Should not be greather than %% characters";
     errors[4] = "Wrong %%";
    
     var k= document.getElementsByClassName("frm-error-text");
     var radio_arr = new Array();
    
    //clearing the errors
     for(var l=0;l<k.length;l++ ){ 
         var o= l+1;
         var mn = document.getElementById(o);
         if (mn!= null)
          mn.parentNode.removeChild(mn);
     }
     
     for(var i=0; i<frm.length; i++){
       var e=frm.elements[i];
       var a=e.getAttribute("required");
       var fmin= /min/;
       var fmax= /max/;
       var femail= /email/;
       var fnn= /nn/;
       var fnlmi=/nlmi/;
       var fdob=/date/;
       var fminmax=/minmax/;
       var div = document.createElement("div");
       div.className = "frm-error-text";
     
     if(a){
       
       if(fminmax.test(a)) {
         var v = parseInt(a.substring(7,8));
         var v1 = parseInt(a.substring(9));
         var etext;
         if( e.value.length < v) {
            etext = ntx(errors[2].replace("%%", v));
         }else if(e.value.length > v1) {
            etext = ntx(errors[3].replace("%%", v1));
         }else{
           e.className = "";
           etext = ""
         }  
        if(etext != ""){
          ids=++ids;
          div.id=ids;
          div.appendChild(etext);
          //e.parentNode.insertBefore(div,e);
          insertAfter(div,e);
          e.className = "frm-error";
          valid = false; 
        }    
       }
       else if(fmin.test(a)) {
       var v = parseInt(a.substring(4));
       if(e.value.length < v) {
         ids=++ids;
         div.id=ids;
         var etext = ntx(errors[2].replace("%%", v));
         div.appendChild(etext);
         //e.parentNode.insertBefore(div,e);
         insertAfter(div,e);
         e.className = "frm-error";
         valid = false;
       }
       else
       e.className = "";
      
       }
      
       else if(fnlmi.test(a)) {
         var v = parseInt(a.substring(5));
         if( e.value.length>0 && e.value.length < v) {
         ids=++ids;
         div.id=ids;
         var etext = ntx(errors[2].replace("%%", v));
         div.appendChild(etext);
         //e.parentNode.insertBefore(div,e);
         insertAfter(div,e);
         e.className = "frm-error";
         valid = false;
         }
         else
         e.className = "";
      
       }
       else if(fmax.test(a)) {
         var v = parseInt(a.substring(4));
         if(e.value.length > v) {
         ids=++ids;
         div.id=ids;
         var etext = ntx(errors[3].replace("%%", v));
         div.appendChild(etext);
         //e.parentNode.insertBefore(div,e);
         insertAfter(div,e);
         e.className = "frm-error";
         valid = false;
         }
         else
         e.className = "";
      
       }
       
       else if(femail.test(a)) {
         var v = parseInt(a.substring(6));
         var filter = /^([\sa-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9\s]{2,4})+$/;
         if(!filter.test(e.value)) {
         ids=++ids;
         div.id=ids;
         var etext = ntx(errors[1]);
         div.appendChild(etext);
         //e.parentNode.insertAfter(div,e);
         insertAfter(div,e);
         
         e.className = "frm-error";
         valid = false;
         }
         else
         e.className = "";
      
       }
       else if(fnn.test(a)) {
           if (e.type == "radio")
           {
             if (radio_arr[e.name] == null)
             {
             radio_arr[radio_arr.length] = e.name;
             radio_arr[e.name] = e;
             }
             if (e.checked)
             {
             radio_arr[e.name].check = e;
             }
           }
           else if(e.value.length == 0) {
             ids=++ids;
             div.id=ids;
             var etext = ntx(errors[0]);
             div.appendChild(etext);
             //e.parentNode.insertBefore(div,e);
             insertAfter(div,e);
             e.className = "frm-error";
             valid = false;
           }
           else
           e.className = "";
      
       }else if(fdob.test(a)) {
               if(e.value.length == 0){
                 var v = a.substring(5);
                 ids=++ids;
                 div.id=ids;
                 var etext = ntx(errors[4].replace("%%", v));
                 div.appendChild(etext);
                 //e.parentNode.insertBefore(div,e);
                 insertAfter(div,e);
                 e.className = "frm-error";
                 valid = false;
          } 
       }
       
    }
    
     }
    
     for (i = 0; i < radio_arr.length; i++)
     {
         if (!radio_arr[radio_arr[i]].check)
         {
         //
         ids=++ids;
         e = radio_arr[radio_arr[i]];
         var div = document.createElement("div");
         div.className = "frm-error-text";
         var etext = ntx(errors[0]);
         div.appendChild(etext);
         //e.parentNode.insertBefore(div,e);
         insertAfter(div,e);
         e.className = "frm-error";
         valid = false;
        }
     }
     return valid;
}
function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}
