<?php
   
  $updateSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 118);
  $forInitialApproval = $userModel->checkSystemActionAccessRights($user_id, 122);
  $cancelSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 123);
  $initialApproveSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 124);
  $forCISalesProposal = $userModel->checkSystemActionAccessRights($user_id, 125);
  $proceedSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 126);
  $rejectSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 127);
  $setToDraftSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 129);
  $viewSalesProposalProductCost = $userModel->checkSystemActionAccessRights($user_id, 130);
  $tagCIAsComplete = $userModel->checkSystemActionAccessRights($user_id, 135);
  $tagSalesProposalForDR = $userModel->checkSystemActionAccessRights($user_id, 134);
  $approveInstallmentSales = $userModel->checkSystemActionAccessRights($user_id, 143);
  $tagChangeRequestAsComplete = $userModel->checkSystemActionAccessRights($user_id, 145);
  $tagChangeRequestForReview = $userModel->checkSystemActionAccessRights($user_id, 166);

  $checkCustomerExist = $customerModel->checkCustomerExist($customerID);
  $total = $checkCustomerExist['total'] ?? 0;
  
  $customerDetails = $customerModel->getPersonalInformation($customerID);
  $customerName = $customerDetails['file_as'] ?? null;

  $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customerID);
  $customerAddress = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];

  $customerContactInformation = $customerModel->getCustomerPrimaryContactInformation($customerID);
  $customerMobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';
  $customerTelephone = !empty($customerContactInformation['telephone']) ? $customerContactInformation['telephone'] : '--';
  $customerEmail = !empty($customerContactInformation['email']) ? $customerContactInformation['email'] : '--';

  if($total == 0){
    header('location: 404.php');
    exit;
  }

  $checkSalesProposalExist = $salesProposalModel->checkSalesProposalExist($salesProposalID);
  $total = $checkSalesProposalExist['total'] ?? 0;

  if($total == 0){
    header('location: 404.php');
    exit;
  }

  $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);  
  $salesProposalStatus = $salesProposalDetails['sales_proposal_status'];
  $initialApprovingOfficer = $salesProposalDetails['initial_approving_officer'];
  $finalApprovingOfficer = $salesProposalDetails['final_approving_officer'];
  $creditAdvice = $salesProposalDetails['credit_advice'];
  $clientConfirmation = $salesProposalDetails['client_confirmation'];
  $transactionType = $salesProposalDetails['transaction_type'];
  $ciStatus = $salesProposalDetails['ci_status'];
  $outgoingChecklist = $salesProposalDetails['outgoing_checklist'];
  $unitImage = $salesProposalDetails['unit_image'];
  $productType = $salesProposalDetails['product_type'];
  $forRegistration = $salesProposalDetails['for_registration'];
  $forTransfer = $salesProposalDetails['for_transfer'];
  $forChangeColor = $salesProposalDetails['for_change_color'];
  $forChangeBody = $salesProposalDetails['for_change_body'];
  $forChangeEngine = $salesProposalDetails['for_change_engine'];
  $changeRequestStatus = $salesProposalDetails['change_request_status'];
  $installmentSalesStatus = $salesProposalDetails['installment_sales_status'] ?? null;
  $initialApprovalDate = $systemModel->checkDate('empty', $salesProposalDetails['initial_approval_date'], '', 'm/d/Y h:i:s a', '');
  $approvalDate = $systemModel->checkDate('empty', $salesProposalDetails['approval_date'], '', 'm/d/Y h:i:s a', '');
  $forCIDate = $systemModel->checkDate('empty', $salesProposalDetails['for_ci_date'], '', 'm/d/Y h:i:s a', '');
  if(!empty($salesProposalDetails['created_date'])){
    $createdDate = $systemModel->checkDate('empty', $salesProposalDetails['created_date'], '', 'm/d/Y h:i:s a', '');
  }
  else{
    $createdDate = $systemModel->checkDate('empty', date('m/d/Y h:i:s a'), '', 'm/d/Y h:i:s a', '');
  }
  $salesProposalStatusBadge = $salesProposalModel->getSalesProposalStatus($salesProposalStatus);
  $additionalJobOrderCount = $salesProposalModel->countSalesProposalAdditionalJobOrder($salesProposalID);
?>


<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <li><a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="pill" href="#v-basic-details" role="tab" aria-controls="v-basic-details" aria-selected="true" disabled>Basic Details</a></li>
          <li><a class="nav-link d-none" id="sales-proposal-tab-2" data-bs-toggle="pill" href="#v-unit-details" role="tab" aria-controls="v-unit-details" aria-selected="false" disabled>Unit Details</a></li>
          <li><a class="nav-link d-none" id="sales-proposal-tab-3" data-bs-toggle="pill" href="#v-fuel-details" role="tab" aria-controls="v-fuel-details" aria-selected="false" disabled>Fuel Details</a></li>
          <li><a class="nav-link d-none" id="sales-proposal-tab-4" data-bs-toggle="pill" href="#v-refinancing-details" role="tab" aria-controls="v-refinancing-details" aria-selected="false" disabled>Refinancing Details</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-5" data-bs-toggle="pill" href="#v-job-order" role="tab" aria-controls="v-job-order" aria-selected="false" disabled>Job Order</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-6" data-bs-toggle="pill" href="#v-pricing-computation" role="tab" aria-controls="v-pricing-computation" aria-selected="false" disabled>Pricing Computation</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-7" data-bs-toggle="pill" href="#v-other-charges" role="tab" aria-controls="v-other-charges" aria-selected="false" disabled>Other Charges</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-8" data-bs-toggle="pill" href="#v-renewal-amount" role="tab" aria-controls="v-renewal-amount" aria-selected="false" disabled>Renewal Amount</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-9" data-bs-toggle="pill" href="#v-amount-of-deposit" role="tab" aria-controls="v-amount-of-deposit" aria-selected="false" disabled>Amount of Deposit</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-10" data-bs-toggle="pill" href="#v-additional-job-order" role="tab" aria-controls="v-additional-job-order" aria-selected="false" disabled>Additional Job Order</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-11" data-bs-toggle="pill" href="#v-confirmations" role="tab" aria-controls="v-confirmations" aria-selected="false" disabled>Confirmations</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-12" data-bs-toggle="pill" href="#v-remarks" role="tab" aria-controls="v-remarks" aria-selected="false" disabled>Remarks</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-14" data-bs-toggle="pill" href="#v-summary" role="tab" aria-controls="v-summary" aria-selected="false" disabled>Summary</a></li>
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
                if($salesProposalStatus == 'Draft'){
                  echo '  <div class="previous me-2 d-none" id="add-sales-proposal-job-order-button">
                            <button class="btn btn-primary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-offcanvas" aria-controls="sales-proposal-job-order-offcanvas" id="add-sales-proposal-job-order">Add Job Order</button>
                          </div>';

                  echo '  <div class="previous me-2 d-none" id="add-sales-proposal-deposit-amount-button">
                            <button class="btn btn-primary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-deposit-amount-offcanvas" aria-controls="sales-proposal-deposit-amount-offcanvas" id="add-sales-proposal-deposit-amount">Add Amount of Deposit</button>
                          </div>';
                }

                if($salesProposalStatus != 'Rejected' && $salesProposalStatus != 'Cancelled' && $salesProposalStatus != 'Ready For Release' && $salesProposalStatus != 'Released'){
                  echo '  <div class="previous me-2 d-none" id="add-sales-proposal-additional-job-order-button">
                            <button class="btn btn-primary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-offcanvas" aria-controls="sales-proposal-additional-job-order-offcanvas" id="add-sales-proposal-additional-job-order">Add Additional Job Order</button>
                          </div>';
                }

                if($salesProposalStatus == 'Draft' && $tagChangeRequestForReview['total'] > 0 && !empty($clientConfirmation)){
                  echo '<div class="previous me-2 d-none" id="tag-for-review-button">
                          <button class="btn btn-primary" id="tag-for-review">For Review</button>
                        </div>';
                }

                if($salesProposalStatus == 'For Review' && $forInitialApproval['total'] > 0 && !empty($clientConfirmation)){
                  echo '<div class="previous me-2 d-none" id="tag-for-initial-approval-button">
                          <button class="btn btn-primary" id="tag-for-initial-approval">For Initial Approval</button>
                        </div>';
                }

                if($salesProposalStatus == 'For Initial Approval' && $initialApproveSalesProposal['total'] > 0 && $initialApprovingOfficer == $contact_id && !empty($clientConfirmation)){
                  echo '<div class="previous me-2 d-none" id="sales-proposal-initial-approval-button">
                          <button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-initial-approval-offcanvas" aria-controls="sales-proposal-initial-approval-offcanvas" id="sales-proposal-initial-approval">Approve</button>
                        </div>';
                }

                if(($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI') && $proceedSalesProposal['total'] > 0 && $finalApprovingOfficer == $contact_id && !empty($clientConfirmation)){
                  if($transactionType == 'COD' || $transactionType == 'Installment Sales' || ($transactionType == 'Bank Financing' && !empty($creditAdvice))){
                    echo '<div class="previous me-2 d-none" id="sales-proposal-final-approval-button">
                          <button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-final-approval-offcanvas" aria-controls="sales-proposal-final-approval-offcanvas" id="sales-proposal-final-approval">Proceed</button>
                        </div>';
                  }
                }

                if((($salesProposalStatus == 'For Initial Approval' && $initialApprovingOfficer == $contact_id) || ($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI') && $finalApprovingOfficer == $contact_id) && $rejectSalesProposal['total'] > 0){
                  echo '<div class="previous me-2 d-none" id="sales-proposal-reject-button">
                            <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-reject-offcanvas" aria-controls="sales-proposal-reject-offcanvas" id="sales-proposal-reject">Reject</button>
                        </div>';
                }

                if(($salesProposalStatus == 'Draft' || $salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For Initial Approval' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'For Review') && $cancelSalesProposal['total'] > 0){
                  echo '<div class="previous me-2 d-none" id="sales-proposal-cancel-button">
                          <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-cancel-offcanvas" aria-controls="sales-proposal-cancel-offcanvas" id="sales-proposal-cancel">Cancel</button>
                        </div>';
                }

                if($salesProposalStatus == 'For Final Approval' && $forCISalesProposal['total'] > 0 && $transactionType != 'COD' && $transactionType != 'Bank Financing'){
                  echo '<div class="previous me-2 d-none" id="for-ci-sales-proposal-button">
                          <button class="btn btn-info" id="for-ci-sales-proposal">For CI</button>
                        </div>';
                }

                if($setToDraftSalesProposal['total'] > 0 && ($salesProposalStatus != 'Released')){
                  echo '<div class="previous me-2 d-none" id="sales-proposal-set-to-draft-button">
                          <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-set-to-draft-offcanvas" aria-controls="sales-proposal-set-to-draft-offcanvas" id="sales-proposal-set-to-draft">Draft</button>
                      </div>';
                }

                if(($salesProposalStatus != 'Rejected' && $salesProposalStatus != 'Cancelled') && !empty($forCIDate) && $tagCIAsComplete['total'] > 0 && empty($ciStatus)) {
                  echo '<div class="previous me-2 d-none" id="complete-ci-button">
                          <button class="btn btn-info" id="complete-ci">Complete CI</button>
                      </div>';
                }

                if($salesProposalStatus == 'Ready For Release' || (($salesProposalStatus == 'Proceed' || $salesProposalStatus == 'On-Process') && ($productType == 'Refinancing' || $productType == 'Restructure' || $productType == 'Brand New' || $productType == 'Fuel' || $productType == 'Parts'))){
                  if($tagSalesProposalForDR['total'] > 0){
                    echo '<div class="previous me-2 d-none" id="for-dr-sales-proposal-button">
                            <button class="btn btn-success m-l-5" id="for-dr-sales-proposal">For DR</button>
                        </div>';
                  }
                }
                
                if($tagChangeRequestAsComplete['total'] > 0 && ($salesProposalStatus == 'Proceed' || $salesProposalStatus == 'On-Process' || $salesProposalStatus == 'Ready For Release' || $salesProposalStatus == 'For DR' || $salesProposalStatus == 'Released') && ($forRegistration == 'Yes' || $forTransfer == 'Yes' || $forChangeColor == 'Yes' || $forChangeBody == 'Yes' || $forChangeEngine == 'Yes') && $changeRequestStatus != 'Completed'){
                  echo '<div class="previous me-2" id="sales-proposal-change-request-button">
                          <button class="btn btn-success m-l-5" id="sales-proposal-change-request">Tag As Complete</button>
                      </div>';
                }

                echo '<div class="previous me-2 d-none" id="print-button">
                          <a href="javascript:void(0)" class="btn btn-outline-info me-1" id="print">Print</a>
                          <a href="javascript:void(0)" class="btn btn-outline-info me-1" id="print2">Print w/o Computation</a>
                      </div>';
              ?>
              <div class="previous me-2">
                <a href="javascript:void(0);" id="previous-step" class="btn btn-secondary disabled">Back To Previous</a>
              </div>
              <div class="next">
                <a href="javascript:void(0);" id="next-step" class="btn btn-secondary mt-3 mt-md-0">Next Step</a>
              </div>
            </div>
            <?php
              if($salesProposalStatus != 'Draft'){
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
            <form id="sales-proposal-form" method="post" action="#">
              <input type="hidden" id="sales_proposal_status" value="<?php echo $salesProposalStatus; ?>">
              <input type="hidden" id="sales_proposal_type" value="details">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Sales Proposal Number :</label>
                    <label class="col-lg-8 col-form-label" id="sales_proposal_number">--</label>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Client ID :</label>
                    <label class="col-lg-8 col-form-label" id="customer-id"> <?php echo $customerID; ?></label>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Client Name :</label>
                    <label class="col-lg-8 col-form-label"> <?php echo strtoupper($customerName); ?></label>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Address :</label>
                    <label class="col-lg-8 col-form-label"> <?php echo strtoupper($customerAddress); ?></label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Sales Proposal Status :</label>
                    <div class="col-lg-8 mt-3">
                      <?php echo $salesProposalStatusBadge; ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Email :</label>
                    <label class="col-lg-8 col-form-label"> <?php echo strtoupper($customerEmail); ?></label>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Mobile :</label>
                    <label class="col-lg-8 col-form-label"> <?php echo $customerMobile; ?></label>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-4 col-form-label">Telephone :</label>
                    <label class="col-lg-8 col-form-label"> <?php echo $customerTelephone; ?></label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Renewal Tag : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <select class="form-control select2" name="renewal_tag" id="renewal_tag">
                        <option value="">--</option>
                        <option value="New">New</option>
                        <option value="Renewal">Renewal</option>
                      </select>
                    </div>
                  </div>
              <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Application Source : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <select class="form-control select2" name="application_source_id" id="application_source_id">
                        <option value="">--</option>
                        <?php echo $applicationSourceModel->generateApplicationSourceOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Company : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <select class="form-control select2" name="company_id" id="company_id">
                        <option value="">--</option>
                        <option value="1">Christian General Motors Inc.</option>
                        <option value="2">NE Truck Builders</option>
                        <option value="3">FUSO Tarlac</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Product Type : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <select class="form-control select2" name="product_type" id="product_type">
                        <option value="">--</option>
                        <option value="Unit">Unit</option>
                        <option value="Fuel">Fuel</option>
                        <option value="Parts">Parts</option>
                        <option value="Repair">Repair</option>
                        <option value="Rental">Rental</option>
                        <option value="Brand New">Brand New</option>
                        <option value="Refinancing">Refinancing</option>
                        <option value="Restructure">Restructure</option>
                        <option value="Real Estate">Real Estate</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Transaction Type : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <select class="form-control select2" name="transaction_type" id="transaction_type">
                        <option value="">--</option>
                        <option value="COD">COD</option>
                        <option value="Installment Sales">Installment Sales</option>
                        <option value="Bank Financing">Bank Financing</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Financing Institution :</label>
                    <div class="col-lg-7">
                      <input type="text" class="form-control text-uppercase" id="financing_institution" name="financing_institution" maxlength="200" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Co-Maker :</label>
                    <div class="col-lg-7">
                    <select class="form-control select2" name="comaker_id" id="comaker_id">
                        <option value="">--</option>
                        <?php echo $customerModel->generateComakerOptions($customerID); ?>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Additional Maker :</label>
                    <div class="col-lg-7">
                    <select class="form-control select2" name="additional_maker_id" id="additional_maker_id">
                        <option value="">--</option>
                        <?php echo $customerModel->generateComakerOptions($customerID); ?>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Additional Co-Maker 2 :</label>
                    <div class="col-lg-7">
                    <select class="form-control select2" name="comaker_id2" id="comaker_id2">
                        <option value="">--</option>
                        <?php echo $customerModel->generateComakerOptions($customerID); ?>
                    </select>
                    </div>
                </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Referred By :</label>
                    <div class="col-lg-7">
                      <input type="text" class="form-control text-uppercase" id="referred_by" name="referred_by" maxlength="100" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Commission Amount : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <input type="text" class="form-control currency" id="commission_amount" name="commission_amount">
                    </div>
                  </div>                  
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Estimated Date of Release : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <div class="input-group date">
                        <input type="text" class="form-control regular-datepicker" id="release_date" name="release_date" autocomplete="off">
                        <span class="input-group-text">
                          <i class="feather icon-calendar"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Start Date : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <div class="input-group date">
                        <input type="text" class="form-control regular-datepicker" id="start_date" name="start_date" autocomplete="off">
                        <span class="input-group-text">
                          <i class="feather icon-calendar"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Term : <span class="text-danger">*</span></label>
                    <div class="col-lg-3">
                      <input type="number" class="form-control" id="term_length" name="term_length" step="1" value="1" min="1">
                    </div>
                    <div class="col-lg-4">
                      <select class="form-control select2" name="term_type" id="term_type">
                        <option value="">--</option>
                        <option value="Days">Days</option>
                        <option value="Months">Months</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Number of Payments : <span class="text-danger">*</span></label>
                    <div class="col-lg-3">
                      <input type="text" class="form-control text-uppercase" id="number_of_payments" name="number_of_payments" autocomplete="off" readonly>
                    </div>
                    <div class="col-lg-4">
                      <select class="form-control select2" name="payment_frequency" id="payment_frequency">
                        <option value="">--</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">First Due Date : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <input type="text" class="form-control text-uppercase" id="first_due_date" name="first_due_date" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Initial Approving Officer : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <select class="form-control select2" name="initial_approving_officer" id="initial_approving_officer">
                        <option value="">--</option>
                        <?php echo $approvingOfficerModel->generateApprovingOfficerOptions('Initial'); ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-5 col-form-label">Final Approving Officer : <span class="text-danger">*</span></label>
                    <div class="col-lg-7">
                      <select class="form-control select2" name="final_approving_officer" id="final_approving_officer">
                        <option value="">--</option>
                        <?php echo $approvingOfficerModel->generateApprovingOfficerOptions('Final'); ?>
                      </select>
                    </div>
                  </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Remarks :</label>
                <div class="col-lg-7">
                  <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane" id="v-unit-details">
            <form id="sales-proposal-unit-details-form" method="post" action="#">
            <div class="row">
              <div class="col-lg-6">
                <input type="hidden" id="product_category">
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Stock : <span class="text-danger">*</span></label>
                  <div class="col-lg-7">
                    <select class="form-control select2" name="product_id" id="product_id">
                      <option value="">--</option>
                      <?php 
                        if($salesProposalStatus == 'Draft' || $salesProposalStatus == 'For Review' || $salesProposalStatus == 'For Initial Approval'){
                          echo $productModel->generateForSaleProductOptions(); 
                        }
                        else if($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'Proceed' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'On-Process' || $salesProposalStatus == 'For DR' || $salesProposalStatus == 'Read For Release'){
                          echo $productModel->generateWithApplicationProductOptions(); 
                        }
                        else{
                          echo $productModel->generateAllProductOptions(); 
                        }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Engine Number :</label>
                  <label class="col-lg-7 col-form-label" id="product_engine_number"></label>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Chassis Number :</label>
                  <label class="col-lg-7 col-form-label" id="product_chassis_number"></label>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Plate Number :</label>
                  <label class="col-lg-7 col-form-label" id="product_plate_number"></label>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">For Registration? : <span class="text-danger">*</span></label>
                  <div class="col-lg-7 mt-2">
                    <select class="form-control select2" name="for_registration" id="for_registration">
                      <option value="">--</option>
                      <option value="Yes" selected>Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">With CR? : <span class="text-danger">*</span></label>
                  <div class="col-lg-7">
                    <select class="form-control select2" name="with_cr" id="with_cr">
                      <option value="">--</option>
                      <option value="Yes" selected>Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">For Transfer? : <span class="text-danger">*</span></label>
                  <div class="col-lg-7">
                    <select class="form-control select2" name="for_transfer" id="for_transfer">
                      <option value="">--</option>
                      <option value="Yes" selected>Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">For Change Color? : <span class="text-danger">*</span></label>
                  <div class="col-lg-7">
                    <select class="form-control select2" name="for_change_color" id="for_change_color">
                      <option value="">--</option>
                      <option value="Yes" selected>Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Old Color :</label>
                  <div class="col-lg-7">
                    <input type="text" class="form-control text-uppercase" id="old_color" autocomplete="off" readonly>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">For Change Body? : <span class="text-danger">*</span></label>
                  <div class="col-lg-7">
                    <select class="form-control select2" name="for_change_body" id="for_change_body">
                      <option value="">--</option>
                      <option value="Yes" selected>Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Old Body :</label>
                  <div class="col-lg-7">
                    <input type="text" class="form-control text-uppercase" id="old_body" autocomplete="off" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">New Body :</label>
                  <div class="col-lg-7">
                    <input type="text" class="form-control text-uppercase" id="new_body" name="new_body" maxlength="100" autocomplete="off">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">For Change Engine? : <span class="text-danger">*</span></label>
                  <div class="col-lg-7 mt-2">
                    <select class="form-control select2" name="for_change_engine" id="for_change_engine">
                      <option value="">--</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Old Engine :</label>
                  <div class="col-lg-7">
                    <input type="text" class="form-control text-uppercase" id="old_engine" autocomplete="off" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">New Engine :</label>
                  <div class="col-lg-7">
                    <input type="text" class="form-control text-uppercase" id="new_engine" name="new_engine" maxlength="100" autocomplete="off" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">Final Name On OR/CR :</label>
                  <div class="col-lg-7">
                    <input type="text" class="form-control text-uppercase" id="final_orcr_name" name="final_orcr_name" maxlength="500" autocomplete="off">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-lg-5 col-form-label">New Color :</label>
                  <div class="col-lg-7">
                    <input type="text" class="form-control text-uppercase" id="new_color" name="new_color" maxlength="100" autocomplete="off">
                  </div>
                </div>
              </div>
            </div>
            </form>
          </div>
          <div class="tab-pane" id="v-fuel-details">
            <form id="sales-proposal-fuel-details-form" method="post" action="#">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Diesel Fuel Quantity : <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                      <div class="input-group">
                        <input type="text" class="form-control currency" id="diesel_fuel_quantity" name="diesel_fuel_quantity">
                        <span class="input-group-text">lt</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Diesel Price/Liter : <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                      <input type="text" class="form-control currency" id="diesel_price_per_liter" name="diesel_price_per_liter">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Sub-Total :</label>
                    <label class="col-lg-6 col-form-label" id="diesel_total">--</label>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Regular Fuel Quantity : <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                      <div class="input-group">
                        <input type="text" class="form-control currency" id="regular_fuel_quantity" name="regular_fuel_quantity">
                        <span class="input-group-text">lt</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Regular Price/Liter : <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                      <input type="text" class="form-control currency" id="regular_price_per_liter" name="regular_price_per_liter">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Sub-Total :</label>
                    <label class="col-lg-6 col-form-label" id="regular_total">--</label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Premium Fuel Quantity : <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                      <div class="input-group">
                        <input type="text" class="form-control currency" id="premium_fuel_quantity" name="premium_fuel_quantity">
                        <span class="input-group-text">lt</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Premium Price/Liter : <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                      <input type="text" class="form-control currency" id="premium_price_per_liter" name="premium_price_per_liter">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Sub-Total :</label>
                    <label class="col-lg-6 col-form-label" id="premium_total">--</label>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Total :</label>
                    <label class="col-lg-6 col-form-label" id="fuel_total">--</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane" id="v-refinancing-details">
            <form id="sales-proposal-refinancing-details-form" method="post" action="#">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Stock Number :</label>
                    <label class="col-lg-6 col-form-label" id="ref_stock_no">--</label>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Engine Number : <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                      <input type="text" class="form-control text-uppercase" id="ref_engine_no" name="ref_engine_no" maxlength="100" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Chassis Number : <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                      <input type="text" class="form-control text-uppercase" id="ref_chassis_no" name="ref_chassis_no" maxlength="100" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">Plate Number : <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                      <input type="text" class="form-control text-uppercase" id="ref_plate_no" name="ref_plate_no" maxlength="100" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">OR/CR Number : </label>
                    <div class="col-lg-6">
                      <input type="text" class="form-control" id="orcr_no" name="orcr_no" maxlength="200" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-6 col-form-label">OR/CR Date : </label>
                    <div class="col-lg-6">
                      <div class="input-group date">
                        <input type="text" class="form-control regular-datepicker" id="orcr_date" name="orcr_date" autocomplete="off">
                        <span class="input-group-text">
                          <i class="feather icon-calendar"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group row">
                      <label class="col-lg-7 col-form-label">OR/CR Expiry Date : </label>
                      <div class="col-lg-5">
                        <div class="input-group date">
                          <input type="text" class="form-control regular-datepicker" id="orcr_expiry_date" name="orcr_expiry_date" autocomplete="off">
                          <span class="input-group-text">
                            <i class="feather icon-calendar"></i>
                          </span>
                        </div>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-lg-7 col-form-label">Received From : </label>
                      <div class="col-lg-5">
                        <input type="text" class="form-control" id="received_from" name="received_from" maxlength="500" autocomplete="off">
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-lg-7 col-form-label">Received From Address : </label>
                      <div class="col-lg-5">
                        <input type="text" class="form-control" id="received_from_address" name="received_from_address" maxlength="1000" autocomplete="off">
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-lg-7 col-form-label">Received From ID Type : </label>
                      <div class="col-lg-5">
                        <select class="form-control select2" name="received_from_id_type" id="received_from_id_type">
                            <option value="">--</option>
                            <?php echo $idTypeModel->generateIDTypeOptions(); ?>
                        </select>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-7 col-form-label">Received From ID Number : </label>
                    <div class="col-lg-5">
                      <input type="text" class="form-control" id="received_from_id_number" name="received_from_id_number" maxlength="200" autocomplete="off">
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Description : </label>
                <div class="col-lg-10">
                  <textarea class="form-control" id="unit_description" name="unit_description" maxlength="1000" rows="3"></textarea>
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane" id="v-job-order">
            <div class="table-responsive">
              <table id="sales-proposal-job-order-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th>Job Order</th>
                    <th>Cost</th>
                    <th>Progress</th>
                    <th>Approval Document</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="v-pricing-computation">
            <form id="sales-proposal-pricing-computation-form" method="post" action="#">
            <?php
                if($viewSalesProposalProductCost['total'] > 0){
                  echo '<div class="form-group row">
                  <label class="col-lg-6 col-form-label">Product Cost :</label>
                  <div class="col-lg-6">
                    <label class="col-form-label" id="product_cost_label"></label>
                  </div>
                </div>';
                }
              ?>
              <div class="row">
            <div class="col-lg-6">
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Deliver Price (AS/IS) : <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="delivery_price" name="delivery_price">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Add-On : <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="add_on_charge" name="add_on_charge">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Nominal Discount : <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="nominal_discount" name="nominal_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Total Delivery Price : <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="total_delivery_price" name="total_delivery_price" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Interest Rate : <span class="text-danger">*</span></label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="interest_rate" name="interest_rate">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Cost of Accessories :</label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="cost_of_accessories" name="cost_of_accessories">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Reconditioning Cost :</label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="reconditioning_cost" name="reconditioning_cost">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Sub-Total :</label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="subtotal" name="subtotal" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Downpayment :</label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="downpayment" name="downpayment">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Outstanding Balance :</label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="outstanding_balance" name="outstanding_balance" readonly>
                </div>
              </div>
              <div class="form-group row d-none">
                <label class="col-lg-6 col-form-label">Amount Financed :</label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="amount_financed" name="amount_financed" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">PN Amount :</label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="pn_amount" name="pn_amount" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-6 col-form-label">Repayment Amount :</label>
                <div class="col-lg-6">
                  <input type="text" class="form-control currency" id="repayment_amount" name="repayment_amount" readonly>
                </div>
              </div>
            </div>
          </div>
            </form>
          </div>
          <div class="tab-pane" id="v-other-charges">
            <form id="sales-proposal-other-charges-form" method="post" action="#">
            <div class="row">
            <div class="col-lg-6">
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Insurance Coverage :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="insurance_coverage" name="insurance_coverage">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Insurance Premium :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="insurance_premium" name="insurance_premium" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Insurance Premium Discount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="insurance_premium_discount" name="insurance_premium_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Insurance Premium Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="insurance_premium_subtotal" name="insurance_premium_subtotal" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Handling Fee :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="handling_fee" name="handling_fee" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Handling Fee Discount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="handling_fee_discount" name="handling_fee_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Handling Fee Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="handling_fee_subtotal" name="handling_fee_subtotal" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transfer Fee :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transfer_fee" name="transfer_fee" value="12000" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transfer Fee Discount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transfer_fee_discount" name="transfer_fee_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transfer Fee Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transfer_fee_subtotal" name="transfer_fee_subtotal" readonly>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Registration Fee :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="registration_fee" name="registration_fee">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Doc. Stamp Tax :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="doc_stamp_tax" name="doc_stamp_tax" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Doc. Stamp Tax Discount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="doc_stamp_tax_discount" name="doc_stamp_tax_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Doc. Stamp Tax Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="doc_stamp_tax_subtotal" name="doc_stamp_tax_subtotal" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transaction Fee :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transaction_fee" name="transaction_fee" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transaction Fee Discount :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transaction_fee_discount" name="transaction_fee_discount">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Transaction Fee Sub-Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="transaction_fee_subtotal" name="transaction_fee_subtotal" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-5 col-form-label">Total :</label>
                <div class="col-lg-7">
                  <input type="text" class="form-control currency" id="total_other_charges" name="total_other_charges" readonly>
                </div>
              </div>
            </div>
          </div>
            </form>
          </div>
          <div class="tab-pane" id="v-renewal-amount">
            <form id="sales-proposal-renewal-amount-form" method="post" action="#">
              <div class="row">
              <div class="col-lg-12">
                <table class="table table-borderless table-xl">
                  <thead>
                    <tr class="text-center">
                      <th></th>
                      <th>2nd Year</th>
                      <th>3rd Year</th>
                      <th>4th Year</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td></td>
                      <td style="horizontal-align: center !important">
                      <div class="form-check form-switch mb-2 text-center">
                          <input type="checkbox" class="form-check-input input-primary" id="compute_second_year">
                        </div>
                      </td>
                      <td style="horizontal-align: center !important">
                        <div class="form-check form-switch mb-2 text-center">
                          <input type="checkbox" class="form-check-input input-primary" id="compute_third_year">
                        </div>
                      </td>
                      <td style="horizontal-align: center !important">
                        <div class="form-check form-switch mb-2 text-center">
                          <input type="checkbox" class="form-check-input input-primary" id="compute_fourth_year">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>Registration</td>
                      <td><input type="text" class="form-control currency" id="registration_second_year" name="registration_second_year"></td>
                      <td><input type="text" class="form-control currency" id="registration_third_year" name="registration_third_year"></td>
                      <td><input type="text" class="form-control currency" id="registration_fourth_year" name="registration_fourth_year"></td>
                    </tr>
                    <tr>
                      <td>Ins. Coverage</td>
                      <td><input type="text" class="form-control currency" id="insurance_coverage_second_year" name="insurance_coverage_second_year" readonly></td>
                      <td><input type="text" class="form-control currency" id="insurance_coverage_third_year" name="insurance_coverage_third_year" readonly></td>
                      <td><input type="text" class="form-control currency" id="insurance_coverage_fourth_year" name="insurance_coverage_fourth_year" readonly></td>
                    </tr>
                    <tr>
                      <td>Ins. Premium</td>
                      <td><input type="text" class="form-control currency" id="insurance_premium_second_year" name="insurance_premium_second_year" readonly></td>
                      <td><input type="text" class="form-control currency" id="insurance_premium_third_year" name="insurance_premium_third_year" readonly></td>
                      <td><input type="text" class="form-control currency" id="insurance_premium_fourth_year" name="insurance_premium_fourth_year" readonly></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            </form>
          </div>
          <div class="tab-pane" id="v-amount-of-deposit">
            <div class="table-responsive">
              <table id="sales-proposal-deposit-amount-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Reference No.</th>
                    <th>Amount</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="v-additional-job-order">
            <div class="table-responsive">
              <table id="sales-proposal-additional-job-order-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th>Job Order Number</th>
                    <th>Job Order Date</th>
                    <th>Particulars</th>
                    <th>Cost</th>
                    <th>Progress</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="v-confirmations">
            <div class="row">
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Client Confirmation </h5>
                        <?php
                          if($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'For Initial Approval' || $salesProposalStatus == 'Draft' || $salesProposalStatus == 'For Review'){
                            echo '<button class="btn btn-success me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-client-confirmation-offcanvas" aria-controls="sales-proposal-client-confirmation-offcanvas" id="sales-proposal-client-confirmation">Client Confirmation</button>';
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
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Comaker Confirmation </h5>
                        <?php
                          if($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'For Initial Approval' || $salesProposalStatus == 'Draft' || $salesProposalStatus == 'For Review'){
                            echo '<button class="btn btn-success me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-comaker-confirmation-offcanvas" aria-controls="sales-proposal-comaker-confirmation-offcanvas" id="sales-proposal-comaker-confirmation">Comaker Confirmation</button>';
                          }
                        ?>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Comaker Confirmation Image" id="comaker-confirmation-image" class="img-fluid rounded">
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
                        <h5 class="mb-0">New Engine Stencil </h5>
                        <?php
                          if($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'For Initial Approval' || $salesProposalStatus == 'Draft' || $salesProposalStatus == 'For Review'){
                            echo '<button class="btn btn-info me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-new-engine-stencil-offcanvas" aria-controls="sales-proposal-new-engine-stencil-offcanvas" id="sales-proposal-new-engine-stencil">New Engine Stencil</button>';
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
                        <h5 class="mb-0">Credit Advice / Purchase Order</h5>
                        <?php
                          if($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'For Initial Approval' || $salesProposalStatus == 'Draft' || $salesProposalStatus == 'For Review' || $salesProposalStatus == 'For DR'){
                            if($transactionType == 'Bank Financing'){
                              echo '<button class="btn btn-warning me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-credit-advice-offcanvas" aria-controls="sales-proposal-credit-advice-offcanvas" id="sales-proposal-credit-advice">Credit Advice</button>';
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
            </div>
            <div class="row">
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
            </div>
            <div class="row">
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Additional Job Order Confirmation</h5>
                        <?php
                          if($salesProposalStatus != 'For DR' && $additionalJobOrderCount['total'] > 0){
                            echo '<button class="btn btn-warning me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-confirmation-offcanvas" aria-controls="sales-proposal-job-order-confirmation-offcanvas" id="sales-proposal-job-order-confirmation">Additional Job Order Confirmation</button>';
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
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0">
                        <h5 class="mb-0">Unit (Front)</h5>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Front" id="unit-image" class="img-fluid rounded">
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
                        <h5 class="mb-0">Unit (Back)</h5>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Back" id="unit-back" class="img-fluid rounded">
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
                        <h5 class="mb-0">Unit (Left Side)</h5>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Left" id="unit-left" class="img-fluid rounded">
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
                        <h5 class="mb-0">Unit (Right Side)</h5>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Right" id="unit-right" class="img-fluid rounded">
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
                        <h5 class="mb-0">Unit (Interior)</h5>
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Interior" id="unit-interior" class="img-fluid rounded">
                          </div>                      
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-12">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Other Document</h5>
                        <?php
                          if($salesProposalStatus != 'Rejected' && $salesProposalStatus != 'Cancelled' && $salesProposalStatus != 'Released'){
                            echo '<button class="btn btn-warning me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-other-document-offcanvas" aria-controls="sales-proposal-other-document-offcanvas" id="sales-proposal-other-document">Other Document</button>';
                          }
                        ?>
                          
                      </li>
                      <li class="list-group-item px-0">
                        <div class="row align-items-center mb-3">
                          <div class="col-sm-12 mb-sm-0">
                            <embed id="other-document-file" width="100%" height="600" type="application/pdf" />
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
              <label class="col-lg-5 col-form-label">Installment Sales Approval Remarks :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="installment_sales_approval_remarks_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Release Remarks :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="release_remarks_label"></label>
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
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Set To Draft Reason :</label>
              <div class="col-lg-7">
                <label class="col-form-label" id="set_to_draft_reason_label"></label>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-5 col-form-label">Set To Draft File :</label>
              <div class="col-lg-7">
                <embed id="draft-file" width="100%" height="600" type="application/pdf" />
              </div>
            </div>
          </div>
          <div class="tab-pane" id="v-summary">
            <?php include_once('_sales_proposal_summary_print.php'); ?>
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
                  '. $userModel->generateLogNotes('sales_proposal', $salesProposalID) .'
                </div>
              </div>
            </div>
          </div>
          </div>';
  ?>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-job-order-offcanvas" aria-labelledby="sales-proposal-job-order-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Job Order</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-job-order-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12">
                <label class="form-label">Job Order <span class="text-danger">*</span></label>
                <input type="hidden" id="sales_proposal_job_order_id" name="sales_proposal_job_order_id">
                <input type="hidden" id="job_order_type" name="job_order_type">
                <input type="text" class="form-control text-uppercase" id="job_order" name="job_order" maxlength="500" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label" for="job_order_cost">Cost <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="job_order_cost" name="job_order_cost" min="0" step="0.01">
              </div>
            </div>
            <div class="form-group row" >
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Approval Document</label>
                <input type="file" class="form-control" id="approval_document" name="approval_document">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-job-order" form="sales-proposal-job-order-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-deposit-amount-offcanvas" aria-labelledby="sales-proposal-deposit-amount-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-deposit-amount-offcanvas-label" style="margin-bottom:-0.5rem">Amount of Deposit</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="row">
      <div class="col-lg-12">
        <form id="sales-proposal-deposit-amount-form" method="post" action="#">
          <div class="form-group row">
            <div class="col-lg-12 mt-3 mt-lg-0">
              <label class="form-label">Deposit Date <span class="text-danger">*</span></label>
              <input type="hidden" id="sales_proposal_deposit_amount_id" name="sales_proposal_deposit_amount_id">
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
        <button type="submit" class="btn btn-primary" id="submit-sales-proposal-deposit-amount" form="sales-proposal-deposit-amount-form">Submit</button>
        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-additional-job-order-offcanvas" aria-labelledby="sales-proposal-additional-job-order-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-additional-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Additional Job Order</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-additional-job-order-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12">
                <label class="form-label">Job Order Number <span class="text-danger">*</span></label>
                <input type="hidden" id="sales_proposal_additional_job_order_id" name="sales_proposal_additional_job_order_id">
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
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-additional-job-order" form="sales-proposal-additional-job-order-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-client-confirmation-offcanvas" aria-labelledby="sales-proposal-client-confirmation-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-client-confirmation-offcanvas-label" style="margin-bottom:-0.5rem">Client Confirmantion</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-client-confirmation-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-client-confirmation" form="sales-proposal-client-confirmation-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-comaker-confirmation-offcanvas" aria-labelledby="sales-proposal-client-confirmation-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-comaker-confirmation-offcanvas-label" style="margin-bottom:-0.5rem">Co-maker Confirmantion</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-comaker-confirmation-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Comaker Confirmation Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="comaker_confirmation_image" name="comaker_confirmation_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-comaker-confirmation" form="sales-proposal-comaker-confirmation-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-job-order-confirmation-offcanvas" aria-labelledby="sales-proposal-job-order-confirmation-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-job-order-confirmation-offcanvas-label" style="margin-bottom:-0.5rem">Additional Job Order Confirmation</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-job-order-confirmation-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Additional Job Order Confirmation Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="additional_job_order_confirmation_image" name="additional_job_order_confirmation_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
        <button type="submit" class="btn btn-primary" id="submit-sales-proposal-additional-job-order-confirmation" form="sales-proposal-job-order-confirmation-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-other-document-offcanvas" aria-labelledby="sales-proposal-other-document-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-other-document-offcanvas-label" style="margin-bottom:-0.5rem">Other Document</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-other-document-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Other Document</label>
                <input type="file" class="form-control" id="other_document_file" name="other_document_file">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-other-document-form" form="sales-proposal-other-document-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-new-engine-stencil-offcanvas" aria-labelledby="sales-proposal-engine-stencil-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-engine-stencil-offcanvas-label" style="margin-bottom:-0.5rem">Client Confirmantion</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-engine-stencil-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-engine-stencil" form="sales-proposal-engine-stencil-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-credit-advice-offcanvas" aria-labelledby="sales-proposal-credit-advice-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-credit-advice-offcanvas-label" style="margin-bottom:-0.5rem">Credit Advice</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-credit-advice-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-credit-advice" form="sales-proposal-credit-advice-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-initial-approval-offcanvas" aria-labelledby="sales-proposal-initial-approval-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-initial-approval-offcanvas-label" style="margin-bottom:-0.5rem">Initial Approve Sales Proposal</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-initial-approval-form" method="post" action="#">
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
        <button type="submit" class="btn btn-primary" id="submit-sales-proposal-initial-approval" form="sales-proposal-initial-approval-form">Submit</button>
        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-reject-offcanvas" aria-labelledby="sales-proposal-reject-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-reject-offcanvas-label" style="margin-bottom:-0.5rem">Reject Sales Proposal</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-reject-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-reject" form="sales-proposal-reject-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-cancel-offcanvas" aria-labelledby="sales-proposal-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="sales-proposal-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Sales Proposal</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-cancel-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-cancel" form="sales-proposal-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-set-to-draft-offcanvas" aria-labelledby="sales-proposal-set-to-draft-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-set-to-draft-offcanvas-label" style="margin-bottom:-0.5rem">Set Sales Proposal To Draft</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-set-to-draft-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Set To Draft Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="set_to_draft_reason" name="set_to_draft_reason" maxlength="500"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Set To Draft File</label>
                <input type="file" class="form-control" id="set_to_draft_file" name="set_to_draft_file">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-set-to-draft" form="sales-proposal-set-to-draft-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-final-approval-offcanvas" aria-labelledby="sales-proposal-final-approval-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-final-approval-offcanvas-label" style="margin-bottom:-0.5rem">Proceed Sales Proposal</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-final-approval-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-proceed" form="sales-proposal-final-approval-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>