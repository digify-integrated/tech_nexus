<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Leave Application</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                 $dropdown = '<div class="btn-group m-r-5">
                 <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                     Action
                 </button>
                 <ul class="dropdown-menu dropdown-menu-end">';
             
                  if ($leaveApplicationDeleteAccess['total'] > 0 && $status == 'Draft') {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-leave-application-details">Delete Leave Application</button></li>';
                  }
                              
                  if ($leaveApplicationForApproval['total'] > 0 && $status == 'Draft') {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-leave-application-for-recommendation">For Recommendation</button></li>';
                  }

                  if ($leaveApplicationForRecommendation['total'] > 0 && $status == 'For Recommendation') {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-leave-application-recommendation">Recommend</button></li>';
                  }
                              
                  if ($leaveApplicationApprove['total'] > 0 && $status == 'For Approval') {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-leave-application-approve">Approve</button></li>';
                  }
                              
                  if ($leaveApplicationReject['total'] > 0 && ($status == 'For Approval' ||  $status == 'For Recommendation')) {
                      $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#leave-application-reject-offcanvas" aria-controls="leave-application-reject-offcanvas" id="leave-application-reject" id="tag-leave-application-reject">Reject</button></li>';
                  }
                              
                  if ($leaveApplicationCancel['total'] > 0 && ($status == 'Draft' || $status == 'For Approval' || ($status == 'Approved' && (strtotime($leaveDate) < strtotime(date('Y-m-d')))) )) {
                      $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#leave-application-cancel-offcanvas" aria-controls="leave-application-cancel-offcanvas" id="leave-application-cancel" id="tag-leave-application-cancel">Cancel</button></li>';
                  }
                          
                  $dropdown .= '</ul>
                              </div>';
                      
                  echo $dropdown;

                  if ($leaveApplicationWriteAccess['total'] > 0 && $status == 'Draft') {
                      echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                          <button type="submit" form="leave-application-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                          <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                  }

                  if ($leaveApplicationCreateAccess['total'] > 0) {
                      echo '<a class="btn btn-success m-r-5 form-details" href="leave-application.php?new">Create</a>';
                  }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="leave-application-form" method="post" action="#">
          <?php
            if($leaveApplicationWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Leave Type <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                          <label class="col-form-label form-details fw-normal" id="leave_type_id_label"></label>
                          <div class="d-none form-edit">
                              <select class="form-control select2" name="leave_type_id" id="leave_type_id">
                                  <option value="">--</option>
                                  '. $leaveTypeModel->generateLeaveTypeOptions() .'
                              </select>
                          </div>
                      </div>
                       <label class="col-lg-2 col-form-label">Leave Date <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_date_label"></label>
                        <div class="input-group date d-none form-edit">
                            <input type="text" class="form-control regular-datepicker" id="leave_date" name="leave_date" autocomplete="off">
                            <span class="input-group-text">
                                <i class="feather icon-calendar"></i>
                            </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Start Time <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_start_time_label"></label>
                        <input class="form-control d-none form-edit" id="leave_start_time" name="leave_start_time" type="time">
                      </div>
                      <label class="col-lg-2 col-form-label">End Time <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_end_time_label"></label>
                        <input class="form-control d-none form-edit" id="leave_end_time" name="leave_end_time" type="time">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Leave Reason <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="reason_label"></label>
                        <textarea class="form-control d-none form-edit" id="reason" name="reason" maxlength="500"></textarea>
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Leave Type</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_type_id_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Leave Date</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_date_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Start Time</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_start_time_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">End Time</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_end_time_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Reason</label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="reason_label"></label>
                      </div>
                    </div>';
            }
          ?>
        </form>
      </div>
    </div>
  </div>

  
  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="leave-application-cancel-offcanvas" aria-labelledby="leave-application-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="leave-application-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Sales Proposal</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leave-application-cancel-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-leave-application-cancel" form="leave-application-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
  
  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="leave-application-reject-offcanvas" aria-labelledby="leave-application-reject-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="leave-application-reject-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Sales Proposal</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leave-application-reject-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-leave-application-reject" form="leave-application-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
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
                '. $userModel->generateLogNotes('leave_application', $leaveApplicationID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>