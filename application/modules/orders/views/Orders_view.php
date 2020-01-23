<?php if (!defined('BASEPATH')) exit('No direct script access allowed...');?>
<div class="header bg-gradient-success pb-9 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<h2 class="text-white"><?=$page_title?></h2>
		</div>
	</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--7 mb-5">
	<div class="row">
		<div class="col mb-5 mb-xl-0 ">
			<div class="card bg-gradient-default shadow">
				<div class="card-header bg-gradient-default">

				</div>
				<div class="table-responsive scrollbar-inner detailed_table bg-gradient-default">
					<table class="table align-items-center table-flush table-small-font text-white" id="users_table">
						<thead class="thead-success">
							<tr>
								<th scope="col">Id</th>
								<th scope="col">Sender</th>
								<th scope="col">Recipient</th>
								<th scope="col">remarks</th>
								<th scope="col">cust actual kg</th>
								<th scope="col">cust applied kg</th>
								<th scope="col">box type</th>
								<th scope="col">Payment</th>
								<th scope="col">declared value</th>
								<th scope="col">oder date</th>
								<th scope="col">last modified</th>
								<th scope="col">cancel date</th>
								<th scope="col">entry date</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	// $(function(){
	// 	get_users();
	// 	$("#new_create").click(function(){

	// 		username 	= $("#username").val("");
	// 		upass1 		= $('#userPass').val("");
	// 		upass2 		= $("#userPassCheck").val("");
	// 		fname 		= $("#fname").val("");
	// 		lname 		= $("#lname").val("");
	// 		contact     = $("#contact").val("");

	// 		$("#user-modal").modal('show');
	// 	});
	$(function () {
		base_url = "<?=base_url()?>";

		$('#users_table').DataTable({
			"processing": true,
			"serverSide": true,
			"autoWidth": true,
			"responsive": true,
			"bDestroy": true,
			"ajax": {
				url: base_url + "orders/get_orders",
				type: 'GET'

			},
			oLanguage: {
				oPaginate: {
					sNext: '<span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
					sPrevious: '<span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
				}
			}
		});

	});


</script>

<!-- 
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
</script> -->