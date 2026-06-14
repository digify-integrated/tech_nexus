<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Insurance Request</h5>
          </div>
          <?php
            if ($insuranceRequestCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="insurance-request-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="insurance-request-form" method="post" action="#">
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row mb-3">
                <label class="col-lg-4 col-form-label">Request Type <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-select select2" id="request_type" name="request_type" required>
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
                  <select class="form-control select2" name="insurance_policy_id" id="insurance_policy_id">
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
                    <input type="text" class="form-control regular-datepicker" id="inception_date" name="inception_date" autocomplete="off">
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
                  <select class="form-control select2" name="insurance_provider_id" id="insurance_provider_id">
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
                  <select class="form-control select2" name="insurance_type_id" id="insurance_type_id">
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
                  <select class="form-control select2" name="customer_type" id="customer_type">
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
                  <select class="form-control select2" name="misc_id" id="misc_id">
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
                  <select class="form-control select2" name="customer_id" id="customer_id">
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
                  <select class="form-control select2" name="sales_proposal_id" id="sales_proposal_id">
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