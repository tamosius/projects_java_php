<%@ page language="java" contentType="text/html; charset=UTF-8"
	pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="css/content.css" type="text/css" rel="stylesheet" />
<script src="javascript/jquery-1.11.3.js"></script>
<script src="javascript/javascript.js"></script>
<title>Chat Client</title>
</head>
<body>
	<div class="main_container">

		<div class="top_panel">
			<input type="text" id="input_name" name="input_name" maxlength="9"
				placeholder="submit your name to start chat.." />
			<button type="button" id="button_name">Submit Name</button>
			<button type="button" id="button_delete">Delete My Messages</button>
		</div>

		<div class="middle_panel">
			<div id="text_area"></div>
			<div id="connections_area"></div>
		</div>


		<div class="bottom_panel">
			<form action="#" method="post">
				<textarea disabled rows="1" cols="78" id="input_text" name="message" placeholder="type message here..."/></textarea>
				<button type="submit" id="send_button">Send</button>
			</form>
		</div>


	</div>


</body>
</html>