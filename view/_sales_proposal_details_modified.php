<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <li><a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="pill" href="#v-basic-details" role="tab" aria-controls="v-basic-details" aria-selected="true" disabled>Basic Details</a></li>
          <li><a class="nav-link d-none" id="sales-proposal-tab-4" data-bs-toggle="pill" href="#v-refinancing-details" role="tab" aria-controls="v-refinancing-details" aria-selected="false" disabled>Refinancing Details</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-5" data-bs-toggle="pill" href="#v-job-order" role="tab" aria-controls="v-job-order" aria-selected="false" disabled>Job Order</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-10" data-bs-toggle="pill" href="#v-additional-job-order" role="tab" aria-controls="v-additional-job-order" aria-selected="false" disabled>Additional Job Order</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-9" data-bs-toggle="pill" href="#v-confirmations" role="tab" aria-controls="v-confirmations" aria-selected="false" disabled>Confirmations</a></li>
          <li><a class="nav-link" id="sales-proposal-tab-100" data-bs-toggle="pill" href="#v-summary" role="tab" aria-controls="v-summary" aria-selected="false" disabled>Summary</a></li>
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
                if(($salesProposalStatus == 'Proceed' || $salesProposalStatus == 'Ready For Release' || $salesProposalStatus == 'For DR') && $tagSalesProposalForOnProcess['total'] > 0){
                  echo '<div class="previous me-2 d-none" id="on-process-sales-proposal-button">
                          <button class="btn btn-info" id="on-process-sales-proposal">On-Process</button>
                        </div>';
                }

                if($salesProposalStatus == 'On-Process'){
                  if($tagSalesProposalReadyForRelease['total'] > 0 && !empty($qualityControlForm)){
                    if((($additionalJobOrderCount['total'] > 0 && !empty($additionalJobOrderConfirmation)) || $additionalJobOrderCount['total'] == 0) && $productType != 'Brand New'){
                      echo '<div class="previous me-2 d-none" id="ready-for-release-sales-proposal-button">
                      <button class="btn btn-info m-l-5" id="ready-for-release-sales-proposal">Ready For Release</button>
                    </div>';
                    }
                  }

                  echo '<div class="previous me-2 d-none" id="print-button">
                          <a href="javascript:void(0)" class="btn btn-outline-info me-1" id="print">Print</a>
                          <a href="javascript:void(0)" class="btn btn-outline-info me-1" id="print2">Print w/o Computation</a>
                      </div>';

                  echo '<div class="previous me-2 d-none" id="add-sales-proposal-additional-job-order-button">
                    <button class="btn btn-primary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-offcanvas" aria-controls="sales-proposal-additional-job-order-offcanvas" id="add-sales-proposal-additional-job-order">Add Additional Job Order</button>
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
            <div class="last">
              <a href="javascript:void(0);" id="last-step" class="btn btn-secondary mt-3 mt-md-0">Last</a>
            </div>
          </div>
          <div id="bar" class="progress mb-3" style="height: 7px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%"></div>
          </div>
          <div class="tab-pane show active" id="v-basic-details">
            <form id="sales-proposal-form" method="post" action="#">
              <input type="hidden" id="sales_proposal_status" value="<?php echo $salesProposalStatus; ?>">
              <input type="hidden" id="sales_proposal_type" value="modified">
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Sales Proposal Number :</label>
                <label class="col-lg-8 col-form-label" id="sales_proposal_number">--</label>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Sales Proposal Status :</label>
                <div class="col-lg-8 mt-3">
                  <?php echo $salesProposalStatusBadge; ?>
                </div>
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
                <label class="col-lg-4 col-form-label">Corporate Name :</label>
                <label class="col-lg-8 col-form-label"> <?php echo strtoupper($corporateName); ?></label>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Address :</label>
                <label class="col-lg-8 col-form-label"> <?php echo strtoupper($customerAddress); ?></label>
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
            </form>
          </div>
          <div class="tab-pane" id="v-job-order">
            <div class="table-responsive">
              <table id="sales-proposal-job-order-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th>Job Order</th>
                    <th>Cost</th>
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
                        <h5 class="mb-0">Quality Control Form </h5>
                        <?php
                          if($salesProposalStatus == 'On-Process'){
                            echo '<button class="btn btn-success m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-quality-control-offcanvas" aria-controls="sales-proposal-quality-control-offcanvas" id="sales-proposal-quality-control">Quality Control Form</button>';
                          }
                        ?>
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
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Outgoing Checklist </h5>
                        <?php
                          if($salesProposalStatus == 'Ready For Release' || $salesProposalStatus == 'For DR'){
                            echo '<button class="btn btn-success m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-outgoing-checklist-offcanvas" aria-controls="sales-proposal-outgoing-checklist-offcanvas" id="sales-proposal-outgoing-checklist">Outgoing Checklist</button>';
                          }
                        ?>
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
                        <h5 class="mb-0">Unit Image </h5>
                        <?php
                          if($salesProposalStatus == 'Ready For Release' || $salesProposalStatus == 'For DR'){          
                            if(($productType == 'Unit' || $productType == 'Repair')){
                              echo ' <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-unit-image-offcanvas" aria-controls="sales-proposal-unit-image-offcanvas" id="sales-proposal-unit-image">Unit Image</button>';
                            }
                          }
                        ?>
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
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-body py-2">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Additional Job Order Confirmation</h5>
                        <?php
                          if($salesProposalStatus != 'For DR' && $additionalJobOrderCount['total'] > 0){
                            echo '<div class="previous me-2" >
                                    <button class="btn btn-warning m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-confirmation-offcanvas" aria-controls="sales-proposal-job-order-confirmation-offcanvas" id="sales-proposal-job-order-confirmation">Additional Job Order Confirmation</button>
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
          <div class="tab-pane" id="v-summary">
            <div class="print-area-approved">
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
                                                        <td colspan="3"><b>No. <span id="summary-sales-proposal-number"></span></b></td>
                                                        <td colspan="2" class="text-center"><b>Status: <?php echo $salesProposalStatus; ?></b></td>
                                                        <td colspan="3" class="text-end"><b>Date: <?php echo date('d-M-Y'); ?> </b></td>
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
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>REFERRED BY</b></small><br/><span id="summary-referred-by"></span> - <span id="summary-commission"></span></td>
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206"><b>ESTIMATED DATE OF RELEASE</b></small><br/><span id="summary-release-date"></span></td>
                                                        <td class="text-wrap"style="vertical-align: top !important;"><small style="color:#c60206"><b>PRODUCT TYPE</b></small><br/><span id="summary-product-type"></span></td>
                                                        <td class="text-wrap"style="vertical-align: top !important;"><small style="color:#c60206"><b>TRANSACTION TYPE</b></small><br/><span id="summary-transaction-type"></span></td>
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
                                                        <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;"><b></b></small><br/></td>
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
                                                            ?></small><br/><span id="summary-initial-approval-by" class="text-sm"></span>
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
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-quality-control-offcanvas" aria-labelledby="sales-proposal-quality-control-offcanvas-label">
  <div class="offcanvas-header">
    <h2 id="sales-proposal-quality-control-offcanvas-label" style="margin-bottom:-0.5rem">Quality Control Form</h2>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="row">
      <div class="col-lg-12">
        <form id="sales-proposal-quality-control-form" method="post" action="#">
          <div class="form-group row">
            <div class="col-lg-12 mt-3 mt-lg-0">
              <label class="form-label">Quality Control Image <span class="text-danger">*</span></label>
              <input type="file" class="form-control" id="quality_control_image" name="quality_control_image">
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <button type="submit" class="btn btn-primary" id="submit-sales-proposal-quality-control" form="sales-proposal-quality-control-form">Submit</button>
        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
      </div>
    </div>
  </div>
</div>
        
<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-outgoing-checklist-offcanvas" aria-labelledby="sales-proposal-outgoing-checklist-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-outgoing-checklist-offcanvas-label" style="margin-bottom:-0.5rem">Outgoing Checklist</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-outgoing-checklist-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Outgoing Checklist Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="outgoing_checklist_image" name="outgoing_checklist_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-outgoing-checklist" form="sales-proposal-outgoing-checklist-form">Submit</button>
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
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-unit-image-offcanvas" aria-labelledby="sales-proposal-unit-image-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="sales-proposal-unit-image-offcanvas-label" style="margin-bottom:-0.5rem">Unit Image</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-unit-image-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Unit Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="unit_image_image" name="unit_image_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-unit-image" form="sales-proposal-unit-image-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>