<?php
  $tabDisabled = '';
  if($salesProposalStatus == 'Draft'){
    $tabDisabled = 'disabled';
  }
?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body p-0">
        <ul class="nav nav-tabs checkout-tabs mb-0" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="tab" href="#job-order-tab" role="tab" aria-controls="job-order-tab" aria-selected="true">
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
            <a class="nav-link" id="sales-proposal-tab-2" data-bs-toggle="tab" href="#additional-job-order-tab" role="tab" aria-controls="additional-job-order-tab" aria-selected="true">
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
            <a class="nav-link" id="sales-proposal-tab-3" data-bs-toggle="tab" href="#images-tab" role="tab" aria-controls="images-tab" aria-selected="true">
              <div class="media align-items-center">
                <div class="avtar avtar-s">
                  <i class="ti ti-photo"></i>
                </div>
                <div class="media-body ms-2">
                  <h5 class="mb-0">Images</h5>
                </div>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="tab-content">
      <div class="tab-pane show active" id="job-order-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-1">
        <div class="row">
          <div class="col-xl-4">
            <div class="card">
              <div class="card-body py-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item px-0">
                    <h5 class="mb-0">Sales Proposal Details</h5>
                  </li>
                  <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Customer Name</p>
                        </div>
                        <div class="col-sm-6">
                          <?php echo $customerName; ?>
                        </div>
                      </div>
                    </li>
                  <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Corporate Name</p>
                        </div>
                        <div class="col-sm-6">
                          <?php echo $corporateName; ?>
                        </div>
                      </div>
                    </li>
                  <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Customer Address</p>
                        </div>
                        <div class="col-sm-6">
                          <?php echo $customerAddress; ?>
                        </div>
                      </div>
                    </li>
                  <li class="list-group-item px-0">
                    <div class="row align-items-center mb-3">
                      <div class="col-sm-6 mb-sm-0">
                        <p class="mb-0">Created By</p>
                      </div>
                      <div class="col-sm-6" id="created-by"></div>
                    </div>
                  </li>
                  <li class="list-group-item px-0">
                    <div class="row align-items-center mb-3">
                      <div class="col-sm-6 mb-sm-0">
                        <p class="mb-0">Proceed Date</p>
                      </div>
                      <div class="col-sm-6" id="final-approval-date"></div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-xl-8">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center my-2">
                  <div class="col">
                    <div class="progress" style="height: 6px">
                      <div class="progress-bar bg-primary" style="width: 33.33%"></div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <p class="mb-0 h6">Job Order</p>
                  </div>
                  <div class="col-auto">
                    <?php
                      if($salesProposalStatus == 'Proceed' && $tagSalesProposalForOnProcess['total'] > 0){
                        echo '<button class="btn btn-info" id="on-process-sales-proposal">On-Process</button>';
                      }

                      if($salesProposalStatus == 'On-Process'){
                        echo '<button class="btn btn-success m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-quality-control-offcanvas" aria-controls="sales-proposal-quality-control-offcanvas" id="sales-proposal-quality-control">Quality Control Form</button>';

                        if($tagSalesProposalReadyForRelease['total'] > 0 && !empty($qualityControlForm)){
                          if(($additionalJobOrderCount['total'] > 0 && !empty($additionalJobOrderConfirmation)) || $additionalJobOrderCount['total'] == 0){
                            echo '<button class="btn btn-info m-l-5" id="ready-for-release-sales-proposal">Ready For Release</button>';
                           
                          }
                        }
                      }

                      if($salesProposalStatus == 'Ready For Release'){
                        echo '<button class="btn btn-success m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-outgoing-checklist-offcanvas" aria-controls="sales-proposal-outgoing-checklist-offcanvas" id="sales-proposal-outgoing-checklist">Outgoing Checklist</button>';

                        if(($productType == 'Unit' || $productType == 'Repair')){
                          echo ' <button class="btn btn-info m-l-5 " type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-unit-image-offcanvas" aria-controls="sales-proposal-unit-image-offcanvas" id="sales-proposal-unit-image">Unit Image</button>';
                        }
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
            
          </div>
        </div>
      </div>
      <div class="tab-pane" id="additional-job-order-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-2">
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="row align-items-center">
                  <div class="col">
                    <h5 class="mb-0">Additional Job Order</h5>
                  </div>
                  <div class="col-auto">
                    <?php
                      if($salesProposalStatus != 'Rejected' && $salesProposalStatus != 'Cancelled' && $salesProposalStatus != 'For DR'){
                        echo '<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-offcanvas" aria-controls="sales-proposal-additional-job-order-offcanvas" id="add-sales-proposal-additional-job-order">Add Additional Job Order</button>';
                      }

                      if($salesProposalStatus != 'For DR' && $additionalJobOrderCount['total'] > 0){
                        echo '<button class="btn btn-warning m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-ob-order-confirmation-offcanvas" aria-controls="sales-proposal-job-order-confirmation-offcanvas" id="sales-proposal-ob-order-confirmation">Additional Job Order Confirmation</button>';
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
            
          </div>
        </div>
      </div>
      <div class="tab-pane" id="images-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-3">
        <div class="row">
          <div class="col-xl-6">
            <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0">
                        <h5 class="mb-0">Quality Control Form </h5>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Quality Control Form Image" id="quality-control-form-image" class="img-fluid rounded">
                          </div>                      
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-xl-6">
              <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0">
                        <h5 class="mb-0">Outgoing Checklist </h5>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Outgoing Checklist Image" id="outgoing-checklist-image" class="img-fluid rounded">
                          </div>                      
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
                </div>
                <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0">
                        <h5 class="mb-0">Unit Image </h5>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Image" id="unit-image" class="img-fluid rounded">
                          </div>                      
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
                </div>
                <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0">
                        <h5 class="mb-0">Additional Job Order Confirmation</h5>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Image" id="additional-job-order-confirmation-image" class="img-fluid rounded">
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
        </div>
        <div>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-quality-control-offcanvas" aria-labelledby="sales-proposal-quality-control-offcanvas-label">
              <div class="offcanvas-header">
                  <h2 id="sales-proposal-quality-control-offcanvas-label" style="margin-bottom:-0.5rem">Quality Control Form</h2>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                  <div class="row">
                      <div class="col-lg-12">
                      <form id="sales-proposal-quality-control-form" method="post" action="#">
                          <div class="form-group row">
                              <div class="col-lg-12 mt-3 mt-lg-0">
                                  <label class="form-label">Quality Control Image <span class="text-danger">*</span></label>
                                  <input type="file" class="form-control" id="quality_control_image" name="quality_control_image">
                              </div>
                          </div>
                      </form>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-quality-control" form="sales-proposal-quality-control-form">Submit</button>
                          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </di>
                  </div>
              </div>
          </div>
          </div>
        </div>
        <div>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-outgoing-checklist-offcanvas" aria-labelledby="sales-proposal-outgoing-checklist-offcanvas-label">
              <div class="offcanvas-header">
                  <h2 id="sales-proposal-outgoing-checklist-offcanvas-label" style="margin-bottom:-0.5rem">Outgoing Checklist</h2>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                  <div class="row">
                      <div class="col-lg-12">
                      <form id="sales-proposal-outgoing-checklist-form" method="post" action="#">
                          <div class="form-group row">
                              <div class="col-lg-12 mt-3 mt-lg-0">
                                  <label class="form-label">Outgoing Checklist Image <span class="text-danger">*</span></label>
                                  <input type="file" class="form-control" id="outgoing_checklist_image" name="outgoing_checklist_image">
                              </div>
                          </div>
                      </form>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-outgoing-checklist" form="sales-proposal-outgoing-checklist-form">Submit</button>
                          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </di>
                  </div>
              </div>
          </div>
          </div>
        </div>
        <div>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-ob-order-confirmation-offcanvas" aria-labelledby="sales-proposal-ob-order-confirmation-offcanvas-label">
              <div class="offcanvas-header">
                  <h2 id="sales-proposal-ob-order-confirmation-offcanvas-label" style="margin-bottom:-0.5rem">Additional Job Order Confirmation</h2>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                  <div class="row">
                      <div class="col-lg-12">
                      <form id="sales-proposal-ob-order-confirmation-form" method="post" action="#">
                          <div class="form-group row">
                              <div class="col-lg-12 mt-3 mt-lg-0">
                                  <label class="form-label">Additional Job Order Confirmation Image <span class="text-danger">*</span></label>
                                  <input type="file" class="form-control" id="additional_job_order_confirmation_image" name="additional_job_order_confirmation_image">
                              </div>
                          </div>
                      </form>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-additional-job-order-confirmation" form="sales-proposal-ob-order-confirmation-form">Submit</button>
                          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </di>
                  </div>
              </div>
          </div>
          </div>
        </div>
        <div>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-unit-image-offcanvas" aria-labelledby="sales-proposal-unit-image-offcanvas-label">
              <div class="offcanvas-header">
                  <h2 id="sales-proposal-unit-image-offcanvas-label" style="margin-bottom:-0.5rem">Unit Image</h2>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                  <div class="row">
                      <div class="col-lg-12">
                      <form id="sales-proposal-unit-image-form" method="post" action="#">
                          <div class="form-group row">
                              <div class="col-lg-12 mt-3 mt-lg-0">
                                  <label class="form-label">Unit Image <span class="text-danger">*</span></label>
                                  <input type="file" class="form-control" id="unit_image_image" name="unit_image_image">
                              </div>
                          </div>
                      </form>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-unit-image" form="sales-proposal-unit-image-form">Submit</button>
                          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </di>
                  </div>
              </div>
          </div>
          </div>
        </div>';
?>