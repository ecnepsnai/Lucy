<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Welcome'); 
?>
<style type="text/css">
.row{
	padding-top: 1em;
}
</style>
<?php getNav(1); ?>
<h1>Submit a thread</h1>
<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
<?php foreach($GLOBALS['config']['Support']['Order'] as $input_name){
	switch ($input_name) {
		case 'name': 
		if(!$usr_IsSignedIn){ ?>
			<div class="form-group">
				<label class="col-sm-4 control-label"><?php echo($designer['static']['name']['title']); ?></label>
				<div class="col-sm-4">
					<input type="text" class="form-control" name="name" value="<?php echo($_GET['n']); ?>" required/>
					<p class="help-block"><?php echo($designer['static']['name']['helptext']); ?></p>
				</div>
			</div>
		<?php } break;
		case 'email': 
		if(!$usr_IsSignedIn){ ?>
			<div class="form-group">
				<label class="col-sm-4 control-label"><?php echo($designer['static']['email']['title']); ?></label>
				<div class="col-sm-4">
					<input type="email" class="form-control" name="email" value="<?php echo($_GET['e']); ?>" required/>
					<p class="help-block"><?php echo($designer['static']['email']['helptext']); ?></p>
				</div>
			</div>
		<?php } break;
		case 'password': 
		if(!$usr_IsSignedIn){ ?>
			<div class="form-group">
				<label class="col-sm-4 control-label"><?php echo($designer['static']['password']['title']); ?></label>
				<div class="col-sm-4">
					<input type="password" class="form-control" name="password" required/>
					<p class="help-block"><?php echo($designer['static']['password']['helptext']); ?></p>
				</div>
			</div>
		<?php } break;
		case 'message': ?>
			<div class="form-group">
				<label class="col-sm-4 control-label"><?php echo($designer['static']['message']['title']); ?></label>
				<div class="col-sm-4">
					<textarea name="message" rows="10" cols="75" class="form-control" required></textarea>
					<p class="help-block"><?php echo($designer['static']['message']['helptext']); ?></p>
				</div>
			</div>
		<?php break;
		case 'image': if($GLOBALS['config']['Images']['Enable']){ ?>
			<div class="form-group">
				<label class="col-sm-4 control-label"><?php echo($designer['static']['image']['title']); ?></label>
				<div class="col-sm-4">
					<input type="file" id="screenshot" name="screenshot"/>
					<p class="help-block"><?php echo($designer['static']['image']['helptext']); ?></p>
				</div>
			</div>
		<?php } break;
		default:
			$input = $designer['config'][$input_name]; ?>
			<div class="form-group">
				<label class="col-sm-4 control-label"><?php echo($input['title']); ?></label>
				<div class="col-sm-4">
					<?php
					switch ($input['type']) {
						case 'text':
							?><input type="text" id="<?php echo($input_name); ?>" name="<?php echo($input_name); ?>" min="<?php echo($input['length_min']); ?>" max="<?php echo($input['length_max']); ?>" class="form-control" <?php if($input['required'] === true){ ?>required<?php } ?>/><?php
						break;

						case 'number':
							?><input type="number" id="<?php echo($input_name); ?>" name="<?php echo($input_name); ?>" min="<?php echo($input['length_min']); ?>" max="<?php echo($input['length_max']); ?>" class="form-control" <?php if($input['required'] === true){ ?>required<?php } ?>/><?php
						break;

						case 'select':
							?><select id="<?php echo($input_name); ?>" name="<?php echo($input_name); ?>" class="form-control" <?php if($input['required'] === true){ ?>required<?php } ?>><?php foreach (explode(",", $input['options']) as $key) { echo('<option value="' . $key . '">' . $key . '</option>'); } ?></select><?php
						break;

						case 'textarea':
							?><textarea id="<?php echo($input_name); ?>" name="<?php echo($input_name); ?>" rows="10" cols="75" class="form-control" <?php if($input['required'] === true){ ?>required<?php } ?>></textarea><?php
						break;

						case 'checkbox':
							foreach (explode(",", $input['options']) as $key) { ?><label class="checkbox">	<input type="checkbox" value="<?php echo($key); ?>" name="<?php echo($input_name); ?>" <?php if($input['required'] === true){ ?>required<?php } ?>><?php echo($key); ?></label><?php }
						break;

						case 'radio':
							foreach (explode(",", $input['options']) as $key) { ?><label class="radio">	<input type="radio" value="<?php echo($key); ?>" name="<?php echo($input_name); ?>" <?php if($input['required'] === true){ ?>required<?php } ?>><?php echo($key); ?></label><?php }
						break;
					} ?>
					<p class="help-block"><?php echo($input['helptext']); ?></p>
				</div>
			</div>
			<?php
		break;
	}
} ?>
	<div class="form-group">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-4">
			<input type="submit" name="submit" value="Create thread" class="btn btn-primary" />
		</div>
	</div>
</form>
<?php getFooter(); ?>