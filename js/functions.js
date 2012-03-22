/*
Arguments for request types:
1. URL
2. Name of the function to be called upon success.  Returned data is passed into this function as its only argument.
3. (optional) any data to be passed to the page

*/

function get(){
	if (arguments.length == 2){
		var $url = arguments[0];
		var $funct = arguments[1];
		var $data = "";	
	}
	else{
		var $url = arguments[0];
		var $data = arguments[1];
		var $funct = arguments[2];
	}
	$.ajax({
		url: $url,
		data: $data,
		type: "GET",
		success: function(data){
					if (typeof($funct) == "string"){
						eval($funct + '(data)');
					}
					else if (typeof($funct) == "function"){
						$funct(data);
					}
				},
		error: function(jqXHR, textStatus, errorThrown){
					var $errormessage = textStatus + " " + errorThrown;
					if (typeof($funct) == "string"){
						eval($funct + '($errormessage)');
					}
					else if (typeof($funct) == "function"){
						$funct($errormessage);	
					}
				}
	});	
}

function post(){
	if (arguments.length == 2){
		var $url = arguments[0];
		var $funct = arguments[1];
		var $data = "";	
	}
	else{
		var $url = arguments[0];
		var $data = arguments[1];
		var $funct = arguments[2];
	}
	$.ajax({
		url: $url,
		data: $data,
		type: "POST",
		success: function(data){
					if (typeof($funct) == "string"){
						eval($funct + '(data)');
					}
					else if (typeof($funct) == "function"){
						$funct(data);
					}
				},
		error: function(jqXHR, textStatus, errorThrown){
					var $errormessage = textStatus + " " + errorThrown;
					if (typeof($funct) == "string"){
						eval($funct + '($errormessage)');
					}
					else if (typeof($funct) == "function"){
						$funct($errormessage);	
					}
				}
	});	
}

function put(){
	if (arguments.length == 2){
		var $url = arguments[0];
		var $funct = arguments[1];
		var $data = "";	
	}
	else{
		var $url = arguments[0];
		var $data = arguments[1];
		var $funct = arguments[2];
	}
	$.ajax({
		url: $url,
		data: $data,
		type: "PUT",
		success: function(data){
					if (typeof($funct) == "string"){
						eval($funct + '(data)');
					}
					else if (typeof($funct) == "function"){
						$funct(data);
					}
				},
		error: function(jqXHR, textStatus, errorThrown){
					var $errormessage = textStatus + " " + errorThrown;
					if (typeof($funct) == "string"){
						eval($funct + '($errormessage)');
					}
					else if (typeof($funct) == "function"){
						$funct($errormessage);	
					}
				}
	});	
}

function del(){
	if (arguments.length == 2){
		var $url = arguments[0];
		var $funct = arguments[1];
		var $data = "";	
	}
	else{
		var $url = arguments[0];
		var $data = arguments[1];
		var $funct = arguments[2];
	}
	$.ajax({
		url: $url,
		data: $data,
		type: "DELETE",
		success: function(data){
					if (typeof($funct) == "string"){
						eval($funct + '(data)');
					}
					else if (typeof($funct) == "function"){
						$funct(data);
					}
				},
		error: function(jqXHR, textStatus, errorThrown){
					var $errormessage = textStatus + " " + errorThrown;
					if (typeof($funct) == "string"){
						eval($funct + '($errormessage)');
					}
					else if (typeof($funct) == "function"){
						$funct($errormessage);	
					}
				}
	});	
}

//eval_form(e, submitbutton)
function eval_form(e, submitbutton){
	var keynum;
	if(window.event){
		keynum = e.keyCode;
	}
	else if(e.which){
		keynum = e.which;
	}
	
	if (keynum == 13){
		$(submitbutton).click();
	}	
}

//requires an element with the id "logout-anchor"
function logout(){
	post('http://vis.cs.pitt.edu/webtest/cs1630Academy/logout.php',function(){
		var data = arguments[0];
		if (data.indexOf("success") != -1){
				window.location.href = window.location.href;
		}
		else{
			$('#logout-anchor').attr("href","logout.php");
			alert("Logout failed.  Please try again.");
		}
	});
}