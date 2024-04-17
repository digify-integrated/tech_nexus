<?php
  $disabled = '';
  if($salesProposalStatus == 'Draft'){
    $disabled = 'disabled';       
  }
?>
<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <li><a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="pill" href="#v-basic-details" role="tab" aria-controls="v-basic-details" aria-selected="true" <?php echo $disabled; ?>>Basic Details</a></li>
          <li><a class="nav-link d-none" id="sales-proposal-tab-2" data-bs-toggle="pill" href="#v-unit-details" role="tab" aria-controls="v-unit-details" aria-selected="false" <?php echo $disabled; ?>>Unit Details</a></li>
          <li><a class="nav-link d-none" id="sales-proposal-tab-3" data-bs-toggle="pill" href="#v-fuel-details" role="tab" aria-controls="v-fuel-details" aria-selected="false" <?php echo $disabled; ?>>Fuel Details</a></li>
          <li><a class="nav-link d-none" id="sales-proposal-tab-4" data-bs-toggle="pill" href="#v-refinancing-details" role="tab" aria-controls="v-refinancing-details" aria-selected="false" <?php echo $disabled; ?>>Refinancing Details</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-5" data-bs-toggle="pill" href="#v-job-order" role="tab" aria-controls="v-job-order" aria-selected="false" <?php echo $disabled; ?>>Job Order</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-6" data-bs-toggle="pill" href="#v-pricing-computation" role="tab" aria-controls="v-pricing-computation" aria-selected="false" <?php echo $disabled; ?>>Pricing Computation</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-7" data-bs-toggle="pill" href="#v-other-charges" role="tab" aria-controls="v-other-charges" aria-selected="false" <?php echo $disabled; ?>>Other Charges</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-8" data-bs-toggle="pill" href="#v-renewal-amount" role="tab" aria-controls="v-renewal-amount" aria-selected="false" <?php echo $disabled; ?>>Renewal Amount</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-9" data-bs-toggle="pill" href="#v-amount-of-deposit" role="tab" aria-controls="v-amount-of-deposit" aria-selected="false" <?php echo $disabled; ?>>Amount of Deposit</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-10" data-bs-toggle="pill" href="#v-additional-job-order" role="tab" aria-controls="v-additional-job-order" aria-selected="false" <?php echo $disabled; ?>>Additional Job Order</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-11" data-bs-toggle="pill" href="#v-summary" role="tab" aria-controls="v-summary" aria-selected="false" <?php echo $disabled; ?>>Summary</a></li>
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
                if($salesProposalStatus == 'Draft'){
                  echo '  <div class="previous me-2">
                  <button class="btn btn-primary me-2 d-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-offcanvas" aria-controls="sales-proposal-job-order-offcanvas" id="add-sales-proposal-job-order">Add Job Order</button>
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
              if($salesProposalStatus != 'Draft'){
                echo '<div class="last">
                        <a href="javascript:void(0);" id="last-step" class="btn btn-secondary mt-3 mt-md-0">Finish</a>
                      </div>';
              }
            ?>
          </div>
          <div id="bar" class="progress mb-3" style="height: 7px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%"></div>
          </div>
          <div class="tab-pane show active" id="v-basic-details">
            <form id="sales-proposal-form" method="post" action="#">
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Sales Proposal Number :</label>
                <label class="col-lg-8 col-form-label" id="sales_proposal_number">--</label>
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
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-job-order-offcanvas" aria-labelledby="sales-proposal-job-order-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Job Order</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-job-order-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12">
                <label class="form-label">Job Order <span class="text-danger">*</span></label>
                <input type="hidden" id="sales_proposal_job_order_id" name="sales_proposal_job_order_id">
                <input type="text" class="form-control text-uppercase" id="job_order" name="job_order" maxlength="500" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label" for="job_order_cost">Cost <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="job_order_cost" name="job_order_cost" min="0" step="0.01">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-job-order" form="sales-proposal-job-order-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>