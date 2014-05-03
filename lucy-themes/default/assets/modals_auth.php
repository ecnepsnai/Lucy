<div class="modal fade" id="startModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Verify your Password</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-5 control-label">Your Password</label>
						<div class="col-sm-7">
							<input type="password" class="form-control" id="password"/>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" class="btn btn-primary" id="next">Next</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="secretModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Set up Authenticator App</h4>
			</div>
			<div class="modal-body">
				<button id="enableTFA" class="btn btn-primary">Generate QR Code for Authenticator App</button>
				<div class="form-horizontal" role="form" id="showCode" style="display:none;">
					<div class="form-group">
						<label class="col-sm-5 control-label">QR Code:</label>
						<div class="col-sm-7">
							<img id="qrcode" height="200" width="200" alt="QR Code" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label">Code Generating Apps:</label>
						<div class="col-sm-7">
							<ul>
								<li><a href="https://itunes.apple.com/ca/app/authy/id494168017?mt=8">Authy (iOS)</a></li>
								<li><a href="https://itunes.apple.com/ca/app/google-authenticator/id388497605?mt=8">Google Authenticator (iOS)</a>
								<li><a href="https://play.google.com/store/apps/details?id=com.authy.authy">Authy (Android)</a></li>
								<li><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">Google Authenticator (Android)</a></li>
								<li><a href="http://www.windowsphone.com/en-ca/store/app/authenticator/e7994dbc-2336-4950-91ba-ca22d653759b">Authenticator (Windows Phone)</a></li>
								<li><a href="http://appworld.blackberry.com/webstore/content/22517879/">Authomator (BlackBerry 10)</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" class="btn btn-primary" id="next">Next</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="testModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Verifying Authentication Token</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-5 control-label">Authentication Token:</label>
						<div class="col-sm-7">
							<input type="text" id="token" maxlength="6" class="form-control" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" id="cancel">My code isn't working</button>
				<button type="button" class="btn btn-primary" id="next">Next</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="backupModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Your Backup Key</h4>
			</div>
			<div class="modal-body">
				<p>If your code generator isn't working, you can use this code to get access into your account.  Keep this code handy, and don't share it with anybody!</p>
				<h2 id="backupcode"></h2>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="next">Finish!</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="disableModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Verify your Password</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-5 control-label">Your Password</label>
						<div class="col-sm-7">
							<input type="password" class="form-control" id="password"/>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" class="btn btn-primary" id="next">Next</button>
			</div>
		</div>
	</div>
</div>