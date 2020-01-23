<?php if (!defined('BASEPATH')) exit('No direct script access allowed...');?>
<div class="header bg-gradient-success pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
		</div>
	</div>
</div>
<!-- Page content -->   
<div class="container-fluid mt--7 mb-5">
	<div class="row">
		<div class="col mb-5 mb-xl-0">
			<div class="card bg-default shadow">
				<div class="card-header bg-default">
					<div class="card-tools float-right">
						<button class="btn btn-primary" id="new_create"> <i class="fa fa-fw fa-plus"></i> Add User</button>
					</div>
					<h2 class="text-white">Users</h2>
				</div>
				<div class="table-responsive scrollbar-inner detailed_table">
					<table class="table align-items-center table-dark table-flush table-small-font" id="users_table">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Email Address</th>
								<th scope="col">Contact</th>
								<th scope="col">Address</th>
								<th scope="col">Last Login</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form class='form-horizontal' id="form-user">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i> <span class="title">Create User</span> </h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="header_msg"></div>
					<div class="form-group row">
						<div class="col-6">
							<label>First Name </label>
							<input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name" required autocomplete="off"/>
						</div>
						<div class="col-6">
							<label>Last Name</label>
							<input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name" required autocomplete="off"/>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-12">
							<label>Mobile Number</label>
							<input type="text" class="form-control" id="contact" name="contact" placeholder="Enter Mobile Number" required autocomplete="off"/>
						</div>
					</div>
					<div class="form-group">
						<div class="">
							<label>Username / Email</label>
							<input type="email" class="form-control" id="username" name="username" placeholder="Enter Username" required  autocomplete="off"/>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-12 mb-2">
							<label>Enter Password</label>
							<input type="password" class="form-control" id="userPass" name="contact" placeholder="Enter Password" required autocomplete="off"/>
						</div>
						<div class="col-12">
							<label>Re-Enter Password</label>
							<input type="password" class="form-control" id="userPassCheck" name="contact" placeholder="Re-Enter Password" required disabled autocomplete="off"/>
						</div>
						<div id="pass_msg" class="text-warning ml-2"></div>
					</div>								
					<div class="form-group text-center">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
						<button type="button" id="create" name="create" class="btn btn-primary" disabled>Create</button>
					</div>		
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(function(){
		get_users();
		$("#new_create").click(function(){

			username 	= $("#username").val("");
			upass1 		= $('#userPass').val("");
			upass2 		= $("#userPassCheck").val("");
			fname 		= $("#fname").val("");
			lname 		= $("#lname").val("");
			contact     = $("#contact").val("");

			$("#user-modal").modal('show');
		});

		$("#userPass").keyup(function(){
			if($(this).val()){
				$("#userPassCheck").attr("disabled",false);
			}
			else
				$("#userPassCheck").attr("disabled",true);
		});

		$("#userPassCheck").keyup(function(){
			var username = $("#username").val();
			var upass1 = $('#userPass').val();
			var upass2 = $(this).val();
			 check(upass1,upass2);
			
		});

		function check(upass1,upass2)
		{
			pass_num1 = upass1.length;
			pass_num2 = upass2.length;
			if(upass1 != upass2)
			{
				if(pass_num1 >= 8 || pass_num2 >= 8){
					$("#create").attr("disabled",true);
					$("#pass_msg").addClass("text-warning");
					$("#pass_msg").removeClass("text-success");
					$("#pass_msg").html("<small><b>*Warning!</b> Password not match ,Password must be at least 8 characters</small>");
				}
			}
			else{
				if(username){
					if(pass_num1 >= 8 || pass_num2 >= 8)
					{
						$("#create").attr("disabled",false);
						$("#pass_msg").addClass("text-success");
						$("#pass_msg").removeClass("text-warning");
						$("#pass_msg").addClass("text-success");
						$("#pass_msg").html("<small><b>*Success!</b> Password match.</small>");
					}
					else{
						$("#create").attr("disabled",true);
						$("#pass_msg").addClass("text-warning");
						$("#pass_msg").removeClass("text-success");
						$("#pass_msg").html("<small><b>*Warning!</b> Password not match ,Password must be at least 8 characters</small>");
					}
				}
			}
		}

		$("#create").click(function()
		{
			var username 	= $("#username").val();
			var upass1 		= $('#userPass').val();
			var upass2 		= $("#userPassCheck").val();
			var fname 		= $("#fname").val();
			var lname 		= $("#lname").val();
			var contact     = $("#contact").val();

			$.post(base_url + "users/create",{
				username : username,
				upass1 : upass1,
				upass2 : upass2,
				fname : fname,
				lname : lname,
				contact : contact,
				ajax : 1
			},function(res){
				if(res.result == 'ok')
				{
					$("#header_msg").addClass("alert alert-success");
					$("#header_msg").html("<b>*Success!</b> New user has been created!");
					setTimeout(function(){
						get_users();
						$("#header_msg").removeClass("alert alert-success");
						$("#header_msg").html("");
						$("#pass_msg").removeClass("text-success");
						$("#pass_msg").html("");
						$("#user-modal").modal('hide');
					},3000);
				}
			},'json');
		});
	});

	
	function get_users()
	{
		var users = $("#users_table").DataTable({
			"processing": true,
			"serverSide": true,
			"autoWidth": false,
			"responsive": true,
			"bDestroy": true,
			initComplete: function () {
				var api = this.api();
				delaySearch(api);
			},
			"ajax": {
				url: base_url + "users/get_users", // json datasource
				error: function () {}
			},
			oLanguage: {
				oPaginate: {
					sNext: '<span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
					sPrevious: '<span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
				}
			}
		});
	}

	function delaySearch(api) 
	{
		var searchWait = 0;
		var searchWaitInterval;
		$(".dataTables_filter input")
			.unbind()
			.bind("input", function (e) { 
			var item = $(this);
			searchWait = 0;
			if (!searchWaitInterval) searchWaitInterval = setInterval(function () {
				searchTerm = $(item).val();
				clearInterval(searchWaitInterval);
				searchWaitInterval = '';
				api.search(searchTerm).draw();
				searchWait = 0;
				searchWait++;
			}, 3000);
			return;
		});
	}
</script>