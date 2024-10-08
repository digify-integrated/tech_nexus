<?php
$travelFormDetails = $travelFormModel->getTravelForm($travelFormID);
$travelFormStatus = $travelFormDetails['travel_form_status'];
$checkedBy = $travelFormDetails['checked_by'];
$recommendedBy = $travelFormDetails['recommended_by'];
$approvalBy = $travelFormDetails['approval_by'];

$disabled = '';
if($travelFormStatus != 'Draft'){
    $disabled = 'disabled';
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5>Travel Form</h5>
                    </div>
                    <div class="col-md-8 text-sm-end mt-3 mt-sm-0">
                        <?php                            
                            $dropdown = '<div class="btn-group m-r-5">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" type="button" id="print-consolidated">Print Consolidated</button></li>
                            ';

                            $dropdown .= '</ul>
                                        </div>';
                                
                            echo $dropdown;

                            if ($travelFormWriteAccess['total'] > 0 && $travelFormStatus == 'Draft') {
                                echo '<button type="submit" form="travel-form" class="btn btn-success" id="submit-data">Save</button>
                                    <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>';
                            }

                            if ($travelFormStatus == 'Draft' && !empty($checkedBy)) {
                                echo '<button type="button" id="tag-as-for-checking" class="btn btn-warning me-2">For Checking</button>';
                            }

                            if ($travelFormStatus == 'For Checking' && $checkedBy == $contact_id) {
                                echo '<button type="button" id="tag-as-checked" class="btn btn-warning me-2">Checked</button>';
                            }

                            if (($travelFormStatus == 'Draft' && empty($checkedBy)) || $travelFormStatus == 'Checked') {
                                echo '<button type="button" id="tag-as-for-recommendation" class="btn btn-warning me-2">For Recommendation</button>';
                            }

                            if ($travelFormStatus == 'For Recommendation' && $recommendedBy == $contact_id) {
                                echo '<button type="button" id="tag-as-recommended" class="btn btn-warning me-2">Recommended</button>';
                            }

                            if ($travelFormStatus == 'Recommended' && $approvalBy == $contact_id) {
                                echo '<button type="button" id="tag-as-approved" class="btn btn-warning me-2">Approved</button>';
                            }

                            if ($travelFormStatus == 'Recommended' && $approvalBy == $contact_id) {
                                echo '<button type="button" id="tag-as-rejected" data-bs-toggle="offcanvas" data-bs-target="#travel-form-reject-offcanvas" aria-controls="travel-form-reject-offcanvas" class="btn btn-danger me-2">Reject</button>';
                            }

                            if ($travelFormStatus != 'Approved' && $travelFormStatus != 'Draft') {
                                echo '<button type="button" id="tag-as-rejected" data-bs-toggle="offcanvas" data-bs-target="#travel-form-set-to-draft-offcanvas" aria-controls="travel-form-set-to-draft-offcanvas" class="btn btn-warning me-2">Set To Draft</button>';
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
                    <label class="col-lg-2 col-form-label">Checked By</label>
                    <div class="col-lg-4">
                        <select class="form-control select2" name="checked_by" id="checked_by" <?php echo $disabled; ?>>
                            <option value="">--</option>
                            <?php echo $employeeModel->generateEmployeeOptions('all'); ?>
                        </select>
                    </div>
                    <label class="col-lg-2 col-form-label">Recommended By <span class="text-danger">*</span></label>
                    <div class="col-lg-4">
                        <select class="form-control select2" name="recommended_by" id="recommended_by" <?php echo $disabled; ?>>
                            <option value="">--</option>
                            <?php echo $employeeModel->generateEmployeeOptions('all'); ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Approval By <span class="text-danger">*</span></label>
                    <div class="col-lg-4">
                        <select class="form-control select2" name="approval_by" id="approval_by" <?php echo $disabled; ?>>
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
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5>Itinerary</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                        <?php
                            if ($travelFormWriteAccess['total'] > 0 && $travelFormStatus == 'Draft') {
                                echo '<button type="button" form="travel-form" class="btn btn-success" id="add-itinerary" data-bs-toggle="offcanvas" data-bs-target="#itinerary-offcanvas" aria-controls="itinerary-offcanvas">Add</button>';
                            }

                            if ($travelFormStatus == 'Approved') {
                                echo '<button type="button" class="btn btn-warning" id="print-itinerary">Print</button>';
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
                            if ($travelFormWriteAccess['total'] > 0 && $travelFormStatus == 'Draft') {
                                echo '<button type="submit" form="travel-authorization-form" class="btn btn-success" id="submit-travel-authorization-data">Save</button>';
                            }

                            if ($travelFormStatus == 'Approved') {
                                echo '<button type="button" class="btn btn-warning" id="print-travel-authorization">Print</button>';
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
                            <input type="text" class="form-control" id="destination" name="destination" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Mode of Transportation <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="mode_of_transportation" name="mode_of_transportation" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Purpose of Travel <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="purpose_of_travel" name="purpose_of_travel" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Departure Date <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <div class="input-group date">
                            <input type="text" class="form-control regular-datepicker" id="authorization_departure_date" name="authorization_departure_date" autocomplete="off" <?php echo $disabled; ?>>
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
                                <input type="text" class="form-control regular-datepicker" id="authorization_return_date" name="authorization_return_date" autocomplete="off" <?php echo $disabled; ?>>
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Accomodation Details</label>
                        <div class="col-lg-7">
                            <textarea class="form-control" id="accomodation_details" name="accomodation_details" maxlength="1000" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Toll Fee</label>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="toll_fee" name="toll_fee" step="0.01" value="0" min="0" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Accomodation</label>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="accomodation" name="accomodation" step="0.01" value="0" min="0" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Meals</label>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="meals" name="meals" step="0.01" value="0" min="0" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Other Expenses (Specify on Notes)</label>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="other_expenses" name="other_expenses" step="0.01" value="0" min="0" <?php echo $disabled; ?>>
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
                            <textarea class="form-control" id="additional_comments" name="additional_comments" maxlength="1000" <?php echo $disabled; ?>></textarea>
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
                            if ($travelFormWriteAccess['total'] > 0 && $travelFormStatus == 'Draft') {
                                echo '<button type="submit" form="gate-pass-form" class="btn btn-success" id="submit-gate-pass-data">Save</button>';
                            }

                            if ($travelFormStatus == 'Approved') {
                                echo '<button type="button" class="btn btn-warning" id="print-gate-pass">Print</button>';
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
                            <input type="text" class="form-control" id="name_of_driver" name="name_of_driver" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Contact Number <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="contact_number" name="contact_number" maxlength="50" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Vehicle Type <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" maxlength="200" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Plate No. <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="plate_number" name="plate_number" maxlength="200" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Purpose of Entry/Exit<span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="purpose_of_entry_exit" name="purpose_of_entry_exit" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Department <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <select class="form-control select2" name="department_id" id="department_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $departmentModel->generateDepartmentOptions(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Departure Date <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <div class="input-group date">
                            <input type="text" class="form-control regular-datepicker" id="gate_pass_departure_date" name="gate_pass_departure_date" autocomplete="off" <?php echo $disabled; ?>>
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Odometer Reading</label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="odometer_reading" name="odometer_reading" maxlength="200" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Remarks</label>
                        <div class="col-lg-7">
                            <textarea class="form-control" id="remarks" name="remarks" maxlength="1000" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="itinerary-offcanvas" aria-labelledby="itinerary-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="itinerary-offcanvas-label" style="margin-bottom:-0.5rem">Itinerary</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="itinerary-form" method="post" action="#">
          <input type="hidden" id="itinerary_id" name="itinerary_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Itinerary Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="itinerary_date" name="itinerary_date" autocomplete="off">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                    <label class="form-label">Client <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="client_id" id="client_id">
                      <option value="">--</option>
                      <?php echo $customerModel->generateAllContactsOptions(); ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                    <label class="form-label">Destination <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="itinerary_destination" name="itinerary_destination" maxlength="500" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                    <label class="form-label">Purpose <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="itinerary_purpose" name="itinerary_purpose" maxlength="500" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Expected Time of Departure (ETD) <span class="text-danger">*</span></label>
                <input class="form-control" id="expected_time_of_departure" name="expected_time_of_departure" type="time">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Expected Time of Arrival (ETA) <span class="text-danger">*</span></label>
                <input class="form-control" id="expected_time_of_arrival" name="expected_time_of_arrival" type="time">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="itinerary_remarks" name="itinerary_remarks" maxlength="1000"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-itinerary" form="itinerary-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="travel-form-reject-offcanvas" aria-labelledby="travel-form-reject-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="travel-form-reject-offcanvas-label" style="margin-bottom:-0.5rem">Reject Travel Form</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="travel-form-reject-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-travel-form-reject" form="travel-form-reject-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="travel-form-set-to-draft-offcanvas" aria-labelledby="travel-form-set-to-draft-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="travel-form-set-to-draft-offcanvas-label" style="margin-bottom:-0.5rem">Set Travel Form To Draft</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="travel-form-set-to-draft-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Set To Draft Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="set_to_draft_reason" name="set_to_draft_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-travel-form-set-to-draft" form="travel-form-set-to-draft-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
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