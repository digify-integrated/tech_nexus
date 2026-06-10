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
        </form>
      </div>
    </div>
  </div>
</div>