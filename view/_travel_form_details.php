<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5>Travel Form</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                        <?php                            
                            $dropdown = '<div class="btn-group m-r-5">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">';
                        
                                    
                            $dropdown .= '</ul>
                                        </div>';
                                
                            echo $dropdown;

                            if ($travelFormWriteAccess['total'] > 0) {
                                echo '<button type="submit" form="travel-form" class="btn btn-success" id="submit-data">Save</button>
                                    <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>';
                            }

                            if ($travelFormCreateAccess['total'] > 0) {
                                echo '<a class="btn btn-success m-r-5 form-details" href="travel-form.php?new">Create</a>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        <div class="card-body">
            <form id="travel-form" method="post" action="#">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Checked By <span class="text-danger">*</span></label>
                    <div class="col-lg-4">
                        <select class="form-control select2" name="checked_by" id="checked_by">
                            <option value="">--</option>
                            <?php echo $employeeModel->generateEmployeeOptions('all'); ?>
                        </select>
                    </div>
                    <label class="col-lg-2 col-form-label">Approval By <span class="text-danger">*</span></label>
                    <div class="col-lg-4">
                        <select class="form-control select2" name="approval_by" id="approval_by">
                            <option value="">--</option>
                            <?php echo $employeeModel->generateEmployeeOptions('all'); ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5>Travel Authorization</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                        <?php
                            if ($travelFormWriteAccess['total'] > 0) {
                                echo '<button type="submit" form="travel-authorization-form" class="btn btn-success" id="submit-travel-authorization-data">Save</button>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="travel-authorization-form" method="post" action="#">
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Destination <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="destination" name="destination" maxlength="500" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Mode of Transportation <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="mode_of_transportation" name="mode_of_transportation" maxlength="500" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Purpose of Travel <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="purpose_of_travel" name="purpose_of_travel" maxlength="500" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Departure Date <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <div class="input-group date">
                            <input type="text" class="form-control regular-datepicker" id="authorization_departure_date" name="authorization_departure_date" autocomplete="off">
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Return Date <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="authorization_return_date" name="authorization_return_date" autocomplete="off">
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Accomodation Details</label>
                        <div class="col-lg-7">
                            <textarea class="form-control" id="accomodation_details" name="accomodation_details" maxlength="1000"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Toll Fee</label>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="toll_fee" name="toll_fee" step="0.01" value="0" min="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Accomodation</label>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="accomodation" name="accomodation" step="0.01" value="0" min="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Meals</label>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="meals" name="meals" step="0.01" value="0" min="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Other Expenses (Specify on Notes)</label>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="other_expenses" name="other_expenses" step="0.01" value="0" min="0">
                        </div>
                        </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Total Estimated Cost</label>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="total_estimated_cost" name="total_estimated_cost" step="0.01" value="0" min="0" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Additional Comments/Notes </label>
                        <div class="col-lg-7">
                            <textarea class="form-control" id="additional_comments" name="additional_comments" maxlength="1000"></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5>Gate Pass</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                        <?php
                            if ($travelFormWriteAccess['total'] > 0) {
                                echo '<button type="submit" form="gate-pass-form" class="btn btn-success" id="submit-gate-pass-data">Save</button>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="gate-pass-form" method="post" action="#">
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Name of Driver <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="name_of_driver" name="name_of_driver" maxlength="500" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Contact Number <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="contact_number" name="contact_number" maxlength="50" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Vehicle Type <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" maxlength="200" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Plate No. <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="plate_number" name="plate_number" maxlength="200" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Department <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <select class="form-control select2" name="department_id" id="department_id">
                                <option value="">--</option>
                                <?php echo $departmentModel->generateDepartmentOptions(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Departure Date <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <div class="input-group date">
                            <input type="text" class="form-control regular-datepicker" id="gate_pass_departure_date" name="gate_pass_departure_date" autocomplete="off">
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Odometer Reading <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="odometer_reading" name="odometer_reading" maxlength="200" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Remarks</label>
                        <div class="col-lg-7">
                            <textarea class="form-control" id="remarks" name="remarks" maxlength="1000"></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5>Itinerary</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                        <?php
                            if ($travelFormWriteAccess['total'] > 0) {
                                echo '<button type="submit" form="travel-form" class="btn btn-success" id="submit-data">Save</button>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive dt-responsive">
                    <table id="itinerary-table" class="table table-hover nowrap w-100 text-uppercase">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name of Client</th>
                                <th>Destination</th>
                                <th>Purpose</th>
                                <th>Expected Time of Departure (ETD)</th>
                                <th>Expected Time of Arrival (ETA)</th>
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
                '. $userModel->generateLogNotes('transaction_form', $travelFormID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>