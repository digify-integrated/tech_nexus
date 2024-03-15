<?php
  $tabDisabled = '';
  if($salesProposalSatus == 'Draft'){
    $tabDisabled = 'disabled';
  }
?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body p-0">
        <ul class="nav nav-tabs checkout-tabs mb-0" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="sales-proposal-tab-2" data-bs-toggle="tab" href="#job-order-tab" role="tab" aria-controls="job-order-tab" aria-selected="true" <?php echo $tabDisabled; ?>>
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
            <a class="nav-link" id="sales-proposal-tab-4" data-bs-toggle="tab" href="#additional-job-order-tab" role="tab" aria-controls="additional-job-order-tab" aria-selected="true" <?php echo $tabDisabled; ?>>
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
        </ul>
      </div>
    </div>
    <div class="tab-content">
      <div class="tab-pane show active" id="job-order-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-2">
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center my-2">
                  <div class="col">
                    <div class="progress" style="height: 6px">
                      <div class="progress-bar bg-primary" style="width: 50%"></div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <p class="mb-0 h6">Step 1</p>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-primary" id="next-step-1-modified">Next</button>
                  </div>
                </div>
              </div>
              <div class="card-body border-bottom">
                <div class="row align-items-center">
                  <div class="col">
                    <h5 class="mb-0">Job Order</h5>
                  </div>
                  <div class="col-auto">
                    <?php
                      if($salesProposalSatus == 'Draft'){
                        echo '<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-offcanvas" aria-controls="sales-proposal-job-order-offcanvas" id="add-sales-proposal-job-order">Add Job Order</button>';
                      }
                    ?>
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
                    <tbody></tbody>
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
          </div>
        </div>
      </div>
      <div class="tab-pane" id="additional-job-order-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-4">
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
                    <p class="mb-0 h6">Step 2</p>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-warning" id="prev-step-2-modified">Previous</button>
                  </div>
                </div>
              </div>
              <div class="card-body border-bottom">
                <div class="row align-items-center">
                  <div class="col">
                    <h5 class="mb-0">Additional Job Order</h5>
                  </div>
                  <div class="col-auto">
                    <?php
                      if($salesProposalSatus != 'Rejected' && $salesProposalSatus != 'Cancelled'){
                        echo '<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-offcanvas" aria-controls="sales-proposal-additional-job-order-offcanvas" id="add-sales-proposal-additional-job-order">Add Additional Job Order</button>';
                      }
                    ?>
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
                    <tbody></tbody>
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
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
    echo '<div class="col-lg-12">
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
                  '. $userModel->generateLogNotes('sales_proposal', $salesProposalID) .'
                </div>
              </div>
            </div>
          </div>';
  ?>
</div>

<?php
    echo '
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
                                  <input type="text" class="form-control text-uppercase" id="job_order" name="job_order" maxlength="500" autocomplete="off">
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
                                  <input type="text" class="form-control text-uppercase" id="job_order_number" name="job_order_number" maxlength="500" autocomplete="off">
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
                                  <input type="text" class="form-control text-uppercase" id="particulars" name="particulars" maxlength="1000" autocomplete="off">
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
        </div>';
?>