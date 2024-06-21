<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Leave Entitlement</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';
                            
                if ($leaveEntitlementDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-leave-entitlement-details">Delete Leave Entitlement</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($leaveEntitlementWriteAccess['total'] > 0 && $entitleAmount == $remainingEntitlement) {
                    echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                        <button type="submit" form="leave-entitlement-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                }

                if ($leaveEntitlementCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="leave-entitlement.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="leave-entitlement-form" method="post" action="#">
          <?php
            if($leaveEntitlementWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Employee <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-10">
                          <label class="col-form-label form-details fw-normal" id="employee_id_label"></label>
                          <div class="d-none form-edit">
                              <select class="form-control select2" name="employee_id" id="employee_id">
                                  <option value="">--</option>
                                  '. $employeeModel->generateEmployeeOptions('active employee') .'
                              </select>
                          </div>
                      </div>
                    </div>
                    <div class="form-group row">
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
                      <label class="col-lg-2 col-form-label">Entitlement (in hours) <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="entitlement_amount_label"></label>
                        <input type="number" class="form-control d-none form-edit" id="entitlement_amount" name="entitlement_amount" min="1">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Coverage Start Date <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_period_start_label"></label>
                        <div class="input-group date d-none form-edit">
                            <input type="text" class="form-control regular-datepicker" id="leave_period_start" name="leave_period_start" autocomplete="off">
                            <span class="input-group-text">
                                <i class="feather icon-calendar"></i>
                            </span>
                        </div>
                      </div>
                      <label class="col-lg-2 col-form-label">Coverage End Date <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_period_end_label"></label>
                        <div class="input-group date d-none form-edit">
                            <input type="text" class="form-control regular-datepicker" id="leave_period_end" name="leave_period_end" autocomplete="off">
                            <span class="input-group-text">
                                <i class="feather icon-calendar"></i>
                            </span>
                        </div>
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Employee</label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="employee_id_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Leave Type</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_type_id_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Entitlement</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="entitlement_amount_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Coverage Start Date</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_period_start_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Coverage End Date</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="leave_period_end_label"></label>
                      </div>
                    </div>';
            }
          ?>
        </form>
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
                '. $userModel->generateLogNotes('leave_entitlement', $leaveEntitlementID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>