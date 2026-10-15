<?php
  $insurancePolicyDetails = $insurancePolicyModel->getInsurancePolicy($insurancePolicyID);
  $status = $insurancePolicyDetails['status'] ?? 'Active';
  $expiry_date = $insurancePolicyDetails['expiry_date'];
  $insurance_request_id = $insurancePolicyDetails['insurance_request_id'] ?? null;
  $remittance_status = $insurancePolicyDetails['remittance_status'] ?? 'Pending';
  $payment_status = $insurancePolicyDetails['payment_status'] ?? 'Unpaid';
?>

<div class="row g-4">

    <!-- POLICY OVERVIEW -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-body">

              <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <!-- Policy Information -->
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h4 class="mb-0">
                            Policy <span id="policy_number">--</span>
                        </h4>

                        <span id="policy_status_1" class="small">
                            --
                        </span>
                    </div>
                </div>


                <!-- Policy Metadata -->
                <div class="d-flex align-items-center gap-4">

                    <div>
                        <small class="text-muted d-block">
                            Insurance Type
                        </small>
                        <strong id="insurance_type_1">
                            --
                        </strong>
                    </div>

                </div>


                <!-- Actions -->
                <div class="d-flex flex-wrap justify-content-lg-end gap-2">

                    <?php
                    $isActive = $status === 'Active';
                    $isExpired = strtotime($expiry_date) < time();

                    if (!empty($remittance_status) && $remittance_status === 'Pending') {
                        echo '
                            <button
                                type="button"
                                class="btn btn-outline-warning"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#remit-offcanvas"
                                aria-controls="remit-offcanvas"
                                id="remit-insurance-policy-details">
                                <i class="bi bi-check2-circle me-1"></i>
                                Tag as Remitted
                            </button>';
                    }

                    if (!empty($payment_status) && $payment_status === 'Unpaid') {
                        echo '
                            <button
                                type="button"
                                class="btn btn-outline-success"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#collect-offcanvas"
                                aria-controls="collect-offcanvas"
                                id="collect-insurance-policy-details">
                                <i class="bi bi-cash-coin me-1"></i>
                                Tag as Collected
                            </button>';
                    }

                    if (!empty($insurance_request_id)) {
                        $insuranceRequestIDEncrypted = $securityModel->encryptData($insurance_request_id);

                        echo '
                            <a
                                href="insurance-request.php?id=' . $insuranceRequestIDEncrypted . '"
                                class="btn btn-outline-info"
                                target="_blank">
                                <i class="bi bi-file-earmark-text me-1"></i>
                                View Request
                            </a>';
                    }

                    if ($isActive && !$isExpired) {
                        echo '
                            <button
                                type="button"
                                class="btn btn-outline-danger"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#cancel-offcanvas"
                                aria-controls="cancel-offcanvas">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancel Policy
                            </button>';
                    }
                    ?>

                </div>

            </div>

          </div>
      </div>
    </div>

    <!-- QUICK SUMMARY -->
    <div class="col-md-3">
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

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-cash-stack fs-1 text-success"></i>

                <small class="text-muted d-block mt-2">
                    Gross Amount
                </small>

                <h3 class="mb-0 text-success" id="gross_amount">
                    ₱ 0.00
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-cash-stack fs-1 text-success"></i>

                <small class="text-muted d-block mt-2">
                    Net Premium Amount
                </small>

                <h3 class="mb-0 text-success" id="net_premium_amount">
                    ₱ 0.00
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
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

    <!-- POLICY INFORMATION -->
    <!-- MAIN INFORMATION -->
    <div class="row g-4">

        <!-- =========================
            POLICY DETAILS
        ========================== -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">

                <div class="card-header  border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box me-3">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>

                        <div>
                            <h5 class="mb-0 fw-semibold">
                                Policy Details
                            </h5>
                            <small class="text-muted">
                                Policy and coverage information
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <!-- POLICY OVERVIEW -->
                    <div class="section-title">
                        <i class="bi bi-info-circle me-2"></i>
                        Policy Overview
                    </div>

                    <div class="row g-4 mb-2">

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Provider</div>
                                <div class="detail-value" id="provider_name">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Insurance Type</div>
                                <div class="detail-value" id="insurance_type_2">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Inception Date</div>
                                <div class="detail-value" id="inception_date">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Expiry Date</div>
                                <div class="detail-value" id="expiry_date_2">
                                    --
                                </div>
                            </div>
                        </div>

                    </div>


                    <!-- POLICY STATUS -->
                    <div class="section-title border-top pt-4">
                        <i class="bi bi-shield-check me-2"></i>
                        Policy Status
                    </div>

                    <div class="row g-4 mb-2">

                        <div class="col-md-6">
                            <div class="status-card">
                                <div class="detail-label">
                                    Policy Status
                                </div>

                                <div id="policy_status_2">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Assured
                                </div>

                                <div class="detail-value" id="assured">
                                    --
                                </div>
                            </div>
                        </div>

                    </div>


                    <!-- PAYMENT & REMITTANCE -->
                    <div class="section-title border-top pt-4">
                        <i class="bi bi-wallet2 me-2"></i>
                        Payment & Remittance
                    </div>

                    <div class="row g-4">

                        <div class="col-md-6">
                            <div class="status-card">
                                <div class="detail-label">
                                    Collection Status
                                </div>

                                <div id="collection_status">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Collection Date
                                </div>

                                <div class="detail-value" id="collection_date">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Paid By
                                </div>

                                <div class="detail-value" id="paid_by">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="status-card">
                                <div class="detail-label">
                                    Remittance Status
                                </div>

                                <div id="remittance_status">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Remittance Date
                                </div>

                                <div class="detail-value" id="remittance_date">
                                    --
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- RIGHT COLUMN -->
        <div class="col-lg-4">

            <!-- =========================
                INSURED PARTY
            ========================== -->
            <div class="card border-0 shadow-sm mb-2">

                <div class="card-header  border-bottom py-3">

                    <div class="d-flex align-items-center">

                        <div class="icon-box me-3">
                            <i class="bi bi-person"></i>
                        </div>

                        <div>
                            <h5 class="mb-0 fw-semibold">
                                Insured Party
                            </h5>

                            <small class="text-muted">
                                Customer information
                            </small>
                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <!-- CUSTOMER -->
                    <div class="customer-profile mb-2">
                        <div class="min-width-0">

                            <div class="detail-label">
                                Insured Name
                            </div>

                            <div
                                class="fw-semibold fs-5 text-truncate"
                                id="customer_name">
                                --
                            </div>

                        </div>

                    </div>


                    <!-- CONTACT -->
                    <div class="detail-item border-top pt-4 mb-2">

                        <div class="detail-label">
                            <i class="bi bi-telephone me-1"></i>
                            Contact Number
                        </div>

                        <div class="detail-value" id="mobile">
                            --
                        </div>

                    </div>


                    <!-- ADDRESS -->
                    <div class="detail-item border-top pt-4">

                        <div class="detail-label">
                            <i class="bi bi-geo-alt me-1"></i>
                            Address
                        </div>

                        <div class="detail-value address-value" id="address">
                            --
                        </div>

                    </div>

                </div>
            </div>


            <!-- =========================
                VEHICLE INFORMATION
            ========================== -->
            <div class="card border-0 shadow-sm">

                <div class="card-header  border-bottom py-3">

                    <div class="d-flex align-items-center">

                        <div class="icon-box me-3">
                            <i class="bi bi-car-front"></i>
                        </div>

                        <div>
                            <h5 class="mb-0 fw-semibold">
                                Vehicle Information
                            </h5>

                            <small class="text-muted">
                                Vehicle and registration details
                            </small>
                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <!-- VEHICLE DETAILS -->
                    <div class="section-title mb-3">
                        <i class="bi bi-car-front-fill me-2"></i>
                        Vehicle Details
                    </div>

                    <div class="row g-3 mb-2">

                        <div class="col-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Year Model
                                </div>

                                <div class="detail-value" id="year_model">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Make
                                </div>

                                <div class="detail-value" id="make">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Color
                                </div>

                                <div class="detail-value" id="color">
                                    --
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="detail-item">
                                <div class="detail-label">
                                    Plate Number
                                </div>

                                <div
                                    class="detail-value fw-bold"
                                    id="plate_number">
                                    --
                                </div>
                            </div>
                        </div>

                    </div>


                    <!-- VEHICLE IDENTIFICATION -->
                    <div class="section-title border-top pt-4 mb-3">
                        <i class="bi bi-upc-scan me-2"></i>
                        Identification
                    </div>

                    <div class="detail-item mb-3">

                        <div class="detail-label">
                            Engine Number
                        </div>

                        <div
                            class="detail-value text-break"
                            id="engine_number">
                            --
                        </div>

                    </div>

                    <div class="detail-item mb-3">

                        <div class="detail-label">
                            Chassis Number
                        </div>

                        <div
                            class="detail-value text-break"
                            id="chassis_number">
                            --
                        </div>

                    </div>

                    <div class="detail-item">

                        <div class="detail-label">
                            MV File Number
                        </div>

                        <div
                            class="detail-value text-break"
                            id="mv_file_number">
                            --
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
  <div class="offcanvas offcanvas-end" tabindex="-1" id="collect-offcanvas" aria-labelledby="collect-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="collect-offcanvas-label" style="margin-bottom:-0.5rem">Tag as Collected</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="collect-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3"> 
                <label class="form-label">Collection Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="payment_date" name="payment_date" autocomplete="off">
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
          <button type="submit" class="btn btn-primary" id="submit-receive" form="collect-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="remit-offcanvas" aria-labelledby="remit-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="remit-offcanvas-label" style="margin-bottom:-0.5rem">Tag as Collected</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="remit-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3"> 
                <label class="form-label">Remittance Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="remittance_date" name="remittance_date" autocomplete="off">
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
          <button type="submit" class="btn btn-primary" id="submit-receive" form="remit-form">Submit</button>
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
                '. $userModel->generateLogNotes('insurance_policy', $insurancePolicyID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>