<div class="row">
  <div class="col-xl-8">
    <div class="card">
      <div class="card-body border-bottom">
        <div class="row align-items-center">
          <div class="col">
            <h5 class="mb-0">Details</h5>
          </div>
          <div class="col-auto">
            <button type="submit" form="add-sales-proposal-form" class="btn btn-success" id="submit-data">Submit</button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="add-sales-proposal-form" method="post" action="#">
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
            <label class="col-lg-4 col-form-label">Renewal Tag : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <select class="form-control select2" name="renewal_tag" id="renewal_tag">
                <option value="">--</option>
                <option value="New">New</option>
                <option value="Renewal">Renewal</option>
              </select>
            </div>
          </div>
          <div class="form-group row d-none refinancing-row">
            <label class="col-lg-4 col-form-label">Engine Number : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <input type="text" class="form-control text-uppercase" id="ref_engine_no" name="ref_engine_no" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row d-none refinancing-row">
            <label class="col-lg-4 col-form-label">Chassis Number : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <input type="text" class="form-control text-uppercase" id="ref_chassis_no" name="ref_chassis_no" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row d-none refinancing-row">
            <label class="col-lg-4 col-form-label">Plate Number : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <input type="text" class="form-control text-uppercase" id="ref_plate_no" name="ref_plate_no" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row d-none unit-row">
            <label class="col-lg-4 col-form-label">Stock : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <select class="form-control select2" name="product_id" id="product_id">
                <option value="">--</option>
                <?php echo $productModel->generateInStockProductOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row d-none fuel-row">
            <label class="col-lg-4 col-form-label">Fuel Type : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <select class="form-control select2" multiple="multiple" name="fuel_type[]" id="fuel_type">
                                    <option value="Diesel">Diesel</option>
                                    <option value="Regular">Regular</option>
                                    <option value="Premium">Premium</option>
                                  </select>
            </div>
          </div>
          <div class="form-group row d-none fuel-row">
            <label class="col-lg-4 col-form-label">Fuel Quantity : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <div class="form-group">
                  <div class="input-group">
                    <input type="number" class="form-control" id="fuel_quantity" name="fuel_quantity" step="0.01" min="0.01">
                    <span class="input-group-text">lt</span>
                  </div>
                </div>
            </div>
          </div>
          <div class="form-group row d-none fuel-row">
            <label class="col-lg-4 col-form-label">Price/Liter : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <input type="number" class="form-control" id="price_per_liter" name="price_per_liter" step="0.01" min="0.01">
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
          <div class="form-group row d-none" id="financing-institution-row">
            <label class="col-lg-4 col-form-label">Financing Institution : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <input type="text" class="form-control text-uppercase" id="financing_institution" name="financing_institution" maxlength="200" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Co-Maker :</label>
            <div class="col-lg-8">
              <select class="form-control select2" name="comaker_id" id="comaker_id">
                <option value="">--</option>
                <?php echo $customerModel->generateComakerOptions($customerID); ?>
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
            <div class="col-lg-4">
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
            <label class="col-lg-4 col-form-label">Number of Payments : <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="number" class="form-control" id="number_of_payments" name="number_of_payments" step="1" value="0" min="1" readonly>
            </div>
            <div class="col-lg-4">
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
            <label class="col-lg-4 col-form-label">For Registration? : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
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
            <div class="col-lg-2">
              <select class="form-control select2" name="for_change_color" id="for_change_color">
                <option value="">--</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="col-lg-3">
              <input type="text" class="form-control text-uppercase" id="old_color" autocomplete="off" readonly>
            </div>
            <div class="col-lg-3">
              <input type="text" class="form-control text-uppercase" id="new_color" name="new_color" maxlength="100" autocomplete="off" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">For Change Body? : <span class="text-danger">*</span></label>
            <div class="col-lg-2">
              <select class="form-control select2" name="for_change_body" id="for_change_body">
                <option value="">--</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="col-lg-3">
              <input type="text" class="form-control text-uppercase" id="old_body" autocomplete="off" readonly>
            </div>
            <div class="col-lg-3">
              <input type="text" class="form-control text-uppercase" id="new_body" name="new_body" maxlength="100" autocomplete="off" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">For Change Engine? : <span class="text-danger">*</span></label>
            <div class="col-lg-2">
              <select class="form-control select2" name="for_change_engine" id="for_change_engine">
                <option value="">--</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="col-lg-3">
              <input type="text" class="form-control text-uppercase" id="old_engine" autocomplete="off" readonly>
            </div>
            <div class="col-lg-3">
              <input type="text" class="form-control text-uppercase" id="new_engine" name="new_engine" maxlength="100" autocomplete="off" readonly>
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
    </div>
  </div>
  <div class="col-xl-4">
    <div class="card">
      <div class="card-body py-2">
        <ul class="list-group list-group-flush">
          <li class="list-group-item px-0">
            <h5 class="mb-0">Customer Details</h5>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Customer ID</p>
              </div>
              <div class="col-sm-6" id="customer-id">
                <?php echo $customerID; ?>
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Full Name</p>
              </div>
              <div class="col-sm-6">
                <?php echo $customerName; ?>
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Address</p>
              </div>
              <div class="col-sm-6">
                <?php echo $customerAddress; ?>
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-6">
                <?php echo $customerEmail; ?>
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Mobile</p>
              </div>
              <div class="col-sm-6">
                <?php echo $customerMobile; ?>
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Telephone</p>
              </div>
              <div class="col-sm-6">
                <?php echo $customerTelephone; ?>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="card">
      <div class="card-body py-2">
        <ul class="list-group list-group-flush">
          <li class="list-group-item px-0">
            <h5 class="mb-0">Co-Maker Details</h5>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Full Name</p>
              </div>
              <div class="col-sm-6" id="comaker_file_as">
                --
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Address</p>
              </div>
              <div class="col-sm-6" id="comaker_address">
                --
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-6" id="comaker_email">
                --
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Mobile</p>
              </div>
              <div class="col-sm-6" id="comaker_mobile">
                --
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Telephone</p>
              </div>
              <div class="col-sm-6" id="comaker_telephone">
                --
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="card">
      <div class="card-body py-2">
        <ul class="list-group list-group-flush">
          <li class="list-group-item px-0">
            <h5 class="mb-0">Product Details </h5>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Engine Number</p>
              </div>
              <div class="col-sm-6" id="product_engine_number">
                --
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Chassis Number</p>
              </div>
              <div class="col-sm-6" id="product_chassis_number">
                --
              </div>
            </div>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-6 mb-sm-0">
                <p class="mb-0">Plate Number</p>
              </div>
              <div class="col-sm-6" id="product_plate_number">
                --
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>