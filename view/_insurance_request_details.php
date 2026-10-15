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
                    $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#receive-offcanvas" aria-controls="receive-offcanvas" id="received">Receive Insurance Policy</button></li>';
                }

                if($status == 'For Submission' || $status == 'Submitted'){
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="draft">Set to Draft</button></li>';
                    $dropdown .= '<li><a href="print-insurance-request.php?id='. $insuranceRequestID .'" class="dropdown-item" target="_blank">Print</a></li>';
                }

                if($status == 'Draft' || $status == 'For Submission'){
                  $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#cancel-offcanvas" aria-controls="cancel-offcanvas" id="cancel">Cancel</button></li>';
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

            
            <div class="col-md-6 ">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Paid By <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="paid_by" name="paid_by" maxlength="500" autocomplete="off" <?= $disabled ?>>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">For Collection Amount <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="number" class="form-control" id="collection_amount" name="collection_amount" step="0.01" min="0" value="0.00" <?= $disabled ?>>
                </div>
              </div>
            </div>
            
            <div class="col-md-6 ">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Assured</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="assured" name="assured" maxlength="500" autocomplete="off" <?= $disabled ?>>
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
                    <option value="Product">Product</option>
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

            <div class="col-md-6 product-field d-none">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Product</label>
                <div class="col-lg-8">
                   <select class="form-control select2" name="product_id" id="product_id" <?= $disabled ?>>
                    <option value="">--</option>
                    <?php echo $productModel->generateAllProductWithStockNumberOptions(); ?>
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
                  <input type="text" class="form-control" id="year_model" name="year_model" maxlength="100" autocomplete="off" <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Color</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="color" name="color" maxlength="100" autocomplete="off" <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Make <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="make" name="make" maxlength="100" autocomplete="off" <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Plate Number <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="plate_number" name="plate_number" maxlength="100" autocomplete="off" <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Chassis Number</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="chassis_number" name="chassis_number" maxlength="100" autocomplete="off" <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Engine Number</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="engine_number" name="engine_number" maxlength="100" autocomplete="off" <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">MV File Number</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="mv_file_number" name="mv_file_number" maxlength="100" autocomplete="off" <?= $disabled ?>>
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

    <div class="row">

        <!-- Coverage Table -->
        <div class="col-lg-7">

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
                                  <?php echo $insuranceCategoryModel->generateInsuranceCategoryOptions(); ?>
                                </select>
                              </div>
                            </div>
                            <div class="form-group row mb-3">
                              <label class="col-lg-4 col-form-label">Encoding Mode <span class="text-danger">*</span></label>
                              <div class="col-lg-8">
                                <select class="form-control select2" name="encoding_mode" id="encoding_mode" <?= $disabled ?>>
                                  <option value="Auto" selected>Auto</option>
                                  <option value="Manual">Manual</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead class="table-light">
                                    <tr>
                                        <th>Coverage Type</th>
                                        <th>Coverage Amount</th>
                                        <th>Rate (%)</th>
                                        <th>Premium</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <td>
                                            <select class="form-control" name="coverage_type" id="coverage_type" <?= $disabled ?>>
                                              <option value="OD/Theft">OD/Theft</option>
                                              <option value="All Risk">All Risk</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="od_theft_coverage"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="od_theft_rate"
                                                max="100"
                                                value="0.00"
                                                readonly
                                                 <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="od_theft_premium"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>CTPL</td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="ctpl_coverage"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="ctpl_rate"
                                                max="100"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="ctpl_premium"
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
                                                class="form-control"
                                                id="aon_coverage"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="aon_rate"
                                                max="100"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="aon_premium"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TPBI</td>
                                        <td colspan="2">
                                            <select class="form-control select2" name="tpbi_coverage" id="tpbi_coverage" <?= $disabled ?>>
                                              <option value="">--</option>
                                              <option value="100000">100,000</option>
                                              <option value="200000">200,000</option>
                                              <option value="300000">300,000</option>
                                              <option value="400000">400,000</option>
                                              <option value="500000">500,000</option>
                                            </select>
                                        </td>
                                        <td class="d-none">
                                            <input type="number"
                                                class="form-control d-none"
                                                id="tpbi_rate"
                                                max="100"
                                                value="0.00"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="tpbi_premium"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TPPD</td>
                                        <td colspan="2">
                                            <select class="form-control select2" name="tppd_coverage" id="tppd_coverage" <?= $disabled ?>>
                                              <option value="">--</option>
                                              <option value="100000">100,000</option>
                                              <option value="200000">200,000</option>
                                              <option value="300000">300,000</option>
                                              <option value="400000">400,000</option>
                                              <option value="500000">500,000</option>
                                            </select>
                                        </td>
                                        <td class="d-none">
                                            <input type="number"
                                                class="form-control d-none"
                                                id="tppd_rate"
                                                max="100"
                                                value="0.00"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="tppd_premium"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>PAR</td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="par_coverage"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="par_rate"
                                                max="100"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
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
        <div class="col-lg-5">

          <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Commission Details
                    </h5>
                </div>

                <div class="card-body">
                  <form id="insurance-request-commission-form">
                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead class="table-light">
                                    <tr>
                                        <th>Coverage Type</th>
                                        <th>Rate (%)</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>
                                            Premium
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="od_theft_commission_rate"
                                                max="100"
                                                value="0.00"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="od_theft_commission"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            AON
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="aon_commission_rate"
                                                max="100"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="aon_commission"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TPBI</td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="tpbi_commission_rate"
                                                max="100"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="tpbi_commission"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>TPPD</td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="tppd_commission_rate"
                                                max="100"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="tppd_commission"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>PAR</td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="par_commission_rate"
                                                max="100"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="par_commission"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Subtotal</td>
                                        <td colspan="2">
                                            <input type="number"
                                                class="form-control text-end"
                                                id="commission_subtotal"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Less wtp</td>
                                        <td>
                                            <input type="number"
                                                class="form-control"
                                                id="commission_discount_rate"
                                                max="100"
                                                value="0.00" <?= $disabled ?>>
                                        </td>
                                        <td>
                                            <input type="number"
                                                class="form-control text-end"
                                                id="commission_discount"
                                                value="0.00"
                                                readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Net Commission</td>
                                        <td colspan="2">
                                            <input type="number"
                                                class="form-control text-end"
                                                id="net_commission"
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

          <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Premium Summary
                    </h5>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <td>
                              Total Premium
                          </td>
                          <td>
                            <input type="number" class="form-control text-end" id="total_premium" value="0.00" readonly>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              VAT / Premium Tax
                          </td>
                          <td>
                            <input type="number" class="form-control text-end" id="vat_premium_tax" value="0.00" readonly>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              Document Stamp
                          </td>
                          <td>
                            <input type="number" class="form-control text-end" id="docstamp" value="0.00" readonly>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              Local Government Tax
                          </td>
                          <td>
                            <input type="hidden" class="form-control text-end" id="local_government_tax_rate" value="0.00" readonly>
                            <input type="number" class="form-control text-end" id="local_tax" value="0.00" readonly>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              Gross
                          </td>
                          <td>
                            <input type="number" class="form-control text-end" id="gross" value="0.00" readonly>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              Net Premium
                          </td>
                          <td>
                            <input type="number" class="form-control text-end" id="net_premium" value="0.00" readonly>
                          </td>
                        </tr>
                      </tbody>
                    </table>
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
                <label class="form-label">Gross <span class="text-danger">*</span></label>
               <input type="number" class="form-control" id="gross" name="gross" min="1">
              </div>
              <div class="col-lg-12 mt-3"> 
                <label class="form-label">Net Amount <span class="text-danger">*</span></label>
               <input type="number" class="form-control" id="net_premium" name="net_premium" min="1">
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

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cancel-offcanvas" aria-labelledby="cancel-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="cancel-offcanvas-label" style="margin-bottom:-0.5rem">Tag As Cancelled </h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="cancel-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="cancellation_reason">Cancellation Remarks <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" maxlength="500"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-cancel" form="cancel-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
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
                '. $userModel->generateLogNotes('insurance_request', $insuranceRequestID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>