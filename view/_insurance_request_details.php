<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Insurance Request</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';


                if($status == 'Draft'){
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="for-submission">For Submission</button></li>';
                }

                if($status == 'For Submission'){
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="submitted">Submitted</button></li>';
                }

                if($status == 'Submitted'){
                    $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#receive-offcanvas" aria-controls="receive-offcanvas" id="received">Receive</button></li>';
                }

                if($status == 'For Submission' || $status == 'Submitted'){
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="draft">Set to Draft</button></li>';
                    $dropdown .= '<li><a href="insurance-request.php?id='. $insuranceRequestID .'" class="dropdown-item" type="button">Print</a></li>';
                }
                            
                if ($insuranceRequestDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-insurance-request-details">Delete Insurance Request</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($insuranceRequestWriteAccess['total'] > 0 && $status == 'Draft') {
                    echo '<button type="submit" form="insurance-request-form" class="btn btn-success" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger me-1">Discard</button>';
                }

                if ($insuranceRequestCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="insurance-request.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="insurance-request-form" method="post" action="#">
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Request Type <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-select select2" id="request_type" name="request_type" <?= $disabled ?>>
                    <option value="">--</option>
                    <option value="New Policy">New Policy</option>
                    <option value="Renewal">Renewal</option>
                  </select>
                </div>
              </div>
            </div>

             <div class="col-md-6 d-none renewal-field">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Insurance Policy <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="insurance_policy_id" id="insurance_policy_id" <?= $disabled ?>>
                    <option value="">--</option>
                    <?php echo $insurancePolicyModel->generateInsurancePolicyOptions(); ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Inception Date <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="inception_date" name="inception_date" autocomplete="off" <?= $disabled ?>>
                      <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                      </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Insurance Provider <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="insurance_provider_id" id="insurance_provider_id" <?= $disabled ?>>
                    <option value="">--</option>
                    <?php echo $insuranceProviderModel->generateInsuranceProviderOptions(); ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Insurance Type <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="insurance_type_id" id="insurance_type_id" <?= $disabled ?>>
                    <option value="">--</option>
                    <?php echo $insuranceTypeModel->generateInsuranceTypeOptions(); ?>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3 text-muted">Related References</h5>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Customer Type <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="customer_type" id="customer_type" <?= $disabled ?>>
                    <option value="">--</option>
                    <option value="Customer">Customer</option>
                    <option value="Miscellaneous">Miscellaneous</option>
                    <option value="Sales Proposal">Sales Proposal</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6 misc-field d-none">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Customer <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="misc_id" id="misc_id" <?= $disabled ?>>
                    <option value="">--</option>
                    <?php echo $miscellaneousClientModel->generateMiscellaneousClientOptions(); ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6 customer-field d-none">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Customer <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="customer_id" id="customer_id" <?= $disabled ?>>
                    <option value="">--</option>
                    <?php echo $customerModel->generateCustomerOptions('active customer'); ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6 sales-proposal-field d-none">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Sales Proposal</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="sales_proposal_id" id="sales_proposal_id" <?= $disabled ?>>
                    <option value="">--</option>        
                    <?php echo $salesProposalModel->generateReleasedSalesProposalOptions(); ?>            
                  </select>
                </div>
              </div>
            </div>
            
          </div>
          
          <hr class="my-4 vehicle-field d-none">
          <h5 class="mb-3 text-muted vehicle-field d-none">Vehicle Information</h5>

           <div class="row vehicle-field d-none">
            <div class="col-md-6 ">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Year Model <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="year_model" name="year_model" maxlength="100" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Color</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="color" name="color" maxlength="100" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Make <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="make" name="make" maxlength="100" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Plate Number <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="plate_number" name="plate_number" maxlength="100" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Chassis Number</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="chassis_number" name="chassis_number" maxlength="100" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Engine Number</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="engine_number" name="engine_number" maxlength="100" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">MV File Number</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="mv_file_number" name="mv_file_number" maxlength="100" autocomplete="off">
                </div>
              </div>
            </div>
            
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="mb-1">
                        <i class="bi bi-shield-check me-2"></i>
                        Insurance Computation
                    </h3>
                    <p class="text-muted mb-0">
                        Coverage, Premium Computation and Commission Summary
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <small class="text-muted text-uppercase">
                        Gross Premium
                    </small>
                    <h2 class="fw-bold mt-2 mb-0" id="gross_display">
                        ₱0.00
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <small class="text-muted text-uppercase">
                        Net Premium
                    </small>
                    <h2 class="fw-bold mt-2 mb-0" id="net_premium_display">
                        ₱0.00
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <small class="text-muted text-uppercase">
                        Net Commission
                    </small>
                    <h2 class="fw-bold mt-2 mb-0" id="net_commission_display">
                        ₱0.00
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <!-- Coverage Table -->
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Coverage Details
                    </h5>
                </div>

                <div class="card-body">

                    <form id="insurance-request-computation-form">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group row mb-3">
                              <label class="col-lg-4 col-form-label">Category <span class="text-danger">*</span></label>
                              <div class="col-lg-8">
                                <select class="form-control select2" name="insurance_category" id="insurance_category" <?= $disabled ?>>
                                  <option value="">--</option>
                                  <option value="Trucks">Trucks</option>
                                  <option value="Equipment">Equipment</option>
                                  <option value="Equipment with AON">Equipment with AON</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead class="table-light">
                                    <tr>
                                        <th width="30%">Coverage Type</th>
                                        <th width="35%">Coverage Amount</th>
                                        <th width="35%">Premium</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <td>CTPL</td>
                                        <td>
                                            <input type="number"
                                                class="form-control bg-light"
                                                id="ctpl_coverage"
                                                value="0.00"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control bg-light"
                                                id="ctpl_premium"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            OD/Theft
                                            <span class="text-danger">*</span>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control bg-light"
                                                id="od_theft_coverage"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control bg-light"
                                                id="od_theft_premium"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            AON
                                            <span class="text-danger">*</span>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control bg-light"
                                                id="aon_coverage"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control bg-light"
                                                id="aon_premium"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TPBI</td>
                                        <td>
                                            <select class="form-control bg-light select2" name="tpbi_coverage" id="tpbi_coverage" <?= $disabled ?>>
                                              <option value="">--</option>
                                              <option value="100000">100,000</option>
                                              <option value="200000">200,000</option>
                                              <option value="300000">300,000</option>
                                              <option value="400000" selected>400,000</option>
                                              <option value="500000">500,000</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control bg-light"
                                                id="tpbi_premium"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TPPD</td>
                                        <td>
                                            <select class="form-control bg-light select2" name="tppd_coverage" id="tppd_coverage" <?= $disabled ?>>
                                              <option value="">--</option>
                                              <option value="100000">100,000</option>
                                              <option value="200000">200,000</option>
                                              <option value="300000">300,000</option>
                                              <option value="400000" selected>400,000</option>
                                              <option value="500000">500,000</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control bg-light"
                                                id="tppd_premium"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>PAR</td>
                                        <td>
                                            <input type="number"
                                                class="form-control bg-light"
                                                id="par_coverage"
                                                value="0.00"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control bg-light"
                                                id="par_premium"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </form>

                </div>
            </div>

        </div>

        <!-- Right Side Summary -->
        <div class="col-lg-4">

            <!-- Premium Summary -->
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt me-2"></i>
                        Premium Summary
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between py-2">
                        <span>Total Premium</span>
                        <strong id="total_premium">₱0.00</strong>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span>VAT / Premium Tax</span>
                        <strong id="vat_premium_tax">₱0.00</strong>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span>Document Stamp</span>
                        <strong id="docstamp">₱0.00</strong>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span>Local Government Tax</span>
                        <strong id="local_tax">₱0.00</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between py-2">
                        <span class="fw-bold">Gross</span>
                        <span class="fw-bold fs-5" id="gross">
                            ₱0.00
                        </span>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span class="fw-bold">Net Premium</span>
                        <span class="fw-bold fs-5" id="net_premium">
                            ₱0.00
                        </span>
                    </div>

                </div>

            </div>

            <!-- Commission Summary -->
            <div class="card border-0 shadow-sm">

                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-stack me-2"></i>
                        Commission Summary
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between py-2">
                        <span>Premium</span>
                        <strong id="premium_comission">₱0.00</strong>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span>AON</span>
                        <strong id="aon_comission">₱0.00</strong>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span>TPBI</span>
                        <strong id="tpbi_comission">₱0.00</strong>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span>TPPD</span>
                        <strong id="tppd_comission">₱0.00</strong>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span>PAR</span>
                        <strong id="par_comission">₱0.00</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between py-2">
                        <span>Subtotal</span>
                        <strong id="comission_subtotal">
                            ₱0.00
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span>Less 10% WHT</span>
                        <strong id="commission_discount">
                            ₱0.00
                        </strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between py-2">
                        <span class="fw-bold">
                            Net Commission
                        </span>
                        <span class="fw-bold fs-5"
                            id="net_commission">
                            ₱0.00
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="receive-offcanvas" aria-labelledby="receive-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="receive-offcanvas-label" style="margin-bottom:-0.5rem">Mark as Received</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="receive-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3"> 
                <label class="form-label">Policy Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="policy_number" name="policy_number" maxlength="200" autocomplete="off">
              </div>
              <div class="col-lg-12 mt-3"> 
                <label class="form-label">Inception Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="inception_date2" name="inception_date2" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
              <div class="col-lg-12 mt-3"> 
                <label class="form-label">Expiration Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="expiration_date" name="expiration_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
              <div class="col-lg-12 mt-3"> 
                <label class="form-label">Premium Amount <span class="text-danger">*</span></label>
               <input type="number" class="form-control" id="premium_amount" name="premium_amount" min="1">
              </div>
              <div class="col-lg-12 mt-3"> 
                <label class="form-label">Coverage Amount <span class="text-danger">*</span></label>
               <input type="number" class="form-control" id="coverage_amount" name="coverage_amount" min="1">
              </div>
              <div class="col-lg-12 mt-3"> 
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-receive" form="receive-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>


<div class="row">
  
<?php
  echo '<div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>Log Notes</h5>
                </div>
              </div>
            </div>
            <div class="log-notes-scroll" style="max-height: 450px; position: relative;">
              <div class="card-body p-b-0">
                '. $userModel->generateLogNotes('inquiry_type', $insuranceRequestID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>