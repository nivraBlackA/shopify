<div class="header bg-gradient-danger pb-8 pt-5 pt-md-8">
	<div class="container-fluid">
		<div class="header-body">

		</div>
	</div>
</div>
<div class="container-fluid mt--7">
	<div class="row">
		<div class="col-xl-8 order-xl-1">
			<div class="card bg-secondary shadow">
				<div class="card-header bg-white border-0">
					<div class="row align-items-center">
						<div class="col-12">
							<h3 class="float-right"><?=strtoupper($type)?> ID: <?=(($type == 'prs') ? $item->prs_id : ((($type == 'drs') ? $item->drs_id : $item->rrs_id)))?></h3>
							<h3 class="mb-0">Tracking No : <?=$item->tracking_no?></h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<!-- <form> -->
						<div>
							<h6 class="heading-small text-muted mb-4"><?=$item->info?> information <span
									class="float-right"><button type="button" class="btn btn-link pt-0"
										data-toggle="collapse" data-target="#pickup_box"><i
											class="fa fa-minus"></i></button></span></h6>
							<div class="pl-lg-4 collapse show" id="pickup_box">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="pickup_name">Customer Name</label>
											<input type="text" id="pickup_name"
												class="form-control form-control-alternative" placeholder="Pickup Name"
												value="<?=$item->cust_name?>" readonly>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="pickup_contact">Customer Number</label>
											<input type="text" id="pickup_contact"
												class="form-control form-control-alternative"
												placeholder="Pickup Contact" value="<?=$item->cust_mobile?>"
												readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label class="form-control-label" for="pickup_address">Customer
												Address</label>
											<input type="text" id="pickup_address"
												class="form-control form-control-alternative"
												placeholder="Pickup Address" value="<?=$item->cust_address?>"
												readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label class="form-control-label" for="pickup_instruction">Customer
												Instruction</label>
											<textarea class="form-control form-control-alternative" id="pickup_instruction"
												placeholder="Pickup Instruction"
												readonly><?=$item->cust_instruction?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<!-- Item Information -->
						<div>
							<h6 class="heading-small text-muted mb-4">Item information <span
									class="float-right"><button type="button" class="btn btn-link pt-0"
										data-toggle="collapse" data-target="#item_box"><i
											class="fa fa-minus"></i></button></span></h6>
							<div class="pl-lg-4 collapse show" id="item_box">
                                <div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label">Packaging Type</label>
											<p><?=$item->packaging_type?></p>
										</div>
                                    </div>
                                    <div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label">COD Amount</label>
											<p>&#8369; <?=number_format($item->cod_amount,2)?></p>
										</div>
									</div>
								</div>
							</div>
                        </div>
                        <hr class="my-4" />
                        <!-- Report Information -->
                        <div>
							<h6 class="heading-small text-muted mb-4">Report information <span
									class="float-right"><button type="button" class="btn btn-link pt-0"
										data-toggle="collapse" data-target="#item_box"><i
											class="fa fa-minus"></i></button></span></h6>
							<div class="pl-lg-4 collapse show" id="item_box">
                                <div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label class="form-control-label">Report Notes</label>
                                            <textarea class="form-control form-control-alternative" id="pickup_instruction"
												placeholder="Courier Notes"
												readonly><?=$item->report_notes?></textarea>
										</div>
                                    </div>
                                    <div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label">Report Signature</label>
											<img src="<?=$item->report_signature?>" class="img img-fluid img-thumbnail">
										</div>
                                    </div>
                                    <div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label">Report Image</label>
											<img src="<?=$item->report_image?>" class="img img-fluid img-thumbnail">
										</div>
									</div>
								</div>
							</div>
                        </div>
					<!-- </form> -->
				</div>
			</div>
        </div>
        <div class="col-xl-4 order-xl-2 mt-5 mt-lg-0">
            <div class="card bg-secondary shadow mb-5">
				<div class="card-header bg-transparent border-0">
					<div class="px-lg-5">
						<img src="<?=base_url("assets/svg/undraw_deliveries.svg")?>" class="img img-fluid"/>
					</div>
				</div>
				<div class="card-body">
					<div class="row mb-2">
						<label class="col form-control-label">Courier Name</label>
						<div class="col-auto text-right small">
							<a href="<?=base_url('/courier/id/'.$item->courier_id)?>" class="text-decoration-none text-gray" target="_blank"><?=ucwords($item->courier_name)?></a>
						</div>
					</div>
					<div class="row mb-2">
						<label class="col form-control-label">EC Name</label>
						<div class="col-auto text-right small">
							<span><?=$item->ec_name?></span>
						</div>
					</div>
                    <div class="row mb-2">
						<label class="col form-control-label">Status Date</label>
						<div class="col-auto text-right small">
							<?=($item->report_status_date) ? date("M d, Y h:i A", strtotime($item->report_status_date)) : ""?>
						</div>
                    </div>
                    <div class="row mb-2">
						<label class="col form-control-label">Report Status</label>
						<div class="col-auto text-right small">
                            <span><?=$item->report_status?></span>
						</div>
					</div>
                   
                </div>
            </div>
        </div>
	</div>
</div>

