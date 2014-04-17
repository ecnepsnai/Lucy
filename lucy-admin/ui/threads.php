<?php
	require("../session.php");
	require("../cda.php");
	require("default.php");

	// Administrator or Agent access only.
	if($usr_Type != "Admin" && $usr_Type != "Agent"){
		lucy_die(0);
	}

	getHeader("Threads");
	getNav(1);
	if($_GET['notice'] == 'del'){ ?>
	<div class="alert alert-info">Thread Deleted</div>
	<?php } ?>
<div class="row">
	<div class="col-md-2">
		<ul class="nav nav-pills nav-stacked">
			<li class="active"><a href="#your" id="tab_your" data-toggle="tab">Assigned to you<span class="badge pull-right" id="num_your" style="display:none"></span></a></li>
			<li><a href="#all" id="tab_all" data-toggle="tab">All Threads<span class="badge pull-right" id="num_all" style="display:none"></span></a></li>
			<li><a href="#closed" id="tab_closed" data-toggle="tab">Closed<span class="badge pull-right" id="num_closed" style="display:none"></span></a></li>
		</ul>
	</div>
	<div class="col-md-10">
		<div class="tabbable">
			<div class="tab-content">
				<div id="your" class="tab-pane active">
					<h2>Your Threads</h2>
					<table class="table table-hover">
						<thead>
							<tr>
								<th><strong>Subject</strong></th>
								<th><strong>Status</strong></th>
								<th><strong>Date</strong></th>
								<th><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody id="threads_mine">
						</tbody>
					</table>
				</div>
				<div id="all" class="tab-pane">
					<h2>All Threads</h2>
					<table class="table table-hover">
						<thead>
							<tr>
								<th><strong>Subject</strong></th>
								<th><strong>Status</strong></th>
								<th><strong>Date</strong></th>
								<th><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody id="threads_all">
						</tbody>
					</table>
				</div>
				<div id="closed" class="tab-pane">
					<h2>Closed Threads</h2>
					<table class="table table-hover">
						<thead>
							<tr>
								<th><strong>Subject</strong></th>
								<th><strong>Date</strong></th>
								<th><strong>Actions</strong></th>
							</tr>
						</thead>
						<tbody id="threads_closed">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php getFooter(); ?>
<script type="text/javascript">
var loadedMyThreads = false;
var loadedAllThreads = false;
var loadedClosedThreads = false;
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	if(e.target.id == "tab_all"){
		if(loadedAllThreads !== true){
			var postRequest = $.post("../api/admin_threads_list.php", {
				f: "all"
			});

			postRequest.done(function(obj){
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					obj.response.data.forEach(function(thread){
						var tr = document.createElement("tr");
						var subject_td = document.createElement("td");
						$(subject_td).text(thread.subject + '...');
						tr.appendChild(subject_td);
						var status_td = document.createElement("td");
						if(thread.status == "Active"){
							$(status_td).html('<span class="label label-success">Active</span>');
						} else if(thread.status == "Pending"){
							$(status_td).html('<span class="label label-info">Pending</span>');
						}
						tr.appendChild(status_td);
						var date_td = document.createElement("td");
						$(date_td).text(thread.date);
						tr.appendChild(date_td);
						var id_td = document.createElement("td");
						var va = document.createElement("a");
						va.href = "view_thread.php?id=" + thread.id;
						$(va).html('<span class="glyphicon glyphicon-eye-open"></span>');
						var da = document.createElement("a");
						da.href = "del_thread.php?id=" + thread.id;
						$(da).html('<span class="glyphicon glyphicon-remove"></span>');
						id_td.appendChild(va);
						id_td.appendChild(da);
						tr.appendChild(id_td);
						document.getElementById("threads_all").appendChild(tr);
					});
					loadedAllThreads = true;
				}
			});
		}
	} else if(e.target.id == "tab_closed"){
		if(loadedClosedThreads !== true){
			var postRequest = $.post("../api/admin_threads_list.php", {
				f: "closed"
			});

			postRequest.done(function(obj){
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					obj.response.data.forEach(function(thread){
						var tr = document.createElement("tr");
						var subject_td = document.createElement("td");
						$(subject_td).text(thread.subject + '...');
						tr.appendChild(subject_td);
						var date_td = document.createElement("td");
						$(date_td).text(thread.date);
						tr.appendChild(date_td);
						var id_td = document.createElement("td");
						var va = document.createElement("a");
						va.href = "view_thread.php?id=" + thread.id;
						$(va).html('<span class="glyphicon glyphicon-eye-open"></span>');
						var da = document.createElement("a");
						da.href = "del_thread.php?id=" + thread.id;
						$(da).html('<span class="glyphicon glyphicon-remove"></span>');
						id_td.appendChild(va);
						id_td.appendChild(da);
						tr.appendChild(id_td);
						document.getElementById("threads_closed").appendChild(tr);
					});
					loadedClosedThreads = true;
				}
			});
		}
	}
});
$(function(){
var postRequest = $.post("../api/admin_threads_list.php", {
	f: "mine"
});

postRequest.done(function(obj){
	if(obj.response.code != 200){
		alert(obj.response.message);
	} else {
		obj.response.data.forEach(function(thread){
			var tr = document.createElement("tr");
			var subject_td = document.createElement("td");
			$(subject_td).text(thread.subject + '...');
			tr.appendChild(subject_td);
			var status_td = document.createElement("td");
			if(thread.status == "Active"){
				$(status_td).html('<span class="label label-success">Active</span>');
			} else if(thread.status == "Pending"){
				$(status_td).html('<span class="label label-info">Pending</span>');
			}
			tr.appendChild(status_td);
			var date_td = document.createElement("td");
			$(date_td).text(thread.date);
			tr.appendChild(date_td);
			var id_td = document.createElement("td");
			var va = document.createElement("a");
			va.href = "view_thread.php?id=" + thread.id;
			$(va).html('<span class="glyphicon glyphicon-eye-open"></span>');
			var da = document.createElement("a");
			da.href = "del_thread.php?id=" + thread.id;
			$(da).html('<span class="glyphicon glyphicon-remove"></span>');
			id_td.appendChild(va);
			id_td.appendChild(da);
			tr.appendChild(id_td);
			document.getElementById("threads_mine").appendChild(tr);
		});
		loadedMyThreads = true;
	}
});
});
</script>