<!-- Header -->
<?php if (!defined('BASEPATH')) exit('No direct script access allowed...');?>
<div class="header bg-gradient-danger pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body mb-3">
			<!-- Card stats -->
			<div class="row">
				<div class="col-xl-3 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<a href="<?=base_url("courier")?>">
								<div class="row">
									<div class="col">
										<h5 class="card-title text-uppercase text-muted mb-0">Courier</h5>
										<span class="h2 font-weight-bold mb-0"><?=$courier?></span>
									</div>
									<div class="col-auto">
										<div class="icon icon-shape bg-danger text-dark rounded-circle shadow">
											<i class="fas fa-motorcycle"></i>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<a href="<?=base_url("users")?>">
								<div class="row">
									<div class="col">
										<h5 class="card-title text-uppercase text-muted mb-0">Users</h5>
										<span class="h2 font-weight-bold mb-0"><?=$users?></span>
									</div>
									<div class="col-auto">
										<div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
											<i class="fas fa-users"></i>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid mt-4">
		<div class="row">
			<div class="col-12">
				<div class="card mb-3 bg-primary shadow">
					<div class="card-header bg-transparent">
						<h4 class="text-uppercase text-white">Pickup Run Sheets 
						<span class="float-right"><button type="button" class="btn btn-link pt-0 text-white" data-toggle="collapse" data-target="#prs_box"><i class="fa fa-minus"></i></button></span>
						</h4>
						<div class="card-body" id="prs_box">
							<div class="row mb-2">
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("pickup?status=ongoing")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Ongoing</h5>
														<span class="h2 font-weight-bold mb-0"><?=$prs_ongoing?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-primary text-white rounded-circle shadow">
															<i class="fas fa-hourglass-half"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("pickup?status=failed")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Failed</h5>
														<span class="h2 font-weight-bold mb-0"><?=$prs_failed?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
															<i class="fas fa-ban"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("pickup?status=success")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Success</h5>
														<span class="h2 font-weight-bold mb-0"><?=$prs_success?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-success text-white rounded-circle shadow">
															<i class="fas fa-check"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("pickup")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">All</h5>
														<span class="h2 font-weight-bold mb-0"><?=$prs_all?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-info text-white rounded-circle shadow">
															<i class="fas fa-file-excel"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Delivery -->
				<div class="card mb-3 bg-success shadow">
					<div class="card-header bg-transparent">
						<h4 class="text-uppercase text-white">Delivery Run Sheets 
						<span class="float-right"><button type="button" class="btn btn-link pt-0 text-white" data-toggle="collapse" data-target="#rrs_box"><i class="fa fa-minus"></i></button></span>
						</h4>
						<div class="card-body" id="rrs_box">
							<div class="row mb-2">
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("delivery?status=ongoing")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Ongoing</h5>
														<span class="h2 font-weight-bold mb-0"><?=$drs_ongoing?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-primary text-white rounded-circle shadow">
															<i class="fas fa-hourglass-half"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("delivery?status=failed")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Failed</h5>
														<span class="h2 font-weight-bold mb-0"><?=$drs_failed?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
															<i class="fas fa-ban"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("delivery?status=success")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Success</h5>
														<span class="h2 font-weight-bold mb-0"><?=$drs_success?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-success text-white rounded-circle shadow">
															<i class="fas fa-check"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("delivery")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">All</h5>
														<span class="h2 font-weight-bold mb-0"><?=$drs_all?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-info text-white rounded-circle shadow">
															<i class="fas fa-file-excel"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- return -->
				<!-- Delivery -->
				<div class="card bg-warning shadow">
					<div class="card-header bg-transparent">
						<h4 class="text-uppercase text-white">Return Run Sheets 
						<span class="float-right"><button type="button" class="btn btn-link pt-0 text-white" data-toggle="collapse" data-target="#drs_box"><i class="fa fa-minus"></i></button></span>
						</h4>
						<div class="card-body" id="drs_box">
							<div class="row mb-2">
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("returns?status=ongoing")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Ongoing</h5>
														<span class="h2 font-weight-bold mb-0"><?=$rrs_ongoing?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-primary text-white rounded-circle shadow">
															<i class="fas fa-hourglass-half"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("returns?status=failed")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Failed</h5>
														<span class="h2 font-weight-bold mb-0"><?=$rrs_failed?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
															<i class="fas fa-ban"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("returns?status=success")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">Success</h5>
														<span class="h2 font-weight-bold mb-0"><?=$rrs_success?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-success text-white rounded-circle shadow">
															<i class="fas fa-check"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-xl-3 col-lg-6">
									<div class="card card-stats mb-4 mb-xl-0">
										<div class="card-body">
											<a href="<?=base_url("returns")?>">
												<div class="row">
													<div class="col">
														<h5 class="card-title text-uppercase text-muted mb-0">All</h5>
														<span class="h2 font-weight-bold mb-0"><?=$rrs_all?></span>
													</div>
													<div class="col-auto">
														<div class="icon icon-shape bg-info text-white rounded-circle shadow">
															<i class="fas fa-file-excel"></i>
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3>Courier Run Sheet</h3>
					</div>
					<div class="card-body">
						<form action="<?=base_url('home')?>" method="GET">
						<div class="row mb-2">
							<div class="col-3">
								<!-- <label>EC Name</label> -->
								<select class="selectpicker form-control select_name" name="ec_id" id="ec_id" data-live-search='true'>
									<?=$select_opt;?>
								</select>
							</div>
							<div class="col-3">
								<!-- <label>Entry Date</label> -->
								<div class="input-group datetimepicker1">
									<input type="text" class="form-control datepicker" name="date" id="date" value="<?=$date;?>">
									<div class="input-group-append">
										<span class="input-group-text" id="basic-addon2"><i class="fas fa-fw fa-calendar"></i></span>
									</div>
								</div>
							</div>
							<div class="col-3">
								<div class="input-group">
									<button type="submit" class="btn btn-primary">Filter</button>
								</div>
							</div>
						</div>
						</form>
						<div class="row z">
							<div class="table-responsive scrollbar-inner detailed_table">
								<table class="table align-items-center table-bordered table-dark table-small-font" id="courier_table">
									<thead class="thead-dark">
										<tr>
											<th scope="col" rowspan="2" class="text-center">Courier</th>
											<th scope="col" colspan="3" class="text-center">Pickup</th>
											<th scope="col" colspan="3" class="text-center">Delivery</th>
											<th scope="col" colspan="3" class="text-center">Return</th>
										</tr>
										<tr>
											<th scope="col">Ongoing</th>
											<th scope="col">Failed</th>
											<th scope="col">Success</th>
											<th scope="col">Ongoing</th>
											<th scope="col">Failed</th>
											<th scope="col">Success</th>
											<th scope="col">Ongoing</th>
											<th scope="col">Failed</th>
											<th scope="col">Success</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($courier_info as $info):?>
											<?php foreach($courier_table as $courier_id => $xdata):?>
												<?php if($info->courier_id == $courier_id):?>
													<tr>
														<td><a href="<?=base_url('courier/id/'.$info->courier_id)?>" class="text-white"><?=$info->courier_name?></a></td>
														<td class="text-center"><?=$xdata['prs']->ongoing?></td>
														<td class="text-center"><?=$xdata['prs']->failed?></td>
														<td class="text-center"><?=$xdata['prs']->success?></td>

														<td class="text-center"><?=$xdata['drs']->ongoing?></td>
														<td class="text-center"><?=$xdata['drs']->failed?></td>
														<td class="text-center"><?=$xdata['drs']->success?></td>

														<td class="text-center"><?=$xdata['rrs']->ongoing?></td>
														<td class="text-center"><?=$xdata['rrs']->failed?></td>
														<td class="text-center"><?=$xdata['rrs']->success?></td>
													</tr>
												<?php endif;?>
											<?php endforeach;?>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(function(){
		$(".datepicker").datepicker({
			format: 'yyyy-mm-dd',
			clearBtn : true,
			todayHighlight : true,
			autoClose : true
		});
	});
</script>