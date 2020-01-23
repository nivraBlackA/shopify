<!-- Header -->
<?php if (!defined('BASEPATH')) exit('No direct script access allowed...');?>
<div class="header bg-gradient-success pb-4 pt-2 pt-md-8">

	<div class="container-fluid">
		<div class="header-body mb-3">
			<h4 class="text-white text-xl pb-5">
				DASHBOARD
			</h4>
</div>
			<!-- Card stats -->
			<div class="row">
				<div class="col-xl-3 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<a href="<?=base_url("courier")?>">
								<div class="row">
									<div class="col">
										<h5 class="card-title text-uppercase text-muted mb-0">Total Orders</h5>
										<span class="h2 font-weight-bold mb-0"><?=$count_orders?></span>
									</div>
									<div class="col-auto">
										<div class="icon icon-shape bg-info text-white rounded-circle shadow">
											<i class="fas fa-shopping-cart"></i>
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
										<h5 class="card-title text-uppercase text-muted mb-0">Total COD Amount</h5>
										<span class="h2 font-weight-bold mb-0"><?=$cod_amount?></span>
									</div>
									<div class="col-auto">
										<div class="icon icon-shape bg-success text-white rounded-circle shadow">
											<i class="fas fa-dollar-sign"></i>
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
										<h5 class="card-title text-uppercase text-muted mb-0">Number of Users</h5>
										<span class="h2 font-weight-bold mb-0"><?=$count_user?></span>
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
				<div class="col-xl-3 col-lg-6">
					<div class="card card-stats mb-4 mb-xl-0">
						<div class="card-body">
							<a href="<?=base_url("users")?>">
								<div class="row">
									<div class="col">
										<h5 class="card-title text-uppercase text-muted mb-0">Number of Install</h5>
										<span class="h2 font-weight-bold mb-0"><?=$count_install?></span>
									</div>
									<div class="col-auto">
										<div class="icon icon-shape bg-warning text-white rounded-circle shadow">
											<i class="fas fa-download"></i>
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



<script>
	// 	$(function(){
	// 		$('#'){

	// 		}

	// });
</script>