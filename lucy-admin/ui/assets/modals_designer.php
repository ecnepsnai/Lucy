<!-- Text Modal -->
<div class="modal fade" id="textModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add Text Box</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(); ?>
				</div>
				<a href="#" onClick="$('#adv_text').toggle();">Advance Settings</a>
				<div id="adv_text" style="display:none;">
					<div class="form-horizontal" role="form">
						<div class="form-group">
							<label class="col-sm-5 control-label">Min Length</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="number" class="form-control" id="length_min" value="1" min="1"/>
									<span class="input-group-addon">characters</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Max Length</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="number" class="form-control" id="length_max" value="65535" max="65535"/>
									<span class="input-group-addon">characters</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"></label>
							<div class="col-sm-7">
								<label class="checkbox"><input type="checkbox" id="acpt_num" checked> Accept Numbers</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"></label>
							<div class="col-sm-7">
								<label class="checkbox"><input type="checkbox" id="acpt_sym" checked> Accept Symbols</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="addInput('text')">Add</button>
			</div>
		</div>
	</div>
</div>

<!-- Number Modal -->
<div class="modal fade" id="numberModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add Number Box</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(); ?>
				</div>
				<a href="#" onClick="$('#adv_number').toggle();">Advance Settings</a>
				<div id="adv_number" style="display:none;">
					<div class="form-horizontal" role="form">
						<div class="form-group">
							<label class="col-sm-5 control-label">Min Length</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="number" class="form-control" id="length_min" value="1" min="1"/>
									<span class="input-group-addon">characters</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Max Length</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="number" class="form-control" id="length_max" value="65535" max="65535"/>
									<span class="input-group-addon">characters</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="addInput('number')">Add</button>
			</div>
		</div>
	</div>
</div>

<!-- Select Modal -->
<div class="modal fade" id="selectModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add Dropdown Menu</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(); ?>
					<div class="form-group">
						<label class="col-sm-5 control-label">Choices</label>
						<div class="col-sm-7">
							<ol id="dropdownItems"></ol>
							<a href="#" id="addDropbownItem" class="btn btn-primary">Add Item</a>
							<input type="hidden" id="dropdownOptions" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="addInput('select')">Add</button>
			</div>
		</div>
	</div>
</div>

<!-- Textarea Modal -->
<div class="modal fade" id="textareaModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add Textarea</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(); ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="addInput('textarea')">Add</button>
			</div>
		</div>
	</div>
</div>

<!-- Checkbox Modal -->
<div class="modal fade" id="checkboxModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add Checkbox</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(); ?>
					<div class="form-group">
						<label class="col-sm-5 control-label">Choices</label>
						<div class="col-sm-7">
							<ol id="checkboxItems"></ol>
							<a href="#" id="addCheckboxItem" class="btn btn-primary">Add Item</a>
							<input type="hidden" id="checkboxOptions" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="addInput('checkbox')">Add</button>
			</div>
		</div>
	</div>
</div>

<!-- Radio Modal -->
<div class="modal fade" id="radioModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add Radio Button</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(); ?>
					<div class="form-group">
						<label class="col-sm-5 control-label">Choices</label>
						<div class="col-sm-7">
							<ol id="radioItems"></ol>
							<a href="#" id="addRadioItem" class="btn btn-primary">Add Item</a>
							<input type="hidden" id="radioOptions" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="addInput('radio')">Add</button>
			</div>
		</div>
	</div>
</div>

<!-- Edit Text Modal -->
<div class="modal fade" id="edittextModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Text Box</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(false); ?>
				</div>
				<a href="#" onClick="$('#adv_edit_text').toggle();">Advance Settings</a>
				<div id="adv_edit_text" style="display:none;">
					<div class="form-horizontal" role="form">
						<div class="form-group">
							<label class="col-sm-5 control-label">Min Length</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="number" class="form-control" id="length_min" value="1" min="1"/>
									<span class="input-group-addon">characters</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Max Length</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="number" class="form-control" id="length_max" value="65535" max="65535"/>
									<span class="input-group-addon">characters</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"></label>
							<div class="col-sm-7">
								<label class="checkbox"><input type="checkbox" id="acpt_num" checked> Accept Numbers</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"></label>
							<div class="col-sm-7">
								<label class="checkbox"><input type="checkbox" id="acpt_sym" checked> Accept Symbols</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" id="editSaveBtn">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Edit Number Modal -->
<div class="modal fade" id="editnumberModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Number Box</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(false); ?>
				</div>
				<a href="#" onClick="$('#adv_edit_number').toggle();">Advance Settings</a>
				<div id="adv_edit_number" style="display:none;">
					<div class="form-horizontal" role="form">
						<div class="form-group">
							<label class="col-sm-5 control-label">Min Length</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="number" class="form-control" id="length_min" value="1" min="1"/>
									<span class="input-group-addon">characters</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Max Length</label>
							<div class="col-sm-7">
								<div class="input-group">
									<input type="number" class="form-control" id="length_max" value="65535" max="65535"/>
									<span class="input-group-addon">characters</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" id="editSaveBtn">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Edit Select Modal -->
<div class="modal fade" id="editselectModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Dropdown Menu</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(false); ?>
					<div class="form-group">
						<label class="col-sm-5 control-label">Choices</label>
						<div class="col-sm-7">
							<ol id="dropdownItems"></ol>
							<a href="#" id="addDropbownItem" class="btn btn-primary">Add Item</a>
							<input type="hidden" id="dropdownOptions" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" id="editSaveBtn">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Edit Textarea Modal -->
<div class="modal fade" id="edittextareaModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Textarea</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(false); ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" id="editSaveBtn">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Edit Checkbox Modal -->
<div class="modal fade" id="editcheckboxModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Checkbox</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(false); ?>
					<div class="form-group">
						<label class="col-sm-5 control-label">Choices</label>
						<div class="col-sm-7">
							<ol id="checkboxItems"></ol>
							<a href="#" id="addCheckboxItem" class="btn btn-primary">Add Item</a>
							<input type="hidden" id="checkboxOptions" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" id="editSaveBtn">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Edit Radio Modal -->
<div class="modal fade" id="editradioModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Radio Button</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<?php input_general_items(false); ?>
					<div class="form-group">
						<label class="col-sm-5 control-label">Choices</label>
						<div class="col-sm-7">
							<ol id="radioItems"></ol>
							<a href="#" id="addRadioItem" class="btn btn-primary">Add Item</a>
							<input type="hidden" id="radioOptions" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" id="editSaveBtn">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Name Constant Modal -->
<div class="modal fade" id="nameModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit name Input</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-5 control-label">Question Title</label>
						<div class="col-sm-7">
							<input type="text" id="title" class="form-control" placeholder="What's your Name?"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label">Help Text</label>
						<div class="col-sm-7">
							<input type="text" id="help" class="form-control" placeholder="We need your name because..."/>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="updateConstant('name')">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Email Constant Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit email Input</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-5 control-label">Question Title</label>
						<div class="col-sm-7">
							<input type="text" id="title" class="form-control" placeholder="What's your Email?"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label">Help Text</label>
						<div class="col-sm-7">
							<input type="text" id="help" class="form-control" placeholder="We need your email because..."/>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="updateConstant('email')">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Password Constant Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit password Input</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-5 control-label">Question Title</label>
						<div class="col-sm-7">
							<input type="text" id="title" class="form-control" placeholder="Choose a password"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label">Help Text</label>
						<div class="col-sm-7">
							<input type="text" id="help" class="form-control" placeholder="We need your password because..."/>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="updateConstant('password')">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Message Constant Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit message Input</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-5 control-label">Question Title</label>
						<div class="col-sm-7">
							<input type="text" id="title" class="form-control" placeholder="Enter a message"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label">Help Text</label>
						<div class="col-sm-7">
							<input type="text" id="help" class="form-control" placeholder="Make it as detailed as possible"/>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="updateConstant('message')">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Image Constant Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit image Input</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-5 control-label">Question Title</label>
						<div class="col-sm-7">
							<input type="text" id="title" class="form-control" placeholder="Enter a message"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label">Help Text</label>
						<div class="col-sm-7">
							<input type="text" id="help" class="form-control" placeholder="Make it as detailed as possible"/>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" onClick="updateConstant('image')">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Delete Input Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete Input</h4>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete this input?  You cannot undo this.
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" data-dismiss="modal" class="btn btn-primary" id="delinputBtn">Delete</button>
			</div>
		</div>
	</div>
</div>

<?php

function input_general_items($includeName = true){ ?>
<?php if($includeName){ ?>
<div class="form-group">
	<label class="col-sm-5 control-label">Input Name (non-descriptive)</label>
	<div class="col-sm-7">
		<input type="text" class="form-control" id="name" placeholder="serialnumber"/>
	</div>
</div>
<?php } ?>
<div class="form-group">
	<label class="col-sm-5 control-label">Question Title</label>
	<div class="col-sm-7">
		<input type="text" id="title" class="form-control" placeholder="Serial Number"/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-5 control-label">Help Text</label>
	<div class="col-sm-7">
		<input type="text" class="form-control" id="help" placeholder="You can find your serial number here..."/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-5 control-label"></label>
	<div class="col-sm-7">
		<label class="checkbox"><input type="checkbox" id="req"> Required</label>
	</div>
</div>
<?php }