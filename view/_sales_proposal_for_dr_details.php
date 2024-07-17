<?php
    $hidden = '';
    if($salesProposalStatus == 'Released'){
        $hidden = 'd-none';
    }

    $generatePDC = $userModel->checkSystemActionAccessRights($user_id, 153);
?>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <li><a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="pill" href="#v-basic-details" role="tab" aria-controls="v-basic-details" aria-selected="true" disabled>Basic Details</a></li>
                    <li><a class="nav-link d-none" id="sales-proposal-tab-2" data-bs-toggle="pill" href="#v-unit-details" role="tab" aria-controls="v-unit-details" aria-selected="false" disabled>Unit Details</a></li>
                    <li><a class="nav-link d-none" id="sales-proposal-tab-3" data-bs-toggle="pill" href="#v-fuel-details" role="tab" aria-controls="v-fuel-details" aria-selected="false" disabled>Fuel Details</a></li>
                    <li><a class="nav-link d-none" id="sales-proposal-tab-4" data-bs-toggle="pill" href="#v-refinancing-details" role="tab" aria-controls="v-refinancing-details" aria-selected="false" disabled>Refinancing Details</a></li>
                    <li><a class="nav-link" id="sales-proposal-tab-5" data-bs-toggle="pill" href="#v-job-order" role="tab" aria-controls="v-job-order" aria-selected="false" disabled>Job Order</a></li>
                    <li><a class="nav-link" id="sales-proposal-tab-6" data-bs-toggle="pill" href="#v-pricing-computation" role="tab" aria-controls="v-pricing-computation" aria-selected="false" disabled>Pricing Computation</a></li>
                    <li><a class="nav-link" id="sales-proposal-tab-7" data-bs-toggle="pill" href="#v-other-charges" role="tab" aria-controls="v-other-charges" aria-selected="false" disabled>Other Charges</a></li>
                    <li><a class="nav-link" id="sales-proposal-tab-8" data-bs-toggle="pill" href="#v-renewal-amount" role="tab" aria-controls="v-renewal-amount" aria-selected="false" disabled>Renewal Amount</a></li>
                    <li><a class="nav-link" id="sales-proposal-tab-9" data-bs-toggle="pill" href="#v-amount-of-deposit" role="tab" aria-controls="v-amount-of-deposit" aria-selected="false" disabled>Amount of Deposit</a></li>
                    <li><a class="nav-link" id="sales-proposal-tab-10" data-bs-toggle="pill" href="#v-additional-job-order" role="tab" aria-controls="v-additional-job-order" aria-selected="false" disabled>Additional Job Order</a></li>
                    <li><a class="nav-link" id="sales-proposal-tab-11" data-bs-toggle="pill" href="#v-confirmations" role="tab" aria-controls="v-confirmations" aria-selected="false" disabled>Confirmations</a></li>
                    <li><a class="nav-link" id="sales-proposal-tab-12" data-bs-toggle="pill" href="#v-summary" role="tab" aria-controls="v-summary" aria-selected="false" disabled>Summary</a></li>
                    <li><a class="nav-link <?php echo $hidden; ?>" id="sales-proposal-tab-13" data-bs-toggle="pill" href="#v-pdc-manual-input" role="tab" aria-controls="v-pdc-manual-input" aria-selected="false" disabled>PDC Manual Input</a></li>
                    <li><a class="nav-link <?php echo $hidden; ?>" id="sales-proposal-tab-14" data-bs-toggle="pill" href="#v-release-details" role="tab" aria-controls="v-release-details" aria-selected="false" disabled>Release Details</a></li>
                    <li><a class="nav-link <?php echo $hidden; ?>" id="sales-proposal-tab-15" data-bs-toggle="pill" href="#v-printable-report" role="tab" aria-controls="v-printable-report" aria-selected="false" disabled>Printable Report</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div class="d-flex wizard justify-content-between mb-3">
                        <div class="first">
                            <a href="javascript:void(0);" id="first-step" class="btn btn-secondary disabled">First</a>
                        </div>
                        <div class="d-flex">
                            <?php
                                if($salesProposalStatus == 'For DR'){
                                    echo '<div class="previous me-2 d-none" id="add-sales-proposal-pdc-manual-input-button">
                                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#generate-pdc-offcanvas" aria-controls="generate-pdc-offcanvas" id="add-sales-proposal-pdc-manual-input">Add PDC</button>
                                        </div>';

                                    echo '<div class="previous me-2 d-none" id="add-sales-proposal-deposit-amount-button">
                                        <button class="btn btn-primary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-deposit-amount-offcanvas" aria-controls="sales-proposal-deposit-amount-offcanvas" id="add-sales-proposal-deposit-amount">Add Amount of Deposit</button>
                                      </div>';

                                    echo '<div class="previous me-2 d-none" id="summary-print-button">
                                        <a href="javascript:window.print()" class="btn btn-outline-info me-1" id="print">Print</a>
                                    </div>';
                                }

                                if($salesProposalStatus == 'Released'){
                                    $salesProposalRepaymentExist = $salesProposalModel->checkSalesProposalRepaymentExist($salesProposalID);
                                    $repaymentTotal = $salesProposalRepaymentExist['total'] ?? 0;

                                    if($generatePDC['total'] > 0 && $repaymentTotal == 0){
                                        echo '<div class="previous me-2">
                                                <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#generate-pdc-offcanvas" aria-controls="generate-pdc-offcanvas">Generate Repayment PDC</button>
                                            </div>';
                                    }
                                  }

                                if(!empty($startDate) && !empty($drNumber) && $salesProposalStatus == 'For DR'){
                                    echo '<div class="previous me-2">
                                            <button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-tag-as-released-offcanvas" aria-controls="sales-proposal-tag-as-released-offcanvas" >Release</button>
                                        </div>';
                                }

                                if($salesProposalStatus == 'Released'){
                                    echo '<div class="previous me-2 d-none" id="gatepass-print-button">
                                        <a href="javascript:window.print()" class="btn btn-outline-info me-1" id="print">Print</a>
                                    </div>';
                                }
                            ?>
                            <div class="previous me-2">
                                <a href="javascript:void(0);" id="previous-step" class="btn btn-secondary disabled">Back To Previous</a>
                            </div>
                            <div class="next">
                                <a href="javascript:void(0);" id="next-step" class="btn btn-secondary mt-3 mt-md-0">Next Step</a>
                            </div>
                        </div>
                        <?php
                        if($salesProposalStatus == 'Released'){
                            echo '<div class="last">
                                    <a href="javascript:void(0);" id="last-step2" class="btn btn-secondary mt-3 mt-md-0">Last</a>
                                </div>';
                        }
                        ?>
                    </div>
                    <div id="bar" class="progress mb-3" style="height: 7px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%"></div>
                    </div>
                    <div class="tab-pane show active" id="v-basic-details">
                        <input type="hidden" id="sales_proposal_status" value="<?php echo $salesProposalStatus; ?>">
                        <form id="sales-proposal-form" method="post" action="#">
                        <input type="hidden" id="sales_proposal_type" value="details">
                        <div class="row">
                            <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Sales Proposal Number :</label>
                                <label class="col-lg-8 col-form-label" id="sales_proposal_number">--</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Client ID :</label>
                                <label class="col-lg-8 col-form-label" id="customer-id"> <?php echo $customerID; ?></label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Client Name :</label>
                                <label class="col-lg-8 col-form-label"> <?php echo strtoupper($customerName); ?></label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Address :</label>
                                <label class="col-lg-8 col-form-label"> <?php echo strtoupper($customerAddress); ?></label>
                            </div>
                            </div>
                            <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Sales Proposal Status :</label>
                                <div class="col-lg-8 mt-3">
                                <?php echo $salesProposalStatusBadge; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Email :</label>
                                <label class="col-lg-8 col-form-label"> <?php echo strtoupper($customerEmail); ?></label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Mobile :</label>
                                <label class="col-lg-8 col-form-label"> <?php echo $customerMobile; ?></label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Telephone :</label>
                                <label class="col-lg-8 col-form-label"> <?php echo $customerTelephone; ?></label>
                            </div>
                            </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Renewal Tag : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                <select class="form-control select2" name="renewal_tag" id="renewal_tag">
                                    <option value="">--</option>
                                    <option value="New">New</option>
                                    <option value="Renewal">Renewal</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Company : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                <select class="form-control select2" name="company_id" id="company_id">
                                    <option value="">--</option>
                                    <option value="1">Christian General Motors Inc.</option>
                                    <option value="3">FUSO Tarlac</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Product Type : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                <select class="form-control select2" name="product_type" id="product_type">
                                    <option value="">--</option>
                                    <option value="Unit">Unit</option>
                                    <option value="Fuel">Fuel</option>
                                    <option value="Parts">Parts</option>
                                    <option value="Repair">Repair</option>
                                    <option value="Rental">Rental</option>
                                    <option value="Brand New">Brand New</option>
                                    <option value="Refinancing">Refinancing</option>
                                    <option value="Restructure">Restructure</option>
                                    <option value="Real Estate">Real Estate</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Transaction Type : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                <select class="form-control select2" name="transaction_type" id="transaction_type">
                                    <option value="">--</option>
                                    <option value="COD">COD</option>
                                    <option value="Installment Sales">Installment Sales</option>
                                    <option value="Bank Financing">Bank Financing</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Financing Institution :</label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control text-uppercase" id="financing_institution" name="financing_institution" maxlength="200" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Co-Maker :</label>
                                <div class="col-lg-7">
                                <select class="form-control select2" name="comaker_id" id="comaker_id">
                                    <option value="">--</option>
                                    <?php echo $customerModel->generateComakerOptions($customerID); ?>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Referred By :</label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control text-uppercase" id="referred_by" name="referred_by" maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Commission Amount : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control currency" id="commission_amount" name="commission_amount">
                                </div>
                            </div>                  
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Estimated Date of Release : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="release_date" name="release_date" autocomplete="off">
                                    <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                    </span>
                                </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Start Date : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="start_date" name="start_date" autocomplete="off">
                                    <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                    </span>
                                </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Term : <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                <input type="number" class="form-control" id="term_length" name="term_length" step="1" value="1" min="1">
                                </div>
                                <div class="col-lg-4">
                                <select class="form-control select2" name="term_type" id="term_type">
                                    <option value="">--</option>
                                    <option value="Days">Days</option>
                                    <option value="Months">Months</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Number of Payments : <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                <input type="text" class="form-control text-uppercase" id="number_of_payments" name="number_of_payments" autocomplete="off" readonly>
                                </div>
                                <div class="col-lg-4">
                                <select class="form-control select2" name="payment_frequency" id="payment_frequency">
                                    <option value="">--</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">First Due Date : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control text-uppercase" id="first_due_date" name="first_due_date" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Initial Approving Officer : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                <select class="form-control select2" name="initial_approving_officer" id="initial_approving_officer">
                                    <option value="">--</option>
                                    <?php echo $approvingOfficerModel->generateApprovingOfficerOptions('Initial'); ?>
                                </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Final Approving Officer : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                <select class="form-control select2" name="final_approving_officer" id="final_approving_officer">
                                    <option value="">--</option>
                                    <?php echo $approvingOfficerModel->generateApprovingOfficerOptions('Final'); ?>
                                </select>
                                </div>
                            </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Remarks :</label>
                            <div class="col-lg-7">
                            <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-unit-details">
                        <form id="sales-proposal-unit-details-form" method="post" action="#">
                        <div class="row">
                        <div class="col-lg-6">
                            <input type="hidden" id="product_category">
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Stock : <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                                <select class="form-control select2" name="product_id" id="product_id">
                                <option value="">--</option>
                                <?php echo $productModel->generateInStockProductOptions(); ?>
                                </select>
                            </div>
                            </div>
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Engine Number :</label>
                            <label class="col-lg-7 col-form-label" id="product_engine_number"></label>
                            </div>
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Chassis Number :</label>
                            <label class="col-lg-7 col-form-label" id="product_chassis_number"></label>
                            </div>
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Plate Number :</label>
                            <label class="col-lg-7 col-form-label" id="product_plate_number"></label>
                            </div>
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Old Color :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control text-uppercase" id="old_color" autocomplete="off" readonly>
                            </div>
                            </div>
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">New Color :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control text-uppercase" id="new_color" name="new_color" maxlength="100" autocomplete="off">
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Old Body :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control text-uppercase" id="old_body" autocomplete="off" readonly>
                            </div>
                            </div>
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">New Body :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control text-uppercase" id="new_body" name="new_body" maxlength="100" autocomplete="off">
                            </div>
                            </div>
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">For Change Engine? : <span class="text-danger">*</span></label>
                            <div class="col-lg-7 mt-2">
                                <select class="form-control select2" name="for_change_engine" id="for_change_engine">
                                <option value="">--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                </select>
                            </div>
                            </div>
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Old Engine :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control text-uppercase" id="old_engine" autocomplete="off" readonly>
                            </div>
                            </div>
                            <div class="form-group row">
                            <label class="col-lg-5 col-form-label">New Engine :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control text-uppercase" id="new_engine" name="new_engine" maxlength="100" autocomplete="off" readonly>
                            </div>
                            </div>
                        </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-fuel-details">
                        <form id="sales-proposal-fuel-details-form" method="post" action="#">
                        <div class="row">
                            <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Diesel Fuel Quantity : <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                <div class="input-group">
                                    <input type="text" class="form-control currency" id="diesel_fuel_quantity" name="diesel_fuel_quantity">
                                    <span class="input-group-text">lt</span>
                                </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Diesel Price/Liter : <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                <input type="text" class="form-control currency" id="diesel_price_per_liter" name="diesel_price_per_liter">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Sub-Total :</label>
                                <label class="col-lg-6 col-form-label" id="diesel_total">--</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Regular Fuel Quantity : <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                <div class="input-group">
                                    <input type="text" class="form-control currency" id="regular_fuel_quantity" name="regular_fuel_quantity">
                                    <span class="input-group-text">lt</span>
                                </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Regular Price/Liter : <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                <input type="text" class="form-control currency" id="regular_price_per_liter" name="regular_price_per_liter">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Sub-Total :</label>
                                <label class="col-lg-6 col-form-label" id="regular_total">--</label>
                            </div>
                            </div>
                            <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Premium Fuel Quantity : <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                <div class="input-group">
                                    <input type="text" class="form-control currency" id="premium_fuel_quantity" name="premium_fuel_quantity">
                                    <span class="input-group-text">lt</span>
                                </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Premium Price/Liter : <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                <input type="text" class="form-control currency" id="premium_price_per_liter" name="premium_price_per_liter">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Sub-Total :</label>
                                <label class="col-lg-6 col-form-label" id="premium_total">--</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Total :</label>
                                <label class="col-lg-6 col-form-label" id="fuel_total">--</label>
                            </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-refinancing-details">
                        <form id="sales-proposal-refinancing-details-form" method="post" action="#">
                        <div class="row">
                            <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Stock Number :</label>
                                <label class="col-lg-6 col-form-label" id="ref_stock_no">--</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Engine Number : <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                <input type="text" class="form-control text-uppercase" id="ref_engine_no" name="ref_engine_no" maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Chassis Number : <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                <input type="text" class="form-control text-uppercase" id="ref_chassis_no" name="ref_chassis_no" maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">Plate Number : <span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                <input type="text" class="form-control text-uppercase" id="ref_plate_no" name="ref_plate_no" maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">OR/CR Number : </label>
                                <div class="col-lg-6">
                                <input type="text" class="form-control" id="orcr_no" name="orcr_no" maxlength="200" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-6 col-form-label">OR/CR Date : </label>
                                <div class="col-lg-6">
                                <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="orcr_date" name="orcr_date" autocomplete="off">
                                    <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                    </span>
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-7 col-form-label">OR/CR Expiry Date : </label>
                                <div class="col-lg-5">
                                    <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="orcr_expiry_date" name="orcr_expiry_date" autocomplete="off">
                                    <span class="input-group-text">
                                        <i class="feather icon-calendar"></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-7 col-form-label">Received From : </label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" id="received_from" name="received_from" maxlength="500" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-7 col-form-label">Received From Address : </label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" id="received_from_address" name="received_from_address" maxlength="1000" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-7 col-form-label">Received From ID Type : </label>
                                <div class="col-lg-5">
                                    <select class="form-control select2" name="received_from_id_type" id="received_from_id_type">
                                        <option value="">--</option>
                                        <?php echo $idTypeModel->generateIDTypeOptions(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-7 col-form-label">Received From ID Number : </label>
                                <div class="col-lg-5">
                                <input type="text" class="form-control" id="received_from_id_number" name="received_from_id_number" maxlength="200" autocomplete="off">
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Unit Description : </label>
                            <div class="col-lg-10">
                            <textarea class="form-control" id="unit_description" name="unit_description" maxlength="1000" rows="3"></textarea>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-job-order">
                        <div class="table-responsive">
                        <table id="sales-proposal-job-order-table" class="table table-hover nowrap w-100">
                            <thead>
                            <tr>
                                <th>Job Order</th>
                                <th>Cost</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-pricing-computation">
                        <form id="sales-proposal-pricing-computation-form" method="post" action="#">
                        <?php
                            if($viewSalesProposalProductCost['total'] > 0){
                            echo '<div class="form-group row">
                            <label class="col-lg-6 col-form-label">Product Cost :</label>
                            <div class="col-lg-6">
                                <label class="col-form-label" id="product_cost_label"></label>
                            </div>
                            </div>';
                            }
                        ?>
                        <div class="row">
                        <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Deliver Price (AS/IS) : <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="delivery_price" name="delivery_price">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Add-On : <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="add_on_charge" name="add_on_charge">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Nominal Discount : <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="nominal_discount" name="nominal_discount">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Total Delivery Price : <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="total_delivery_price" name="total_delivery_price" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Interest Rate : <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="interest_rate" name="interest_rate">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Cost of Accessories :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="cost_of_accessories" name="cost_of_accessories">
                            </div>
                        </div>
                        </div>
                        <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Reconditioning Cost :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="reconditioning_cost" name="reconditioning_cost">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Sub-Total :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="subtotal" name="subtotal" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Downpayment :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="downpayment" name="downpayment">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Outstanding Balance :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="outstanding_balance" name="outstanding_balance" readonly>
                            </div>
                        </div>
                        <div class="form-group row d-none">
                            <label class="col-lg-6 col-form-label">Amount Financed :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="amount_financed" name="amount_financed" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">PN Amount :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="pn_amount" name="pn_amount" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label">Repayment Amount :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control currency" id="repayment_amount" name="repayment_amount" readonly>
                            </div>
                        </div>
                        </div>
                    </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-other-charges">
                        <form id="sales-proposal-other-charges-form" method="post" action="#">
                        <div class="row">
                            <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Insurance Coverage :</label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control currency" id="insurance_coverage" name="insurance_coverage">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Insurance Premium :</label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control currency" id="insurance_premium" name="insurance_premium">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Handling Fee :</label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control currency" id="handling_fee" name="handling_fee">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Transfer Fee :</label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control currency" id="transfer_fee" name="transfer_fee">
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Registration Fee :</label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control currency" id="registration_fee" name="registration_fee">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Doc. Stamp Tax :</label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control currency" id="doc_stamp_tax" name="doc_stamp_tax">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Transaction Fee :</label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control currency" id="transaction_fee" name="transaction_fee">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Total :</label>
                                <div class="col-lg-7">
                                <input type="text" class="form-control currency" id="total_other_charges" name="total_other_charges" readonly>
                                </div>
                            </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-renewal-amount">
                        <form id="sales-proposal-renewal-amount-form" method="post" action="#">
                        <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-borderless table-xl">
                            <thead>
                                <tr class="text-center">
                                <th></th>
                                <th>2nd Year</th>
                                <th>3rd Year</th>
                                <th>4th Year</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <td></td>
                                <td style="horizontal-align: center !important">
                                <div class="form-check form-switch mb-2 text-center">
                                    <input type="checkbox" class="form-check-input input-primary" id="compute_second_year">
                                    </div>
                                </td>
                                <td style="horizontal-align: center !important">
                                    <div class="form-check form-switch mb-2 text-center">
                                    <input type="checkbox" class="form-check-input input-primary" id="compute_third_year">
                                    </div>
                                </td>
                                <td style="horizontal-align: center !important">
                                    <div class="form-check form-switch mb-2 text-center">
                                    <input type="checkbox" class="form-check-input input-primary" id="compute_fourth_year">
                                    </div>
                                </td>
                                </tr>
                                <tr>
                                <td>Registration</td>
                                <td><input type="text" class="form-control currency" id="registration_second_year" name="registration_second_year"></td>
                                <td><input type="text" class="form-control currency" id="registration_third_year" name="registration_third_year"></td>
                                <td><input type="text" class="form-control currency" id="registration_fourth_year" name="registration_fourth_year"></td>
                                </tr>
                                <tr>
                                <td>Ins. Coverage</td>
                                <td><input type="text" class="form-control currency" id="insurance_coverage_second_year" name="insurance_coverage_second_year" readonly></td>
                                <td><input type="text" class="form-control currency" id="insurance_coverage_third_year" name="insurance_coverage_third_year" readonly></td>
                                <td><input type="text" class="form-control currency" id="insurance_coverage_fourth_year" name="insurance_coverage_fourth_year" readonly></td>
                                </tr>
                                <tr>
                                <td>Ins. Premium</td>
                                <td><input type="text" class="form-control currency" id="insurance_premium_second_year" name="insurance_premium_second_year" readonly></td>
                                <td><input type="text" class="form-control currency" id="insurance_premium_third_year" name="insurance_premium_third_year" readonly></td>
                                <td><input type="text" class="form-control currency" id="insurance_premium_fourth_year" name="insurance_premium_fourth_year" readonly></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-amount-of-deposit">
                        <div class="table-responsive">
                        <table id="sales-proposal-deposit-amount-table" class="table table-hover nowrap w-100">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference No.</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-additional-job-order">
                        <div class="table-responsive">
                        <table id="sales-proposal-additional-job-order-table" class="table table-hover nowrap w-100">
                            <thead>
                            <tr>
                                <th>Job Order Number</th>
                                <th>Job Order Date</th>
                                <th>Particulars</th>
                                <th>Cost</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-confirmations">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">New Engine Stencil </h5>
                                                <?php
                                                if($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'For Initial Approval' || $salesProposalStatus == 'Draft'){
                                                    echo '<button class="btn btn-info me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-new-engine-stencil-offcanvas" aria-controls="sales-proposal-new-engine-stencil-offcanvas" id="sales-proposal-new-engine-stencil">New Engine Stencil</button>';
                                                }
                                                ?>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Engine Stencil Image" id="new-engine-stencil-image" class="img-fluid rounded">
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Client Confirmation </h5>
                                                <?php
                                                if($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'For Initial Approval' || $salesProposalStatus == 'Draft'){
                                                    echo '<button class="btn btn-success me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-client-confirmation-offcanvas" aria-controls="sales-proposal-client-confirmation-offcanvas" id="sales-proposal-client-confirmation">Client Confirmation</button>';
                                                }
                                                ?>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                <div class="col-sm-12 mb-sm-0">
                                                    <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Client Confirmation Image" id="client-confirmation-image" class="img-fluid rounded">
                                                </div>                      
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Credit Advice</h5>
                                                <?php
                                                if($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'For Initial Approval' || $salesProposalStatus == 'Draft'){
                                                    if(!empty($clientConfirmation) && $transactionType == 'Bank Financing'){
                                                    echo '<button class="btn btn-warning me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-credit-advice-offcanvas" aria-controls="sales-proposal-credit-advice-offcanvas" id="sales-proposal-credit-advice">Credit Advice</button>';
                                                    }
                                                }
                                                ?>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Credit Advice Image" id="credit-advice-image" class="img-fluid rounded">
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0">
                                                <h5 class="mb-0">Quality Control Form </h5>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Quality Control Form Image" id="quality-control-form-image" class="img-fluid rounded">
                                                    </div>                      
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0">
                                                <h5 class="mb-0">Outgoing Checklist Form </h5>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Outgoing Checklist Image" id="outgoing-checklist-image" class="img-fluid rounded">
                                                    </div>                      
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0">
                                                <h5 class="mb-0">Unit Image </h5>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Image" id="unit-image" class="img-fluid rounded">
                                                    </div>                      
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Additional Job Order Confirmation</h5>
                                                <?php
                                                if($salesProposalStatus != 'For DR' && $additionalJobOrderCount['total'] > 0){
                                                    echo '<div class="previous me-2 d-none" id="sales- proposal-job-order-confirmation-button">
                                                            <button class="btn btn-warning m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales- proposal-job-order-confirmation-offcanvas" aria-controls="sales-proposal-job-order-confirmation-offcanvas" id="sales- proposal-job-order-confirmation">Additional Job Order Confirmation</button>
                                                        </div>';
                                                }
                                                ?>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Image" id="additional-job-order-confirmation-image" class="img-fluid rounded">
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-summary">
                        <div class="print-summary">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-border-style mw-100">
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-uppercase text-wrap">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="8" class="text-center bg-danger pb-0"><h3 class="font-size-15 fw-bold text-light">SALES PROPOSAL</h3></td>
                                                    </tr>
                                                    <tr>
                                                    <tr>
                                                        <td colspan="3"><b>No. <span id="summary-sales-proposal-number"></span></b></td>
                                                        <td colspan="2" class="text-center"><b>Status: <?php echo $salesProposalStatus; ?></b></td>
                                                        <td colspan="3" class="text-end"><b>Date: <?php echo date('d-M-Y'); ?> </b></td>
                                                    </tr>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" class="text-wrap"><small style="color:#c60206"><b>NAME OF CUSTOMER</b></small><br/><?php echo strtoupper($customerName);?></td>
                                                        <td colspan="3" class="text-wrap"><small style="color:#c60206"><b>ADDRESS</b></small><br/><?php echo strtoupper($customerAddress);?></td>
                                                        <td class="text-wrap"><small style="color:#c60206"><b>CONTACT NO.</b></small><br/><?php echo $customerMobile;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" class="text-wrap"><small style="color:#c60206"><b>CO-BORROWER/CO-MORTGAGOR/CO-MAKER</b></small><br/><span id="summary-comaker-name"></span></td>
                                                        <td colspan="3" class="text-wrap"><small style="color:#c60206"><b>ADDRESS</b></small><br/><span id="summary-comaker-address"></span></td>
                                                        <td class="text-wrap"><small style="color:#c60206"><b>CONTACT NO.</b></small><br/><span id="summary-comaker-mobile"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>REFERRED BY</b></small><br/><span id="summary-referred-by"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>ESTIMATED DATE OF RELEASE</b></small><br/><span id="summary-release-date"></span></td>
                                                        <td class="text-wrap"style="vertical-align: top !important;"><small style="color:#c60206"><b>PRODUCT TYPE</b></small><br/><span id="summary-product-type"></span></td>
                                                        <td class="text-wrap"style="vertical-align: top !important;"><small style="color:#c60206"><b>TANSACTION TYPE</b></small><br/><span id="summary-transaction-type"></span></td>
                                                        <td class="text-wrap"style="vertical-align: top !important;"><small style="color:#c60206"><b>TERM</b></small><br/><span id="summary-term"></span></td>
                                                        <td class="text-wrap"style="vertical-align: top !important;"><small style="color:#c60206"><b>NO. OF PAYMENTS</b></small><br/><span id="summary-no-payments"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-wrap"><small style="color:#c60206"><b>STOCK NO.</b></small><br/><span id="summary-stock-no"></span></td>
                                                        <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>ENGINE NO.</b></small><br/><span id="summary-engine-no"></span></td>
                                                        <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>CHASSIS NO.</b></small><br/><span id="summary-chassis-no"></span></td>
                                                        <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>PLATE NO.</b></small><br/><span id="summary-plate-no"></span></td>
                                                        <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>FOR REGISTRATION?</b></small><br/><span id="summary-for-registration"></span></td>
                                                        <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>WITH CR?</b></small><br/><span id="summary-with-cr"></span></td>
                                                        <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>FOR TRANSFER?</b></small><br/><span id="summary-for-transfer"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>FOR CHANGE COLOR?</b></small><br/><span id="summary-for-change-color"></span></td>
                                                        <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>NEW COLOR</b></small><br/><span id="summary-new-color"></span></td>
                                                        <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>FOR CHANGE BODY?</b></small><br/><span id="summary-for-change-body"></span></td>
                                                        <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>NEW BODY</b></small><br/><span id="summary-new-body"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>FOR CHANGE ENGINE?</b></small><br/><span id="summary-for-change-engine"></span></td>
                                                        <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>NEW ENGINE</b></small><br/><span id="summary-new-engine"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>DIESEL FUEL QUANTITY</b></small><br/><span id="summary-diesel-fuel-quantity"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>REGULAR FUEL QUANTITY</b></small><br/><span id="summary-regular-fuel-quantity"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>PREMIUM FUEL QUANTITY</b></small><br/><span id="summary-premium-fuel-quantity"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>COMMISSION</b></small><br/><span id="summary-commission"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8" style="padding-bottom:0 !important;" class="text-wrap"><small><b><span style="color:#c60206; margin-right: 20px;">JOB ORDER</span> TOTAL COST  <span id="summary-job-order-total"></span></b></small><br/><br/>
                                                            <div class="row pb-0 mb-0">
                                                                <div class="col-lg-12">
                                                                    <div class="table-responsive" id="summary-job-order-table"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>PRICING COMPUTATION:</b></small><br/>
                                                            <div class="row pb-0 mb-0">
                                                                <div class="col-lg-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-borderless text-sm ">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>DELIVERY PRICE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-deliver-price"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>ADD: RECONDITIONING COST</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-reconditioning-cost"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>SUB-TOTAL</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-sub-total"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>LESS: DOWNPAYMENT</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-downpayment"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>OUTSTANDING BALANCE</b></td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-outstanding-balance"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td colspan="4" style="padding-bottom:0 !important;" class="text-wrap"><small style="color:#c60206"><b>OTHER CHARGES:</b></small><br/>
                                                            <div class="row pb-0 mb-0">
                                                                <div class="col-lg-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-borderless text-sm ">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>INSURANCE COVERAGE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-insurance-coverage"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>INSURANCE PREMIUM</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-insurance-premium"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>HANDLING FEE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-handing-fee"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>TRANSFER FEE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-transfer-fee"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>REGISTRATION FEE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-registration-fee"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>DOC. STAMP TAX</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-doc-stamp-tax"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>TRANSACTION FEE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-transaction-fee"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>TOTAL</b></td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-other-charges-total"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="vertical-align: top !important;" class="text-wrap">
                                                            <small><b>FOR REFERRAL TO FINANCING, PLEASE COMPUTE MO AMORTIZATION:</b></small><br/><br/>
                                                            <small style="color:#c60206"><b>AMORTIZATION NET</b></small><br/><span class="text-sm" id="summary-repayment-amount"></span><br/><br/>
                                                            <small style="color:#c60206"><b>INTEREST RATE</b></small><br/><span class="text-sm" id="summary-interest-rate"></span>
                                                        </td>
                                                        <td colspan="4" style="padding-bottom:0 !important; vertical-align: top !important;" class="text-wrap">
                                                            <small style="color:#c60206"><b>AMOUNT OF DEPOSIT:</b></small><br/><br/>
                                                            <div class="row pb-0 mb-0">
                                                                <div class="col-lg-12">
                                                                    <div class="table-responsive" id="summary-amount-of-deposit-table"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="padding-bottom:0 !important;" class="text-wrap">
                                                            <div class="row pb-0 mb-0">
                                                                <div class="col-lg-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-borderless text-sm ">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td><u>2ND YEAR</u></td>
                                                                                    <td><u>3RD YEAR</u></td>
                                                                                    <td><u>4TH YEAR</u></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>REGISTRATION</td>
                                                                                    <td id="summary-registration-second-year"></td>
                                                                                    <td id="summary-registration-third-year"></td>
                                                                                    <td id="summary-registration-fourth-year"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>INS. COVERAGE</td>
                                                                                    <td id="summary-insurance-coverage-second-year"></td>
                                                                                    <td id="summary-insurance-coverage-third-year"></td>
                                                                                    <td id="summary-insurance-coverage-fourth-year"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>INS. PREMIUM</td>
                                                                                    <td id="summary-insurance-premium-second-year"></td>
                                                                                    <td id="summary-insurance-premium-third-year"></td>
                                                                                    <td id="summary-insurance-premium-fourth-year"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td colspan="4" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>REMARKS</b></small><br/><br/><span id="summary-remarks" class="text-sm"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8" style="padding-bottom:0 !important;" class="text-wrap">
                                                            <small style="color:#c60206"><b>REQUIREMENTS:</b></small><br/><br/>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <ul>
                                                                        <li><small>PICTURE WITH SIGNATURE AT THE BACK</small></li>
                                                                        <li><small>POST-DATED CHECKS</small></li>
                                                                        <li><small>VALID ID (PHOTOCOPY)</small></li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-6">
                                                                    <ul>
                                                                        <li><small>BARANGAY CERTIFICATE</small></li>
                                                                        <li><small>BANK STATEMENT FOR THREE (3) MONTHS</small></li>
                                                                        <li><small>BUSINESS LICENSE/CERTIFICATE OF EMPLOYMENT</small></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <small style="color:#c60206"><b>ADDITIONAL REQUIREMENTS (IN CASE OF NON-INDIVIDUAL ACCOUNT):</b></small>
                                                                    <ul>
                                                                        <li><small>DTI REGISTRATION (FOR SINGLE PROPRIETORSHIP)</small></li>
                                                                        <li><small>SEC REGISTRATION (FOR CORPORATION) INCLUDING SECRETARY'S CERTIFICATE FOR AUTHORIZED SIGNATORY/IES</small></li>
                                                                        </ul>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8" style="padding-bottom:0 !important;" class="text-wrap">
                                                            <small style="color:#c60206"><b>REMINDERS:</b></small><br/>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <ol class="text-sm">
                                                                        <li><small>PRICES ARE SUBJECT TO CHANGE WITHOUT PRIOR NOTICE. FINANCING IS STILL SUBJECT FOR APPROVAL BY OUR ACCREDITED FINANCING COMPANY</small></li>
                                                                        <li><small>DOWNPAYMENT IS STRICTLY PAYABLE ON OR BEFORE RELEASE OF UNIT IN CASH OR MANAGER'S CHECK</small></li>
                                                                        <li><small>POST-DATED CHECKS SHOULD BE GIVEN ON OR BEFORE RELEASE OF UNIT OTHERWISE UNIT WILL NOT BE RELEASED</small></li>
                                                                        <li><small>ADDITIONAL POST-DATED CHECKS SHALL BE ISSUED FOR INSURANCE AND REGISTRATION RENEWAL</small></li>
                                                                        <li><small>THAT THE BUYER SHALL TAKE AND ASSUME ALL CIVIL AND/OR CRIMINAL LIABILITIES IN THE USE OF SAID VEHICLE EITHER FOR PRIVATE OR BUSINESS USE</small></li>
                                                                        <li><small>THAT THE BUYER HAS FULL KNOWLEDGE OF THE CONDITION OF THE VEHICLE UPON PURCHASE THEREOF</small></li>
                                                                        <li><small>THAT THE UNIT SUBJECT OF SALE IS NOT COVERED WITH WARRANTY AND CONVEYED ON AS-IS BASIS ONLY</small></li>
                                                                        <li><small>THAT THE SELLER IS NOT LIABLE FOR WHATEVER UNSEEN DEFECTS THAT MAY BE DISCOVERED AFTER RELEASE OF UNIT</small></li>
                                                                    </ol>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>PREPARED BY:</b></small><br/><br/><br/><small><?php
                                                            if(!empty($createdDate)){
                                                                echo 'CREATED THRU SYSTEM<br/>' . $createdDate;
                                                            }
                                                            ?></small><br/><span id="summary-created-by" class="text-sm"></span>
                                                        </td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>INITIAL APPROVAL BY:</b></small><br/><br/><br/><small><?php
                                                            if(!empty($initialApprovalDate)){
                                                                echo 'APPROVED THRU SYSTEM<br/>' . $initialApprovalDate;
                                                            }
                                                            ?></small><br/><span id="summary-initial-approval-by" class="text-sm"></span>
                                                        </td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>FINAL APPROVAL BY:</b></small><br/><br/><br/><small><?php
                                                            if(!empty($approvalDate)){
                                                                 echo 'APPROVED THRU SYSTEM<br/>' . $approvalDate;
                                                            }
                                                            ?></small><br/><span id="summary-final-approval-by" class="text-sm"></span>
                                                        </td>
                                                        <td colspan="2" style="vertical-align: top !important;"><small><b>WITH MY CONFORMITY:</b><br/><br/><br/><br/><br/>CUSTOMER'S PRINTED NAME OVER SIGNATURE</small></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col-lg-12">
                                    <small style="color:#c60206;"><b>ADDITIONAL JOB ORDER:</b></small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-border-style">
                                        <div class="table-responsive" id="summary-additional-job-order-table"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-pdc-manual-input">
                        <div class="table-responsive">
                            <table id="sales-proposal-pdc-manual-input-table" class="table table-hover nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Account Number</th>
                                        <th>Bank/Branch</th>
                                        <th>Check Date</th>
                                        <th>Check Number</th>
                                        <th>Payment For</th>
                                        <th>Gross Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-release-details">
                        <form id="sales-proposal-other-product-details-form" method="post" action="#">
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">DR Number : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control text-uppercase" id="dr_number" name="dr_number" maxlength="50" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Actual Start Date : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <div class="input-group date">
                                        <input type="text" class="form-control regular-datepicker" id="actual_start_date" name="actual_start_date" autocomplete="off">
                                        <span class="input-group-text">
                                            <i class="feather icon-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Release To : </label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control text-uppercase" id="release_to" name="release_to" maxlength="500" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Year Model :</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control text-uppercase" id="year_model" name="year_model" maxlength="20" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">CR No :</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control text-uppercase" id="cr_no" name="cr_no" maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">MV File No :</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control text-uppercase" id="mv_file_no" name="mv_file_no" maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Make :</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control text-uppercase" id="make" name="make" maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Product Description : <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <textarea class="form-control text-uppercase" id="product_description" name="product_description" maxlength="500"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Business Style :</label>
                                <div class="col-lg-7">
                                    <textarea class="form-control text-uppercase" id="business_style" name="business_style" maxlength="500"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-printable-report">
                        <div class="row">
                            <div class="col-lg-6 align-items-stretch">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Online</div>
                                            <div class="d-grid"><a href="online-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 align-items-stretch">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Authorization & Undertaking</div>
                                            <div class="d-grid"><a href="authorization-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 align-items-stretch">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Promissory Note</div>
                                            <div class="d-grid"><a href="pn-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Disclosure</div>
                                            <div class="d-grid"><a href="disclosure-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Insurance Request</div>
                                            <div class="d-grid"><a class="btn btn-outline-info mt-4" href="insurance-request-dr-print.php?id=<?php echo $salesProposalID; ?>" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">DR Receipt</div>
                                            <div class="d-grid"><a href="dr-receipt-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Repayment Schedule</div>
                                            <div class="d-grid"><a href="schedule-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Voluntary Surrender</div>
                                            <div class="d-grid"><a href="voluntary-surrender-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Sinumpaang Salaysay</div>
                                            <div class="d-grid"><a href="sinumpaang-salaysay-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Deed of Absolute Sale</div>
                                            <div class="d-grid"><a href="deed-of-absolute-sale-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Deed of Conditional Sale</div>
                                            <div class="d-grid"><a href="deed-of-conditional-sale-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Chattel Mortgage</div>
                                            <div class="d-grid"><a href="chattel-mortgage-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Sales Invoice (FUSO Only)</div>
                                            <div class="d-grid"><a href="sales-invoice-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card price-card">
                                    <div class="card-body">
                                        <div class="price-head">
                                            <div class="price-price">Service Invoice (FUSO Only)</div>
                                            <div class="d-grid"><a href="service-invoice-dr-print.php?id=<?php echo $salesProposalID; ?>" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                if($salesProposalStatus == 'Released'){
                                    echo '<div class="col-lg-6">
                                                <div class="card price-card">
                                                    <div class="card-body">
                                                        <div class="price-head">
                                                            <div class="price-price">Gatepass</div>
                                                            <div class="d-grid"><a href="gatepass-dr-print.php?id='. $salesProposalID .'" class="btn btn-outline-info mt-4" target="_blank">Print</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="generate-pdc-offcanvas" aria-labelledby="generate-pdc-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="generate-pdc-offcanvas-label" style="margin-bottom:-0.5rem">PDC Manual Input</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="sales-proposal-pdc-manual-input-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <div class="form-group">
                                    <label class="form-label">Payment Frequency <span class="text-danger">*</span></label>
                                    <select class="form-control offcanvas-select2" name="pdc_payment_frequency" id="pdc_payment_frequency">
                                        <option value="">--</option>
                                        <option value="Daily">Daily</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <div class="form-group">
                                    <label class="form-label">Payment For <span class="text-danger">*</span></label>
                                    <select class="form-control offcanvas-select2" name="payment_for" id="payment_for">
                                        <option value="">--</option>
                                        <option value="Acct Amort">Acct Amort</option>
                                        <option value="Acct Amort W/ OC">Acct Amort W/ OC</option>
                                        <option value="Advances">Advances</option>
                                        <option value="Collateral Check">Collateral Check</option>
                                        <option value="Deposit">Deposit</option>
                                        <option value="Downpayment">Downpayment</option>
                                        <option value="Insurance">Insurance</option>
                                        <option value="Insurance Renewal">Insurance Renewal</option>
                                        <option value="Other Charges">Other Charges</option>
                                        <option value="Additional Job Order">Additional Job Order</option>
                                        <option value="Parts">Parts</option>
                                        <option value="Registration">Registration</option>
                                        <option value="Registration Renewal">Registration Renewal</option>
                                        <option value="Transaction Fee">Transaction Fee</option>
                                        <option value="Transfer Fee">Transfer Fee</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="form-label">Account Number </label>
                                <input type="text" class="form-control text-uppercase" id="account_number" name="account_number" maxlength="100" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="form-label">Bank/Branch</label>
                                <input type="text" class="form-control text-uppercase" id="bank_branch" name="bank_branch" maxlength="200" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="no_of_payments">Number of payments <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="no_of_payments" name="no_of_payments" min="1" step="1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="first_check_number">First Check Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="first_check_number" name="first_check_number" min="1" step="1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="form-label">First Check Date <span class="text-danger">*</span></label>
                                <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="first_check_date" name="first_check_date" autocomplete="off">
                                    <span class="input-group-text">
                                        <i class="feather icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="amount_due">Gross Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="amount_due" name="amount_due" min="1" step="0.01">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-sales-proposal-pdc-manual-input" form="sales-proposal-pdc-manual-input-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
            </div>
        </div>
    </div>
<div>
    
<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-tag-as-released-offcanvas" aria-labelledby="sales-proposal-tag-as-released-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="sales-proposal-tag-as-released-offcanvas-label" style="margin-bottom:-0.5rem">Tag As Released </h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="sales-proposal-tag-as-released-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="release_remarks">Release Remarks <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="release_remarks" name="release_remarks" maxlength="500"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-sales-proposal-tag-as-released" form="sales-proposal-tag-as-released-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-deposit-amount-offcanvas" aria-labelledby="sales-proposal-deposit-amount-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-deposit-amount-offcanvas-label" style="margin-bottom:-0.5rem">Amount of Deposit</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="row">
      <div class="col-lg-12">
        <form id="sales-proposal-deposit-amount-form" method="post" action="#">
          <div class="form-group row">
            <div class="col-lg-12 mt-3 mt-lg-0">
              <label class="form-label">Deposit Date <span class="text-danger">*</span></label>
              <input type="hidden" id="sales_proposal_deposit_amount_id" name="sales_proposal_deposit_amount_id">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="deposit_date" name="deposit_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-12">
              <label class="form-label">Reference Number <span class="text-danger">*</span></label>
              <input type="text" class="form-control text-uppercase" id="reference_number" name="reference_number" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-12 mt-3 mt-lg-0">
              <label class="form-label" for="deposit_amount">Deposit Amount <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="deposit_amount" name="deposit_amount" min="0" step="0.01">
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <button type="submit" class="btn btn-primary" id="submit-sales-proposal-deposit-amount" form="sales-proposal-deposit-amount-form">Submit</button>
        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
      </div>
    </div>
  </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="generate-pdc-offcanvas" aria-labelledby="generate-pdc-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="generate-pdc-offcanvas-label" style="margin-bottom:-0.5rem">Repayment PDC Generation</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="pdc-generation-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="no_of_pdc">Number of PDC <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="no_of_pdc" name="no_of_pdc" min="1" step="1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="first_check_number">First Check Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="first_check_number" name="first_check_number" min="1" step="1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="form-label">Bank/Branch <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="bank_branch" name="bank_branch" maxlength="200" autocomplete="off">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-pdc-generation" form="pdc-generation-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
            </div>
        </div>
    </div>
<div>