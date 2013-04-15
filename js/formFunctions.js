function ajaxFunction(url,value,divid)
{
	//url  : it url of php file where realy execution.
	// var : it is field value if u r want to check in db.
	// divid: it is DIV ID, and it will use when ajax will come back.
	
	
	var xmlHttp;
	try
	  {
		  // Firefox, Opera 8.0+, Safari
		  xmlHttp=new XMLHttpRequest();
	  }
	catch (e)
	  {
	  // Internet Explorer
	  try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
	  catch (e)
		{
		try
		  {
		  	xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		catch (e)
		  {
		  alert("Your browser does not support AJAX!");
		  return false;
		  }
		}
	  }
	  xmlHttp.onreadystatechange=function()
		{
		if(xmlHttp.readyState==4)
		  {
			var result = xmlHttp.responseText;
			result = result.split('|||');
			
		 	document.getElementById(divid).innerHTML= result[0];
			if(result[1] == 'no') {
				//document.getElementById("zipcode_msg").style.display = 'none';
			}else {
				//document.getElementById("zipcode_msg").innerHTML= '<p class="error">You have been charged '+result[1]+'% Sales tax</p>';
				//document.getElementById("zipcode_msg").style.display = 'block';
			}
		  // alert(xmlHttp.responseText);
		  }
		}
	  xmlHttp.open("GET",url+"?avail="+value+"&targetid="+divid,Math.random());
	  xmlHttp.send(null);
} // and ajax function