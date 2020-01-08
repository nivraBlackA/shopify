<?php if (!defined('BASEPATH')) exit('No direct script access allowed...');?>
<div class="header bg-gradient-danger pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">
			<h1 class="text-white"><?=ucwords($page_name)?></h1>
		</div>
	</div>
</div>
<!-- Page content -->   
<div class="container-fluid mt--7">
	<div class="row">
		<div class="col-12">
			<div class="card bg-default shadow">
				<div class="card-header bg-transparent">
					<div class="row align-items-center">
						<div class="col-3">
							<label class="text-white">Entry Date  </label>
							<div class="input-group datetimepicker1">
								<input type="text" class="form-control datepicker" id="date" value="<?=$date?>">
								<div class="input-group-append">
									<span class="input-group-text" id="basic-addon2"><i class="fas fa-fw fa-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="col-3">
							<label class="text-white">EC Name</label>
							<select name="" class="selectpicker select_name" id="ec_id" data-live-search='true'>
								<?=$select_opt;?>
							</select>
						</div>
					</div>
				</div>
			<div class="table-responsive scrollbar-inner detailed_table">
					<table class="table align-items-center table-dark table-flush table-small-font" id="courier_table">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Courier</th>
								<th scope="col">EC Name</th>
								<th scope="col">Tracking No </th>
								<th scope="col">DRS ID</th>
								<th scope="col">Customer</th>
								<th scope="col">COD Amount</th>
								<th scope="col">Report Signature</th>
								<th scope="col">Report Image</th>
								<th scope="col">Status</th>
								<th scope="col">Latitude</th>
								<th scope="col">Longitude</th>
								<th scope="col">Date Status</th>
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
<script>
$(function(){
	var date = $("#date").val();
	var ec_id = $("#ec_id").val();
	get_table(date,ec_id);

	$("#date").change(function(){
		date = $(this).val();
		ec_id = $("#ec_id").val();
		$(this).datepicker('hide');
		get_table(date,ec_id);
	});


	$(".select_name").change(function(){
		date = $("#date").val();
		ec_id = $(this).val();
		get_table(date,ec_id);
	});


	$(".datepicker").datepicker({
		format: 'yyyy-mm-dd',
		clearBtn : true,
		todayHighlight : true,
		autoClose : true,
	});

	function get_table(date,ec_id)
	{
		list_table = $("#courier_table").DataTable({
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
				url: base_url + "delivery/get_table", // json datasource
				data : {
					date : date,
					ec_id : ec_id,
					status : <?=json_encode($status)?>
				},
				error: function () {}
			},
			'columnDefs': [
				{
					'targets': 0,
					'searchable': true,
					'orderable': true,
					'visibily': true,
					'className': '',
					'render': function (data, type, full, meta) {
						var view = "<a href='" + base_url + "courier/id/" + data.courier_id + "' class='font-weight-bold text-light'>" + data.courier_name + "</br>" +  data.mobile +"</a>";
						return view;
					}
				},
				{
					'targets': 2,
					'searchable': true,
					'orderable': true,
					'visibily': true,
					'className': '',
					'render': function (data, type, full, meta) {
						var view = "<a href='" + base_url + "order/item/drs/" + data.item_id + "' class='font-weight-bold text-light'>" + data.tracking_no +"</a>";
						return view;
					}
				},
				{
					'targets': 4,
					'searchable': true,
					'orderable': true,
					'visibily': true,
					'className': '',
					'render': function (data, type, full, meta) {
						var view = data.cust_name + "</br>" + data.cust_mobile;
						return view;
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
});

</script>
