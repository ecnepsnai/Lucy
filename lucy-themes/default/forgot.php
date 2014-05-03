<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Forgot Password');?>
<style type="text/css">
.row{
	padding-top: 1em;
}
</style>
<?php getNav(999); 

if($change_password == false){
?>
<div class="row">
	<div class="col-md-6">
		<h1>Forgot your password?</h1>
		<p>Don't worry, it can happen to the best of us.  Start off by entering your email address and we'll go from there.</p>
	</div>
	<div class="col-md-6" id="emailVerify">
		<div class="form-horizontal" role="form">
			<div class="form-group">
				<label>Email:</label>
				<div>
					<input type="email" class="form-control" id="email" required/>
				</div>
			</div>
			<div class="form-group">
				<div>
					<button id="email_submit" class="btn btn-default">Verify Identity</button> <a id="entercode">I have a reset PIN</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6" id="pinVerify" style="display:none">
		<div class="alert alert-info" id="checkemail"><strong>Check your Email</strong> - Enter the 6-digit pin from the email here, and then you can reset your password</div>
		<form class="form-horizontal" role="form" method="get">
			<div class="form-group">
				<label>Pin:</label>
				<div>
					<input type="text" maxlength="6" class="form-control" name="p" required/>
				</div>
			</div>
			<div class="form-group">
				<div>
					<button type="submit" class="btn btn-default">Verify Identity</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php getFooter(); ?>
<script type="text/javascript">
$("#email_submit").bind('click',function(){
	postRequest = $.post("lucy-admin/api/password_reset.php", {
		email: $("#email").val()
	});
	postRequest.done(function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.response.code != 200){
			alert(obj.response.message);
			$("#emailVerify .form-group").attr('class','form-group has-error');
			$("#emailVerify #email").val('');
			$("#emailVerify #email").focus();
		} else {
			$("#emailVerify").hide();
			$("#pinVerify").show();
			$("#checkemail").show();
		}
	});
});
$("#entercode").bind('click',function(){
	$("#emailVerify").hide();
	$("#checkemail").hide();
	$("#pinVerify").show();
});
</script>
<?php } else { ?>
<div class="row">
	<div class="col-md-6">
		<h1>Change your password:</h1>
		<p>Use a combination of lowercase and uppercase letters, numbers, and symbols.</p>
	</div>
	<div class="col-md-6">
		<form class="form-horizontal" role="form" method="post">
			<div class="form-group">
				<label>Password:</label>
				<div>
					<input type="password" class="form-control" name="password" required/>
				</div>
			</div>
			<div class="form-group">
				<label>Password (Again):</label>
				<div>
					<input type="password" class="form-control" name="password_2" required/>
				</div>
			</div>
			<div class="form-group">
				<div>
					<button type="submit" id="email_submit" class="btn btn-success">Change Password</button>
				</div>
			</div>
			<input type="hidden" name="email" value="<?php echo($user_email); ?>" />
			<input type="hidden" name="token" value="<?php echo($_GET['p']); ?>" />
		</form>
	</div>
</div>
<?php getFooter(); }