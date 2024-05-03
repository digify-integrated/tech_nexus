<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <li><a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="pill" href="#v-basic-details" role="tab" aria-controls="v-basic-details" aria-selected="true" disabled>Basic Details</a></li>
          <li><a class="nav-link d-none" id="sales-proposal-tab-4" data-bs-toggle="pill" href="#v-refinancing-details" role="tab" aria-controls="v-refinancing-details" aria-selected="false" disabled>Refinancing Details</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-5" data-bs-toggle="pill" href="#v-job-order" role="tab" aria-controls="v-job-order" aria-selected="false" disabled>Job Order</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-10" data-bs-toggle="pill" href="#v-additional-job-order" role="tab" aria-controls="v-additional-job-order" aria-selected="false" disabled>Additional Job Order</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-11" data-bs-toggle="pill" href="#v-confirmations" role="tab" aria-controls="v-confirmations" aria-selected="false" disabled>Confirmations</a></li>
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
                if($salesProposalStatus == 'Proceed' && $tagSalesProposalForOnProcess['total'] > 0){
                  echo '<div class="previous me-2 d-none" id="on-process-sales-proposal-button">
                          <button class="btn btn-info" id="on-process-sales-proposal">On-Process</button>
                        </div>';
                }

                if($salesProposalStatus == 'On-Process'){
                  if($tagSalesProposalReadyForRelease['total'] > 0 && !empty($qualityControlForm)){
                    if(($additionalJobOrderCount['total'] > 0 && !empty($additionalJobOrderConfirmation)) || $additionalJobOrderCount['total'] == 0){
                      echo '<div class="previous me-2 d-none" id="ready-for-release-sales-proposal-button">
                      <button class="btn btn-info m-l-5" id="ready-for-release-sales-proposal">Ready For Release</button>
                    </div>';
                    }
                  }

                  echo '<div class="previous me-2 d-none" id="add-sales-proposal-additional-job-order-button">
                    <button class="btn btn-primary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-offcanvas" aria-controls="sales-proposal-additional-job-order-offcanvas" id="add-sales-proposal-additional-job-order">Add Additional Job Order</button>
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
            <form id="sales-proposal-form" method="post" action="#">
              <input type="hidden" id="sales_proposal_status" value="<?php echo $salesProposalStatus; ?>">
              <input type="hidden" id="sales_proposal_type" value="modified">
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Sales Proposal Number :</label>
                <label class="col-lg-8 col-form-label" id="sales_proposal_number">--</label>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Sales Proposal Status :</label>
                <div class="col-lg-8 mt-3">
                  <?php echo $salesProposalStatusBadge; ?>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Client ID :</label>
                <label class="col-lg-8 col-form-label" id="customer-id"> <?php echo $customerID; ?></label>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Client Name :</label>
                <label class="col-lg-8 col-form-label"> <?php echo strtoupper($customerName); ?></label>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Corporate Name :</label>
                <label class="col-lg-8 col-form-label"> <?php echo strtoupper($corporateName); ?></label>
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
            </form>
          </div>
          <div class="tab-pane" id="v-job-order">
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
          <div class="tab-pane" id="v-additional-job-order">
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
          <div class="tab-pane" id="v-confirmations">
            <div class="row">
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Quality Control Form </h5>
                        <?php
                          if($salesProposalStatus == 'On-Process'){
                            echo '<button class="btn btn-success m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-quality-control-offcanvas" aria-controls="sales-proposal-quality-control-offcanvas" id="sales-proposal-quality-control">Quality Control Form</button>';
                          }
                        ?>
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
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Outgoing Checklist </h5>
                        <?php
                          if($salesProposalStatus == 'Ready For Release' || $salesProposalStatus == 'For DR'){
                            echo '<button class="btn btn-success m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-outgoing-checklist-offcanvas" aria-controls="sales-proposal-outgoing-checklist-offcanvas" id="sales-proposal-outgoing-checklist">Outgoing Checklist</button>';
                          }
                        ?>
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
            </div>
            <div class="row">
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Unit Image </h5>
                        <?php
                          if($salesProposalStatus == 'Ready For Release' || $salesProposalStatus == 'For DR'){          
                            if(($productType == 'Unit' || $productType == 'Repair')){
                              echo ' <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-unit-image-offcanvas" aria-controls="sales-proposal-unit-image-offcanvas" id="sales-proposal-unit-image">Unit Image</button>';
                            }
                          }
                        ?>
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
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Additional Job Order Confirmation</h5>
                        <?php
                          if($salesProposalStatus != 'For DR' && $additionalJobOrderCount['total'] > 0){
                            echo '<div class="previous me-2" >
                                    <button class="btn btn-warning m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-confirmation-offcanvas" aria-controls="sales-proposal-job-order-confirmation-offcanvas" id="sales-proposal-job-order-confirmation">Additional Job Order Confirmation</button>
                                  </div>';
                          }
                        ?>
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
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-job-order-confirmation-offcanvas" aria-labelledby="sales-proposal-job-order-confirmation-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-job-order-confirmation-offcanvas-label" style="margin-bottom:-0.5rem">Additional Job Order Confirmation</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-job-order-confirmation-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-additional-job-order-confirmation" form="sales-proposal-job-order-confirmation-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
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
        </div>
      </div>
    </div>
  </div>
</div>