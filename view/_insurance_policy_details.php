<?php
  $insurancePolicyDetails = $insurancePolicyModel->getInsurancePolicy($insurancePolicyID);
  $status = $insurancePolicyDetails['status'] ?? 'Active';
  $expiry_date = $insurancePolicyDetails['expiry_date'];
?>

<div class="row g-4">

    <!-- POLICY OVERVIEW -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-body">

              <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                  <div>
                      <h4 class="mb-1">
                          Policy <span id="policy_number">--</span>
                      </h4>

                      <div id="policy_status_1">
                          --
                      </div>
                  </div>

                  <div class="text-end">
                      <small class="text-muted d-block">
                          Insurance Type
                      </small>
                      <strong id="insurance_type_1">--</strong>
                  </div>

                  <?php
                    $isActive = $status === 'Active';
                    $isExpired = strtotime($expiry_date) < time();

                    if ($isActive && !$isExpired) {
                        echo '
                        <div>
                            <button
                                class="btn btn-outline-danger"
                                data-bs-toggle="offcanvas" data-bs-target="#cancel-offcanvas" aria-controls="cancel-offcanvas">
                                Cancel Policy
                            </button>
                        </div>';
                    }
                  ?>
                  

              </div>

          </div>
      </div>
    </div>

    <!-- QUICK SUMMARY -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-shield-check fs-1 text-primary"></i>

                <small class="text-muted d-block mt-2">
                    Coverage Amount
                </small>

                <h3 class="mb-0 text-primary" id="coverage_amount">
                    ₱ 0.00
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-cash-stack fs-1 text-success"></i>

                <small class="text-muted d-block mt-2">
                    Premium Amount
                </small>

                <h3 class="mb-0 text-success" id="premium_amount">
                    ₱ 0.00
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar-event fs-1 text-danger"></i>

                <small class="text-muted d-block mt-2">
                    Expiry Date
                </small>

                <h3 class="mb-0 text-danger" id="expiry_date_1">
                    --
                </h3>
            </div>
        </div>
    </div>

    <!-- POLICY DETAILS -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">

            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Policy Details
                </h5>
            </div>

            <div class="card-body">

                <div class="detail-row">
                    <span>Provider</span>
                    <strong id="provider_name">--</strong>
                </div>

                <div class="detail-row">
                    <span>Inception Date</span>
                    <strong id="inception_date">--</strong>
                </div>

                <div class="detail-row">
                    <span>Expiry Date</span>
                    <strong id="expiry_date_2">--</strong>
                </div>

                <div class="detail-row">
                    <span>Insurance Type</span>
                    <strong id="insurance_type_2">--</strong>
                </div>

                <div class="detail-row">
                    <span>Policy Status</span>
                    <div id="policy_status_2"></div> 
                </div>

            </div>
        </div>
    </div>

    <!-- INSURED PARTY -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">

            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>
                    Insured Party
                </h5>
            </div>

            <div class="card-body">

                <div class="detail-row">
                    <span>Name</span>
                    <strong id="customer_name">--</strong>
                </div>

                <div class="detail-row">
                    <span>Contact Number</span>
                    <strong id="mobile">--</strong>
                </div>

                <div class="detail-row">
                    <span>Address</span>
                    <strong id="address">
                        --
                    </strong>
                </div>

            </div>
        </div>
    </div>

    <!-- VEHICLE INFORMATION -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-car-front me-2"></i>
                    Vehicle Information
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-3">
                        <small class="text-muted d-block">
                            Year Model
                        </small>
                        <strong id="year_model">--</strong>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">
                            Make
                        </small>
                        <strong id="make">--</strong>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">
                            Color
                        </small>
                        <strong id="color"></strong>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">
                            Plate Number
                        </small>
                        <strong id="plate_number">--</strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">
                            Engine Number
                        </small>
                        <strong id="engine_number">--</strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">
                            Chassis Number
                        </small>
                        <strong id="chassis_number">--</strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">
                            MV File Number
                        </small>
                        <strong id="mv_file_number">--</strong>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="cancel-offcanvas" aria-labelledby="cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="cancel-offcanvas-label" style="margin-bottom:-0.5rem">Mark as Cancelled</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="cancel-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3"> 
                <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="cancellation_reason" name="cancellation_reason"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-receive" form="cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>


<div class="row">
  
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
                '. $userModel->generateLogNotes('inquiry_type', $insurancePolicyID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>