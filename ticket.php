<?php
	require("session.php");
	if(!$usr_IsSignedIn){
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "login.php?rdirect=ticket.php?id=" . $_GET['id'] . "&notice=login\">Redirecting...");
	}
	$id = $_GET['id'];
	if(empty($id)){
		require("error_empty.php");
	}
	require("db_connect.php");
	$id = mysql_real_escape_string($id);
	//User chose to close the ticket.
	if(isset($_POST['close']) && $_POST['close'] == "CloseTicket"){
		$sql = "UPDATE ticketlist SET status = 'Closed' WHERE  id = '" . $id . "';";
		$request = mysql_query($sql);
		if(!$request){
			require("error_db.php");
		}
		$sql = "INSERT INTO " . $id . " (`From`, `Date`, `Message`) VALUES ('Client', '" . date("Y-m-d H:i:s")  . "', 'CLOSED');";
		$request = mysql_query($sql);
		if(!$request){
			require("error_db.php");
		}
	}
	//User added a reply
	if(isset($_POST['reply']) && $_POST['reply'] == "ReplyToTicket"){
		if(empty($_POST['message'])){
			require("error_empty.php");
		}
		$message = mysql_real_escape_string($_POST['message']);
		$isFile = False;
		$filename = $_FILES['screenshot']['tmp_name'];

		//get files - if any //
		if($filename != "") {
			$isFile = True;
			$handle = fopen($filename, "r");
			$data = fread($handle, filesize($filename));
			$pvars = array('image' => base64_encode($data), 'key' => API_IMGUR);
			$timeout = 30;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://api.imgur.com/2/upload.json');
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
			$json = curl_exec($curl);
			curl_close ($curl);
			$data = json_decode($json,true);
			$img_hash = $data["upload"]["image"]["hash"];
		} else {
			$img_hash = "";
		}
		$sql = "INSERT INTO " . $id . " (`From`, `Date`, `Message`,`File`) VALUES ('Client', '" . date("Y-m-d H:i:s")  . "', '" . $message . "', '" . $img_hash . "');";
		$request = mysql_query($sql);
		if(!$request){
			require("error_db.php");
		}
	}
	$sql = "SELECT * FROM ticketlist WHERE id = '" . $id . "' AND email = '" . $usr_Email . "'";
	$request = mysql_query($sql);
	if(!$request){
		require("error_db.php");
	}
	if(mysql_num_rows($request) <= 0){
		require("error_empty.php");
	}
	$ticket_info = mysql_fetch_array($request);
	$sql = "SELECT * FROM " . $id;
	$request = mysql_query($sql);
	if(!$request){
		require("error_db.php");
	}
	if(mysql_num_rows($request) <= 0){
		require("error_empty.php");
	}
?>
<!doctype html>
<title><?php echo(TITLE_TICKET . TITLE_SEPARATOR . TITLE_MAIN) ?></title>
<link rel="stylesheet" href="img/loader.css">
<link href="img/styles.css" rel="stylesheet" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<div id="wrapper">
<?php require("mdl_header.php"); ?>
<div id="content">
<h2>Ticket Status</h2>
<table id="ticket_info">
	<tr>
		<td>
			<strong>Application:</strong> <?php echo($ticket_info['application']); ?><br/>
			<strong>Version:</strong> <?php echo($ticket_info['version']); ?><br/>
			<strong>Operating System:</strong> <?php echo($ticket_info['os']); ?><br/>
			<strong>Status:</strong> <?php echo($ticket_info['status']); ?>
		</td>
		<td>
			<strong>Ticket ID:</strong> <?php echo($ticket_info['id']); ?><br/>
			<strong>Name:</strong> <?php echo($ticket_info['name']); ?><br/>
			<strong>Email:</strong> <?php echo($ticket_info['email']); ?><br/>
			<strong>Date Created:</strong> <?php echo(date_format(date_create($ticket_info['date']), 'l, F jS \a\t g:i a')); ?> 
		</td>
	</tr>
</table>
<?php
	while($message = mysql_fetch_array($request)) {
		if($message['From'] == "Client"){
				if($message['Message'] == "CLOSED") {
					?>
<div class="notice" id="yellow"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> you closed this ticket.</strong></div>
					<?php
				} else { ?>
<div class="msgc">
	<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> you said:</strong><br/><?php echo($message['Message']); ?>
	<?php if($message['File'] != ""){
		?>
	<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
		<?php
	} ?>
</div><?php } } else {
				if($message['Message'] == "CLOSED") {
					?>
		<div class="notice" id="yellow"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> Lucy closed this ticket.</strong></div>
					<?php
				} else { ?>
<div class="msga">
	<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> Lucy said:</strong><br/><?php echo($message['Message']); ?>
	<?php if($message['File'] != ""){
		?>
	<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
		<?php
	} ?>
</div><?php } } } if($ticket_info['status'] == "Open") { ?>
<script type="text/javascript">
function hideTools() {
	$('#ticket_options').hide();
	$('#ticket_reply').show();
}
</script>
<div id="ticket_options">
	You can <button onclick="parent.location='javascript:hideTools()'" value="Reply to this ticket" class="btn" id="gray">Reply to this ticket</button> or <form method="POST" style="display:inline"><input type="hidden" name="close" value="CloseTicket"/><input type="submit" name"submit" value="Close this ticket" class="btn" id="gray"/></form>
</div>
<div id="ticket_reply" style="display:none">
	<form method="POST">
		<input type="hidden" name="reply" value="ReplyToTicket"/>
		<textarea name="message" rows="10" cols="75" class="txtglow" placeholder="Type your reply here"></textarea><br/>
		<p>Include a screenshot? (<em>Optional</em> - <a href="help_screenshots.php">Help</a>)<br/>
		<input type="file" name="screenshot" /></p>
		<input type="submit" name"reply" value="Add Reply" class="btn" id="blue"/>
	</form>
</div>
<?php } ?>
</div>
<?php require("mdl_footer.php"); ?>
</div>