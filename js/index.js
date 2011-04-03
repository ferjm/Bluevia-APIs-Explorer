 $(document).ready(function(){
     initAccordeons();
 	 loadApiList();
	 initListElements();		      
 });
 
 
 
 //global vars for the querys
 var actualApi;
 var actualVersion;
 var actualFunction;
 
 function initListElements(){
 	 $('.accordeon_button').mouseover(function() {
        $(this).addClass('over_list');
    }).mouseout(function() {
        $(this).removeClass('over_list');                                        
    });
 }
 
 function executeForm() {
	alert("execute query!");
}
 
 function initAccordeons() {
	  $('.accordeon_button').click(function() {
        $('.accordeon_button').removeClass('on');
        $('.accordeon_content').slideUp('normal');
        if($(this).next().is(':hidden') == true) {
            $(this).addClass('on');
            $(this).next().slideDown('normal');
         }     
     });
    $('.accordeon_button').mouseover(function() {
        $(this).addClass('over');
    }).mouseout(function() {
        $(this).removeClass('over');                                        
    });
    $('.accordeon_content').hide();
}
 
 	function loadApiList() {
		$url='selector/get_services.php';
		$.get($url,function(result) {
            $('#API_content').html(result);
        });
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
			if (i%2==1)
			{
				myclass="selector_alt";
			}else
			{
				myclass="";
			}
			mHTML += "<div id=\"selector_row\" onclick=\"loadApiFunctions('"+data[i]+"');\" >"
	                 +"<p id=\"selector_value\" class=\"selector_border "+myclass+"\">"+data[i]+"</p>"
	                 +"</div>";
		}
		mHTML+="";
		div_api_selector.innerHTML = mHTML;
	}	
	
	 function loadApiFunctions(p_version) {
        $.get('selector/get_methods.php','service_id='+p_version,function(result) {
            $('#API_functions').html(result);
        });
	}		



	//load all functions from de params
	function loadFormForFunction(p_function)
	{
        $.get('selector/get_parameters.php','method_id='+p_function,function(result) {
            $('#function_form').html(result);
        });		
	}

	//this function will draw the test output
	function drawInCode(p_string)
	{
		var div_api_selector=document.getElementById("result_div");
		div_api_selector.innerHTML = p_string;
	}
	
	function click_token1(){}
	function click_token2(){}
	function click_token3(){}	
	
	
	function sendForm(uri) {		
		$('#apiForm').submit(function(event) {
	        event.preventDefault();
	        var url = uri;
	        var datos = $(this).serialize();
	        $.get(url,datos,function(result) {
	            $('#result_div').html(result);
	        });
	    });		
}
