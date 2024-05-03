<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <li><a class="nav-link active" id="leasing-application-tab-1" data-bs-toggle="pill" href="#v-basic-details" role="tab" aria-controls="v-basic-details" aria-selected="true" disabled>Leasing Details</a></li>
          <li><a class="nav-link" id="leasing-application-tab-2" data-bs-toggle="pill" href="#v-contract" role="tab" aria-controls="v-contract" aria-selected="false" disabled>Contract</a></li>
          <li><a class="nav-link" id="leasing-application-tab-3" data-bs-toggle="pill" href="#v-remarks" role="tab" aria-controls="v-remarks" aria-selected="false" disabled>Remarks</a></li>
          <li><a class="nav-link" id="leasing-application-tab-4" data-bs-toggle="pill" href="#v-summary" role="tab" aria-controls="v-summary" aria-selected="false" disabled>Rental Summary</a></li>
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
              <div class="previous me-2">
                <a href="javascript:void(0);" id="previous-step" class="btn btn-secondary disabled">Back To Previous</a>
              </div>
              <div class="next">
                <a href="javascript:void(0);" id="next-step" class="btn btn-secondary mt-3 mt-md-0">Next Step</a>
              </div>
            </div>
            <div class="last">
                <a href="javascript:void(0);" id="last-step" class="btn btn-secondary mt-3 mt-md-0">Last</a>
            </div>
          </div>
          <div id="bar" class="progress mb-3" style="height: 7px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%"></div>
          </div>
          <div class="tab-pane show active" id="v-basic-details">
            <form id="leasing-application-form" method="post" action="#">
              <input type="hidden" id="leasing_application_status" value="<?php echo $applicationStatus; ?>">
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Leasing Application Number :</label>
                <label class="col-lg-8 col-form-label" id="leasing_application_number">--</label>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Leasing Application Status :</label>
                <div class="col-lg-8 mt-3">
                  <?php echo $applicationStatusBadge; ?>
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
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Tenant : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <select class="form-control select2" name="tenant_id" id="tenant_id">
                        <option value="">--</option>
                        <?php echo $tenantModel->generateTenantOptions(); ?>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Property : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <select class="form-control select2" name="property_id" id="property_id">
                        <option value="">--</option>
                        <?php echo $propertyModel->generatePropertyOptions(); ?>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Contract Effectivity Date : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <div class="input-group date">
                        <input type="text" class="form-control regular-datepicker" id="contract_date" name="contract_date" autocomplete="off">
                        <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                        </span>
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">First Rental Due Date : <span class="text-danger">*</span></label>
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
                    <label class="col-lg-4 col-form-label">Maturity Date : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <input type="text" class="form-control text-uppercase" id="maturity_date" name="maturity_date" readonly>
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
                        <option value="Months">Months</option>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Payment Frequency : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <select class="form-control select2" name="payment_frequency" id="payment_frequency">
                        <option value="">--</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Initial Basic Rental : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <input type="number" class="form-control" id="initial_basic_rental" name="initial_basic_rental" step="0.01" value="0" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Escalation Rate : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <input type="number" class="form-control" id="escalation_rate" name="escalation_rate" step="0.01" value="0" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Security Deposit : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <input type="number" class="form-control" id="security_deposit" name="security_deposit" step="0.01" value="0" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Floor Area : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <input type="number" class="form-control" id="floor_area" name="floor_area" step="0.01" value="0" min="0">
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
          <div class="tab-pane" id="v-contract">
            <div class="row">
              <div class="col-xl-12">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Contract </h5>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Contract Image" id="contract-image" class="img-fluid rounded">
                          </div>                      
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="v-remarks">
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Approval Remarks :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="initial_approval_remarks_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Activation Remarks :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="activation_remarks_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Set To Draft Reason :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="set_to_draft_reason_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Rejection Reason :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="rejection_reason_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Cancellation Reason :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="cancellation_reason_label"></label>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="v-summary">
            <div class="row g-3">
              <div class="col-sm-6">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Unpaid Rental</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Unpaid Rental</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="total-unpaid-rental"></h6>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Unpaid Water</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Unpaid Water</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="total-unpaid-water"></h6>
                </div>
              </div>
            </div>
            <div class="row g-3 mt-1">
              <div class="col-sm-6">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Unpaid Electricty</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Unpaid Electricty</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="total-unpaid-electricity"></h6>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Unpaid Other Charges</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Unpaid Other Charges</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="total-unpaid-other-charges"></h6>
                </div>
              </div>
            </div>
            <div class="row g-3 mt-1">
              <div class="col-sm-12">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Outstanding Balance</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Outstanding Balance</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="total-outstanding-balance"></h6>
                </div>
              </div>
            </div>
            <div class="table-responsive mt-2">
                <table id="leasing-application-repayment-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>Reference</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Paid Rental</th>
                    <th>Unpaid Rental</th>
                    <th>Paid Electricity</th>
                    <th>Unpaid Electricity</th>
                    <th>Paid Water</th>
                    <th>Unpaid Water</th>
                    <th>Paid Other Charges</th>
                    <th>Unpaid Other Charges</th>
                    <th>Outstanding Balance</th>
                    <th>Action</th>
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

<?php
    echo '<div class="row"><div class="col-lg-12">
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
                  '. $userModel->generateLogNotes('leasing_application', $leasingApplicationID) .'
                </div>
              </div>
            </div>
          </div>
          </div>';
  ?>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-contract-offcanvas" aria-labelledby="leasing-application-contact-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-contact-offcanvas-label" style="margin-bottom:-0.5rem">Contract</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-contact-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Contract Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="contract_image" name="contract_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-contact" form="leasing-application-contact-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-cancel-offcanvas" aria-labelledby="leasing-application-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="leasing-application-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Leasing Application</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-cancel-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-cancel" form="leasing-application-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-reject-offcanvas" aria-labelledby="leasing-application-reject-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-reject-offcanvas-label" style="margin-bottom:-0.5rem">Reject Leasing Application</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-reject-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="rejection_reason" name="rejection_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-reject" form="leasing-application-reject-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-set-to-draft-offcanvas" aria-labelledby="leasing-application-set-to-draft-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-set-to-draft-offcanvas-label" style="margin-bottom:-0.5rem">Set Leasing Application To Draft</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-set-to-draft-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Set To Draft Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="set_to_draft_reason" name="set_to_draft_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-set-to-draft" form="leasing-application-set-to-draft-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-approval-offcanvas" aria-labelledby="leasing-application-approval-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-approval-offcanvas-label" style="margin-bottom:-0.5rem">Approve Leasing Application</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-approval-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Approval Remarks <span class="text-danger">*</span></label>
                <textarea class="form-control" id="approval_remarks" name="approval_remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-approve" form="leasing-application-approval-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-activation-offcanvas" aria-labelledby="leasing-application-activation-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-activation-offcanvas-label" style="margin-bottom:-0.5rem">Activate Leasing Application</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-activation-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Activation Remarks <span class="text-danger">*</span></label>
                <textarea class="form-control" id="activation_remarks" name="activation_remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-activate" form="leasing-application-activation-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>