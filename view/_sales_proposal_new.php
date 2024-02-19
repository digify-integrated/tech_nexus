<div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body p-0">
              <ul class="nav nav-tabs checkout-tabs mb-0" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="tab" href="#comaker-tab" role="tab"
                    aria-controls="comaker-tab" aria-selected="true">
                    <div class="media align-items-center">
                      <div class="avtar avtar-s">
                        <i class="ti ti-users"></i>
                      </div>
                      <div class="media-body ms-2">
                        <h5 class="mb-0">Co-Maker</h5>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="sales-proposal-tab-2" data-bs-toggle="tab" href="#details-tab" role="tab"
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
                  <a class="nav-link" id="sales-proposal-tab-3" data-bs-toggle="tab" href="#accessories-tab" role="tab"
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
                  <a class="nav-link" id="sales-proposal-tab-4" data-bs-toggle="tab" href="#job-order-tab" role="tab"
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
                  <a class="nav-link" id="sales-proposal-tab-5" data-bs-toggle="tab" href="#other-charges-tab" role="tab"
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
                  <a class="nav-link" id="sales-proposal-tab-6" data-bs-toggle="tab" href="#additional-job-order-tab" role="tab"
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
                  <a class="nav-link" id="sales-proposal-tab-7" data-bs-toggle="tab" href="#summary-tab" role="tab"
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
            <div class="tab-pane show active" id="comaker-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-1">
              <div class="row">
                <div class="col-xl-12">
                  <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center my-2">
                        <div class="col">
                          <div class="progress" style="height: 6px">
                            <div class="progress-bar bg-primary" style="width: 14.28%"></div>
                          </div>
                        </div>
                        <div class="col-auto">
                          <p class="mb-0 h6">Step 1</p>
                        </div>
                      </div>
                    </div>
                    <div class="card-body border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5>Co-Maker</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                      <div class="comaker-check-block">
                        <div class="address-check border rounded p-3">
                          <div class="form-check">
                            <input type="radio" name="comaker_options" class="form-check-input input-primary"
                              id="comaker-check-none" value="" checked="">
                            <label class="form-check-label d-block" for="comaker-check-none">
                              <span class="h6 mb-0 d-block">None</span>
                            </label>
                          </div>
                        </div>
                        <?php echo $customerModel->generateComakerRadioOptions($customerID) ?>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary">Next</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="details-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-2">
              <div class="card-body">
                <div class="row">
                  <div class="col-xl-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="row align-items-center my-2">
                          <div class="col">
                            <div class="progress" style="height: 6px">
                              <div class="progress-bar bg-primary" style="width: 28.56%"></div>
                            </div>
                          </div>
                          <div class="col-auto">
                            <p class="mb-0 h6">Step 2</p>
                          </div>
                        </div>
                      </div>
                      <div class="card-body border-bottom">
                        <h5>Details</h5>
                      </div>
                      <div class="card-body">
                        
                      </div>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                      <button class="btn btn-warning">Previous</button>
                      <button class="btn btn-primary">Next</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="accessories-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-3">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center my-2">
                                    <div class="col">
                                        <div class="progress" style="height: 6px">
                                            <div class="progress-bar bg-primary" style="width: 42.84%"></div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="mb-0 h6">Step 3</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-bottom">
                                <h5>Accessories</h5>
                            </div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="job-order-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-4">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center my-2">
                                    <div class="col">
                                        <div class="progress" style="height: 6px">
                                            <div class="progress-bar bg-primary" style="width: 57.12%"></div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="mb-0 h6">Step 4</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-bottom">
                                <h5>Job Order</h5>
                            </div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="other-charges-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-5">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center my-2">
                                    <div class="col">
                                        <div class="progress" style="height: 6px">
                                            <div class="progress-bar bg-primary" style="width: 71.4%"></div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="mb-0 h6">Step 5</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-bottom">
                                <h5>Other Charges</h5>
                            </div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="additional-job-order-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-6">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center my-2">
                                    <div class="col">
                                        <div class="progress" style="height: 6px">
                                            <div class="progress-bar bg-primary" style="width: 85.68%"></div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <p class="mb-0 h6">Step 6</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-bottom">
                                <h5>Additional Job Order</h5>
                            </div>
                            <div class="card-body">
                            </div>
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
                                        <p class="mb-0 h6">Step 7</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-bottom">
                                <h5>Summary</h5>
                            </div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <!-- [ sample-page ] end -->
      </div>