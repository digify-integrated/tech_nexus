<form id="add-sales-proposal-form" method="post" action="#">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body border-bottom">
          <div class="row align-items-center">
            <div class="col">
              <h5 class="mb-0">Customer Details</h5>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
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
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body border-bottom">
          <div class="row align-items-center">
            <div class="col">
              <h5 class="mb-0">Sales Proposal Details</h5>
            </div>
            <div class="col-auto">
              <button type="submit" form="add-sales-proposal-form" class="btn btn-success" id="submit-data">Submit</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
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
            </div>
            <div class="col-lg-6">
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Estimated Date of Release : <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="release_date" name="release_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Start Date : <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="start_date" name="start_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Term : <span class="text-danger">*</span></label>
                <div class="col-lg-2">
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
                <label class="col-lg-6 col-form-label">Number of Payments : <span class="text-danger">*</span></label>
                <div class="col-lg-2">
                  <input type="text" class="form-control text-uppercase" id="number_of_payments" name="number_of_payments" autocomplete="off" readonly>
                </div>
                <div class="col-lg-4">
                  <select class="form-control select2" name="payment_frequency" id="payment_frequency">
                    <option value="">--</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">First Due Date : <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                  <input type="text" class="form-control text-uppercase" id="first_due_date" name="first_due_date" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Initial Approving Officer : <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                  <select class="form-control select2" name="initial_approving_officer" id="initial_approving_officer">
                    <option value="">--</option>
                    <?php echo $approvingOfficerModel->generateApprovingOfficerOptions('Initial'); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Final Approving Officer : <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                  <select class="form-control select2" name="final_approving_officer" id="final_approving_officer">
                    <option value="">--</option>
                    <?php echo $approvingOfficerModel->generateApprovingOfficerOptions('Final'); ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Remarks :</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row d-none" id="unit-card">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body border-bottom">
          <div class="row align-items-center">
            <div class="col">
              <h5 class="mb-0">Unit Details</h5>
            </div>
          </div>
        </div>
        <div class="card-body">
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
              <div class="form-group row d-none">
                <label class="col-lg-4 col-form-label">For Registration? : <span class="text-danger">*</span></label>
                <div class="col-lg-8 mt-2">
                  <select class="form-control select2" name="for_registration" id="for_registration">
                    <option value="">--</option>
                    <option value="Yes" selected>Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group row d-none">
                <label class="col-lg-4 col-form-label">With CR? : <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="with_cr" id="with_cr">
                    <option value="">--</option>
                    <option value="Yes" selected>Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group row d-none">
                <label class="col-lg-4 col-form-label">For Transfer? : <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="for_transfer" id="for_transfer">
                    <option value="">--</option>
                    <option value="Yes" selected>Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group row d-none">
                <label class="col-lg-4 col-form-label">For Change Color? : <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="for_change_color" id="for_change_color">
                    <option value="">--</option>
                    <option value="Yes" selected>Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
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
              <div class="form-group row d-none">
                <label class="col-lg-5 col-form-label">For Change Body? : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <select class="form-control select2" name="for_change_body" id="for_change_body">
                    <option value="">--</option>
                    <option value="Yes" selected>Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
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
        </div>
      </div>
    </div>
  </div>
  <div class="row d-none" id="fuel-card">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body border-bottom">
          <div class="row align-items-center">
            <div class="col">
              <h5 class="mb-0">Fuel Details</h5>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Diesel Fuel Quantity : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <div class="input-group">
                    <input type="text" class="form-control currency" id="diesel_fuel_quantity" name="diesel_fuel_quantity">
                    <span class="input-group-text">lt</span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Diesel Price/Liter : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="diesel_price_per_liter" name="diesel_price_per_liter">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Sub-Total :</label>
                <label class="col-lg-7 col-form-label" id="diesel_total">--</label>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Regular Fuel Quantity : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <div class="input-group">
                    <input type="text" class="form-control currency" id="regular_fuel_quantity" name="regular_fuel_quantity">
                    <span class="input-group-text">lt</span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Regular Price/Liter : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="regular_price_per_liter" name="regular_price_per_liter">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Sub-Total :</label>
                <label class="col-lg-7 col-form-label" id="regular_total">--</label>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Premium Fuel Quantity : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <div class="input-group">
                    <input type="text" class="form-control currency" id="premium_fuel_quantity" name="premium_fuel_quantity">
                    <span class="input-group-text">lt</span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Premium Price/Liter : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="premium_price_per_liter" name="premium_price_per_liter">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Sub-Total :</label>
                <label class="col-lg-7 col-form-label" id="premium_total">--</label>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Total :</label>
                <label class="col-lg-7 col-form-label" id="fuel_total">--</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row d-none" id="refinancing-card">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body border-bottom">
          <div class="row align-items-center">
            <div class="col">
              <h5 class="mb-0" id="refinancing-title"></h5>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Stock Number :</label>
                <label class="col-lg-7 col-form-label" id="ref_stock_no">--</label>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Engine Number : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control text-uppercase" id="ref_engine_no" name="ref_engine_no" maxlength="100" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Chassis Number : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control text-uppercase" id="ref_chassis_no" name="ref_chassis_no" maxlength="100" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Plate Number : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control text-uppercase" id="ref_plate_no" name="ref_plate_no" maxlength="100" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">OR/CR Number : </label>
                <div class="col-lg-7">
                  <input type="text" class="form-control" id="orcr_no" name="orcr_no" maxlength="200" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">OR/CR Date : </label>
                <div class="col-lg-7">
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
                  <label class="col-lg-5 col-form-label">OR/CR Expiry Date : </label>
                  <div class="col-lg-7">
                    <div class="input-group date">
                      <input type="text" class="form-control regular-datepicker" id="orcr_expiry_date" name="orcr_expiry_date" autocomplete="off">
                      <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                      </span>
                    </div>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Received From : </label>
                  <div class="col-lg-7">
                    <input type="text" class="form-control" id="received_from" name="received_from" maxlength="500" autocomplete="off">
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Received From Address : </label>
                  <div class="col-lg-7">
                    <input type="text" class="form-control" id="received_from_address" name="received_from_address" maxlength="1000" autocomplete="off">
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Received From ID Type : </label>
                  <div class="col-lg-7">
                    <select class="form-control select2" name="received_from_id_type" id="received_from_id_type">
                        <option value="">--</option>
                        <?php echo $idTypeModel->generateIDTypeOptions(); ?>
                    </select>
                  </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Received From ID Number : </label>
                <div class="col-lg-7">
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
        </div>
      </div>
    </div>
  </div>
  <div class="row d-none" id="pricing-computation-card">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body border-bottom">
          <div class="row align-items-center">
            <div class="col">
              <h5 class="mb-0">Pricing Computation</h5>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Deliver Price (AS/IS) : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="delivery_price" name="delivery_price">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Add-On : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="add_on_charge" name="add_on_charge">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Nominal Discount : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="nominal_discount" name="nominal_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Total Delivery Price : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="total_delivery_price" name="total_delivery_price" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Interest Rate : <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="interest_rate" name="interest_rate">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Cost of Accessories :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="cost_of_accessories" name="cost_of_accessories">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Reconditioning Cost :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="reconditioning_cost" name="reconditioning_cost">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="subtotal" name="subtotal" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Downpayment :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="downpayment" name="downpayment">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Outstanding Balance :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="outstanding_balance" name="outstanding_balance" readonly>
                </div>
              </div>
              <div class="form-group row d-none">
                <label class="col-lg-5 col-form-label">Amount Financed :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="amount_financed" name="amount_financed" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">PN Amount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="pn_amount" name="pn_amount" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Repayment Amount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="repayment_amount" name="repayment_amount" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row d-none" id="other-charges-card">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body border-bottom">
          <div class="row align-items-center">
            <div class="col">
              <h5 class="mb-0">Other Charges</h5>
            </div>
          </div>
        </div>
        <div class="card-body">
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
                  <input type="text" class="form-control currency" id="insurance_premium" name="insurance_premium" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Insurance Premium Discount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="insurance_premium_discount" name="insurance_premium_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Insurance Premium Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="insurance_premium_subtotal" name="insurance_premium_subtotal" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Handling Fee :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="handling_fee" name="handling_fee" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Handling Fee Discount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="handling_fee_discount" name="handling_fee_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Handling Fee Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="handling_fee_subtotal" name="handling_fee_subtotal" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transfer Fee :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transfer_fee" name="transfer_fee" value="12000" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transfer Fee Discount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transfer_fee_discount" name="transfer_fee_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transfer Fee Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transfer_fee_subtotal" name="transfer_fee_subtotal" readonly>
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
                  <input type="text" class="form-control currency" id="doc_stamp_tax" name="doc_stamp_tax" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Doc. Stamp Tax Discount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="doc_stamp_tax_discount" name="doc_stamp_tax_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Doc. Stamp Tax Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="doc_stamp_tax_subtotal" name="doc_stamp_tax_subtotal" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transaction Fee :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transaction_fee" name="transaction_fee" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transaction Fee Discount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transaction_fee_discount" name="transaction_fee_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transaction Fee Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transaction_fee_subtotal" name="transaction_fee_subtotal" readonly>
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
        </div>
      </div>
    </div>
  </div>
  <div class="row d-none" id="renewal-amount-card">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body border-bottom">
          <div class="row align-items-center">
            <div class="col">
              <h5 class="mb-0">Renewal Amount</h5>
            </div>
          </div>
        </div>
        <div class="card-body">
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
        </div>
      </div>
    </div>
  </div>
</form>