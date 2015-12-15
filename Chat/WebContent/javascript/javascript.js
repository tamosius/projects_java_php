$(document).ready(function(){
	var textAreaScroll = document.getElementById("text_area");  // variable to scroll down 'text_area' with messages written
	var userName;  // user name for chat (on the top of window)
	$('#input_name').removeAttr('disabled');  // remove 'disabled' attribute from text box for name input
	$('#button_name').removeAttr('disabled'); // remove 'disabled' attribute from button 'Ok' beside text box for name input
	$('#input_text').attr('disabled', 'disabled'); // disable input for messages until the user name has not been entered
	$('#input_name').val("");  // clear input for the name field
	$('#input_name').focus();   // focus on input name for chat user when a page loads up
	
/* ---------- WHEN USER EXITS CHAT BY CLOSING WINDOW OR BY REFRESHING THE PAGE, REMOVE HIS NAME FROM CHAT SERVER -------------------- */
	
	window.onbeforeunload = function(){
		$.post("ChatConnections", {"nameToRemove" : userName});
	};
	
	
/*----------- SHOW USERS WHO ARE CURRENTLY CONNECTED ------------------------------------------------------------------- */	
	showConnected();  // execute this function straight away when page loads up, to see who are connected, the use 'setInterval' function
	
	var connections = setInterval(showConnected, 5000);
	
	function showConnected(){  // show users who are connected
		
		if(typeof userName != "undefined"){
			
			$.get("ChatConnections", {"name" : userName}, function(data){
				
				$('.middle_panel #connections_area').text("");
				
				$.each(data, function(key, value){
					
						$('.middle_panel #connections_area').append("<div class='connected_users'>" +
								key + "<span class='ip_connected'>IP:" + value + "</span></div>");
					
				}); // end outer 'each'
			}); // end 'get'
		}else{
			
			$.get("ChatConnections", function(data){
				
				$('.middle_panel #connections_area').text("");
				
				$.each(data, function(key, value){
					
						$('.middle_panel #connections_area').append("<div class='connected_users'>" +
								key + "<span class='ip_connected'>IP:" + value + "</span></div>");
					
				}); // end outer 'each'
			}); // end 'get'
		}
	} // end function 'showConnected()'

/* -------- BUTTON CLICK 'SUBMIT NAME' -------------------------------------------------------------------------------- */
	$('#button_name').click(function(){
		
		userName = $('#input_name').val().trim();
		
		if(userName == ""){
			return false;
		}
		
		$('#input_name').attr('disabled', 'disabled');
		$('#button_name').attr('disabled', 'disabled');
		$('#input_text').removeAttr('disabled'); // enable text area for message input after user enters a name
		$('#input_text').focus();  // focus on message input text area after name has been entered
		
		showConnected();  // show that you are connected straight away after name has been entered
		startCallingServlet(); // if name entered, start calling servlet to receive messages
	});
/* --------- SUBMIT NAME ALSO ON 'ENTER' KEY PRESS -------------------------------------------------------------------- */
	$('#input_name').keypress(function(event){
		if(event.keyCode == 13){
			userName = $(this).val().trim();
			
			if(userName == ""){
				return false;
			}
			
			$('#input_name').attr('disabled', 'disabled');
			$('#button_name').attr('disabled', 'disabled');
			$('#input_text').removeAttr('disabled'); // enable text area for message input after user enters a name
			$('#input_text').focus();  // focus on message input text area after name has been entered
			
			showConnected();  // show that you are connected straight away after name has been entered
			startCallingServlet(); // if name entered, start calling servlet to receive messages
		}	
	});
	
/* ---------- SUBMIT MESSAGE, SENT TO SERVER --------------------------------------------------------------------------- */
	$('form').submit(function(){
		var message = $('#input_text').val();
		
		if(message.trim() == ""){
			return false;
		}
		
		$('.bottom_panel #input_text').val("");
		
		$.post("ChatServlet", {"message" : message, "name" : userName, "delete" : ""}); // send message to the server and store into the database
		
		var startMessagesDate;
		var messagesDate;
		
		$.get("ChatServlet", function(data){  // get messages from the database
			$('#text_area').text("");
			$.each(data, function(names, messagesArray){
				$.each(messagesArray, function(key, value){
					messagesDate = new Date(value.date);
					
					if(typeof startMessagesDate == 'undefined' || (startMessagesDate < messagesDate)){
						$('.middle_panel #text_area').append("<div class='date'>-------------------------------- " + value.date + " -------------------------------------------</div>");
						startMessagesDate = new Date(value.date);
					}
					
					if(value.name == userName){
						$('.middle_panel #text_area').append("<div class='myMessage'><div class='name'>" + value.name + 
								":<p>(You)</p></div><div class='message'>" + value.message + 
								"<p class='time'>" + value.time + "</p></div></div>");
					}else{
						$('.middle_panel #text_area').append("<div class='otherMessage'><div class='name'>" + value.name + 
								":</div><div class='message'>" + value.message + 
								"<p class='time'>" + value.time + "</p></div></div>");
					}
					
					
				});
			});
			//$(".middle_panel #text_area").animate({ scrollTop: $(document).height() }, "slow");  /* keeps the scrollbar always at the bottom of textarea */
			textAreaScroll.scrollTop = textAreaScroll.scrollHeight;
	    });
		
		
		return false; // without this did not worked (loaded new page from start)
	});
/* ------------ SUBMIT MESSAGE ON 'ENTER' KEY PRESS ------------------------------------------------------------------ */
	$('#input_text').keypress(function(event){
		if(event.keyCode == 13){
			$(this.form).submit();
			
			//textAreaScroll.scrollTop = textAreaScroll.scrollHeight;  // scroll down 'text_area' with chat messages
			
			return false;
		}
	});
	
/* -------- DELETE MY MESSAGES ---------------------------------------------------------------------------------------- */
	$('#button_delete').click(function(){
		var delete_my_messages = "delete_my_messages";
		
		$.post("ChatServlet", {"name" : userName, "message" : "", "delete" : delete_my_messages});
	});
	
/* -------- START CALLING SERVER TO RECEIVE MESSAGES ------------------------------------------------------------------- */
	
	function startCallingServlet(){
		
		callServlet();
		
		setTimeout(function(){
			textAreaScroll.scrollTop = textAreaScroll.scrollHeight;
		}, 1000);
		
		var itervalLoop = setInterval(callServlet, 1000);
		
	}
	
	//var textAreaScroll = document.getElementById("text_area");
	
	
	function callServlet(){
		
		var startMessagesDate;
		var messagesDate;
		
		$.get("ChatServlet", function(data){
			$('#text_area').text("");
			$.each(data, function(names, messagesArray){
				$.each(messagesArray, function(key, value){
					messagesDate = new Date(value.date);
					
					if(typeof startMessagesDate == 'undefined' || (startMessagesDate < messagesDate)){
						$('.middle_panel #text_area').append("<div class='date'>-------------------------------- " + value.date + " -------------------------------------------</div>");
						startMessagesDate = new Date(value.date);
					}
					
					if(value.name == userName){
						$('.middle_panel #text_area').append("<div class='myMessage'><div class='name'>" + value.name + 
								":<p>(You)</p></div><div class='message'>" + value.message + 
								"<p class='time'>" + value.time + "</p></div></div>");
					}else{
						$('.middle_panel #text_area').append("<div class='otherMessage'><div class='name'>" + value.name + 
								":</div><div class='message'>" + value.message + 
								"<p class='time'>" + value.time + "</p></div></div>");
					}
					
					
				});
			});
			//$(".middle_panel #text_area").animate({ scrollTop: $(document).height() }, "slow");  /* keeps the scrollbar always at the bottom of textarea */
			//textAreaScroll.scrollTop = textAreaScroll.scrollHeight;
	    });
	}

	
	
});