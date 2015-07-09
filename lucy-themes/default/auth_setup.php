<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Two-Factor Authentication'); getNav(2); ?>
<div class="row">
	<div class="col-md-5">
		<h1>Two-Factor Authentication</h1>
		<p>Take your security to the next level by using a time-sensitive code generated on your phone in conjunction with your email and password.</p>
	</div>
	<div class="col-md-7">
	<?php if($response['data']['tf_secret'] !== "" && $response['data']['tf_secret'] !== null){ ?>
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title">You're all set!</h3>
			</div>
			<div class="panel-body">
				Two-Factor Authentication is enabled for you account.  You are now two times more secure!
			</div>
		</div>
		<p><a class="btn btn-default" role="button" id="disable" data-toggle="modal" href="#disableModal">Disable Two-Factor Authentication</a></p>
		<?php } else { ?>
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Get Started!</h3>
			</div>
			<div class="panel-body">
				All you need is a modern smartphone.  iOS, Android, Windows Phone, or Blackberry will do nicely!
			</div>
		</div>
		<p><a class="btn btn-success" role="button" id="disable" data-toggle="modal" href="#startModal">Enable Two-Factor Authentication</a></p>
	<?php } ?>
	</div>
</div>
<?php getFooter(); ?>
<?php include('assets/modals_auth.php'); ?>
<script type="text/javascript">
$("#startModal #next").bind('click',function(){
	postRequest = $.post("lucy-admin/api/auth_setup.php?s=1", {
		password: $("#startModal #password").val()
	});
	postRequest.done(function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.response.code != 200){
			$("#startModal .form-group").attr('class','form-group has-error');
			$("#startModal #password").val('');
			$("#startModal #password").focus();
		} else {
			$("#startModal").modal("hide");
			$("#secretModal").modal("show");
		}
	});
});
$("#secretModal #enableTFA").bind('click',function(){
	postRequest = $.post("lucy-admin/api/auth_setup.php?s=2");
	postRequest.done(function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.response.code != 200){
			alert(obj.response.message);
		} else {
			$("#secretModal #enableTFA").remove();
			var encodeQR = "otpauth://totp/Lucy?secret=" + obj.response.message;
    		$("#secretModal #qrcode").attr("src","https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=" + encodeURIComponent(encodeQR));
			$("#secretModal #showCode").show();
		}
	});
});
$("#secretModal #next").bind('click',function(){
	$("#secretModal").modal("hide");
	$("#testModal").modal("show");
});
$("#testModal #cancel").bind('click',function(){
	postRequest = $.post("lucy-admin/api/auth_setup.php?s=4");
	postRequest.done(function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.response.code != 200){
			alert(obj.response.message);
		} else {
			window.location.reload();
		}
	});
});
$("#testModal #next").bind('click',function(){
	postRequest = $.post("lucy-admin/api/auth_setup.php?s=3", {
		code: $("#testModal #token").val()
	});
	postRequest.done(function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.response.code != 200){
			$("#testModal .form-group").attr('class','form-group has-error');
			$("#testModal #token").val('');
			$("#testModal #token").focus();
		} else {
			$("#testModal").modal("hide");
			$("#backupModal").modal("show");
			$("#backupModal #backupcode").text(obj.response.message);
		}
	});
});
$("#backupModal #next").bind('click', function(){
	window.location.reload();
});
$("#disableModal #next").bind('click',function(){
	postRequest = $.post("lucy-admin/api/auth_setup.php?s=4", {
		password: $("#disableModal #password").val()
	});
	postRequest.done(function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.response.code != 200){
			$("#disableModal .form-group").attr('class','form-group has-error');
			$("#disableModal #password").val('');
			$("#disableModal #password").focus();
		} else {
			window.location.reload();
		}
	});
});
</script>