 $(document).ready(function(){
  loadApiList();
 });
 
 //global vars for the querys
 var actualApi;
 var actualVersion;
 var actualFunction;
 
 
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
	