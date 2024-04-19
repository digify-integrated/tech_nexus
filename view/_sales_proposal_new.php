<div class="row">
  <div class="col-xl-12">
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
    </div>
  </div>
</div>