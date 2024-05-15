<?php
    $hidden = '';
    if($salesProposalStatus == 'Released'){
        $hidden = 'd-none';
    }
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
                    <li><a class="nav-link <?php echo $hidden; ?>" id="sales-proposal-tab-15" data-bs-toggle="pill" href="#v-online" role="tab" aria-controls="v-online" aria-selected="false" disabled>Online</a></li>
                    <li><a class="nav-link <?php echo $hidden; ?>" id="sales-proposal-tab-16" data-bs-toggle="pill" href="#v-autorization" role="tab" aria-controls="v-autorization" aria-selected="false" disabled>Authorization & Undertaking</a></li>
                    <li><a class="nav-link <?php echo $hidden; ?>" id="sales-proposal-tab-17" data-bs-toggle="pill" href="#v-promissory-note" role="tab" aria-controls="v-promissory-note <?php echo $hidden; ?>" aria-selected="false" disabled>Promissory Note</a></li>
                    <li><a class="nav-link <?php echo $hidden; ?>" id="sales-proposal-tab-18" data-bs-toggle="pill" href="#v-disclosure" role="tab" aria-controls="v-disclosure" aria-selected="false" disabled>Disclosure</a></li>
                    <li><a class="nav-link <?php echo $hidden; ?>" id="sales-proposal-tab-19" data-bs-toggle="pill" href="#v-insurance-request" role="tab" aria-controls="v-insurance-request" aria-selected="false" disabled>Insurance Request</a></li>
                    <?php
                        if($salesProposalStatus == 'Released'){
                            echo '<li><a class="nav-link" id="sales-proposal-tab-20" data-bs-toggle="pill" href="#v-gatepass" role="tab" aria-controls="v-gatepass" aria-selected="false" disabled>Gate Pass</a></li>';
                        }
                    ?>
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
                                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-pdc-manual-input-offcanvas" aria-controls="sales-proposal-pdc-manual-input-offcanvas" id="add-sales-proposal-pdc-manual-input">Add PDC</button>
                                        </div>';

                                    echo '  <div class="previous me-2 d-none" id="add-sales-proposal-deposit-amount-button">
                                        <button class="btn btn-primary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-deposit-amount-offcanvas" aria-controls="sales-proposal-deposit-amount-offcanvas" id="add-sales-proposal-deposit-amount">Add Amount of Deposit</button>
                                      </div>';

                                    echo '<div class="previous me-2 d-none" id="summary-print-button">
                                        <a href="javascript:window.print()" class="btn btn-outline-info me-1" id="print">Print</a>
                                    </div>';

                                    echo '<div class="previous me-2 d-none" id="online-print-button">
                                        <a href="online-dr-print.php?id='. $salesProposalID .'" target="_blank" class="btn btn-outline-info me-1" id="print">Print</a>
                                    </div>';

                                    echo '<div class="previous me-2 d-none" id="authorization-print-button">
                                        <a href="authorization-dr-print.php?id='. $salesProposalID .'" target="_blank" class="btn btn-outline-info me-1" id="print">Print</a>
                                    </div>';

                                    echo '<div class="previous me-2 d-none" id="pn-print-button">
                                        <a href="pn-dr-print.php?id='. $salesProposalID .'" target="_blank" class="btn btn-outline-info me-1" id="print">Print</a>
                                    </div>';

                                    echo '<div class="previous me-2 d-none" id="disclosure-print-button">
                                        <a href="disclosure-dr-print.php?id='. $salesProposalID .'" target="_blank" class="btn btn-outline-info me-1" id="print">Print</a>
                                    </div>';

                                    echo '<div class="previous me-2 d-none" id="insurance-request-print-button">
                                    <a href="javascript:window.print()" class="btn btn-outline-info me-1" id="print">Print</a>
                                        </div>';

                                    echo '<div class="previous me-2 d-none" id="dr-receipt-print-button">
                                            <a href="dr-receipt-dr-print.php?id='. $salesProposalID .'" target="_blank" class="btn btn-outline-warning me-1" id="dr-receipt-print">Print DR Receipt</a>
                                        </div>';

                                    echo '<div class="previous me-2 d-none" id="schedule-print-button">
                                            <a href="schedule-dr-print.php?id='. $salesProposalID .'" target="_blank" class="btn btn-outline-info me-1" id="dr-receipt-print">Print Schedule</a>
                                        </div>';
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
                        <form id="sales-proposal-form" method="post" action="#">
                        <input type="hidden" id="sales_proposal_status" value="<?php echo $salesProposalStatus; ?>">
                        <input type="hidden" id="sales_proposal_type" value="details">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Sales Proposal Number :</label>
                            <label class="col-lg-8 col-form-label" id="sales_proposal_number">--</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Sales Proposal Status :</label>
                            <div class="col-lg-8 mt-3">
                            <?php echo $salesProposalStatusBadge; ?>
                            </div>
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
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Renewal Tag : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="renewal_tag" id="renewal_tag">
                                <option value="">--</option>
                                <option value="New">New</option>
                                <option value="Renewal">Renewal</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Product Type : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="product_type" id="product_type">
                                <option value="">--</option>
                                <option value="Unit">Unit</option>
                                <option value="Fuel">Fuel</option>
                                <option value="Parts">Parts</option>
                                <option value="Repair">Repair</option>
                                <option value="Rental">Rental</option>
                                <option value="Financing Brand New">Financing Brand New</option>
                                <option value="Refinancing">Refinancing</option>
                                <option value="Real Estate">Real Estate</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Transaction Type : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="transaction_type" id="transaction_type">
                                <option value="">--</option>
                                <option value="COD">COD</option>
                                <option value="Installment Sales">Installment Sales</option>
                                <option value="Bank Financing">Bank Financing</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Financing Institution :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="financing_institution" name="financing_institution" maxlength="200" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Co-Maker :</label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="comaker_id" id="comaker_id">
                                <option value="">--</option>
                                <?php $customerModel->generateComakerOptions($customerID); ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Referred By :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="referred_by" name="referred_by" maxlength="100" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Commission Amount : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <input type="number" class="form-control" id="commission_amount" name="commission_amount" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Estimated Date of Release : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="release_date" name="release_date" autocomplete="off">
                                <span class="input-group-text">
                                <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Start Date : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="start_date" name="start_date" autocomplete="off">
                                <span class="input-group-text">
                                <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Term : <span class="text-danger">*</span></label>
                            <div class="col-lg-2">
                            <input type="number" class="form-control" id="term_length" name="term_length" step="1" value="1" min="1">
                            </div>
                            <div class="col-lg-6">
                            <select class="form-control select2" name="term_type" id="term_type">
                                <option value="">--</option>
                                <option value="Days">Days</option>
                                <option value="Months">Months</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Number of Payments : <span class="text-danger">*</span></label>
                            <div class="col-lg-2">
                            <input type="text" class="form-control text-uppercase" id="number_of_payments" name="number_of_payments" autocomplete="off" readonly>
                            </div>
                            <div class="col-lg-6">
                            <select class="form-control select2" name="payment_frequency" id="payment_frequency">
                                <option value="">--</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">First Due Date : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="first_due_date" name="first_due_date" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Initial Approving Officer : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="initial_approving_officer" id="initial_approving_officer">
                                <option value="">--</option>
                                <?php echo $approvingOfficerModel->generateApprovingOfficerOptions('Initial'); ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Final Approving Officer : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="final_approving_officer" id="final_approving_officer">
                                <option value="">--</option>
                                <?php echo $approvingOfficerModel->generateApprovingOfficerOptions('Final'); ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Remarks :</label>
                            <div class="col-lg-8">
                            <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-unit-details">
                        <form id="sales-proposal-unit-details-form" method="post" action="#">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Stock : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="product_id" id="product_id">
                                <option value="">--</option>
                                <?php echo $productModel->generateInStockProductOptions(); ?>
                            </select>
                            </div>
                        </div>
                        <input type="hidden" id="product_category">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Engine Number :</label>
                            <label class="col-lg-8 col-form-label" id="product_engine_number"></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Chassis Number :</label>
                            <label class="col-lg-8 col-form-label" id="product_chassis_number"></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Plate Number :</label>
                            <label class="col-lg-8 col-form-label" id="product_plate_number"></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">For Registration? : <span class="text-danger">*</span></label>
                            <div class="col-lg-8 mt-2">
                            <select class="form-control select2" name="for_registration" id="for_registration">
                                <option value="">--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">With CR? : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="with_cr" id="with_cr">
                                <option value="">--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">For Transfer? : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="for_transfer" id="for_transfer">
                                <option value="">--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">For Change Color? : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="for_change_color" id="for_change_color">
                                <option value="">--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Old Color :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="old_color" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">New Color :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="new_color" name="new_color" maxlength="100" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">For Change Body? : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <select class="form-control select2" name="for_change_body" id="for_change_body">
                                <option value="">--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Old Body :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="old_body" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">New Body :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="new_body" name="new_body" maxlength="100" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">For Change Engine? : <span class="text-danger">*</span></label>
                            <div class="col-lg-8 mt-2">
                            <select class="form-control select2" name="for_change_engine" id="for_change_engine">
                                <option value="">--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Old Engine :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="old_engine" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">New Engine :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="new_engine" name="new_engine" maxlength="100" autocomplete="off" readonly>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-fuel-details">
                        <form id="sales-proposal-fuel-details-form" method="post" action="#">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Diesel Fuel Quantity : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <div class="input-group">
                                <input type="number" class="form-control" id="diesel_fuel_quantity" name="diesel_fuel_quantity" step="0.01" min="0">
                                <span class="input-group-text">lt</span>
                            </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Diesel Price/Liter : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <input type="number" class="form-control" id="diesel_price_per_liter" name="diesel_price_per_liter" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Sub-Total :</label>
                            <label class="col-lg-8 col-form-label" id="diesel_total">--</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Regular Fuel Quantity : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <div class="input-group">
                                <input type="number" class="form-control" id="regular_fuel_quantity" name="regular_fuel_quantity" step="0.01" min="0">
                                <span class="input-group-text">lt</span>
                            </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Regular Price/Liter : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <input type="number" class="form-control" id="regular_price_per_liter" name="regular_price_per_liter" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Sub-Total :</label>
                            <label class="col-lg-8 col-form-label" id="regular_total">--</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Premium Fuel Quantity : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <div class="input-group">
                                <input type="number" class="form-control" id="premium_fuel_quantity" name="premium_fuel_quantity" step="0.01" min="0">
                                <span class="input-group-text">lt</span>
                            </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Premium Price/Liter : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <input type="number" class="form-control" id="premium_price_per_liter" name="premium_price_per_liter" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Sub-Total :</label>
                            <label class="col-lg-8 col-form-label" id="premium_total">--</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Total :</label>
                            <label class="col-lg-8 col-form-label" id="fuel_total">--</label>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-refinancing-details">
                        <form id="sales-proposal-refinancing-details-form" method="post" action="#">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Stock Number :</label>
                            <label class="col-lg-8 col-form-label" id="ref_stock_no">--</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Engine Number : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="ref_engine_no" name="ref_engine_no" maxlength="100" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Chassis Number : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="ref_chassis_no" name="ref_chassis_no" maxlength="100" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Plate Number : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control text-uppercase" id="ref_plate_no" name="ref_plate_no" maxlength="100" autocomplete="off">
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
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Deliver Price (AS/IS) : <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="delivery_price" name="delivery_price" step="0.01" min="0.01">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Add-On : <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="add_on_charge" name="add_on_charge" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Nominal Discount : <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="nominal_discount" name="nominal_discount" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Total Delivery Price : <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="total_delivery_price" name="total_delivery_price" step="0.01" min="0.01" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Interest Rate : <span class="text-danger">*</span></label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="interest_rate" name="interest_rate" step="0.01" value="0" min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Cost of Accessories :</label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="cost_of_accessories" name="cost_of_accessories" step="0.01" value="0" min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Reconditioning Cost :</label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="reconditioning_cost" name="reconditioning_cost" step="0.01" value="0" min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Sub-Total :</label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="subtotal" name="subtotal" step="0.01" value="0" min="0" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Downpayment :</label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="downpayment" name="downpayment" step="0.01" value="0" min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Outstanding Balance :</label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="outstanding_balance" name="outstanding_balance" step="0.01" value="0" min="0" readonly>
                            </div>
                        </div>
                        <div class="form-group row d-none">
                            <label class="col-lg-5 col-form-label">Amount Financed :</label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="amount_financed" name="amount_financed" step="0.01" value="0" min="0" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">PN Amount :</label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="pn_amount" name="pn_amount" step="0.01" value="0" min="0" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Repayment Amount :</label>
                            <div class="col-lg-7">
                            <input type="number" class="form-control" id="repayment_amount" name="repayment_amount" step="0.01" value="0" min="0" readonly>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-other-charges">
                        <form id="sales-proposal-other-charges-form" method="post" action="#">
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Insurance Coverage :</label>
                                <div class="col-lg-7">
                                    <input type="number" class="form-control" id="insurance_coverage" name="insurance_coverage" step="0.01" value="0" min="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Insurance Premium :</label>
                                <div class="col-lg-7">
                                    <input type="number" class="form-control" id="insurance_premium" name="insurance_premium" step="0.01" value="0" min="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Handling Fee :</label>
                                <div class="col-lg-7">
                                    <input type="number" class="form-control" id="handling_fee" name="handling_fee" step="0.01" value="0" min="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Transfer Fee :</label>
                                <div class="col-lg-7">
                                    <input type="number" class="form-control" id="transfer_fee" name="transfer_fee" step="0.01" value="0" min="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Registration Fee :</label>
                                <div class="col-lg-7">
                                    <input type="number" class="form-control" id="registration_fee" name="registration_fee" step="0.01" value="0" min="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Doc. Stamp Tax :</label>
                                <div class="col-lg-7">
                                    <input type="number" class="form-control" id="doc_stamp_tax" name="doc_stamp_tax" step="0.01" value="0" min="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Transaction Fee :</label>
                                <div class="col-lg-7">
                                    <input type="number" class="form-control" id="transaction_fee" name="transaction_fee" step="0.01" value="0" min="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-form-label">Total :</label>
                                <div class="col-lg-7">
                                    <input type="number" class="form-control" id="total_other_charges" name="total_other_charges" step="0.01" value="0" min="0" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="v-renewal-amount">
                        <form id="sales-proposal-renewal-amount-form" method="post" action="#">
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
                                <td><input type="number" class="form-control" id="registration_second_year" name="registration_second_year" step="0.01" value="0" min="0"></td>
                                <td><input type="number" class="form-control" id="registration_third_year" name="registration_third_year" step="0.01" value="0" min="0"></td>
                                <td><input type="number" class="form-control" id="registration_fourth_year" name="registration_fourth_year" step="0.01" value="0" min="0"></td>
                            </tr>
                            <tr>
                                <td>Ins. Coverage</td>
                                <td><input type="number" class="form-control" id="insurance_coverage_second_year" name="insurance_coverage_second_year" step="0.01" value="0" readonly></td>
                                <td><input type="number" class="form-control" id="insurance_coverage_third_year" name="insurance_coverage_third_year" step="0.01" value="0" readonly></td>
                                <td><input type="number" class="form-control" id="insurance_coverage_fourth_year" name="insurance_coverage_fourth_year" step="0.01" value="0" readonly></td>
                            </tr>
                            <tr>
                                <td>Ins. Premium</td>
                                <td><input type="number" class="form-control" id="insurance_premium_second_year" name="insurance_premium_second_year" step="0.01" value="0" readonly></td>
                                <td><input type="number" class="form-control" id="insurance_premium_third_year" name="insurance_premium_third_year" step="0.01" value="0" readonly></td>
                                <td><input type="number" class="form-control" id="insurance_premium_fourth_year" name="insurance_premium_fourth_year" step="0.01" value="0" readonly></td>
                            </tr>
                            </tbody>
                        </table>
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
                                                        <td colspan="4"><b>No. <span id="summary-sales-proposal-number"></span></b></td>
                                                        <td colspan="4" class="text-end"><b>Date: <?php echo date('d-M-Y'); ?> </b></td>
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
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"></td>
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
                        </form>
                    </div>
                    <div class="tab-pane" id="v-online">
                        <div class="print-area-online">
                            <div class="row">
                                <div class="col-lg-12">
                                    <p class="text-center text-danger"><b>MAHALAGANG PAALALA</b></p>
                                    <p class="text-danger"><b>PAGBAYAD SA BANGKO</b></p>
                                    <p style="text-align: justify !important;">IDEPOSITO ANG INYONG BAYAD SA ITINAKDANG ACCOUNT NG AMING KUMPANYA NA NAKAPANGALAN SA “CHRISTIAN GENERAL MOTORS INC", "N E TRUCK BUILDERS CORPORATION", O “GRACE C. BAGUISA”. PAKITINGNAN PO NG MABUTI KUNG ITO ANG NAKASULAT NA PANGALAN SA INYONG DEPOSIT SLIP.</p>
                                    <p class="text-justify text-danger">ANUMANG BAYAD SA PAMAMAGITAN NG LBC, JRS, O IBA PANG KATULAD NA KUMPANYA AY HINDI PINAHIHINTULUTAN NG AMING KUMPANYA.</p>
                                    <p style="text-align: justify !important;">KUNIN LAMANG PO ANG AMING BANK ACCOUNTS KAY MR. CHRISTIAN EDWARD BAGUISA SA CELLPHONE NO. 0919-062-6563.</p>
                                    <p class="text-danger"><b>TAMANG PARAAN NG PAGBABAYAD SA BANGKO</b></p>
                                    <p style="text-align: justify !important;">ISULAT SA DEPOSIT SLIP, INYONG KOPYA AT KOPYA NG BANGKO, ANG INYONG PANGALAN PARA MAIPROSESO NAMIN NG TAMA ANG INYONG BAYAD.</p>
                                    <p style="text-align: justify !important;">PAGKATAPOS I-TEXT, PAKI-SCAN AT PAKI-EMAIL ANG DEPOSIT SLIP SA collections@christianmotors.ph SA ARAW NG PAGKAKADEPOSITO O SA SUSUNOD NA ARAW.</p>
                                    <p style="text-align: justify !important;">PAKITAGO ANG DEPOSIT SLIP AT PAKIDALA ANG ORIHINAL NA KOPYA SA AMING KUMPANYA. MANGHINGI PO NG OFFICIAL RECEIPT SA AMING KAHERA KAPALIT ANG ORIHINAL NA DEPOSIT SLIP.</p>
                                    <p style="text-align: justify !important;">KUNG SAKALI PO NA HINDI KAYO MAKAKAPUNTA SA AMING OPISINA, SIGURADUHIN PO NA ANG INYONG DEPOSIT SLIPS AY NAKATAGO PARA MAGAMIT NA BATAYAN NG INYONG PAGKAKABAYAD SA HINAHARAP.</p>
                                    <p class="text-danger"><b>BABALA</b></p>
                                    <p style="text-align: justify !important;">MANGYARI PO LAMANG NA IPAGBIGAY ALAM SA AMING KUMPANYA KUNG MAYROONG MAG-UUTOS SA INYO NA BAYARAN ANG INYONG ACCOUNT MALIBAN SA ITINAKDANG DEPOSIT ACCOUNTS NG AMING KUMPANYA SA CELLPHONE NOS. 0919-062-6563/0962-098-4672.</p>
                                    <p style="text-align: justify !important;">ANG AMING KUMPANYA AY WALANG PANANAGUTAN SA ANUMANG BAYAD NA INYONG IPINADALA SA IBANG PARAAN.</p>
                                    <p style="text-align: justify !important;">MARAMING SALAMAT PO SA INYONG PATULOY NA PAGTANGKILIK.</p>
                                    <p style="text-align: justify !important;">TINANGGAP AT LUBOS NA NAUUNAWAAN:</p><br/>
                                    <div class="row">
                                        <div class="col-4 border-top" style="border-color: #000 !important;">
                                            <p class="text-center text-uppercase">Lagda / Petsa</p>
                                        </div>
                                    </div>
                                </div>
                            </div><br/><br/>
                            <div class="row">
                                <div class="col-lg-12">
                                    <p class="text-center text-danger"><b>IMPORTANT REMINDERS</b></p>
                                    <p class="text-danger"><b>ON-LINE DEPOSITS OR PAYMENTS</b></p>
                                    <p style="text-align: justify !important;">FOR ALL ON-LINE PAYMENTS, KINDLY ENSURE THAT ONLY THE DESIGNATED BANK ACCOUNTS WITH ACCOUNT NAMES “CHRISTIAN GENERAL MOTORS, INC.", "N E TRUCK BUILDERS CORPORATION” OR “GRACE BAGUISA” WILL BE REFLECTED IN THE BANK DEPOSIT SLIPS.</p>
                                    <p class="text-justify text-danger">OTHER MODE OF PAYMENTS SUCH AS BUT NOT LIMITED TO LBC, JRS OR ANY OTHER MONEY TRANSFER COMPANIES ARE NOT AUTHORIZED BY THE COMPANY.</p>
                                    <p style="text-align: justify !important;">KINDLY SECURE THE ACCOUNT NUMBER OF THE COMPANY’S DESIGNATED BANK ACCOUNTS ONLY FROM MR. CHRISTIAN EDWARD BAGUISA WITH CONTACT NO. 0919-062-6563.</p>
                                    <p class="text-danger"><b>PROCEDURES IN MAKING ON-LINE PAYMENTS THRU BANKS</b></p>
                                    <p style="text-align: justify !important;">KINDLY INDICATE IN THE BANK DEPOSIT SLIP, BOTH IN DEPOSITOR’S COPY AND BANK COPY YOUR NAME FOR PROPER POSTING OF PAYMENT.</p>
                                    <p style="text-align: justify !important;">PLEASE TEXT YOUR NAME, NAME OF BANK AND BRANCH WHERE THE ON-LINE DEPOSIT WAS MADE, AMOUNT OF DEPOSIT AND DATE OF PAYMENT TO CELLPHONE NOS. 0916-062-6563/0962-098-4672.</p>
                                    <p style="text-align: justify !important;">AFTER TEXTING, KINDLY SCAN AND E-MAIL THE DEPOSIT SLIP TO collections@christianmotors.ph ON THE SAME DAY THE DEPOSIT IS MADE OR THE FOLLOWING DAY.</p>
                                    <p style="text-align: justify !important;">KINDLY SAFEKEEP THE BANK DEPOSIT SLIP AND BRING THE ORIGINAL COPY UPON VISIT TO OUR COMPANY. REQUEST THE CORRESPONDING OFFICIAL RECEIPT ONLY TO OUR DESIGNATED CASHIER UPON SURRENDER OF BANK DEPOSIT SLIP.</p>
                                    <p style="text-align: justify !important;">IN CASE YOU WILL NOT BE ABLE TO VISIT OUR COMPANY, PLEASE ENSURE THAT ALL YOUR BANK DEPOSIT SLIPS ARE SAFEKEPT FOR FUTURE REFERENCE.</p>
                                    <p class="text-danger"><b>WARNINGS</b></p>
                                    <p style="text-align: justify !important;">PLEASE ADVISE OUR COMPANY IMMEDIATELY IF THERE IS AN ATTEMPT TO REQUEST YOU TO ON-LINE YOUR PAYMENT TO ACCOUNTS OTHER THAN OUR DESIGNATED BANK ACCOUNTS THRU CELLPHONE NOS. 0916-062-6563/0962-098-4672.</p>
                                    <p style="text-align: justify !important;">OUR COMPANY HAS NO RESPONSILIBITY OR LIABILITY FOR ALL YOUR PAYMENTS COURSED THRU OTHER COMPANIES OR INDIVIDUALS.</p>
                                    <p style="text-align: justify !important;">THANK YOU VERY MUCH FOR YOUR CONTINUED SUPPORT TO OUR COMPANY.</p>
                                    <p style="text-align: justify !important;">RECEIVED AND CLEARLY UNDERSTOOD BY:</p><br/>
                                    <div class="row">
                                        <div class="col-4 border-top" style="border-color: #000 !important;">
                                            <p class="text-center text-uppercase">Signature /  Date</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-autorization">
                        <div class="print-area-autorization">
                            <div class="row">
                                <div class="col-lg-12">
                                    <p><b>THE MANAGER<br/>CHRISTIAN GENERAL MOTORS, INC<br/>CABANATUAN CITY</b></p>
                                    <p class="text-center"><b>AUTHORIZATION AND UNDERTAKING</b></p>
                                    <p style="text-align: justify !important;">This is to authorize your company to deposit any of the checks I issued upon due date of my monthly installment. In case the check is dishonored by the drawee bank for whatever reason, I agreed to pay additional 3% late payment penalty.</p>
                                    <p style="text-align: justify !important;">If my check is dishonored due to "Account Closed", I hereby undertake to replace the remaining checks to cover my monthly installment without need of demand or notice, otherwise, my entire outstanding obligation will become due and demandable. I also agreed to pay additional 3% late payment penalty.</p>
                                    <p style="text-align: justify !important;">Finally, I hereby authorize the company to indicate any details on the face of the checks provided it is based on the agreed terms and conditions as stipulated in the Deed of Conditional Sale.</p><br/>
                                    <div class="row">
                                        <div class="col-6 border-top" style="border-color: #000 !important;">
                                            <p class="text-center text-uppercase">NAME OF CUSTOMER OVER PRINTED NAME</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-promissory-note">
                        <div class="card-body print-area-promissory-note">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="text-center fw-8"><b>PROMISSORY NOTE</b></h4>
                                    <p class="text-left fw-8"><b><?php echo number_format($totalPn, 2); ?></b></p>
                                    <p style="text-align: justify !important; font-size:12px;">For value received, I/we jointly and severally promise to pay without need of demand to the order of Christian General Motors Incorporated at its principal office at Km 112, Maharlika Highway, Brgy Hermogenes Concepcion, Cabanatuan City, Nueva Ecija, Philippines, the sum of <b><?php echo strtoupper($amountInWords->format($totalPn)) . ' PESOS'; ?> <?php echo '(' .number_format($totalPn, 2) . ')'; ?></b> payable based on the <b>SCHEDULE OF PAYMENTS</b> as stipulated in the <b>Disclosure Statement on Credit Sales Transaction</b> which I/we duly received, agreed and understood.</p>
                                    <p style="text-align: justify !important; font-size:12px;">Time is declared of the essence hereof and in case of default in the payment of any installment due, all the other instalments shall automatically become due and demandable and shall make me liable for the additional sum equivalent to <b>THREE percent (3%) per month</b> based on the total amount due and demandable as penalty, compounded monthly until fully paid; and in case it becomes necessary to collect this note through any Attorney-at-Law, the further sum of <b>TWENTY percent (20%)</b> thereof and <b>ATTORNEY'S FEES of THIRTY percent (30%)</b> of total amount due , exclusive of costs and judicial/extra-judicial expenses; Moreover, I further empower the holder or any of his authorized representative(s), at his option, to hold as security therefore any real or personal property which may be in my possession or control by virtue of any other contract.</p>
                                    <p style="text-align: justify !important; font-size:12px;">In case of extraordinary change in value of the peso due to extraordinary inflation or deflation or any other reason, the basis of payment for this note shall be the value of the peso at the time the obligation was incurred as provided in Article 1250 of the New Civil Code.</p>
                                    <p style="text-align: justify !important; font-size:12px;">DEMAND AND NOTICE OF DISHONOR WAIVED. I hereby waive any diligence, presentment, demand, protests or notice of non-payment or dishonor pertaining to this note, or any extension or renewal therefore. Holder(s) may accept partial payment(s) and grant renewals or extensions of payment reserving its/their rights against each and all indorsers, and all parties to this note. Acceptance by holder(s) of any partial payment(s) after due date shall not be considered as extending the time for payment or as a modification of any conditions hereof.</p>
                                    <p style="text-align: justify !important; font-size:12px;">All actions arising from or connected with this note shall be brought exclusively in the proper courts of CABANATUAN CITY, Philippines; and in case of judicial execution of this obligation or any part of it, the debtor waives all rights under the provisions of Rule 39 Sec. 13, of the Rules of the Court.</p>
                                    <p style="text-align: justify !important; font-size:12px;">Signed this ____ day of ___________________, 20___  at_______________________________, Philippines.</p>
                                    <div class="row mt-5">
                                        <div class="col-5">
                                            <p class="text-center mb-0"><?php echo strtoupper($customerName); ?></p>
                                            <p class="text-center text-uppercase border-top" style="border-color: #000 !important;">MAKER</p>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-5">
                                            <?php echo $comakerLabel; ?>
                                            <p class="text-center text-uppercase border-top" style="border-color: #000 !important;">CO-MAKER</p>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-5">
                                            <small class="text-left mb-0"><?php echo strtoupper($customerAddress); ?></small>
                                            <p class="text-center text-uppercase border-top" style="border-color: #000 !important;">ADDRESS</p>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-5">
                                            <?php echo strtoupper($comakerAddressLabel); ?>
                                            <p class="text-center text-uppercase border-top" style="border-color: #000 !important;">ADDRESS</p>
                                        </div>
                                    </div>
                                    <p class="text-center fw-8"><b>SIGNED IN THE PRESENCE OF:</b></p>

                                    <div class="row mt-4">
                                        <div class="col-5">
                                            <p class="text-center text-uppercase border-top" style="border-color: #000 !important;"></p>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-5">
                                            <p class="text-center text-uppercase border-top" style="border-color: #000 !important;"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-disclosure">
                        <div class="print-area-disclosure">
                            <div class="row">
                                <div class="col-lg-12" style="font-size:12px !important;">
                                    <h5 class="text-center fw-8"><b>DISCLOSURE STATEMENT ON CREDIT SALES TRANSACTION</b></h5>
                                    <h5 class="text-center fw-8"><b>(As required under R. A. 3765 Truth in Lending Act)</b></h5><br/>
                                    <div class="row">
                                        <div class="col-3 mb-0">
                                            <p class="text-left">NAME OF BUYER</p>
                                        </div>
                                        <div class="col-9 mb-0">
                                            <p class="text-left"><?php echo strtoupper($customerName); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <p class="text-left mb-3">ADDRESS</p>
                                        </div>
                                        <div class="col-9">
                                            <p class="text-left mb-3"><?php echo strtoupper($customerAddress); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p class="text-left ">1. TOTAL PRICE OF ITEM PURCHASED</p>
                                        </div>
                                        <div class="col-4">
                                            <p class="text-end "><?php echo number_format($totalPn2, 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p class="text-left ">2. DOWNPAYMENT AND/OR TRADE-IN VALUE</p>
                                        </div>
                                        <div class="col-4">
                                            <p class="text-end "><?php echo number_format($downpayment, 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8">
                                            <p class="text-left ">3. UNPAID BALANCE OF TOTAL PRICE</p>
                                        </div>
                                        <div class="col-4">
                                            <p class="text-end "><?php echo number_format($pnAmount, 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="text-left ">4. OTHER CHARGES (TO BE COLLECTED SEPARATELY TO BUYER)</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start ">a. Insurance</p>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start "><?php echo number_format($insurancePremium, 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start ">b. Handling Fee</p>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start "><?php echo number_format($handlingFee, 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start ">c. Transfer Fee</p>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start "><?php echo number_format($transferFee, 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start ">d. Miscellaneous/Transaction Fee</p>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start "><?php echo number_format(($transactionFee + $docStampTax), 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start ">e. Insurance Renewal</p>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start "><?php echo number_format($totalInsuranceFee, 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start ">f. Registration Renewal</p>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start "><?php echo number_format($totalRenewalFee, 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start "><b>TOTAL OTHER CHARGES</b></p>
                                         </div>
                                        <div class="col-2"></div>
                                        <div class="col-4">
                                            <p class="text-start "><?php echo number_format($totalCharges, 2); ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="text-left ">5. CONDITIONAL CHARGES MAYBE IMPOSED</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <p class="text-start ">a. Late payment penalty of straight 3% per month based on unpaid installments due. </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <p class="text-start ">b. Attorney's fee - 30% of total amount due; liquidated damages- further sum of 20% in addition to costs and other litigation expenses;</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <p class="text-start ">c. Liquidated damages- further sum of 20% in addition to costs and other litigation expenses</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="text-left ">6. OTHER CHARGES</p>
                                        </div>
                                    </div>
                                    <table class="table table-bordered w-100 text-center" style="border: 1px solid #000 !important;">
                                        <tr>
                                            <td>DUE DATE</td>
                                            <td>AMOUNT DUE</td>
                                            <td>PAYMENT FOR</td>
                                        </tr>
                                        <tbody id="other-charges-rows"></tbody>
                                    </table>
                                    <div class="pagebreak mt-0"></div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="text-left ">7. SCHEDULE OF PAYMENTS</p>
                                        </div>
                                    </div>
                                    <table class="table table-bordered w-100 text-center" id="disclosure-schedule" style="border: 1px solid #000 !important;">
                                        
                                    </table>
                                    <p style="text-align: justify !important;"><b>I ACKNOWLEDGE RECEIPT OF A COPY OF THIS STATEMENT PRIOR TO THE CONSUMMATION OF THE CREDIT SALES TRANSACTION AND THAT I UNDERSTAND AND FULLY AGREE TO THE TERMS AND CONDITIONS THEREOF.</b></p><br/>
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <p class="text-center text-uppercase border-top" style="border-color: #000 !important;">SIGNATURE OF BUYER OVER PRINTED NAME</p>
                                        </div>
                                    </div>
                                    <p style="text-align: justify !important;"><b>This is a system-generated Disclosure Statement on Credit Sales Transaction and does not require a signature from Christian General Motors Inc.</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-insurance-request">
                        <div class="print-area-insurance-request">
                            <div class="row" style="font-size:12px !important;">
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">STM NUMBER</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left"><?php echo strtoupper($drNumber); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-0">
                                        <p class="text-left"><b>CHRISTIAN GENERAL MOTORS, INC.</b></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">NAME:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left"><?php echo strtoupper($customerName); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">ADDRESS:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left"><?php echo strtoupper($customerAddress); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">INCEPTION:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left"><?php echo strtoupper(date('F d, Y', strtotime($startDate))); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">UNIT NO.:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left" id="insurance_unit_no"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">YR/MODEL:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left" id="insurance_year_model"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">COLOR:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left text-uppercase" id="insurance_color"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">MAKE/TYPE:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left" id="insurance_make"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">PLATE NO.:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left" id="insurance_plate_no"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">CHASSIS NO.:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left" id="insurance_chassis_no"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">ENGINE NO.:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left" id="insurance_engine_no"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">MV FILE NO.:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                    <p class="text-left" id="insurance_mv_file_no"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 mb-0">
                                        <p class="text-left">MORTGAGEE:</p>
                                    </div>
                                    <div class="col-9 mb-0">
                                        <p class="text-left">CGMI</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-0">
                                        <p class="text-left"><b>CHRISTIAN GENERAL MOTORS, INC.</b></p>
                                    </div>
                                </div>
                                <table class="table table-bordered w-100 text-center" style="border: 1px solid #000 !important;">
                                    <tr>
                                        <td><b>RISK</b></td>
                                        <td><b>COVERAGE</b></td>
                                        <td><b>RATE</b></td>
                                        <td><b>PREMIUM</b></td>
                                    </tr>
                                    <tbody>
                                        <tr>
                                            <td><b>CTPL</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>OD/Theft</b></td>
                                            <td id="od_theft"></td>
                                            <td id="od_rate"></td>
                                            <td id="od_theft_premium"></td>
                                        </tr>
                                        <tr>
                                            <td><b>AON</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>TPBI</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>TPPD</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>PAR</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <div class="pagebreak"></div>
                                        <tr>
                                            <td></td>
                                            <td><b>Total Premium</b></td>
                                            <td></td>
                                            <td id="total_premium"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>VAT/PREMIUM TAX</td>
                                            <td></td>
                                            <td id="vat_premium"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>DOC. STAMPS</td>
                                            <td></td>
                                            <td id="doc_stamps"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>LOCAL GOV'T TAX</td>
                                            <td></td>
                                            <td id="local_govt_tax"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><b>Gross</b></td>
                                            <td></td>
                                            <td id="gross"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered w-100 text-center" style="border: 1px solid #000 !important;">
                                    <tbody>
                                        <tr>
                                            <td>TERM</td>
                                            <td class="text-uppercase" id="insurance_term"></td>
                                            <td rowspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td>MATURITY</td>
                                            <td class="text-uppercase" id="insurance_maturity"></td>
                                        </tr>
                                        <tr>
                                            <td>1st YEAR COV</td>
                                            <td id="1st_year_coverage"></td>
                                            <td><?php echo strtoupper(date('F d, Y', strtotime($startDate))); ?></td>
                                        </tr>
                                        <tr>
                                            <td>2nd YEAR COV</td>
                                            <td id="2nd_year_coverage"></td>
                                            <td id="2nd_year_date"></td>
                                        </tr>
                                        <tr>
                                            <td>3rd YEAR COV</td>
                                            <td id="3rd_year_coverage"></td>
                                            <td id="3rd_year_date"></td>
                                        </tr>
                                        <tr>
                                            <td>4th YEAR COV</td>
                                            <td id="4th_year_coverage"></td>
                                            <td id="4th_year_date"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-gatepass">
                        <div class="print-area-gatepass">
                            <div class="row">
                                <div class="col-4">
                                    <h5 class="text-center"><b>GATE PASS - UNIT</b></h5>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-center"><b>NO : <?php echo $drNumber; ?></b></h6>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-center"><b>DATE : <?php echo date('d-M-Y'); ?></b></h6>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <div class="table-border-style mw-100">
                                        <div class="table-border-style mw-100">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-xs text-uppercase">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2"><small>Customer Name</small></td>
                                                            <td colspan="3"><small><?php echo $customerName; ?></small></td>
                                                            <td rowspan="4" class="text-center">
                                                                <img src="<?php echo $unitImage; ?>" width="100">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>Contact Number</small></td>
                                                            <td colspan="3"><small><?php echo $customerMobile; ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>Address</small></td>
                                                            <td colspan="3"><small><?php echo strtoupper($customerAddress); ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>DR No.</small></td>
                                                            <td><small><?php echo $drNumber; ?></small></td>
                                                            <td><small>DR Date</small></td>
                                                            <td><small><?php echo date('d-M-Y'); ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6">
                                                                <p class="text-center"><small>PARTICULARS</small></p>
                                                                <p class="text-wrap"><small id="other-details-gatepass1"></small></p>
                                                                <?php
                                                                    if($productType == 'Unit'){
                                                                        echo '<p class="text-danger"><b><u><small id="unit_id_gatepass"></small></u></b></p>';
                                                                        }
                                                                        else{
                                                                            echo '<p class="text-danger"><b><u><small>'. $productType . $salesProposalNumber .'</small></u></b></p>';
                                                                        }
                                                                    ?>
                                                                    <p><small id="product_description_gatepass"></small></p><br/>
                                                                    <small class="text-danger">REMINDER:</small><br/>
                                                                    <small >GATE PASS SHALL ALWAYS BE ACCOMPANIED BY DULY APPROVED DELIVERY RECEIPT</small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            <td colspan="3" style="vertical-align: top !important;" class="text-nowrap">
                                                                <small>RELEASED BY:</small><br/><br/><br/>
                                                                <small>CUSTOMER/AUTHORIZED REPRESENTATIVE</small>
                                                            </td>
                                                            <td colspan="2" style="vertical-align: top !important;" class="text-nowrap">
                                                                <small>RELEASED APPROVED BY:</small><br/><br/><br/>
                                                                <small>CHRISTIAN EDWARD C. BAGUISA &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; PAOLO EDUARDO C. BAGUISA</small>
                                                            </td>
                                                            <td style="vertical-align: top !important;" class="text-wrap">
                                                                <small>INSPECTED BY/DATE:</small><br/><br/><br/>
                                                                <small>GUARD ON DUTY</small>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table><br/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-4">
                                    <h5 class="text-center"><b>GATE PASS - UNIT</b></h5>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-center"><b>NO : <?php echo $drNumber; ?></b></h6>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-center"><b>DATE : <?php echo date('d-M-Y'); ?></b></h6>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <div class="table-border-style mw-100">
                                        <div class="table-border-style mw-100">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-xs text-uppercase">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2"><small>Customer Name</small></td>
                                                            <td colspan="3"><small><?php echo $customerName; ?></small></td>
                                                            <td rowspan="4" class="text-center">
                                                                <img src="<?php echo $unitImage; ?>" width="100">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>Contact Number</small></td>
                                                            <td colspan="3"><small><?php echo $customerMobile; ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>Address</small></td>
                                                            <td colspan="3"><small><?php echo strtoupper($customerAddress); ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>DR No.</small></td>
                                                            <td><small><?php echo $drNumber; ?></small></td>
                                                            <td><small>DR Date</small></td>
                                                            <td><small><?php echo date('d-M-Y'); ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6">
                                                                <p class="text-center"><small>PARTICULARS</small></p>
                                                                <p class="text-wrap"><small id="other-details-gatepass2"></small></p>
                                                                <?php
                                                                    if($productType == 'Unit'){
                                                                        echo '<p class="text-danger"><b><u><small id="unit_id_gatepass"></small></u></b></p>';
                                                                        }
                                                                        else{
                                                                            echo '<p class="text-danger"><b><u><small>'. $productType . $salesProposalNumber .'</small></u></b></p>';
                                                                        }
                                                                    ?>
                                                                    <p><small id="product_description_gatepass"></small></p><br/>
                                                                    <small class="text-danger">REMINDER:</small><br/>
                                                                    <small >GATE PASS SHALL ALWAYS BE ACCOMPANIED BY DULY APPROVED DELIVERY RECEIPT</small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            <td colspan="3" style="vertical-align: top !important;" class="text-nowrap">
                                                                <small>RELEASED BY:</small><br/><br/><br/>
                                                                <small>CUSTOMER/AUTHORIZED REPRESENTATIVE</small>
                                                            </td>
                                                            <td colspan="2" style="vertical-align: top !important;" class="text-nowrap">
                                                                <small>RELEASED APPROVED BY:</small><br/><br/><br/>
                                                                <small>CHRISTIAN EDWARD C. BAGUISA &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; PAOLO EDUARDO C. BAGUISA</small>
                                                            </td>
                                                            <td style="vertical-align: top !important;" class="text-wrap">
                                                                <small>INSPECTED BY/DATE:</small><br/><br/><br/>
                                                                <small>GUARD ON DUTY</small>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table><br/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-pdc-manual-input-offcanvas" aria-labelledby="sales-proposal-pdc-manual-input-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="sales-proposal-pdc-manual-input-offcanvas-label" style="margin-bottom:-0.5rem">PDC Manual Input</h2>
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