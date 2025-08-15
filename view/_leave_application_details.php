
<?php
           $hiddenEmployee = 'd-none';
           if($creationType === 'manual'){
             $hiddenEmployee = '';
           }
          ?>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5><?php echo $pageTitle; ?></h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php         
                 $dropdownItems = '';
                 
                 // Check each condition and build dropdown items
                 if ($leaveApplicationDeleteAccess['total'] > 0 && $status == 'Draft') {
                     $dropdownItems .= '<li><button class="dropdown-item" type="button" id="delete-leave-application-details">Delete Leave Application</button></li>';
                 }
                 
                 if ($leaveApplicationForApproval['total'] > 0 && $status == 'Draft') {
                     $dropdownItems .= '<li><button class="dropdown-item" type="button" id="tag-leave-application-for-recommendation">For Recommendation</button></li>';
                 }
                 
                 if ($leaveApplicationForRecommendation['total'] > 0 && $status == 'For Recommendation') {
                     $dropdownItems .= '<li><button class="dropdown-item" type="button" id="tag-leave-application-recommendation">Recommend</button></li>';
                 }
                 
                 if ($leaveApplicationApprove['total'] > 0 && $status == 'For Approval') {
                     $dropdownItems .= '<li><button class="dropdown-item" type="button" id="tag-leave-application-approve">Approve</button></li>';
                 }
                 
                 if ($leaveApplicationReject['total'] > 0 && ($status == 'For Approval' || $status == 'For Recommendation')) {
                     $dropdownItems .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#leave-application-reject-offcanvas" aria-controls="leave-application-reject-offcanvas" id="leave-application-reject">Reject</button></li>';
                 }
                 
                 if (
                     $leaveApplicationCancel['total'] > 0 &&
                     (
                         $status == 'Draft' ||
                         $status == 'For Approval' ||
                         $status == 'For Recommendation' ||
                         ($status == 'Approved' && (strtotime($leaveDate) >= strtotime(date('Y-m-d'))))
                     )
                 ) {
                     $dropdownItems .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#leave-application-cancel-offcanvas" aria-controls="leave-application-cancel-offcanvas" id="leave-application-cancel">Cancel</button></li>';
                 }
                 
                 if (!empty($dropdownItems)) {
                     echo '
                     <div class="btn-group m-r-5">
                         <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                             Action
                         </button>
                         <ul class="dropdown-menu dropdown-menu-end">
                             ' . $dropdownItems . '
                         </ul>
                     </div>';
                 }
                 

                  if ($leaveApplicationWriteAccess['total'] > 0 && $status == 'Draft') {
                      echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                          <button type="submit" form="leave-application-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                          <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                  }


                  if($creationType === 'manual'){
                    if ($leaveApplicationCreateAccess['total'] > 0) {
                      echo '<a class="btn btn-success m-r-5 form-details" href="manual-leave-application.php?new">Create</a>';
                    }
                  }
                  else{
                    if ($leaveApplicationCreateAccess['total'] > 0) {
                      echo '<a class="btn btn-success m-r-5 form-details" href="leave-application.php?new">Create</a>';
                    }
                  }
                  
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="leave-application-form" method="post" action="#">
        <input type="hidden" id="creation_type" name="creation_type" value="<?php echo $creationType; ?>" />
          <div class="form-group row <?php echo $hiddenEmployee; ?>">
            <label class="col-lg-2 col-form-label">Employee <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <select class="form-control select2" name="employee_id" id="employee_id">
                    <option value="">--</option>
                    <?php echo $employeeModel->generateEmployeeOptions('active employee'); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Leave Type <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <select class="form-control select2" name="leave_type_id" id="leave_type_id">
                    <option value="">--</option>
                    <?php 
                      if($creationType === 'manual'){
                        echo $leaveTypeModel->generateLeaveTypeOptions();
                      }
                      else{
                        echo $leaveTypeModel->generateLeaveTypeWithoutAWOLOptions();
                      }
                    ?>
                </select>
            </div>
          </div>
          <div class="form-group row d-none sil-group">
            <label class="col-lg-2 col-form-label">Application Type <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <select class="form-control select2" name="application_type" id="application_type">
                    <option value="">--</option>
                    <option value="Whole Day">Whole Day</option>
                    <option value="Half Day Morning">Half Day Morning</option>
                    <option value="Half Day Afternoon">Half Day Afternoon</option>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Leave Date <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="leave_date" name="leave_date" autocomplete="off">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
          </div>
          <div class="form-group row d-none leave-group">
            <label class="col-lg-2 col-form-label">Start Time <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input class="form-control" id="leave_start_time" name="leave_start_time" type="time">
            </div>
            <label class="col-lg-2 col-form-label">End Time <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input class="form-control" id="leave_end_time" name="leave_end_time" type="time">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Leave Reason <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <textarea class="form-control" id="reason" name="reason" maxlength="500"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <div class="col-lg-12 <?php echo $hiddenEmployee; ?>">
    <div class="card">
      <div class="card-body py-2">
        <ul class="list-group list-group-flush">
          <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Leave Form </h5>
            <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#leave-form-image-offcanvas" aria-controls="leave-form-image-offcanvas" id="leave-form-image">Leave Form</button>
          </li>
          <li class="list-group-item px-0">
            <div class="row align-items-center mb-3">
              <div class="col-sm-12 mb-sm-0">
                <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Leave Form" id="leave-form" class="img-fluid rounded w-100">
              </div>                      
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5>Leave Document</h5>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <?php
                      if($status == 'Draft'){
                        echo '<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-leave-document-offcanvas" aria-controls="add-leave-document-offcanvas" id="add-leave-document">Add Document</button>';
                      }  
                    ?>                    
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="dt-responsive table-responsive">
                <table class="table mb-0" id="leave-document-table">
                    <thead>
                        <tr>
                            <th>Leave Document</th>
                            <th class="text-end">Upload Date</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
  </div>

  <div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leave-form-image-offcanvas" aria-labelledby="leave-form-image-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leave-form-image-offcanvas-label" style="margin-bottom:-0.5rem">Leave Form</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leave-form-image-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Leave Form <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="leave_form_image" name="leave_form_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leave-form-image" form="leave-form-image-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="leave-application-cancel-offcanvas" aria-labelledby="leave-application-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="leave-application-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Leave</h2>
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="add-leave-document-offcanvas" aria-labelledby="add-leave-document-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="add-leave-document-offcanvas-label" style="margin-bottom:-0.5rem">Add Document</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="row">
          <div class="col-lg-12">
            <form id="add-leave-document-form" method="post" action="#">
              <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                  <label class="form-label">Document <span class="text-danger">*</span></label>
                  <select class="form-control offcanvas-select2" name="document_name" id="document_name">
                    <option value="">--</option>
                    <option value="Leave Confirmation">Leave Confirmation</option>
                    <option value="Medical Certificate">Medical Certificate</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                  <label class="form-label">Document <span class="text-danger">*</span></label>
                  <input type="file" id="leave_document" name="leave_document" class="form-control">
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <button type="submit" class="btn btn-primary" id="submit-add-leave-document" form="add-leave-document-form">Submit</button>
            <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="leave-application-reject-offcanvas" aria-labelledby="leave-application-reject-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="leave-application-reject-offcanvas-label" style="margin-bottom:-0.5rem">Reject Leave</h2>
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
          <button type="submit" class="btn btn-primary" id="submit-leave-application-reject" form="leave-application-reject-form">Submit</button>
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