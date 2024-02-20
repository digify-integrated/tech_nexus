<div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body p-0">
              <ul class="nav nav-tabs checkout-tabs mb-0" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="tab" href="#details-tab" role="tab"
                    aria-controls="details-tab" aria-selected="true">
                    <div class="media align-items-center">
                      <div class="avtar avtar-s">
                        <i class="ti ti-file-text"></i>
                      </div>
                      <div class="media-body ms-2">
                        <h5 class="mb-0">Details</h5>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="sales-proposal-tab-2" data-bs-toggle="tab" href="#accessories-tab" role="tab"
                    aria-controls="accessories-tab" aria-selected="true">
                    <div class="media align-items-center">
                      <div class="avtar avtar-s">
                        <i class="ti ti-briefcase"></i>
                      </div>
                      <div class="media-body ms-2">
                        <h5 class="mb-0">Accessories</h5>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="sales-proposal-tab-3" data-bs-toggle="tab" href="#job-order-tab" role="tab"
                    aria-controls="job-order-tab" aria-selected="true">
                    <div class="media align-items-center">
                      <div class="avtar avtar-s">
                        <i class="ti ti-clipboard"></i>
                      </div>
                      <div class="media-body ms-2">
                        <h5 class="mb-0">Job Order</h5>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="sales-proposal-tab-4" data-bs-toggle="tab" href="#other-charges-tab" role="tab"
                    aria-controls="other-charges-tab" aria-selected="true">
                    <div class="media align-items-center">
                      <div class="avtar avtar-s">
                        <i class="ti ti-credit-card"></i>
                      </div>
                      <div class="media-body ms-2">
                        <h5 class="mb-0">Other Charges</h5>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="sales-proposal-tab-5" data-bs-toggle="tab" href="#additional-job-order-tab" role="tab"
                    aria-controls="additional-job-order-tab" aria-selected="true">
                    <div class="media align-items-center">
                      <div class="avtar avtar-s">
                        <i class="ti ti-file-plus"></i>
                      </div>
                      <div class="media-body ms-2">
                        <h5 class="mb-0">Additional Job Order</h5>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="sales-proposal-tab-6" data-bs-toggle="tab" href="#summary-tab" role="tab"
                    aria-controls="summary-tab" aria-selected="true">
                    <div class="media align-items-center">
                      <div class="avtar avtar-s">
                        <i class="ti ti-printer"></i>
                      </div>
                      <div class="media-body ms-2">
                        <h5 class="mb-0">Summary</h5>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="tab-content">
            <div class="tab-pane show active" id="details-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-1">
              <div class="card-body">
                <div class="row">
                  <div class="col-xl-8">
                    <div class="card">
                      <div class="card-header">
                        <div class="row align-items-center my-2">
                          <div class="col">
                            <div class="progress" style="height: 6px">
                              <div class="progress-bar bg-primary" style="width: 16.66%"></div>
                            </div>
                          </div>
                          <div class="col-auto">
                            <p class="mb-0 h6">Step 1</p>
                          </div>
                        </div>
                      </div>
                      <div class="card-body border-bottom">
                        <div class="row align-items-center">
                          <div class="col">
                            <h5 class="mb-0">Details</h5>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <form id="sales-proposal-form" method="post" action="#">
                          <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Sales Proposal Number :</label>
                            <label class="col-lg-8 col-form-label" id="sales_proposal_number">--</label>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Product : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                              <select class="form-control select2" name="product_id" id="product_id">
                                <option value="">--</option>
                                <?php echo $productModel->generateInStockProductOptions(); ?>
                              </select>
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
                            <label class="col-lg-4 col-form-label">Referred By : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                              <input type="text" class="form-control" id="referred_by" name="referred_by" maxlength="100" autocomplete="off">
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
                              <input type="text" class="form-control" id="number_of_payments" name="number_of_payments" autocomplete="off" readonly>
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
                             <input type="text" class="form-control" id="first_due_date" name="first_due_date" readonly>
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
                            <label class="col-lg-4 col-form-label">Remarks :</label>
                            <div class="col-lg-8">
                              <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="text-end btn-page mb-0 mt-4">
                      <button class="btn btn-primary">Next</button>
                    </div>
                  </div>
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body py-2">
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item px-0">
                            <h5 class="mb-0">Client Details</h5>
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
              </div>
            </div>
            <div class="tab-pane" id="accessories-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-2">
              <div class="row">
                <div class="col-xl-12">
                  <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center my-2">
                        <div class="col">
                          <div class="progress" style="height: 6px">
                            <div class="progress-bar bg-primary" style="width: 33.32%"></div>
                          </div>
                        </div>
                        <div class="col-auto">
                          <p class="mb-0 h6">Step 2</p>
                        </div>
                      </div>
                    </div>
                    <div class="card-body border-bottom">
                      <div class="row align-items-center">
                        <div class="col">
                          <h5 class="mb-0">Accessories</h5>
                        </div>
                        <div class="col-auto">
                          <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Add Accessories</button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body p-0 table-body">
                      <div class="table-responsive">
                        <table class="table mb-0" id="pc-dt-simple">
                          <thead>
                            <tr>
                              <th>Accessories</th>
                              <th class="text-end">Cost</th>
                              <th class="text-end"></th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body py-2">
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                          <div class="float-end">
                            <h5 class="mb-0">$300.00</h5>
                          </div>
                          <h5 class="mb-0 d-inline-block">Total</h5>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="text-end btn-page mb-0 mt-4">
                    <button class="btn btn-warning">Previous</button>
                    <button class="btn btn-primary">Next</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="job-order-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-3">
              <div class="row">
                <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center my-2">
                        <div class="col">
                          <div class="progress" style="height: 6px">
                            <div class="progress-bar bg-primary" style="width: 33.32%"></div>
                          </div>
                        </div>
                        <div class="col-auto">
                          <p class="mb-0 h6">Step 2</p>
                        </div>
                      </div>
                    </div>
                    <div class="card-body border-bottom">
                      <div class="row align-items-center">
                        <div class="col">
                          <h5 class="mb-0">Accessories</h5>
                        </div>
                        <div class="col-auto">
                          <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Add Accessories</button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body p-0 table-body">
                      <div class="table-responsive">
                        <table class="table mb-0" id="pc-dt-simple">
                          <thead>
                            <tr>
                              <th>Job Order</th>
                              <th class="text-end">Cost</th>
                              <th class="text-end"></th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body py-2">
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                          <div class="float-end">
                            <h5 class="mb-0">$300.00</h5>
                          </div>
                          <h5 class="mb-0 d-inline-block">Total</h5>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="text-end btn-page mb-0 mt-4">
                    <button class="btn btn-warning">Previous</button>
                    <button class="btn btn-primary">Next</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="other-charges-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-4">
              <div class="row">
                <div class="col-xl-12">
                  <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center my-2">
                        <div class="col">
                          <div class="progress" style="height: 6px">
                            <div class="progress-bar bg-primary" style="width: 66.64%"></div>
                          </div>
                        </div>
                        <div class="col-auto">
                          <p class="mb-0 h6">Step 4</p>
                        </div>
                      </div>
                    </div>
                    <div class="card-body border-bottom">
                      <h5>Other Charges</h5>
                    </div>
                    <div class="card-body">
                    </div>
                  </div>
                  <div class="text-end btn-page mb-0 mt-4">
                    <button class="btn btn-warning">Previous</button>
                    <button class="btn btn-primary">Next</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="additional-job-order-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-5">
              <div class="row">
                <div class="col-xl-12">
                  <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center my-2">
                        <div class="col">
                          <div class="progress" style="height: 6px">
                            <div class="progress-bar bg-primary" style="width: 83.30%"></div>
                          </div>
                        </div>
                        <div class="col-auto">
                          <p class="mb-0 h6">Step 5</p>
                        </div>
                      </div>
                    </div>
                    <div class="card-body border-bottom">
                      <div class="row align-items-center">
                        <div class="col">
                          <h5 class="mb-0">Additional Job Order</h5>
                        </div>
                        <div class="col-auto">
                          <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Add Additional Job Order</button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body p-0 table-body">
                      <div class="table-responsive">
                        <table class="table mb-0" id="pc-dt-simple">
                          <thead>
                            <tr>
                              <th>Job Order Number</th>
                              <th>Job Order Date</th>
                              <th>Particulars</th>
                              <th class="text-end">Cost</th>
                              <th class="text-end"></th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="text-end btn-page mb-0 mt-4">
                    <button class="btn btn-warning">Previous</button>
                    <button class="btn btn-primary">Next</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="summary-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-7">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center my-2">
                                    <div class="col">
                                        <div class="progress" style="height: 6px">
                                            <div class="progress-bar bg-primary" style="width: 99%"></div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="mb-0 h6">Step 6</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-bottom">
                                <h5>Summary</h5>
                            </div>
                            <div class="card-body">
                            </div>
                        </div>
                      <div class="text-end btn-page mb-0 mt-4">
                        <button class="btn btn-warning">Previous</button>
                        <button class="btn btn-primary">Next</button>
                      </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <!-- [ sample-page ] end -->
      </div>