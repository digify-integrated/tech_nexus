<div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body p-0">
              <ul class="nav nav-tabs checkout-tabs mb-0" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="tab" href="#details-tab" role="tab"
                    aria-controls="details-tab" aria-selected="true" >
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
                    aria-controls="accessories-tab" aria-selected="true" >
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
                    aria-controls="job-order-tab" aria-selected="true" >
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
                    aria-controls="other-charges-tab" aria-selected="true" >
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
                    aria-controls="additional-job-order-tab" aria-selected="true" >
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
                    aria-controls="summary-tab" aria-selected="true" >
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
                            <div class="col-auto">
                                <button type="submit" form="sales-proposal-form" class="btn btn-success" id="submit-data">Submit</button>
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
                            <label class="col-lg-4 col-form-label">Sales Proposal Status : <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <?php echo $salesProposalSatusBadge; ?>
                            </div>
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
                              <input type="text" class="form-control" id="number_of_payments" name="number_of_payments" autocomplete="off" readonly>
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
                      <button type="submit" form="sales-proposal-form" class="btn btn-primary" id="next-step-1">Next</button>
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
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-accessories-offcanvas" aria-controls="sales-proposal-accessories-offcanvas" id="add-sales-proposal-accessories">Add Accessories</button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body p-0 table-body mb-4">
                      <div class="table-responsive">
                        <table id="sales-proposal-accessories-table" class="table table-hover nowrap w-100">
                          <thead>
                            <tr>
                              <th>Accessories</th>
                              <th>Cost</th>
                              <th>Actions</th>
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
                            <h5 class="mb-0" id="sales-proposal-accessories-total">--</h5>
                          </div>
                          <h5 class="mb-0 d-inline-block">Total</h5>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="text-end btn-page mb-0 mt-4">
                    <button class="btn btn-warning" id="prev-step-2">Previous</button>
                    <button class="btn btn-primary" id="next-step-2">Next</button>
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
                          <p class="mb-0 h6">Step 3</p>
                        </div>
                      </div>
                    </div>
                    <div class="card-body border-bottom">
                      <div class="row align-items-center">
                        <div class="col">
                          <h5 class="mb-0">Job Order</h5>
                        </div>
                        <div class="col-auto">
                          <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-offcanvas" aria-controls="sales-proposal-job-order-offcanvas" id="add-sales-proposal-job-order">Add Job Order</button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body p-0 table-body mb-4">
                      <div class="table-responsive">
                        <table id="sales-proposal-job-order-table" class="table table-hover nowrap w-100">
                          <thead>
                            <tr>
                              <th>Job Order</th>
                              <th>Cost</th>
                              <th>Actions</th>
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
                            <h5 class="mb-0" id="sales-proposal-job-order-total">--</h5>
                          </div>
                          <h5 class="mb-0 d-inline-block">Total</h5>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="text-end btn-page mb-0 mt-4">
                    <button class="btn btn-warning" id="prev-step-3">Previous</button>
                    <button class="btn btn-primary" id="next-step-3">Next</button>
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
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-6">
                  <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center">
                        <div class="col">
                          <h5 class="mb-0">Pricing Computation</h5>
                        </div>
                        <div class="col-auto">
                          <button type="submit" form="sales-proposal-pricing-computation-form" class="btn btn-success" id="submit-pricing-computation-data">Submit</button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <form id="sales-proposal-pricing-computation-form" method="post" action="#">
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Deliver Price (AS/IS) : <span class="text-danger">*</span></label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="delivery_price" name="delivery_price" step="0.01" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Cost of Accessories :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="cost_of_accessories" name="cost_of_accessories" step="0.01" value="0" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Reconditioning Cost :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="reconditioning_cost" name="reconditioning_cost" step="0.01" value="0" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Sub-Total :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="subtotal" name="subtotal" step="0.01" value="0" min="0" readonly>
                          </div>
                        </div><div class="form-group row">
                          <label class="col-lg-4 col-form-label">Downpayment :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="downpayment" name="downpayment" step="0.01" value="0" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Outstanding Balance :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="outstanding_balance" name="outstanding_balance" step="0.01" value="0" min="0" readonly>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>                  
                </div>
                <div class="col-xl-6">
                  <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center">
                        <div class="col">
                          <h5 class="mb-0">Other Charges</h5>
                        </div>
                        <div class="col-auto">
                          <button type="submit" form="sales-proposal-other-charges-form" class="btn btn-success" id="submit-other-charges-data">Submit</button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <form id="sales-proposal-other-charges-form" method="post" action="#">
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Insurance Coverage :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="insurance_coverage" name="insurance_coverage" step="0.01" value="0" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Insurance Premium :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="insurance_premium" name="insurance_premium" step="0.01" value="0" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Handling Fee :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="handling_fee" name="handling_fee" step="0.01" value="0" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Transfer Fee :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="transfer_fee" name="transfer_fee" step="0.01" value="0" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Registration Fee :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="registration_fee" name="registration_fee" step="0.01" value="0" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Doc. Stamp Tax :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="doc_stamp_tax" name="doc_stamp_tax" step="0.01" value="0" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Transaction Fee :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="transaction_fee" name="transaction_fee" step="0.01" value="0" min="0">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-lg-4 col-form-label">Total :</label>
                          <div class="col-lg-8">
                            <input type="number" class="form-control" id="total_other_charges" name="total_other_charges" step="0.01" value="0" min="0" readonly>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>                  
                </div>
              </div>
              <div class="row">
                <div class="col-xl-6">
                  <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center">
                        <div class="col">
                          <h5 class="mb-0">Renewal Amount</h5>
                        </div>
                        <div class="col-auto">
                          <button type="submit" form="sales-proposal-renewal-amount-form" class="btn btn-success" id="submit-renewal-amount-data">Submit</button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
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
                              <td>Registration</td>
                              <td><input type="number" class="form-control" id="registration_second_year" name="registration_second_year" step="0.01" value="0" min="0"></td>
                              <td><input type="number" class="form-control" id="registration_third_year" name="registration_third_year" step="0.01" value="0" min="0"></td>
                              <td><input type="number" class="form-control" id="registration_fourth_year" name="registration_fourth_year" step="0.01" value="0" min="0"></td>
                            </tr>
                            <tr>
                              <td>Ins. Coverage</td>
                              <td><input type="number" class="form-control" id="insurance_coverage_second_year" name="insurance_coverage_second_year" step="0.01" value="0" min="0"></td>
                              <td><input type="number" class="form-control" id="insurance_coverage_third_year" name="insurance_coverage_third_year" step="0.01" value="0" min="0"></td>
                              <td><input type="number" class="form-control" id="insurance_coverage_fourth_year" name="insurance_coverage_fourth_year" step="0.01" value="0" min="0"></td>
                            </tr>
                            <tr>
                              <td>Ins. Premium</td>
                              <td><input type="number" class="form-control" id="insurance_premium_second_year" name="insurance_premium_second_year" step="0.01" value="0" min="0"></td>
                              <td><input type="number" class="form-control" id="insurance_premium_third_year" name="insurance_premium_third_year" step="0.01" value="0" min="0"></td>
                              <td><input type="number" class="form-control" id="insurance_premium_fourth_year" name="insurance_premium_fourth_year" step="0.01" value="0" min="0"></td>
                            </tr>
                          </tbody>
                        </table>
                      </form>
                    </div>
                  </div>                  
                </div>
                <div class="col-xl-6">
                  <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center">
                        <div class="col">
                          <h5 class="mb-0">Amount of Deposit</h5>
                        </div>
                        <div class="col-auto">
                          <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-deposit-amount-offcanvas" aria-controls="sales-proposal-deposit-amount-offcanvas" id="add-sales-proposal-deposit-amount">Add Amount of Deposit</button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body p-0 table-body mb-4">
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
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>                  
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12">
                  <div class="text-end btn-page mb-0 mt-4">
                    <button class="btn btn-warning" id="prev-step-4">Previous</button>
                    <button class="btn btn-primary" id="next-step-4">Next</button>
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
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-offcanvas" aria-controls="sales-proposal-additional-job-order-offcanvas" id="add-sales-proposal-additional-job-order">Add Additional Job Order</button>
                        </div>
                      </div>
                    </div>
                    <div class="card-body p-0 table-body mb-4">
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
                            <h5 class="mb-0" id="sales-proposal-additional-job-order-total">--</h5>
                          </div>
                          <h5 class="mb-0 d-inline-block">Total</h5>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="text-end btn-page mb-0 mt-4">
                    <button class="btn btn-warning" id="prev-step-5">Previous</button>
                    <button class="btn btn-primary" id="next-step-5">Next</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="summary-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-6">
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
                            <button class="btn btn-warning" id="prev-step-6">Previous</button>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>

<?php
    echo '<div><div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-accessories-offcanvas" aria-labelledby="sales-proposal-accessories-offcanvas-label">
            <div class="offcanvas-header">
            <h2 id="sales-proposal-accessories-offcanvas-label" style="margin-bottom:-0.5rem">Accessories</h2>
             <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="row">
                    <div class="col-lg-12">
                    <form id="sales-proposal-accessories-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="form-label">Accessories <span class="text-danger">*</span></label>
                                <input type="hidden" id="sales_proposal_accessories_id" name="sales_proposal_accessories_id">
                                <input type="text" class="form-control" id="accessories" name="accessories" maxlength="500" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="accessories_cost">Cost <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="accessories_cost" name="accessories_cost" min="0" step="0.01">
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary" id="submit-sales-proposal-accessories" form="sales-proposal-accessories-form">Submit</button>
                        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                    </di>
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
                                <input type="text" class="form-control" id="job_order" name="job_order" maxlength="500" autocomplete="off">
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
                    </di>
                </div>
            </div>
        </div>
        </div>
        <div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-additional-job-order-offcanvas" aria-labelledby="sales-proposal-additional-job-order-offcanvas-label">
            <div class="offcanvas-header">
                <h2 id="sales-proposal-additional-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Additional Job Order</h2>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="row">
                    <div class="col-lg-12">
                    <form id="sales-proposal-additional-job-order-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="form-label">Job Order Number <span class="text-danger">*</span></label>
                                <input type="hidden" id="sales_proposal_additional_job_order_id" name="sales_proposal_additional_job_order_id">
                                <input type="text" class="form-control" id="job_order_number" name="job_order_number" maxlength="500" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label">Job Order Date <span class="text-danger">*</span></label>
                                <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="job_order_date" name="job_order_date" autocomplete="off">
                                    <span class="input-group-text">
                                        <i class="feather icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label">Particulars <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="particulars" name="particulars" maxlength="1000" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="additional_job_order_cost">Cost <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="additional_job_order_cost" name="additional_job_order_cost" min="0" step="0.01">
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary" id="submit-sales-proposal-additional-job-order" form="sales-proposal-additional-job-order-form">Submit</button>
                        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                    </di>
                </div>
            </div>
        </div>
        </div>
        <div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-deposit-amount-offcanvas" aria-labelledby="sales-proposal-deposit-amount-offcanvas-label">
            <div class="offcanvas-header">
                <h2 id="sales-proposal-deposit-amount-offcanvas-label" style="margin-bottom:-0.5rem">Amount of Deposit</h2>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="row">
                    <div class="col-lg-12">
                    <form id="sales-proposal-deposit-amount-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label">Deposit Date <span class="text-danger">*</span></label>
                                <input type="hidden" id="sales_proposal_deposit_amount_id" name="sales_proposal_deposit_amount_id">
                                <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="deposit_date" name="deposit_date" autocomplete="off">
                                    <span class="input-group-text">
                                        <i class="feather icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label class="form-label">Reference Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="100" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="deposit_amount">Deposit Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="deposit_amount" name="deposit_amount" min="0" step="0.01">
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary" id="submit-sales-proposal-deposit-amount" form="sales-proposal-deposit-amount-form">Submit</button>
                        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                    </di>
                </div>
            </div>
        </div>
        </div>';
?>