<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <li><a class="nav-link active" id="leasing-application-tab-1" data-bs-toggle="pill" href="#v-basic-details" role="tab" aria-controls="v-basic-details" aria-selected="true" disabled>Rental Details</a></li>
          <li><a class="nav-link" id="leasing-application-tab-2" data-bs-toggle="pill" href="#v-other-charges" role="tab" aria-controls="v-other-charges" aria-selected="false" disabled>Other Charges</a></li>
          <li><a class="nav-link" id="leasing-application-tab-3" data-bs-toggle="pill" href="#v-collections" role="tab" aria-controls="v-collections" aria-selected="false" disabled>Payment Summary</a></li>
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
                if($applicationStatus != 'Closed'){
                  echo '<div class="previous me-2" id="leasing-repayment-rental-button">
                    <button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#leasing-application-rental-offcanvas" aria-controls="leasing-repayment-rental-offcanvas" id="leasing-repayment-other-charges">Rental</button>
                </div>

                <div class="previous me-2" id="leasing-repayment-other-charges-button">
                    <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#leasing-application-other-charges-offcanvas" aria-controls="leasing-repayment-other-charges-offcanvas" id="leasing-repayment-other-charges">Other Charges</button>
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
            <div class="last">
                <a href="javascript:void(0);" id="last-step" class="btn btn-secondary mt-3 mt-md-0">Last</a>
            </div>
          </div>
          <div id="bar" class="progress mb-3" style="height: 7px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%"></div>
          </div>
          <div class="tab-pane show active" id="v-basic-details">
            <div class="row g-3">
              <div class="col-sm-6">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Due Date</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Due Date</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="repayment-due-date"></h6>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Status</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Status</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="repayment-status"></h6>
                </div>
              </div>
            </div>
            <div class="row g-3 mt-1">
              <div class="col-sm-6">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Paid Rental</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Paid Rental</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="total-repayment-paid-rental"></h6>
                </div>
              </div>
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
                    <h6 class="mb-0" id="total-repayment-unpaid-rental"></h6>
                </div>
              </div>
            </div>
            <div class="row g-3 mt-1">
              <div class="col-sm-6">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Paid Electricty</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Paid Electricty</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="total-repayment-paid-electricity"></h6>
                </div>
              </div>
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
                    <h6 class="mb-0" id="total-repayment-unpaid-electricity"></h6>
                </div>
              </div>
            </div>
            <div class="row g-3 mt-1">
              <div class="col-sm-6">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Paid Water</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Paid Water</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="total-repayment-paid-water"></h6>
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
                    <h6 class="mb-0" id="total-repayment-unpaid-water"></h6>
                </div>
              </div>
            </div>
            <div class="row g-3 mt-1">
              <div class="col-sm-6">
                <div class="bg-body p-3 rounded">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <span class="p-1 d-block bg-danger rounded-circle">
                                <span class="visually-hidden">Paid Other Charges</span>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0">Paid Other Charges</p>
                        </div>
                    </div>
                    <h6 class="mb-0" id="total-repayment-paid-other-charges"></h6>
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
                    <h6 class="mb-0" id="total-repayment-unpaid-other-charges"></h6>
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
                    <h6 class="mb-0" id="total-repayment-outstanding-balance"></h6>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="v-other-charges">
            <div class="table-responsive mt-2">
                <table id="repayment-other-charges-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>Other Charges Type</th>
                    <th>Reference Number</th>
                    <th>Due Date</th>
                    <th>Due Amount</th>
                    <th>Paid Amount</th>
                    <th>Outstanding Balance</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="v-collections">
            <div class="table-responsive mt-2">
                <table id="repayment-collections-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>Reference</th>
                    <th>Due Date</th>
                    <th>Payment For</th>
                    <th>Reference Number</th>
                    <th>Payment Mode</th>
                    <th>Payment Date</th>
                    <th>Payment Amount</th>
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-rental-offcanvas" aria-labelledby="leasing-application-rental-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="leasing-application-rental-offcanvas-label" style="margin-bottom:-0.5rem">Rental</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-rental-form" method="post" action="#">
          <div class="form-group row">
                <div class="col-lg-12">
                    <label class="form-label">Payment Mode <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="rental_payment_mode" id="rental_payment_mode">
                        <option value="">--</option>
                        <option value="Cash">Cash</option>
                        <option value="PDC">PDC</option>
                        <option value="Online">Online</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12">
                    <label class="form-label" for="reference_number">Reference Number</label>
                    <input type="text" class="form-control" id="rental_reference_number" name="rental_reference_number" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="rental_payment_amount" name="rental_payment_amount" step="0.01" min="0">
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                    <div class="input-group date">
                        <input type="text" class="form-control regular-datepicker" id="rental_payment_date" name="rental_payment_date" autocomplete="off">
                        <span class="input-group-text">
                            <i class="feather icon-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-rental" form="leasing-application-rental-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-other-charges-payment-offcanvas" aria-labelledby="leasing-other-charges-payment-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="leasing-application-rental-offcanvas-label" style="margin-bottom:-0.5rem">Other Charges</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-other-charges-payment-form" method="post" action="#">
          <div class="form-group row">
                <div class="col-lg-12">
                    <input type="hidden" id="payment_for" name="payment_for">
                    <input type="hidden" id="payment_id" name="payment_id">
                    <label class="form-label">Payment Mode <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="other_charges_payment_mode" id="other_charges_payment_mode">
                        <option value="">--</option>
                        <option value="Cash">Cash</option>
                        <option value="PDC">PDC</option>
                        <option value="Online">Online</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12">
                    <label class="form-label" for="reference_number">Reference Number</label>
                    <input type="text" class="form-control" id="other_charges_payment_reference_number" name="other_charges_payment_reference_number" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="other_charges_payment_amount" name="other_charges_payment_amount" step="0.01" min="0">
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                    <div class="input-group date">
                        <input type="text" class="form-control regular-datepicker" id="other_charges_payment_date" name="other_charges_payment_date" autocomplete="off">
                        <span class="input-group-text">
                            <i class="feather icon-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-other-charges-payment-form" form="leasing-other-charges-payment-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-other-charges-offcanvas" aria-labelledby="leasing-application-other-charges-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="leasing-application-other-charges-offcanvas-label" style="margin-bottom:-0.5rem">Other Charges</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-other-charges-form" method="post" action="#">
            <div class="form-group row">
                <div class="col-lg-12">
                    <input type="hidden" id="leasing_other_charges_id" name="leasing_other_charges_id">
                    <label class="form-label">Other Charges Type <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="other_charges_type" id="other_charges_type">
                        <option value="">--</option>
                        <option value="Electricity">Electricity</option>
                        <option value="Water">Water</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Others">Others</option>                        
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12">
                    <label class="form-label" for="reference_number">Reference Number</label>
                    <input type="text" class="form-control" id="other_charges_reference_number" name="other_charges_reference_number" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label class="form-label">Due Amount <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="other_charges_due_amount" name="other_charges_due_amount" step="0.01" min="0">
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <label class="form-label">Due Date <span class="text-danger">*</span></label>
                    <div class="input-group date">
                        <input type="text" class="form-control regular-datepicker" id="other_charges_due_date" name="other_charges_due_date" autocomplete="off">
                        <span class="input-group-text">
                            <i class="feather icon-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <label class="form-label">Coverage Start Date</label>
                    <div class="input-group date">
                        <input type="text" class="form-control regular-datepicker" id="coverage_start_date" name="coverage_start_date" autocomplete="off">
                        <span class="input-group-text">
                            <i class="feather icon-calendar"></i>
                        </span>
                    </div>
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <label class="form-label">Coverage End Date</label>
                    <div class="input-group date">
                        <input type="text" class="form-control regular-datepicker" id="coverage_end_date" name="coverage_end_date" autocomplete="off">
                        <span class="input-group-text">
                            <i class="feather icon-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group row">
               
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-other-charges" form="leasing-application-other-charges-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
</div>