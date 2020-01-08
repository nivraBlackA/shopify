<?php if (!defined('BASEPATH')) exit('No direct script access allowed...');?>
<div class="header bg-gradient-danger pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<!-- Card stats -->
		</div>
	</div>
</div>
<!-- Page content -->   
<div class="container-fluid mt--7">
	<div class="row">
		<div class="col-12">
			<div class="card bg-default shadow">
				<div class="card-header bg-default">
					<div class="card-tools float-right">
						<form action="<?=base_url('courier/download_list')?>" method="get">
							<button type="submit" class="btn btn-sm btn-danger">Download</button>
							<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#access-modal">Create Access</button>
						</form>
					</div>
					<h2 class="text-white">Courier List</h2>
				</div>
				<div class="table-responsive scrollbar-inner detailed_table">
					<table class="table align-items-center table-dark table-flush table-small-font" id="courier_table">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Courier ID</th>
								<th scope="col">Courier Name </th>
								<th scope="col">EC Name</th>
								<!-- <th scope="col">EC Coordinator</th> -->
								<th scope="col">Status</th>
								<th scope="col">Version</th>
								<th scope="col">Date Registered</th>
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

<div class="modal fade" id="access-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-uppercase">Create Access</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
         		 <span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="disp_msg"></div>
				<div class="row mb-0">
					<div class="form-group col-6">
						<label>EC Name</label><br>
						<select name="" class="selectpicker form-control ec_opt" id="ec_name" data-live-search='true'>
							<?php echo $ec_opt;?>
						</select>
					</div>
					<div class="form-group courier_select col-6"></div>
				</div>
				<div class="row mb-3">
					<div class="col-6 mb-2">
						<label>Courier ID</label><br>
						<input type="text" class="form-control" id="id" placeholder="Courier ID" disabled>
					</div>
					<div class="col-6">
						<label>Date Started</label><br>
						<input type="text" class="form-control" id="date_start" placeholder="Date Started" disabled>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-6 mb-2">
						<label>Fullname</label><br>
						<input type="text" class="form-control" id="fullname" placeholder="Enter Fullname" disabled>
					</div>
					<div class="col-6 mb-2">
						<label>Contact No</label><br>
						<input type="text" class="form-control" id="contact" placeholder="Enter Contact No" disabled>
					</div>
					<div class="col-6">
						<label>Company</label><br>
						<input type="text" class="form-control" id="company" placeholder="Enter Company" disabled>
					</div>
					<div class="col-6">
						<label>Password</label><br>
						<input type="text" class="form-control" id="pass_text" placeholder="Enter Password">
						</select>
					</div>
					
				</div>
				<div class="row">
					<div class="col-12 text-right">
						<input type="hidden" id="ecid">
						<input type="hidden" id="ecname">
						<button type="button" class="btn btn-warning text-uppercase" data-dismiss="modal" aria-label="Close"> Cancel</button>
						<button type="button" class="btn btn-success text-uppercase" id="generate"><i class="fas fa-fw fa-key"></i> Create</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(function(){
		var ec_id  = $(".ec_opt").val();
		courier_table();
		courier_info(ec_id);

		var courierdata = {};

		$(".ec_opt").change(function(){
			ec_id = $(this).val();
			$("#fullname").val("");
			$("#contact").val("");
			$("#pass_text").val("");
			$("#id").val("");
			$("#company").val("");
			$("#date_start").val("");
			courier_info(ec_id);
		
		});

		$(".courier_select").on("change",".courier_opt",function(){
			var courier_id = $(this).val();
			$.get(base_url + "courier/get_couriername/",{
				id : courier_id,
				ec_id : ec_id
			},function(res){	
				// console.log(res);
				$("#ecid").val(res.ec_id);
				$("#ecname").val(res.ec_name);
				$("#fullname").val(res.full_name);
				$("#contact").val(res.contact_no);
				$("#pass_text").val(res.pass_text);
				$("#id").val(res.id);
				$("#company").val(res.company);
				$("#date_start").val(res.date_start);
				courierdata = res;
			},'json');
		});

	//postdata
		$("#generate").click(function(){
			var fullname 	= 	$("#fullname").val();
			var contact 	= 	$("#contact").val();
			var courier_id 	= 	$("#id").val();
			var company 	= 	$("#company").val();
			var pass_text 	= 	$("#pass_text").val();
			var ecid 		= 	$("#ecid").val();
			var ecname 		= 	$("#ecname").val();

			$.post(base_url + "courier/postdata",{
				fullname	:	fullname,
				contact		:	contact,
				courier_id	:	courier_id,
				company		:	company,
				pass_text	:	pass_text,
				ec_id		:	ecid,
				ec_name		:	ecname
			},function(res){
				if(res.result == 'ok')
				{
					$(".disp_msg").removeClass('d-none');
					$(".disp_msg").addClass('alert alert-success');
					$(".disp_msg").text("Successfully saved!!");
				
				}
				else if(res.result == 'warning')
				{
					$(".disp_msg").removeClass('d-none');
					$(".disp_msg").addClass('alert alert-warning');
					$(".disp_msg").text("This courier name already has an access!!!, Data not Save..");
				}

				setTimeout(function(){
					$(".disp_msg").addClass('d-none');
					$("#fullname").val("");
					$("#contact").val("");
					$("#company").val("");
					$("#date_start").val("");
					$("#pass_text").val("");
					$("#id").val("");
					$("#ecid").val("");
					$("#ecname").val("");
					courier_table();
					courier_info(ecid);
				},3000);
			},'json');
		});

		//end here
	});

function courier_table(){
	var courier = $("#courier_table").DataTable({
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
			url: base_url + "courier/get_table", // json datasource
			type : 'GET',
			error: function () {}
		},
		'columnDefs': [
			{
				'targets': 1,
				'searchable': true,
				'orderable': true,
				'visibily': true,
				'className': '',
				'render': function (data, type, full, meta) {
					var user_image = "<img src='" + data.img_url + "' class='rounded-circle' style='height : 40px;'>";
					var view = "<a href='"+ base_url +"courier/id/"+ data.courier_id +"' class='font-weight-bold text-light'>" + data.name + "<br/>" + data.mobile + "<br/>" + data.company;
					var imagebox = "<div class='d-flex'><div class='px-2'>"+user_image+"</div><div class='text-muted'>"+view+"</div></div></a>";
					return imagebox;
				}
			}
		],
		oLanguage: {
			oPaginate: {
				sNext: '<span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
				sPrevious: '<span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
			}
		}
	});
}

function courier_info(ec_id)
	{
		$.get(base_url + "courier/get_courier/" + ec_id,{
			ajax : 1
		},
		function(res){
			// console.log(res);
			opt = "";
			opt += "<label>Courier Name</label>";
			opt += "<select class='selectpicker form-control courier_opt' id='ec_id' data-live-search='true'>";
			opt += "<option value=''></option>";
			
			if(res){
				opt = "";
				opt += "<label>Courier Name</label>";
				opt += "<select class='selectpicker form-control courier_opt' id='ec_id' data-live-search='true'>";
				opt += "<option value=''></option>";
				
				res.forEach(val => {
					opt += "<option value='"+ val.id +"'>"+ val.id + " - " + val.name +"</option>";
				});
			}

			opt += "</select>";
			$(".courier_select").html(opt);
			$(".selectpicker").selectpicker("refresh");
		},'json');
	}

function delaySearch(api) {
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