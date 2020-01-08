<div class="header bg-gradient-danger pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">

		</div>
	</div>
</div>
<div class="container-fluid mt--7">
	<div class="row">
		<div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
			<div class="card card-profile shadow">
				<div class="row justify-content-center">
					<div class="col-lg-3 order-lg-2">
						<div class="card-profile-image">
							<a href="#">
								<img src="http://wms.blackarrow.express/sandbox/uploads/avatars/pig.png"
									class="rounded-circle">
							</a>
						</div>
					</div>
				</div>
				<div class="card-header text-center border-0 pt-8 pt-4 pb-0">
					<div class="d-flex justify-content-between">
						<a href="#" class="btn btn-sm btn-info mr-4"><i class="fa fa-paper-plane"></i> Send
							Notification</a>
						<a href="#" class="btn btn-sm btn-default float-right"><i class="fa fa-envelope"></i> Send
							Message</a>
					</div>
				</div>
				<div class="card-body">
					<div class="text-center">
						<h3>
							<?=$courier_data->courier_name?>
						</h3>
						<div class="h5 font-weight-300">
							<?=$courier_data->mobile?>
						</div>
						<div class="h5 mt-4">
							<span class="badge badge-primary"><?=$courier_data->ec_name?></span>
						</div>

						<hr class="my-4" />
						<ul class="list-group small list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center border-0">Courier ID
								<span class=""><?=$courier_data->courier_id?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">Company
								<span class=""><?=$courier_data->company?></span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-8 order-xl-1">
			<div class="card bg-default shadow mb-3">
				<div class="card-header bg-transparent">
					<div class="row align-items-center mb-3">
						<div class="col-4">
							<h3 class="mb-0 text-white"><?=(($type == 'prs') ? "Pickup" :  (($type == 'drs') ? "Delivery" : "Return"));?> Run Sheets</h3>
						</div>
						<div class="col-8 text-right">
							<a href="<?=base_url('courier/id/'.$courier_data->courier_id.'?type=prs')?>" class="btn btn-sm <?=(($type=='prs') ? "btn-primary" : "btn-danger")?>  opt_btn mb-1">Pickup</a>
							<a href="<?=base_url('courier/id/'.$courier_data->courier_id.'?type=drs')?>" class="btn btn-sm <?=(($type=='drs') ? "btn-primary" : "btn-danger")?> opt_btn mb-1">Delivery</a>
							<a href="<?=base_url('courier/id/'.$courier_data->courier_id.'?type=rrs')?>" class="btn btn-sm <?=(($type=='rrs') ? "btn-primary" : "btn-danger")?> opt_btn mb-1">Return</a>
						</div>
					</div>
					<div class="row">
						<div class="input-group input-group-sm col-3">
							<input type="text" class="form-control datepicker" name="date" id="rundate" value="<?=$date?>">
							<div class="input-group-append">
								<span class="input-group-text" id="basic-addon2"><i class="fas fa-fw fa-calendar"></i></span>
							</div>
						</div>	
						<div class="input-group input-group-sm col-3">
							<select name="status" class="form-control status">
								<option value="ongoing">Ongoing</option>
								<option value="failed">Failed</option>
								<option value="success">Success</option>
							</select>
						</div>
						<div class="input-group input-group-sm col-3">
							<button class="btn btn-danger btn-sm filter">Filter</button>
						</div>
					</div>	
				</div>
				<div class="table-responsive scrollbar-inner">
				<!-- Projects table -->
				<table class="table align-items-center table-dark table-flush" id="runsheet_table">
					<thead class="thead-dark">
					<tr>
						<th scope="col"><?=(($type == 'prs') ? "PRS ID" : (($type == 'drs') ? "DRS ID" : "RRS ID"))?></th>
						<th scope="col">Tracking No</th>
						<th scope="col">Customer</th>
						<th scope="col">COD Amount</th>
						<th scope="col">Status</th>
						<th scope="col">Status Date</th>
					</tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
				</div>
			</div>
			<div class="card bg-secondary shadow">
				<div class="card-header bg-white border-0">
					<div class="row align-items-center">
						<div class="col-8">
							<h3 class="mb-0">Courier Information</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="pl-lg-4">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label class="form-control-label" for="input-username">EC Coordinator</label>
									<input type="text" id="input-username" class="form-control form-control-alternative"
										placeholder="Coordinator" value="<?=$courier_data->coordinator?>" readonly>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label class="form-control-label" for="input-email">Email address</label>
									<input type="email" id="input-email" class="form-control form-control-alternative"
										placeholder="jesse@example.com" value="<?=$courier_data->email?>" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
                                <div class="form-group">
									<label class="form-control-label" for="input-first-name">EC Description</label>
									<input type="text" id="input-ec-description"
										class="form-control form-control-alternative" placeholder="First name" value="<?=$courier_data->desc?>" readonly>
								</div>
							</div>
							<div class="col-lg-6">
                                <div class="form-group">
									<label class="form-control-label" for="input-first-description">EC Number</label>
									<input type="text" id="input-ec-number"
										class="form-control form-control-alternative" placeholder="First name" value="<?=$courier_data->cellno?>" readonly>
								</div>
							</div>
                        </div>
                        <div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="form-control-label" for="input-first-name">EC Address</label>
									<textarea class="form-control" disabled><?=$courier_data->address?></textarea>
								</div>
							</div>
                        </div>
					</div>
				</div>
			</div>
			<div class="card bg-secondary shadow mt-2">
				<div class="card-header bg-white border-0">
					<div class="row align-items-center">
						<div class="col-8">
							<h3 class="mb-0">Runs</h3>
							
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row mb-3">
						<div class="input-group input-group-sm col-4" style="z-index: 1000;">
							<input type="text" class="form-control datepicker" name="date" id="filter_date" value="<?=$date?>">
							<div class="input-group-append">
								<span class="input-group-text" id="basic-addon2"><i class="fas fa-fw fa-calendar"></i></span>
							</div>
						</div>	
						<div class="input-group input-group-sm col-4">
							<select name="accuracy" class="form-control accuracy_opt">
								<option value="">--All--</option>
								<?php foreach($select_opt as $key => $opt) :
									$selected = ($key == $accuracy) ? "selected" : ""?>
									<option <?=$selected?> value="<?=$key?>"><?=$opt?></option>
								<?php endforeach;?>
							</select>
						</div>
						<div class="input-group input-group-sm col-4">
							<button class="btn btn-primary btn-sm filter_marker">Filter</button>
						</div>
					</div>
					<div id="map" style="width: 100%; height : 300px">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var init = false;
	var map;
	$(function(){
		var latlngs 	= [];
		var markerArr 	= [];
		var courier_id = <?=$courier_data->courier_id?>;
		var type  = <?=json_encode($type)?>;
		var status = $(".status").val();
		var rundate = $("#rundate").val();

		get_courier_runsheet(courier_id,type,status,rundate);
	
		$(".datepicker").datepicker({
			format: 'yyyy-mm-dd',
			clearBtn : true,
			todayHighlight : true,
			autoClose : true
		});

		$(".filter").click(function(){
			status = $(".status").val();
			rundate = $("#rundate").val();
			get_courier_runsheet(courier_id,type,status,rundate);
		});

		latlngs 	= JSON.parse('<?=($locationArr)?>');
		markerArr 	= JSON.parse('<?=json_encode($markerArr)?>');


		addMarker(latlngs,markerArr);

		$(".filter_marker").click(function(){
			latlngs = "";
			markerArr = "";
			var filter_date = $("#filter_date").val();
			var accuracy_opt = $(".accuracy_opt").val();
			$.get(base_url + "courier/id/" + courier_id,{
				ajaxx : 1,
				date : filter_date,
				accuracy : accuracy_opt
			},function(res){
				if(res.result == 'ok'){
					latlngs = JSON.parse(res.data.locationArr);
					markerArr = res.data.markerArr;
					addMarker(latlngs,markerArr);
				}
			},'json');
		});	
	});

	function addMarker(latlngs,markerArr)
	{
		// console.log(latlngs);
		// console.log(markerArr);
		var themarker = {};
		if(!init){
				map = L.map('map').setView([14.524262, 121.042273], 13);
				L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
				}).addTo(map);
			// create a red polyline from an array of LatLng points
				var polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);
			// zoom the map to the polyline
				map.fitBounds(polyline.getBounds());
			
				init = true;
		}
		markerArr.forEach(mark => {
			L.marker([mark.lat,mark.lng]).addTo(map);
		});
	

	}

	function get_courier_runsheet(courier_id,type,status,rundate)
	{
		var rs = type;
		var courier = $("#runsheet_table").DataTable({
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
				url: base_url + "courier/get_courier_runsheet", // json datasource
				type : 'GET',
				data : {
					courier_id	:	courier_id,
					type	:	type,
					status	:	status,
					date	:	rundate
				},
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
						var view = "<a href='"+ base_url +"order/item/"+ rs +"/"+ data.item_id +"' class='font-weight-bold text-light'>" + data.tracking_no + "</a>";
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
</script>