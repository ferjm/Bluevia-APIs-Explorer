 $(document).ready(function(){
     initAccordeons();
  loadApiList();

  
 });
 
 
 
 //global vars for the querys
 var actualApi;
 var actualVersion;
 var actualFunction;
 
 function initAccordeons() {
	  $('.accordeon_button').click(function() {

        //REMOVE THE ON CLASS FROM ALL BUTTONS
        $('.accordeon_button').removeClass('on');
          
        //NO MATTER WHAT WE CLOSE ALL OPEN SLIDES
        $('.accordeon_content').slideUp('normal');
   
        //IF THE NEXT SLIDE WASN'T OPEN THEN OPEN IT
        if($(this).next().is(':hidden') == true) {
            
            //ADD THE ON CLASS TO THE BUTTON
            $(this).addClass('on');
              
            //OPEN THE SLIDE
            $(this).next().slideDown('normal');
         } 
          
     });
      
    
    /*** REMOVE IF MOUSEOVER IS NOT REQUIRED ***/
    
    //ADDS THE .OVER CLASS FROM THE STYLESHEET ON MOUSEOVER 
    $('.accordeon_button').mouseover(function() {
        $(this).addClass('over');
        
    //ON MOUSEOUT REMOVE THE OVER CLASS
    }).mouseout(function() {
        $(this).removeClass('over');                                        
    });
    
    /*** END REMOVE IF MOUSEOVER IS NOT REQUIRED ***/
    
    
    /********************************************************************************************************************
    CLOSES ALL S ON PAGE LOAD
    ********************************************************************************************************************/   
    $('.accordeon_content').hide();
}
 
 function loadApiList() {
	var div_api_selector=document.getElementById("API_content");
//TODO fake data! get from db!
	data = new Array("API one","API two","API three","API four");
	mHTML = "";
	for(i=0; i<data.length; i++) {
		mHTML += "<div id=\"selector_row\" onclick=\"loadApiVersions('"+data[i]+"');\" >"
                 +"<p id=\"selector_value\">"+data[i]+"</p>"
                 +"</div>"
	}
	mHTML+="";
	div_api_selector.innerHTML = mHTML;
	}
	
 function loadApiVersions(p_api) {
 	actualApi=p_api;
	//clean all api functions
	document.getElementById("function_form").innerHTML="<br />";
	document.getElementById("API_functions").innerHTML = "";
	var div_api_selector=document.getElementById("API_version");
//TODO fake data! get from db!
	data = new Array("V1["+p_api+"]","V2["+p_api+"]","V3["+p_api+"]","V4["+p_api+"]");
	mHTML = "";
	for(i=0; i<data.length; i++) {
		mHTML += "<div id=\"selector_row\" onclick=\"loadApiFunctions('"+data[i]+"');\" >"
                 +"<p id=\"selector_value\">"+data[i]+"</p>"
                 +"</div>"
	}
	mHTML+="";
	div_api_selector.innerHTML = mHTML;
	}	
	
 function loadApiFunctions(p_version) {
		actualVersion=p_version;
		var div_api_selector=document.getElementById("API_functions");
//TODO fake data! get from db!
		data = new Array("["+p_version+"]function1","["+p_version+"]function2","["+p_version+"]function3","["+p_version+"]function4","["+p_version+"]function5","["+p_version+"]function6");
		mHTML = "";
		for(i=0; i<data.length; i++) {
			mHTML += "<div id=\"selector_row\" onclick=\"loadFormForFunction('"+data[i]+"');\" >"
	                 +"<p id=\"selector_value\">"+data[i]+"</p>"
	                 +"</div>"
		}
		mHTML+="";
		div_api_selector.innerHTML = mHTML;
	}		



	//load all functions from de params
	function loadFormForFunction(p_function)
	{
		actualFunction=p_function;
		var div_api_selector=document.getElementById("function_form");
		mHTML = "<p id='selector_title'>"+p_function+"</p><br />"
//TODO fake data! get from db!		
		data = new Array(p_function+".param1",p_function+".param2",p_function+".param3",p_function+".param4",p_function+".param5");
		for(i=0; i<data.length; i++) {
			mHTML += data[i]+" :  <input type=\"text\" name=\""+data[i]+"\" /><br />"
		}
		mHTML+="<input type=\"submit\" value=\"Submit\" />";
		div_api_selector.innerHTML = mHTML;
		
	}

//this function will draw the test output
	function drawInCode(p_string)
	{
		var div_api_selector=document.getElementById("result_div");
		div_api_selector.innerHTML = p_string;
	}
	