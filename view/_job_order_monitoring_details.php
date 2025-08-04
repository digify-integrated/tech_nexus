<div class="row">
  <div class="col-lg-12">
    <input type="hidden" id="sales_proposal_id" value="<?php echo $salesProposalID; ?>"/>
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Job Order</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <div class="btn-group m-r-5">
              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><button class="dropdown-item" type="button" id="print-job-order">Print Job Order</button></li>
                <li><button class="dropdown-item" type="button" id="print-job-order-detailed">Print Job Order Detailed</button></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="dt-responsive table-responsive">
            <table id="job-order-progress-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th class="all"></th>
                    <th>Job Order</th>
                    <th>Cost</th>
                    <th>Job Order Cost</th>
                    <th>Contactor</th>
                    <th>Work Center</th>
                    <th>Progress</th>
                    <th>Planned Start Date</th>
                    <th>Planned Finished Date</th>
                    <th>Date Started</th>
                    <th>Completion Date</th>
                    <th>Cancellation Date</th>
                    <th>Cancellation Reason</th>
                    <th>Cancellation Confirmation</th>
                    <th>Backjob?</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
      </div>
    </div>

    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Additional Job Order</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <div class="btn-group m-r-5">
              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><button class="dropdown-item" type="button" id="print-additional-job-order">Print Additional Job Order</button></li>
                <li><button class="dropdown-item" type="button" id="print-additional-job-order-detailed">Print Additional Job Order Detailed</button></li>
              </ul>
            </div>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="dt-responsive table-responsive">
            <table id="additional-job-order-progress-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th class="all"></th>
                    <th>Job Order Number</th>
                    <th>Job Order Date</th>
                    <th>Particulars</th>
                    <th>Cost</th>
                    <th>Job Order Cost</th>
                    <th>Contactor</th>
                    <th>Work Center</th>
                    <th>Progress</th>
                    <th>Planned Start Date</th>
                    <th>Planned Finished Date</th>
                    <th>Date Started</th>
                    <th>Completion Date</th>
                    <th>Cancellation Date</th>
                    <th>Cancellation Reason</th>
                    <th>Cancellation Confirmation</th>
                    <th>Backjob?</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-job-order-monitoring-offcanvas" aria-labelledby="sales-proposal-job-order-monitoring-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="job-order-offcanvas-label" style="margin-bottom:-0.5rem">Job Order Progress</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="job-order-progress-form" method="post" action="#">
            <div class="form-group row">
             <input type="hidden" id="sales_proposal_job_order_id" name="sales_proposal_job_order_id">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label" for="job_order_cost">Charge to Customer <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="job_order_cost" name="job_order_cost" min="0" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label" for="job_cost">Job Order Cost <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="job_cost" name="job_cost" min="0" step="0.01">
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
                <label class="form-label">Backjob? <span class="text-danger">*</span></label>
                <select class="form-control" name="job_order_backjob" id="job_order_backjob">
                  <option value="No" selected>No</option>
                  <option value="Yes">Yes</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Planned Start Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="job_order_planned_start_date" name="job_order_planned_start_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Planned Finish Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="job_order_planned_finish_date" name="job_order_planned_finish_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Date Started</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="job_order_date_started" name="job_order_date_started" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
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
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label" for="job_order_remarks">Remarks</label>
                <textarea class="form-control" id="job_order_remarks" name="job_order_remarks" maxlength="1000"></textarea>
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-additional-job-order-monitoring-offcanvas" aria-labelledby="sales-proposal-additional-job-order-monitoring-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="additional-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Additional Job Order Progress</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="additional-job-order-progress-form" method="post" action="#">
            <input type="hidden" id="sales_proposal_additional_job_order_id" name="sales_proposal_additional_job_order_id">
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label" for="additional_job_order_cost">Charge to Customer <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="additional_job_order_cost" name="additional_job_order_cost" min="0" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label" for="additional_job_cost">Additional Job Order Cost <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="additional_job_cost" name="additional_job_cost" min="0" step="0.01">
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
                <label class="form-label">Backjob? <span class="text-danger">*</span></label>
                <select class="form-control" name="additional_job_order_backjob" id="additional_job_order_backjob">
                  <option value="No" selected>No</option>
                  <option value="Yes">Yes</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Planned Start Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="additional_job_order_planned_start_date" name="additional_job_order_planned_start_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Planned Finish Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="additional_job_order_planned_finish_date" name="additional_job_order_planned_finish_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Date Started</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="additional_job_order_date_started" name="additional_job_order_date_started" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
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
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Approval Document</label>
                <input type="file" class="form-control" id="approval_document" name="approval_document">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label" for="additional_job_order_remarks">Remarks</label>
                <textarea class="form-control" id="additional_job_order_remarks" name="additional_job_order_remarks" maxlength="1000"></textarea>
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
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-job-order-cancel-offcanvas" aria-labelledby="sales-proposal-job-order-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="sales-proposal-job-order-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Job Order</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-job-order-cancel-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="job_order_cancellation_reason" name="job_order_cancellation_reason" maxlength="500"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Client Confirmation Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="job_order_cancellation_confirmation_image" name="job_order_cancellation_confirmation_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-job-order-cancel" form="sales-proposal-job-order-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-additional-job-order-cancel-offcanvas" aria-labelledby="sales-proposal-additional-job-order-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="sales-proposal-additional-job-order-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Additional Job Order</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-additional-job-order-cancel-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="additional_job_order_cancellation_reason" name="additional_job_order_cancellation_reason" maxlength="500"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Client Confirmation Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="additional_job_order_cancellation_confirmation_image" name="additional_job_order_cancellation_confirmation_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-additional-job-order-cancel" form="sales-proposal-additional-job-order-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>