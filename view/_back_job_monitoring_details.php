<?php
    $backJobMonitoringDetails = $backJobMonitoringModel->getBackJobMonitoring($backJobMonitoringID);
    $status = $backJobMonitoringDetails['status'] ?? null;
    $type = $backJobMonitoringDetails['type'] ?? null;
    $hidden = ''; 
    $hiddenCost = 'd-none'; 
    $releaseButton = ''; 
    $cancelButton = ''; 
    $saveButton = ''; 
    $addJobOrder = ''; 
    $addAdditionalJobOrder = ''; 
    $printJobOrder = ''; 
    $printAdditionalJobOrder = ''; 

    if($type == 'Internal Repair' && ($status == 'Draft' || $status == 'On-Process')){
        $addJobOrder = '<div class="previous me-2">
           <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#job-order-monitoring-offcanvas" aria-controls="job-order-monitoring-offcanvas">Add Job Order</button>
        </div>';

        $addAdditionalJobOrder = '<div class="previous me-2">
           <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#additional-job-order-monitoring-offcanvas" aria-controls="additional-job-order-monitoring-offcanvas">Add Additional Job Order</button>
        </div>';
    }

    if($type == 'Backjob' && $status == 'On-Process'){        
        $printJobOrder = '<li><button class="dropdown-item" id="print-job-order">Print Job Order</button></li>';
        $printAdditionalJobOrder = '<li><button class="dropdown-item" id="print-additional-job-order">Print Additional Job Order</button></li>';
    }

    if($type == 'Internal Repair' && $status == 'On-Process'){        
        $printJobOrder = '<li><button class="dropdown-item" id="print-job-order2">Print Job Order</button></li>';
        $printAdditionalJobOrder = '<li><button class="dropdown-item" id="print-additional-job-order2">Print Additional Job Order</button></li>';
    }

    if($type == 'Internal Repair' && $status == 'For DR'){
        $releaseButton = '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#tag-as-released-offcanvas" aria-controls="tag-as-released-offcanvas">Tag As Released</button></li>';
    }
  
    if($type == 'Internal Repair'){
        $hiddenCost = '';
    }

    if($status == 'Draft'){
        $releaseButton = '<li><button class="dropdown-item" id="tag-as-on-process">Tag As On-Process</button></li>';

        $saveButton = '<div class="me-2"><button type="submit" form="backjob-monitoring-form" class="btn btn-success" id="submit-data">Save</button></div>';     
    }  

    if($status == 'On-Process'){
        $releaseButton = '<li><button class="dropdown-item" id="tag-as-ready-for-release">Tag As Ready For Release</button></li>';
    }  

    if($status == 'Ready For Release'){
        $releaseButton = '<li><button class="dropdown-item" id="tag-as-for-dr">Tag As For DR</button></li>';
    }  

    if(($status != 'Cancelled' && $status != 'Released')){
        $cancelButton = '<li><button class="dropdown-item" data-bs-toggle="offcanvas" data-bs-target="#tag-as-cancelled-offcanvas" aria-controls="tag-as-cancelled-offcanvas">Tag As Cancelled</button></li>';
    }
?>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <li><a class="nav-link active" id="tab-1" data-bs-toggle="pill" href="#v-basic-details" role="tab" aria-controls="v-basic-details" aria-selected="true">Basic Details</a></li>
                    <li><a class="nav-link <?php echo $hidden; ?>" id="tab-2" data-bs-toggle="pill" href="#v-job-order" role="tab" aria-controls="v-job-order" aria-selected="false">Job Order</a></li>
                    <li><a class="nav-link <?php echo $hidden; ?>" id="tab-3" data-bs-toggle="pill" href="#v-documents" role="tab" aria-controls="v-documents" aria-selected="false">Documents</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div class="d-flex wizard justify-content-between mb-3">
                        <div class="d-flex">                     
                          <div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                  Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">
                                <?php
                                  echo $printJobOrder;
                                  echo $printAdditionalJobOrder;
                                  echo $releaseButton;
                                  echo $cancelButton;
                                ?>
                              </ul>
                          </div>
                          <?php
                            echo $addJobOrder;
                            echo $addAdditionalJobOrder;
                          ?>
                        </div>
                    </div>
                    <div class="tab-pane show active" id="v-basic-details">
                        <form id="backjob-monitoring-form" method="post" action="#">
                            <input type="hidden" id="backjob_monitoring_id">
                            <input type="hidden" id="status" value="<?php echo $status; ?>">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Type <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <select class="form-control select2" name="type" id="type">
                                        <option value="">--</option>
                                        <option value="Backjob">Backjob</option>
                                        <option value="Internal Repair">Internal Repair</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row d-none" id="sales-row">
                                <label class="col-lg-4 col-form-label">Sales Proposal <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <select class="form-control select2" name="sales_proposal_id" id="sales_proposal_id">
                                        <option value="">--</option>
                                        <?php echo $salesProposalModel->generateJobOrderBackjobOptions(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row d-none" id="product-row">
                                <label class="col-lg-4 col-form-label">Product <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <select class="form-control select2" name="product_id" id="product_id">
                                        <option value="">--</option>
                                        <?php echo $productModel->generateInternalRepairProductOptions(); ?>
                                    </select>
                                </div>
                            </div>
                        </form>
                        
                        <?php echo $saveButton; ?>       
                    </div>
                    <div class="tab-pane" id="v-job-order">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <div class="table-responsive">
                                            <table id="job-order-progress-table" class="table table-hover nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th class="all"></th>
                                                        <th>Job Order</th>
                                                        <th>Contactor</th>
                                                        <th>Work Center</th>
                                                        <th>Cost</th>
                                                        <th>Planned Start Date</th>
                                                        <th>Planned Finished Date</th>
                                                        <th>Date Started</th>
                                                        <th>Completion Date</th>
                                                        <th>Progress</th>
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
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <div class="table-responsive">
                                            <table id="additional-job-order-progress-table" class="table table-hover nowrap w-100">
                                                <thead>
                                                <tr>
                                                    <th class="all"></th>
                                                    <th>Job Order Number</th>
                                                    <th>Job Order Date</th>
                                                    <th>Particulars</th>
                                                    <th>Cost</th>
                                                    <th>Contactor</th>
                                                    <th>Work Center</th>
                                                    <th>Progress</th>
                                                    <th>Planned Start Date</th>
                                                    <th>Planned Finished Date</th>
                                                    <th>Date Started</th>
                                                    <th>Completion Date</th>
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
                    <div class="tab-pane" id="v-documents">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Unit Image </h5>
                                                <?php
                                                    if($status == 'On-Process' || $status == 'Ready For Release'){
                                                        echo '<button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#unit-image-offcanvas" aria-controls="unit-image-offcanvas" id="unit-image">Unit Image</button>';
                                                    }
                                                ?>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Image" id="unit-img" class="img-fluid rounded">
                                                    </div>                      
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Outgoing Checklist </h5>
                                                <?php
                                                    if($status == 'On-Process' || $status == 'Ready For Release'){
                                                        echo '<button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#outgoing-checklist-offcanvas" aria-controls="outgoing-checklist-offcanvas" id="outgoing-checklist">Outgoing Checklist Image</button>';
                                                    }
                                                ?>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Outgoing Checklist" id="outgoing-checklst" class="img-fluid rounded">
                                                    </div>                      
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Quality Control Form </h5>
                                                <?php
                                                    if($status == 'On-Process' || $status == 'Ready For Release'){
                                                        echo '<button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#quality-control-form-offcanvas" aria-controls="quality-control-form-offcanvas" id="quality-control-form">Quality Control Form</button>';
                                                    }
                                                ?>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Quality Control Form" id="quality-control-frm" class="img-fluid rounded">
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="tag-as-cancelled-offcanvas" aria-labelledby="tag-as-cancelled-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="tag-as-cancelled-offcanvas-label" style="margin-bottom:-0.5rem">Tag As Cancelled </h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="tag-as-cancelled-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="cancellation_reason">Cancellation Remarks <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" maxlength="500"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-tag-as-cancelled" form="tag-as-cancelled-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="unit-image-offcanvas" aria-labelledby="unit-image-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="unit-image-offcanvas-label" style="margin-bottom:-0.5rem">Unit Image</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="unit-image-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-unit-image" form="unit-image-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="outgoing-checklist-offcanvas" aria-labelledby="outgoing-checklist-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="outgoing-checklist-offcanvas-label" style="margin-bottom:-0.5rem">Outgoing Checklist</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="outgoing-checklist-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Outgoing Checklist <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="outgoing_checklist_image" name="outgoing_checklist_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-outgoing-checklist" form="outgoing-checklist-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="quality-control-form-offcanvas" aria-labelledby="quality-control-form-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="quality-control-form-offcanvas-label" style="margin-bottom:-0.5rem">Quality Control Form</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="quality-control-form-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Quality Control Form <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="quality_control_form" name="quality_control_form">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-quality-control-form" form="quality-control-form-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="job-order-monitoring-offcanvas" aria-labelledby="job-order-monitoring-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="job-order-offcanvas-label" style="margin-bottom:-0.5rem">Job Order Progress</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="job-order-progress-form" method="post" action="#">
            <input type="hidden" id="backjob_monitoring_job_order_id" name="backjob_monitoring_job_order_id">
            <div class="form-group row <?php echo $hiddenCost; ?>">
              <div class="col-lg-12">
                <label class="form-label">Job Order <span class="text-danger">*</span></label>
                <input type="text" class="form-control text-uppercase" id="job_order" name="job_order" maxlength="500" autocomplete="off">
              </div>
            </div>
            <div class="form-group row <?php echo $hiddenCost; ?>">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label" for="job_order_cost">Cost <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="job_order_cost" name="job_order_cost" min="0" step="0.01">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="job_order_progress" name="job_order_progress" min="0" max="100" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Contractor</label>
                <select class="form-control offcanvas-select2" name="job_order_contractor_id" id="job_order_contractor_id">
                  <option value="">--</option>
                  <?php echo $contractorModel->generateContractorOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Work Center</label>
                <select class="form-control offcanvas-select2" name="job_order_work_center_id" id="job_order_work_center_id">
                  <option value="">--</option>
                  <?php echo $workCenterModel->generateWorkCenterOptions(); ?>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Planned Start Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="job_order_planned_start_date" name="job_order_planned_start_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Planned Finish Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="job_order_planned_finish_date" name="job_order_planned_finish_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Date Started</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="job_order_date_started" name="job_order_date_started" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Completion Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="job_order_completion_date" name="job_order_completion_date" autocomplete="off">
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
          <button type="submit" class="btn btn-primary" id="submit-job-order-progress" form="job-order-progress-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="additional-job-order-monitoring-offcanvas" aria-labelledby="additional-job-order-monitoring-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="additional-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Additional Job Order Progress</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="additional-job-order-progress-form" method="post" action="#">
            <input type="hidden" id="backjob_monitoring_additional_job_order_id" name="backjob_monitoring_additional_job_order_id">
            <div class="form-group row <?php echo $hiddenCost; ?>">
              <div class="col-lg-12">
                <label class="form-label">Job Order Number <span class="text-danger">*</span></label>
                <input type="hidden" id="sales_proposal_additional_job_order_id" name="sales_proposal_additional_job_order_id">
                <input type="text" class="form-control text-uppercase" id="job_order_number" name="job_order_number" maxlength="500" autocomplete="off">
              </div>
            </div>
            <div class="form-group row <?php echo $hiddenCost; ?>">
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
            <div class="form-group row <?php echo $hiddenCost; ?>">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Particulars <span class="text-danger">*</span></label>
                <input type="text" class="form-control text-uppercase" id="particulars" name="particulars" maxlength="1000" autocomplete="off">
              </div>
            </div>
            <div class="form-group row <?php echo $hiddenCost; ?>">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label" for="additional_job_order_cost">Cost <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="additional_job_order_cost" name="additional_job_order_cost" min="0" step="0.01">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="additional_job_order_progress" name="additional_job_order_progress" min="0" max="100" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Contractor</label>
                <select class="form-control offcanvas-select2" name="additional_job_order_contractor_id" id="additional_job_order_contractor_id">
                  <option value="">--</option>
                  <?php echo $contractorModel->generateContractorOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Work Center</label>
                <select class="form-control offcanvas-select2" name="additional_job_order_work_center_id" id="additional_job_order_work_center_id">
                  <option value="">--</option>
                  <?php echo $workCenterModel->generateWorkCenterOptions(); ?>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Planned Start Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="additional_job_order_planned_start_date" name="additional_job_order_planned_start_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Planned Finish Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="additional_job_order_planned_finish_date" name="additional_job_order_planned_finish_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Date Started</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="additional_job_order_date_started" name="additional_job_order_date_started" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Completion Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="additional_job_order_completion_date" name="additional_job_order_completion_date" autocomplete="off">
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
          <button type="submit" class="btn btn-primary" id="submit-additional-job-order-progress" form="additional-job-order-progress-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="tag-as-released-offcanvas" aria-labelledby="tag-as-released-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="tag-as-released-offcanvas-label" style="margin-bottom:-0.5rem">Tag As Released </h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="tag-as-released-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="release_remarks">Release Remarks <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="release_remarks" name="release_remarks" maxlength="500"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-tag-as-released" form="tag-as-released-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
            </div>
        </div>
    </div>
</div>