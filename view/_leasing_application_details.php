<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <li><a class="nav-link active" id="leasing-application-tab-1" data-bs-toggle="pill" href="#v-basic-details" role="tab" aria-controls="v-basic-details" aria-selected="true" disabled>Basic Details</a></li>
          <li><a class="nav-link" id="leasing-application-tab-2" data-bs-toggle="pill" href="#v-contact" role="tab" aria-controls="v-confirmations" aria-selected="false" disabled>Contract</a></li>
          <li><a class="nav-link" id="leasing-application-tab-3" data-bs-toggle="pill" href="#v-remarks" role="tab" aria-controls="v-remarks" aria-selected="false" disabled>Remarks</a></li>
          <li><a class="nav-link" id="leasing-application-tab-4" data-bs-toggle="pill" href="#v-summary" role="tab" aria-controls="v-summary" aria-selected="false" disabled>Summary</a></li>
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
               
                if($leasingApplicationStatus == 'Draft' && $forInitialApproval['total'] > 0){
                  echo '<div class="previous me-2 d-none" id="tag-for-approval-button">
                          <button class="btn btn-primary" id="tag-for-approval">For Approval</button>
                        </div>';
                }

                if($leasingApplicationStatus == 'For Approval' && $approveLeasingApplication['total'] > 0){
                  echo '<div class="previous me-2 d-none" id="leasing-application-approval-button">
                          <button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#leasing-application-approval-offcanvas" aria-controls="leasing-application-approval-offcanvas" id="leasing-application-approval">Approve</button>
                        </div>';
                }

                if($leasingApplicationStatus == 'For Approval' && $rejectLeasingApplication['total'] > 0){
                    echo '<div class="previous me-2 d-none" id="leasing-application-reject-button">
                                <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#leasing-application-reject-offcanvas" aria-controls="leasing-application-reject-offcanvas" id="leasing-application-reject">Reject</button>
                            </div>';
                }

                if($leasingApplicationStatus == 'Draft' && $cancelLeasingApplication['total'] > 0){
                  echo '<div class="previous me-2 d-none" id="leasing-application-cancel-button">
                          <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#leasing-application-cancel-offcanvas" aria-controls="leasing-application-cancel-offcanvas" id="leasing-application-cancel">Cancel</button>
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
            <?php
              if($leasingApplicationStatus != 'Draft'){
                echo '<div class="last">
                        <a href="javascript:void(0);" id="last-step" class="btn btn-secondary mt-3 mt-md-0">Last</a>
                      </div>';
              }
            ?>
          </div>
          <div id="bar" class="progress mb-3" style="height: 7px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%"></div>
          </div>
          <div class="tab-pane show active" id="v-basic-details">
            <form id="leasing-application-form" method="post" action="#">
              <input type="hidden" id="leasing_application_status" value="<?php echo $leasingApplicationStatus; ?>">
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Leasing Application Number :</label>
                <label class="col-lg-8 col-form-label" id="leasing_application_number">--</label>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Leasing Application Status :</label>
                <div class="col-lg-8 mt-3">
                  <?php echo $leasingApplicationStatusBadge; ?>
                </div>
              </div>
              <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Renewal Tag : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <select class="form-control select2" name="renewal_tag" id="renewal_tag">
                        <option value="">--</option>
                        <option value="New">New</option>
                        <option value="Renewal">Renewal</option>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Tenant : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <select class="form-control select2" name="tenant_id" id="tenant_id">
                        <option value="">--</option>
                        <?php echo $tenantModel->generateTenantOptions(); ?>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Property : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <select class="form-control select2" name="property_id" id="property_id">
                        <option value="">--</option>
                        <?php echo $propertyModel->generatePropertyOptions(); ?>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Start Date : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <div class="input-group date">
                        <input type="text" class="form-control regular-datepicker" id="start_date" name="start_date" autocomplete="off">
                        <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                        </span>
                    </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Maturity Date : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <input type="text" class="form-control text-uppercase" id="maturity_date" name="maturity_date" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Term : <span class="text-danger">*</span></label>
                    <div class="col-lg-2">
                    <input type="number" class="form-control" id="term_length" name="term_length" step="1" value="1" min="1">
                    </div>
                    <div class="col-lg-6">
                    <select class="form-control select2" name="term_type" id="term_type">
                        <option value="">--</option>
                        <option value="Months">Months</option>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Payment Frequency : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <select class="form-control select2" name="payment_frequency" id="payment_frequency">
                        <option value="">--</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Initial Basic Rental : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <input type="number" class="form-control" id="initial_basic_rental" name="initial_basic_rental" step="0.01" value="0" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Escalation Rate : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <input type="number" class="form-control" id="escalation_rate" name="escalation_rate" step="0.01" value="0" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Security Deposit : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <input type="number" class="form-control" id="security_deposit" name="security_deposit" step="0.01" value="0" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Floor Area : <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                    <input type="number" class="form-control" id="floor_area" name="floor_area" step="0.01" value="0" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Remarks :</label>
                    <div class="col-lg-8">
                    <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
                    </div>
                </div>
            </form>
          </div>
          <div class="tab-pane" id="v-contract">
            <div class="row">
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">New Engine Stencil </h5>
                        <?php
                          if($leasingApplicationStatus == 'For Final Approval' || $leasingApplicationStatus == 'For CI' || $leasingApplicationStatus == 'For Initial Approval' || $leasingApplicationStatus == 'Draft'){
                            echo '<button class="btn btn-info me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#leasing-application-new-engine-stencil-offcanvas" aria-controls="leasing-application-new-engine-stencil-offcanvas" id="leasing-application-new-engine-stencil">New Engine Stencil</button>';
                          }
                        ?>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Engine Stencil Image" id="new-engine-stencil-image" class="img-fluid rounded">
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
                        <h5 class="mb-0">Client Confirmation </h5>
                        <?php
                          if($leasingApplicationStatus == 'For Final Approval' || $leasingApplicationStatus == 'For CI' || $leasingApplicationStatus == 'For Initial Approval' || $leasingApplicationStatus == 'Draft'){
                            echo '<button class="btn btn-success me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#leasing-application-client-confirmation-offcanvas" aria-controls="leasing-application-client-confirmation-offcanvas" id="leasing-application-client-confirmation">Client Confirmation</button>';
                          }
                        ?>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Client Confirmation Image" id="client-confirmation-image" class="img-fluid rounded">
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
                        <h5 class="mb-0">Credit Advice</h5>
                        <?php
                          if($leasingApplicationStatus == 'For Final Approval' || $leasingApplicationStatus == 'For CI' || $leasingApplicationStatus == 'For Initial Approval' || $leasingApplicationStatus == 'Draft'){
                            if(!empty($clientConfirmation) && $transactionType == 'Bank Financing'){
                              echo '<button class="btn btn-warning me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#leasing-application-credit-advice-offcanvas" aria-controls="leasing-application-credit-advice-offcanvas" id="leasing-application-credit-advice">Credit Advice</button>';
                            }
                          }
                        ?>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Credit Advice Image" id="credit-advice-image" class="img-fluid rounded">
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
                      <li class="list-group-item px-0">
                        <h5 class="mb-0">Quality Control Form </h5>
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
            </div>
            <div class="row">
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0">
                        <h5 class="mb-0">Outgoing Checklist Form </h5>
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
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0">
                        <h5 class="mb-0">Unit Image </h5>
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
            </div>
            <div class="row">
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Additional Job Order Confirmation</h5>
                        <?php
                          if($leasingApplicationStatus != 'For DR' && $additionalJobOrderCount['total'] > 0){
                            echo '<div class="previous me-2 d-none" id="sales- proposal-job-order-confirmation-button">
                                    <button class="btn btn-warning m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales- proposal-job-order-confirmation-offcanvas" aria-controls="leasing-application-job-order-confirmation-offcanvas" id="sales- proposal-job-order-confirmation">Additional Job Order Confirmation</button>
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
          <div class="tab-pane" id="v-remarks">
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Initial Approval Remarks :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="initial_approval_remarks_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Final Approval Remarks :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="final_approval_remarks_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Release Remarks :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="release_remarks_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Set To Draft Reason :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="set_to_draft_reason_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Rejection Reason :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="rejection_reason_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Cancellation Reason :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="cancellation_reason_label"></label>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="v-summary">
            <div class="print-area">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-border-style mw-100">
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-uppercase text-wrap">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="8" class="text-center bg-danger pb-0"><h3 class="font-size-15 fw-bold text-light">SALES PROPOSAL</h3></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4"><b>No. <span id="summary-leasing-application-number"></span></b></td>
                                                        <td colspan="4" class="text-end"><b>Date: <?php echo date('d-M-Y'); ?> </b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" class="text-wrap"><small style="color:#c60206"><b>NAME OF CUSTOMER</b></small><br/><?php echo strtoupper($customerName);?></td>
                                                        <td colspan="3" class="text-wrap"><small style="color:#c60206"><b>ADDRESS</b></small><br/><?php echo strtoupper($customerAddress);?></td>
                                                        <td class="text-wrap"><small style="color:#c60206"><b>CONTACT NO.</b></small><br/><?php echo $customerMobile;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" class="text-wrap"><small style="color:#c60206"><b>CO-BORROWER/CO-MORTGAGOR/CO-MAKER</b></small><br/><span id="summary-comaker-name"></span></td>
                                                        <td colspan="3" class="text-wrap"><small style="color:#c60206"><b>ADDRESS</b></small><br/><span id="summary-comaker-address"></span></td>
                                                        <td class="text-wrap"><small style="color:#c60206"><b>CONTACT NO.</b></small><br/><span id="summary-comaker-mobile"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>REFERRED BY</b></small><br/><span id="summary-referred-by"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>ESTIMATED DATE OF RELEASE</b></small><br/><span id="summary-release-date"></span></td>
                                                        <td class="text-wrap"style="vertical-align: top !important;"><small style="color:#c60206"><b>PRODUCT TYPE</b></small><br/><span id="summary-product-type"></span></td>
                                                        <td class="text-wrap"style="vertical-align: top !important;"><small style="color:#c60206"><b>TANSACTION TYPE</b></small><br/><span id="summary-transaction-type"></span></td>
                                                        <td class="text-wrap"style="vertical-align: top !important;"><small style="color:#c60206"><b>TERM</b></small><br/><span id="summary-term"></span></td>
                                                        <td class="text-wrap"style="vertical-align: top !important;"><small style="color:#c60206"><b>NO. OF PAYMENTS</b></small><br/><span id="summary-no-payments"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-wrap"><small style="color:#c60206"><b>STOCK NO.</b></small><br/><span id="summary-stock-no"></span></td>
                                                        <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>ENGINE NO.</b></small><br/><span id="summary-engine-no"></span></td>
                                                        <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>CHASSIS NO.</b></small><br/><span id="summary-chassis-no"></span></td>
                                                        <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>PLATE NO.</b></small><br/><span id="summary-plate-no"></span></td>
                                                        <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>FOR REGISTRATION?</b></small><br/><span id="summary-for-registration"></span></td>
                                                        <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>WITH CR?</b></small><br/><span id="summary-with-cr"></span></td>
                                                        <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>FOR TRANSFER?</b></small><br/><span id="summary-for-transfer"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>FOR CHANGE COLOR?</b></small><br/><span id="summary-for-change-color"></span></td>
                                                        <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>NEW COLOR</b></small><br/><span id="summary-new-color"></span></td>
                                                        <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>FOR CHANGE BODY?</b></small><br/><span id="summary-for-change-body"></span></td>
                                                        <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>NEW BODY</b></small><br/><span id="summary-new-body"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>FOR CHANGE ENGINE?</b></small><br/><span id="summary-for-change-engine"></span></td>
                                                        <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>NEW ENGINE</b></small><br/><span id="summary-new-engine"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>DIESEL FUEL QUANTITY</b></small><br/><span id="summary-diesel-fuel-quantity"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>REGULAR FUEL QUANTITY</b></small><br/><span id="summary-regular-fuel-quantity"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b>PREMIUM FUEL QUANTITY</b></small><br/><span id="summary-premium-fuel-quantity"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8" style="padding-bottom:0 !important;" class="text-wrap"><small><b><span style="color:#c60206; margin-right: 20px;">JOB ORDER</span> TOTAL COST  <span id="summary-job-order-total"></span></b></small><br/><br/>
                                                            <div class="row pb-0 mb-0">
                                                                <div class="col-lg-12">
                                                                    <div class="table-responsive" id="summary-job-order-table"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>PRICING COMPUTATION:</b></small><br/>
                                                            <div class="row pb-0 mb-0">
                                                                <div class="col-lg-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-borderless text-sm ">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>DELIVERY PRICE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-deliver-price"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>ADD: RECONDITIONING COST</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-reconditioning-cost"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>SUB-TOTAL</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-sub-total"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>LESS: DOWNPAYMENT</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-downpayment"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>OUTSTANDING BALANCE</b></td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-outstanding-balance"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td colspan="4" style="padding-bottom:0 !important;" class="text-wrap"><small style="color:#c60206"><b>OTHER CHARGES:</b></small><br/>
                                                            <div class="row pb-0 mb-0">
                                                                <div class="col-lg-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-borderless text-sm ">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>INSURANCE COVERAGE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-insurance-coverage"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>INSURANCE PREMIUM</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-insurance-premium"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>HANDLING FEE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-handing-fee"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>TRANSFER FEE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-transfer-fee"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>REGISTRATION FEE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-registration-fee"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>DOC. STAMP TAX</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-doc-stamp-tax"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>TRANSACTION FEE</td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-transaction-fee"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><b>TOTAL</b></td>
                                                                                    <td style="border-bottom: 1px solid black !important; text-align: right!important;" id="summary-other-charges-total"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="vertical-align: top !important;" class="text-wrap">
                                                            <small><b>FOR REFERRAL TO FINANCING, PLEASE COMPUTE MO AMORTIZATION:</b></small><br/><br/>
                                                            <small style="color:#c60206"><b>AMORTIZATION NET</b></small><br/><span class="text-sm" id="summary-repayment-amount"></span><br/><br/>
                                                            <small style="color:#c60206"><b>INTEREST RATE</b></small><br/><span class="text-sm" id="summary-interest-rate"></span>
                                                        </td>
                                                        <td colspan="4" style="padding-bottom:0 !important; vertical-align: top !important;" class="text-wrap">
                                                            <small style="color:#c60206"><b>AMOUNT OF DEPOSIT:</b></small><br/><br/>
                                                            <div class="row pb-0 mb-0">
                                                                <div class="col-lg-12">
                                                                    <div class="table-responsive" id="summary-amount-of-deposit-table"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="padding-bottom:0 !important;" class="text-wrap">
                                                            <div class="row pb-0 mb-0">
                                                                <div class="col-lg-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-borderless text-sm ">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td><u>2ND YEAR</u></td>
                                                                                    <td><u>3RD YEAR</u></td>
                                                                                    <td><u>4TH YEAR</u></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>REGISTRATION</td>
                                                                                    <td id="summary-registration-second-year"></td>
                                                                                    <td id="summary-registration-third-year"></td>
                                                                                    <td id="summary-registration-fourth-year"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>INS. COVERAGE</td>
                                                                                    <td id="summary-insurance-coverage-second-year"></td>
                                                                                    <td id="summary-insurance-coverage-third-year"></td>
                                                                                    <td id="summary-insurance-coverage-fourth-year"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>INS. PREMIUM</td>
                                                                                    <td id="summary-insurance-premium-second-year"></td>
                                                                                    <td id="summary-insurance-premium-third-year"></td>
                                                                                    <td id="summary-insurance-premium-fourth-year"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td colspan="4" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>REMARKS</b></small><br/><br/><span id="summary-remarks" class="text-sm"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8" style="padding-bottom:0 !important;" class="text-wrap">
                                                            <small style="color:#c60206"><b>REQUIREMENTS:</b></small><br/><br/>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <ul>
                                                                        <li><small>PICTURE WITH SIGNATURE AT THE BACK</small></li>
                                                                        <li><small>POST-DATED CHECKS</small></li>
                                                                        <li><small>VALID ID (PHOTOCOPY)</small></li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-6">
                                                                    <ul>
                                                                        <li><small>BARANGAY CERTIFICATE</small></li>
                                                                        <li><small>BANK STATEMENT FOR THREE (3) MONTHS</small></li>
                                                                        <li><small>BUSINESS LICENSE/CERTIFICATE OF EMPLOYMENT</small></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <small style="color:#c60206"><b>ADDITIONAL REQUIREMENTS (IN CASE OF NON-INDIVIDUAL ACCOUNT):</b></small>
                                                                    <ul>
                                                                        <li><small>DTI REGISTRATION (FOR SINGLE PROPRIETORSHIP)</small></li>
                                                                        <li><small>SEC REGISTRATION (FOR CORPORATION) INCLUDING SECRETARY'S CERTIFICATE FOR AUTHORIZED SIGNATORY/IES</small></li>
                                                                        </ul>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8" style="padding-bottom:0 !important;" class="text-wrap">
                                                            <small style="color:#c60206"><b>REMINDERS:</b></small><br/>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <ol class="text-sm">
                                                                        <li><small>PRICES ARE SUBJECT TO CHANGE WITHOUT PRIOR NOTICE. FINANCING IS STILL SUBJECT FOR APPROVAL BY OUR ACCREDITED FINANCING COMPANY</small></li>
                                                                        <li><small>DOWNPAYMENT IS STRICTLY PAYABLE ON OR BEFORE RELEASE OF UNIT IN CASH OR MANAGER'S CHECK</small></li>
                                                                        <li><small>POST-DATED CHECKS SHOULD BE GIVEN ON OR BEFORE RELEASE OF UNIT OTHERWISE UNIT WILL NOT BE RELEASED</small></li>
                                                                        <li><small>ADDITIONAL POST-DATED CHECKS SHALL BE ISSUED FOR INSURANCE AND REGISTRATION RENEWAL</small></li>
                                                                        <li><small>THAT THE BUYER SHALL TAKE AND ASSUME ALL CIVIL AND/OR CRIMINAL LIABILITIES IN THE USE OF SAID VEHICLE EITHER FOR PRIVATE OR BUSINESS USE</small></li>
                                                                        <li><small>THAT THE BUYER HAS FULL KNOWLEDGE OF THE CONDITION OF THE VEHICLE UPON PURCHASE THEREOF</small></li>
                                                                        <li><small>THAT THE UNIT SUBJECT OF SALE IS NOT COVERED WITH WARRANTY AND CONVEYED ON AS-IS BASIS ONLY</small></li>
                                                                        <li><small>THAT THE SELLER IS NOT LIABLE FOR WHATEVER UNSEEN DEFECTS THAT MAY BE DISCOVERED AFTER RELEASE OF UNIT</small></li>
                                                                    </ol>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>PREPARED BY:</b></small><br/><br/><br/><small><?php
                                                            if(!empty($createdDate)){
                                                                echo 'CREATED THRU SYSTEM<br/>' . $createdDate;
                                                            }
                                                            ?></small><br/><span id="summary-created-by" class="text-sm"></span>
                                                        </td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>INITIAL APPROVAL BY:</b></small><br/><br/><br/><small><?php
                                                            if(!empty($initialApprovalDate)){
                                                                echo 'APPROVED THRU SYSTEM<br/>' . $initialApprovalDate;
                                                            }
                                                            ?></small><br/><span id="summary-approval-by" class="text-sm"></span>
                                                        </td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>FINAL APPROVAL BY:</b></small><br/><br/><br/><small><?php
                                                            if(!empty($approvalDate)){
                                                                 echo 'APPROVED THRU SYSTEM<br/>' . $approvalDate;
                                                            }
                                                            ?></small><br/><span id="summary-final-approval-by" class="text-sm"></span>
                                                        </td>
                                                        <td colspan="2" style="vertical-align: top !important;"><small><b>WITH MY CONFORMITY:</b><br/><br/><br/><br/><br/>CUSTOMER'S PRINTED NAME OVER SIGNATURE</small></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col-lg-12">
                                    <small style="color:#c60206;"><b>ADDITIONAL JOB ORDER:</b></small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-border-style">
                                        <div class="table-responsive" id="summary-additional-job-order-table"></div>
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

<?php
    echo '<div class="row"><div class="col-lg-12">
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
                  '. $userModel->generateLogNotes('leasing_application', $leasingApplicationID) .'
                </div>
              </div>
            </div>
          </div>
          </div>';
  ?>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-job-order-offcanvas" aria-labelledby="leasing-application-job-order-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Job Order</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-job-order-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12">
                <label class="form-label">Job Order <span class="text-danger">*</span></label>
                <input type="hidden" id="leasing_application_job_order_id" name="leasing_application_job_order_id">
                <input type="text" class="form-control text-uppercase" id="job_order" name="job_order" maxlength="500" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label" for="job_order_cost">Cost <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="job_order_cost" name="job_order_cost" min="0" step="0.01">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-job-order" form="leasing-application-job-order-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-deposit-amount-offcanvas" aria-labelledby="leasing-application-deposit-amount-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-deposit-amount-offcanvas-label" style="margin-bottom:-0.5rem">Amount of Deposit</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="row">
      <div class="col-lg-12">
        <form id="leasing-application-deposit-amount-form" method="post" action="#">
          <div class="form-group row">
            <div class="col-lg-12 mt-3 mt-lg-0">
              <label class="form-label">Deposit Date <span class="text-danger">*</span></label>
              <input type="hidden" id="leasing_application_deposit_amount_id" name="leasing_application_deposit_amount_id">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="deposit_date" name="deposit_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-12">
              <label class="form-label">Reference Number <span class="text-danger">*</span></label>
              <input type="text" class="form-control text-uppercase" id="reference_number" name="reference_number" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-12 mt-3 mt-lg-0">
              <label class="form-label" for="deposit_amount">Deposit Amount <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="deposit_amount" name="deposit_amount" min="0" step="0.01">
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <button type="submit" class="btn btn-primary" id="submit-leasing-application-deposit-amount" form="leasing-application-deposit-amount-form">Submit</button>
        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-additional-job-order-offcanvas" aria-labelledby="leasing-application-additional-job-order-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-additional-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Additional Job Order</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-additional-job-order-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12">
                <label class="form-label">Job Order Number <span class="text-danger">*</span></label>
                <input type="hidden" id="leasing_application_additional_job_order_id" name="leasing_application_additional_job_order_id">
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
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-additional-job-order" form="leasing-application-additional-job-order-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-client-confirmation-offcanvas" aria-labelledby="leasing-application-client-confirmation-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-client-confirmation-offcanvas-label" style="margin-bottom:-0.5rem">Client Confirmantion</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-client-confirmation-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Client Confirmation Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="client_confirmation_image" name="client_confirmation_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-client-confirmation" form="leasing-application-client-confirmation-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-new-engine-stencil-offcanvas" aria-labelledby="leasing-application-engine-stencil-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-engine-stencil-offcanvas-label" style="margin-bottom:-0.5rem">Client Confirmantion</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-engine-stencil-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">New Engine Stencil Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="new_engine_stencil_image" name="new_engine_stencil_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-engine-stencil" form="leasing-application-engine-stencil-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-credit-advice-offcanvas" aria-labelledby="leasing-application-credit-advice-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-credit-advice-offcanvas-label" style="margin-bottom:-0.5rem">Credit Advice</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-credit-advice-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Client Confirmation Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="credit_advice_image" name="credit_advice_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-credit-advice" form="leasing-application-credit-advice-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-approval-offcanvas" aria-labelledby="leasing-application-approval-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-approval-offcanvas-label" style="margin-bottom:-0.5rem">Initial Approve Leasing Application</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-approval-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Approval Remarks <span class="text-danger">*</span></label>
                <textarea class="form-control" id="initial_approval_remarks" name="initial_approval_remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
      <div class="col-lg-12">
        <button type="submit" class="btn btn-primary" id="submit-leasing-application-approval" form="leasing-application-approval-form">Submit</button>
        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-reject-offcanvas" aria-labelledby="leasing-application-reject-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-reject-offcanvas-label" style="margin-bottom:-0.5rem">Reject Leasing Application</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-reject-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-reject" form="leasing-application-reject-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-cancel-offcanvas" aria-labelledby="leasing-application-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="leasing-application-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Leasing Application</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-cancel-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-cancel" form="leasing-application-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-set-to-draft-offcanvas" aria-labelledby="leasing-application-set-to-draft-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-set-to-draft-offcanvas-label" style="margin-bottom:-0.5rem">Set Leasing Application To Draft</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-set-to-draft-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-set-to-draft" form="leasing-application-set-to-draft-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="leasing-application-final-approval-offcanvas" aria-labelledby="leasing-application-final-approval-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="leasing-application-final-approval-offcanvas-label" style="margin-bottom:-0.5rem">Proceed Leasing Application</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="leasing-application-final-approval-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Approval Remarks <span class="text-danger">*</span></label>
                <textarea class="form-control" id="final_approval_remarks" name="final_approval_remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-leasing-application-proceed" form="leasing-application-final-approval-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>