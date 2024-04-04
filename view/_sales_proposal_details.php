<?php
  $tabDisabled = '';
  if($salesProposalStatus == 'Draft'){
    $tabDisabled = 'disabled';
  }
?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body p-0">
        <ul class="nav nav-tabs checkout-tabs mb-0" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="tab" href="#details-tab" role="tab" aria-controls="details-tab" aria-selected="true" <?php echo $tabDisabled; ?>>
              <div class="media align-items-center">
                <div class="avtar avtar-s">
                  <i class="ti ti-file-text"></i>
                </div>
                <div class="media-body ms-2">
                  <h5 class="mb-0">Details</h5>
                </div>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="sales-proposal-tab-2" data-bs-toggle="tab" href="#job-order-tab" role="tab" aria-controls="job-order-tab" aria-selected="true" <?php echo $tabDisabled; ?>>
              <div class="media align-items-center">
                <div class="avtar avtar-s">
                  <i class="ti ti-clipboard"></i>
                </div>
                <div class="media-body ms-2">
                  <h5 class="mb-0">Job Order</h5>
                </div>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="sales-proposal-tab-3" data-bs-toggle="tab" href="#other-charges-tab" role="tab" aria-controls="other-charges-tab" aria-selected="true" <?php echo $tabDisabled; ?>>
              <div class="media align-items-center">
                <div class="avtar avtar-s">
                  <i class="ti ti-credit-card"></i>
                </div>
                <div class="media-body ms-2">
                  <h5 class="mb-0">Other Charges</h5>
                </div>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="sales-proposal-tab-4" data-bs-toggle="tab" href="#additional-job-order-tab" role="tab" aria-controls="additional-job-order-tab" aria-selected="true" <?php echo $tabDisabled; ?>>
              <div class="media align-items-center">
                <div class="avtar avtar-s">
                  <i class="ti ti-file-plus"></i>
                </div>
                <div class="media-body ms-2">
                  <h5 class="mb-0">Additional Job Order</h5>
                </div>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="sales-proposal-tab-5" data-bs-toggle="tab" href="#image-tab" role="tab" aria-controls="image-tab" aria-selected="true" <?php echo $tabDisabled; ?>>
              <div class="media align-items-center">
                <div class="avtar avtar-s">
                  <i class="ti ti-photo"></i>
                </div>
                <div class="media-body ms-2">
                  <h5 class="mb-0">Images</h5>
                </div>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="sales-proposal-tab-6" data-bs-toggle="tab" href="#summary-tab" role="tab" aria-controls="summary-tab" aria-selected="true" <?php echo $tabDisabled; ?>>
              <div class="media align-items-center">
                <div class="avtar avtar-s">
                  <i class="ti ti-printer"></i>
                </div>
                <div class="media-body ms-2">
                  <h5 class="mb-0">Summary</h5>
                </div>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="tab-content">
      <div class="tab-pane show active" id="details-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-1">
        <div class="card-body">
          <div class="row">
            <div class="col-xl-12">
              <div class="card">
                <div class="card-header">
                  <div class="row align-items-center my-2">
                    <div class="col">
                      <div class="progress" style="height: 6px">
                        <div class="progress-bar bg-primary" style="width: 16.66%"></div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <p class="mb-0 h6">Step 1</p>
                    </div>
                    <div class="col-auto">
                      <?php
                        if($salesProposalStatus == 'Draft'){
                          echo '<button type="button" class="btn btn-primary" id="next-step-1">Next</button>';
                        }
                        else{
                          echo '<button type="button" class="btn btn-primary" id="next-step-1-normal">Next</button>';
                        }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-8">
              <div class="card">
                <div class="card-body border-bottom">
                  <div class="row align-items-center">
                    <div class="col">
                      <h5 class="mb-0">Details</h5>
                    </div>
                    <div class="col-auto">
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <?php
                    if($salesProposalStatus == 'Draft'){
                      echo '<form id="sales-proposal-form" method="post" action="#">
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Sales Proposal Number :</label>
                                <label class="col-lg-8 col-form-label" id="sales_proposal_number">--</label>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Sales Proposal Status : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  '. $salesProposalStatusBadge .'
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Product Type : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <select class="form-control select2" name="product_type" id="product_type">
                                    <option value="">--</option>
                                    <option value="Unit">Unit</option>
                                    <option value="Fuel">Fuel</option>
                                    <option value="Parts">Parts</option>
                                    <option value="Repair">Repair</option>
                                    <option value="Refinancing">Refinancing</option>
                                    <option value="Real Estate">Real Estate</option>
                                  </select>
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
                              <div class="form-group row d-none unit-row">
                                <label class="col-lg-4 col-form-label">Stock : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <select class="form-control select2" name="product_id" id="product_id">
                                    <option value="">--</option>
                                    '. $productModel->generateInStockProductOptions() .'
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row d-none fuel-row">
                                <label class="col-lg-4 col-form-label">Fuel Type : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <select class="form-control select2" multiple="multiple" name="fuel_type[]" id="fuel_type">
                                    <option value="Diesel">Diesel</option>
                                    <option value="Regular">Regular</option>
                                    <option value="Premium">Premium</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row d-none fuel-row">
                                <label class="col-lg-4 col-form-label">Fuel Quantity : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <div class="form-group">
                                      <div class="input-group">
                                        <input type="number" class="form-control" id="fuel_quantity" name="fuel_quantity" step="0.01" min="0.01">
                                        <span class="input-group-text">lt</span>
                                      </div>
                                    </div>
                                </div>
                              </div>
                              <div class="form-group row d-none fuel-row">
                                <label class="col-lg-4 col-form-label">Price/Liter : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <input type="number" class="form-control" id="price_per_liter" name="price_per_liter" step="0.01" min="0.01">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Transaction Type : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <select class="form-control select2" name="transaction_type" id="transaction_type">
                                    <option value="">--</option>
                                    <option value="COD">COD</option>
                                    <option value="Installment Sales">Installment Sales</option>
                                    <option value="Bank Financing">Bank Financing</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row d-none" id="financing-institution-row">
                                <label class="col-lg-4 col-form-label">Financing Institution : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                <input type="text" class="form-control text-uppercase" id="financing_institution" name="financing_institution" maxlength="200" autocomplete="off">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Co-Maker :</label>
                                <div class="col-lg-8">
                                  <select class="form-control select2" name="comaker_id" id="comaker_id">
                                    <option value="">--</option>
                                    '. $customerModel->generateComakerOptions($customerID) .'
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Referred By :</label>
                                <div class="col-lg-8">
                                  <input type="text" class="form-control text-uppercase" id="referred_by" name="referred_by" maxlength="100" autocomplete="off">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Commission Amount : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <input type="number" class="form-control" id="commission_amount" name="commission_amount" step="0.01" min="0">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Estimated Date of Release : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="release_date" name="release_date" autocomplete="off">
                                    <span class="input-group-text">
                                      <i class="feather icon-calendar"></i>
                                    </span>
                                  </div>
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
                                <label class="col-lg-4 col-form-label">Term : <span class="text-danger">*</span></label>
                                <div class="col-lg-4">
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
                                <label class="col-lg-4 col-form-label">Number of Payments : <span class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                  <input type="text" class="form-control text-uppercase" id="number_of_payments" name="number_of_payments" autocomplete="off" readonly>
                                </div>
                                <div class="col-lg-4">
                                  <select class="form-control select2" name="payment_frequency" id="payment_frequency">
                                    <option value="">--</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">First Due Date : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <input type="text" class="form-control text-uppercase" id="first_due_date" name="first_due_date" readonly>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Initial Approving Officer : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <select class="form-control select2" name="initial_approving_officer" id="initial_approving_officer">
                                    <option value="">--</option>
                                    '. $approvingOfficerModel->generateApprovingOfficerOptions('Initial') .'
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Final Approving Officer : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <select class="form-control select2" name="final_approving_officer" id="final_approving_officer">
                                    <option value="">--</option>
                                    '. $approvingOfficerModel->generateApprovingOfficerOptions('Final') .'
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">For Registration? : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <select class="form-control select2" name="for_registration" id="for_registration">
                                    <option value="">--</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">With CR? : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <select class="form-control select2" name="with_cr" id="with_cr">
                                    <option value="">--</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">For Transfer? : <span class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                  <select class="form-control select2" name="for_transfer" id="for_transfer">
                                    <option value="">--</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">For Change Color? : <span class="text-danger">*</span></label>
                                <div class="col-lg-2">
                                  <select class="form-control select2" name="for_change_color" id="for_change_color">
                                    <option value="">--</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                  </select>
                                </div>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control text-uppercase" id="old_color" autocomplete="off" readonly>
                                </div>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control text-uppercase" id="new_color" name="new_color" maxlength="100" autocomplete="off" readonly>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">For Change Body? : <span class="text-danger">*</span></label>
                                <div class="col-lg-2">
                                  <select class="form-control select2" name="for_change_body" id="for_change_body">
                                    <option value="">--</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                  </select>
                                </div>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control text-uppercase" id="old_body" autocomplete="off" readonly>
                                </div>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control text-uppercase" id="new_body" name="new_body" maxlength="100" autocomplete="off" readonly>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">For Change Engine? : <span class="text-danger">*</span></label>
                                <div class="col-lg-2">
                                  <select class="form-control select2" name="for_change_engine" id="for_change_engine">
                                    <option value="">--</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                  </select>
                                </div>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control text-uppercase" id="old_engine" autocomplete="off" readonly>
                                </div>
                                <div class="col-lg-3">
                                  <input type="text" class="form-control text-uppercase" id="new_engine" name="new_engine" maxlength="100" autocomplete="off" readonly>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Remarks :</label>
                                <div class="col-lg-8">
                                  <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
                                </div>
                              </div>
                            </form>';
                    }
                    else{
                      echo '<div class="form-group row">
                              <label class="col-lg-4 col-form-label">Sales Proposal Number :</label>
                              <label class="col-lg-8 col-form-label" id="sales_proposal_number">--</label>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Sales Proposal Status :</label>
                              <div class="col-lg-8">
                                '. $salesProposalStatusBadge .'
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Product Type :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="product_type_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label">Renewal Tag : </label>
                                <div class="col-lg-8">
                                <label class="col-form-label" id="renewal_tag_label"></label>
                                </div>
                              </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Stock :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="product_id_label"></label>
                                <label class="col-form-label d-none" id="product_id_details"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Fuel Type :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="fuel_type_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Fuel Quantity :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="fuel_quantity_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Price Per Liter :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="price_per_liter_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Transaction Type :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="transaction_type_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Financing Institution :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="financing_institution_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Co-Maker :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="comaker_id_label"></label>
                                <label class="col-form-label d-none" id="comaker_id_details"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Referred By :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="referred_by_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Commission Amount :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="commission_amount_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Estimated Date of Release :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="release_date_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Start Date :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="start_date_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Term :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="term_length_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Number of Payments :</label>
                              <div class="col-lg-2">
                                <label class="col-form-label" id="number_of_payments_label"></label>
                              </div>
                              <div class="col-lg-6">
                                <label class="col-form-label" id="payment_frequency_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">First Due Date :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="first_due_date_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Initial Approving Officer :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="initial_approving_officer_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Final Approving Officer :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="final_approving_officer_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">For Registration? :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="for_registration_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">With CR? :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="with_cr_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">For Transfer? :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="for_transfer_label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">For Change Color? :</label>
                              <div class="col-lg-2">
                                <label class="col-form-label" id="for_change_color_label"></label>
                              </div>
                              <div class="col-lg-3">
                                <label class="col-form-label">Old Color:<br/> <span id="old_color_label"></span></label>
                              </div>
                              <div class="col-lg-3">
                                <label class="col-form-label">New Color:<br/> <span id="new_color_label"></span></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">For Change Body? :</label>
                              <div class="col-lg-2">
                                <label class="col-form-label" id="for_change_body_label"></label>
                              </div>
                              <div class="col-lg-3">
                                <label class="col-form-label">Old Body:<br/> <span id="old_body_label"></span></label>
                              </div>
                              <div class="col-lg-3">
                                <label class="col-form-label">New Body:<br/> <span id="new_body_label"></span></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">For Change Engine? :</label>
                              <div class="col-lg-2">
                                <label class="col-form-label" id="for_change_engine_label"></label>
                              </div>
                              <div class="col-lg-3">
                                <label class="col-form-label">Old Engine:<br/> <span id="old_engine_label"></span></label>
                              </div>
                              <div class="col-lg-3">
                                <label class="col-form-label">New Engine:<br/> <span id="new_engine_label"></span></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-4 col-form-label">Remarks :</label>
                              <div class="col-lg-8">
                                <label class="col-form-label" id="remarks_label"></label>
                              </div>
                            </div>';
                    }
                  ?>
                </div>
              </div>
              <div class="card">
                <div class="card-body py-2">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                      <h5 class="mb-0">Sales Proposal Other Details</h5>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Created By</p>
                        </div>
                        <div class="col-sm-6" id="created-by"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Inital Approval By</p>
                        </div>
                        <div class="col-sm-6" id="initial-approval-by"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Inital Approval Remarks</p>
                        </div>
                        <div class="col-sm-6" id="initial-approval-remarks"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Inital Approval Date</p>
                        </div>
                        <div class="col-sm-6" id="initial-approval-date"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Proceed By</p>
                        </div>
                        <div class="col-sm-6" id="approval-by"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Proceed Remarks</p>
                        </div>
                        <div class="col-sm-6" id="final-approval-remarks"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Proceed Date</p>
                        </div>
                        <div class="col-sm-6" id="final-approval-date"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">For CI Date</p>
                        </div>
                        <div class="col-sm-6" id="for-ci-date"></div>
                      </div>
                    </li>
                    
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Rejection Reason</p>
                        </div>
                        <div class="col-sm-6" id="rejection-reason"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Rejection Date</p>
                        </div>
                        <div class="col-sm-6" id="rejection-date"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Cancellation Reason</p>
                        </div>
                        <div class="col-sm-6" id="cancellation-reason"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Cancellation Date</p>
                        </div>
                        <div class="col-sm-6" id="cancellation-date"></div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Set To Draft Reason</p>
                        </div>
                        <div class="col-sm-6" id="set-to-draft-reason"></div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-xl-4">
              <div class="card">
                <div class="card-body py-2">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                      <h5 class="mb-0">Customer Details</h5>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Customer ID</p>
                        </div>
                        <div class="col-sm-6" id="customer-id">
                          <?php echo $customerID; ?>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Full Name</p>
                        </div>
                        <div class="col-sm-6">
                          <?php echo $customerName; ?>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Address</p>
                        </div>
                        <div class="col-sm-6">
                          <?php echo $customerAddress; ?>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-6">
                          <?php echo $customerEmail; ?>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Mobile</p>
                          </div>
                        <div class="col-sm-6">
                          <?php echo $customerMobile; ?>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Telephone</p>
                        </div>
                        <div class="col-sm-6">
                          <?php echo $customerTelephone; ?>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card">
                <div class="card-body py-2">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                      <h5 class="mb-0">Co-Maker Details</h5>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Full Name</p>
                        </div>
                        <div class="col-sm-6" id="comaker_file_as">
                          --
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Address</p>
                        </div>
                        <div class="col-sm-6" id="comaker_address">
                          --
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-6" id="comaker_email">
                          --
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Mobile</p>
                        </div>
                        <div class="col-sm-6" id="comaker_mobile">
                          --
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Telephone</p>
                        </div>
                        <div class="col-sm-6" id="comaker_telephone">
                          --
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card">
                <div class="card-body py-2">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                      <h5 class="mb-0">Product Details </h5>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Engine Number</p>
                        </div>
                        <div class="col-sm-6" id="product_engine_number">
                          --
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Chassis Number</p>
                        </div>
                        <div class="col-sm-6" id="product_chassis_number">
                          --
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item px-0">
                      <div class="row align-items-center mb-3">
                        <div class="col-sm-6 mb-sm-0">
                          <p class="mb-0">Plate Number</p>
                        </div>
                        <div class="col-sm-6" id="product_plate_number">
                          --
                        </div>
                       </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="job-order-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-2">
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center my-2">
                  <div class="col">
                    <div class="progress" style="height: 6px">
                      <div class="progress-bar bg-primary" style="width: 33.33%"></div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <p class="mb-0 h6">Step 2</p>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-warning" id="prev-step-2">Previous</button>
                    <button class="btn btn-primary" id="next-step-2">Next</button>
                  </div>
                </div>
              </div>
              <div class="card-body border-bottom">
                <div class="row align-items-center">
                  <div class="col">
                    <h5 class="mb-0">Job Order</h5>
                  </div>
                  <div class="col-auto">
                    <?php
                      if($salesProposalStatus == 'Draft'){
                        echo '<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-offcanvas" aria-controls="sales-proposal-job-order-offcanvas" id="add-sales-proposal-job-order">Add Job Order</button>';
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body p-0 table-body mb-4">
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
            </div>
            <div class="card">
              <div class="card-body py-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item px-0">
                    <div class="float-end">
                      <h5 class="mb-0" id="sales-proposal-job-order-total">--</h5>
                    </div>
                    <h5 class="mb-0 d-inline-block">Total</h5>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="other-charges-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-3">
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center my-2">
                  <div class="col">
                    <div class="progress" style="height: 6px">
                      <div class="progress-bar bg-primary" style="width: 49.99%"></div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <p class="mb-0 h6">Step 3</p>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-warning" id="prev-step-3">Previous</button>     
                    <?php
                      if($salesProposalStatus == 'Draft'){
                        echo ' <button type="submit" for="sales-proposal-pricing-computation-form" class="btn btn-primary" id="next-step-3">Next</button>';
                      }
                      else{
                        echo ' <button type="button" class="btn btn-primary" id="next-step-3-normal">Next</button>';
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-6">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h5 class="mb-0">Pricing Computation</h5>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?php
                  if($salesProposalStatus == 'Draft'){
                    echo '<form id="sales-proposal-pricing-computation-form" method="post" action="#">
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Deliver Price (AS/IS) : <span class="text-danger">*</span></label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="delivery_price" name="delivery_price" step="0.01" min="0.01">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Nominal Discount : <span class="text-danger">*</span></label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="nominal_discount" name="nominal_discount" step="0.01" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Total Delivery Price : <span class="text-danger">*</span></label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="total_delivery_price" name="total_delivery_price" step="0.01" min="0.01" readonly>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Interest Rate : <span class="text-danger">*</span></label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="interest_rate" name="interest_rate" step="0.01" value="0" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Cost of Accessories :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="cost_of_accessories" name="cost_of_accessories" step="0.01" value="0" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Reconditioning Cost :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="reconditioning_cost" name="reconditioning_cost" step="0.01" value="0" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Sub-Total :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="subtotal" name="subtotal" step="0.01" value="0" min="0" readonly>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Downpayment :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="downpayment" name="downpayment" step="0.01" value="0" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Outstanding Balance :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="outstanding_balance" name="outstanding_balance" step="0.01" value="0" min="0" readonly>
                              </div>
                            </div>
                            <div class="form-group row d-none">
                              <label class="col-lg-5 col-form-label">Amount Financed :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="amount_financed" name="amount_financed" step="0.01" value="0" min="0" readonly>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">PN Amount :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="pn_amount" name="pn_amount" step="0.01" value="0" min="0" readonly>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Repayment Amount :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="repayment_amount" name="repayment_amount" step="0.01" value="0" min="0" readonly>
                              </div>
                            </div>
                          </form>';
                  }
                  else{
                    $productCostField = '';
                    if($viewSalesProposalProductCost['total'] > 0){
                      $productCostField = '<div class="form-group row">
                      <label class="col-lg-5 col-form-label">Product Cost :</label>
                      <div class="col-lg-7">
                        <label class="col-form-label" id="product_cost_label"></label>
                      </div>
                    </div>';
                    }

                    echo '<div class="form-group row">
                            <label class="col-lg-5 col-form-label">Deliver Price (AS/IS) :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="delivery_price_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Nominal Discount :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="nominal_discount_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Total Delivery Price :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="total_delivery_price_label"></label>
                            </div>
                          </div>
                          '. $productCostField .'
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Interest Rate :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="interest_rate_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Cost of Accessories :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="cost_of_accessories_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Reconditioning Cost :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="reconditioning_cost_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Sub-Total :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="subtotal_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Downpayment :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="downpayment_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Outstanding Balance :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="outstanding_balance_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Amount Financed :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="amount_financed_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">PN Amount :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="pn_amount_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Repayment Amount :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="repayment_amount_label"></label>
                            </div>
                          </div>';
                  }
                ?>
              </div>
            </div>                  
          </div>
          <div class="col-xl-6">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h5 class="mb-0">Other Charges</h5>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?php
                  if($salesProposalStatus == 'Draft'){
                    echo '<form id="sales-proposal-other-charges-form" method="post" action="#">
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Insurance Coverage :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="insurance_coverage" name="insurance_coverage" step="0.01" value="0" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Insurance Premium :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="insurance_premium" name="insurance_premium" step="0.01" value="0" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Handling Fee :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="handling_fee" name="handling_fee" step="0.01" value="0" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Transfer Fee :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="transfer_fee" name="transfer_fee" step="0.01" value="0" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Registration Fee :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="registration_fee" name="registration_fee" step="0.01" value="0" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Doc. Stamp Tax :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="doc_stamp_tax" name="doc_stamp_tax" step="0.01" value="0" min="0">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Transaction Fee :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="transaction_fee" name="transaction_fee" step="0.01" value="0" min="0">
                              </div>
                              </div>
                            <div class="form-group row">
                              <label class="col-lg-5 col-form-label">Total :</label>
                              <div class="col-lg-7">
                                <input type="number" class="form-control" id="total_other_charges" name="total_other_charges" step="0.01" value="0" min="0" readonly>
                              </div>
                            </div>
                          </form>';
                  }
                  else{
                    echo '<div class="form-group row">
                            <label class="col-lg-5 col-form-label">Insurance Coverage :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="insurance_coverage_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Insurance Premium :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="insurance_premium_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Handling Fee :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="handling_fee_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Transfer Fee :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="transfer_fee_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Registration Fee :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="registration_fee_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Doc. Stamp Tax :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="doc_stamp_tax_label"></label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Transaction Fee :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="transaction_fee_label"></label>
                            </div>
                            </div>
                          <div class="form-group row">
                            <label class="col-lg-5 col-form-label">Total :</label>
                            <div class="col-lg-7">
                              <label class="col-form-label" id="total_other_charges_label"></label>
                            </div>
                          </div>';
                  }
                ?>
              </div>
            </div>                  
          </div>
        </div>
        <div class="row">
          <div class="col-xl-6">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h5 class="mb-0">Renewal Amount</h5>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <input type="hidden" id="product_category">
                <?php
                  if($salesProposalStatus == 'Draft'){
                    echo '<form id="sales-proposal-renewal-amount-form" method="post" action="#">
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
                                  <td style="horizontal-align: center !important"><div class="form-check form-switch mb-2 text-center">
                                        <input type="checkbox" class="form-check-input input-primary" id="compute_second_year">
                                      </div>
                                  </td>
                                  <td style="horizontal-align: center !important"><div class="form-check form-switch mb-2 text-center">
                                        <input type="checkbox" class="form-check-input input-primary" id="compute_third_year">
                                      </div>
                                  </td>
                                  <td style="horizontal-align: center !important"><div class="form-check form-switch mb-2 text-center">
                                        <input type="checkbox" class="form-check-input input-primary" id="compute_fourth_year">
                                      </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td>Registration</td>
                                  <td><input type="number" class="form-control" id="registration_second_year" name="registration_second_year" step="0.01" value="0" min="0"></td>
                                  <td><input type="number" class="form-control" id="registration_third_year" name="registration_third_year" step="0.01" value="0" min="0"></td>
                                  <td><input type="number" class="form-control" id="registration_fourth_year" name="registration_fourth_year" step="0.01" value="0" min="0"></td>
                                </tr>
                                <tr>
                                  <td>Ins. Coverage</td>
                                  <td><input type="number" class="form-control" id="insurance_coverage_second_year" name="insurance_coverage_second_year" step="0.01" value="0" readonly></td>
                                  <td><input type="number" class="form-control" id="insurance_coverage_third_year" name="insurance_coverage_third_year" step="0.01" value="0" readonly></td>
                                  <td><input type="number" class="form-control" id="insurance_coverage_fourth_year" name="insurance_coverage_fourth_year" step="0.01" value="0" readonly></td>
                                </tr>
                                <tr>
                                    <td>Ins. Premium</td>
                                    <td><input type="number" class="form-control" id="insurance_premium_second_year" name="insurance_premium_second_year" step="0.01" value="0" readonly></td>
                                    <td><input type="number" class="form-control" id="insurance_premium_third_year" name="insurance_premium_third_year" step="0.01" value="0" readonly></td>
                                    <td><input type="number" class="form-control" id="insurance_premium_fourth_year" name="insurance_premium_fourth_year" step="0.01" value="0" readonly></td>
                                </tr>
                              </tbody>
                            </table>
                          </form>';
                  }
                  else{
                    echo '<table class="table table-borderless table-xl">
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
                                <td>Registration</td>
                                <td><label class="col-form-label" id="registration_second_year_label"></label></td>
                                <td><label class="col-form-label" id="registration_third_year_label"></label></td>
                                <td><label class="col-form-label" id="registration_fourth_year_label"></label></td>
                              </tr>
                              <tr>
                                <td>Ins. Coverage</td>
                                <td><label class="col-form-label" id="insurance_coverage_second_year_label"></label></td>
                                <td><label class="col-form-label" id="insurance_coverage_third_year_label"></label></td>
                                <td><label class="col-form-label" id="insurance_coverage_fourth_year_label"></label></td>
                              </tr>
                              <tr>
                                  <td>Ins. Premium</td>
                                  <td><label class="col-form-label" id="insurance_premium_second_year_label"></label></td>
                                  <td><label class="col-form-label" id="insurance_premium_third_year_label"></label></td>
                                  <td><label class="col-form-label" id="insurance_premium_fourth_year_label"></label></td>
                              </tr>
                            </tbody>
                          </table>';
                  }
                ?>
              </div>
            </div>                  
          </div>
          <div class="col-xl-6">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h5 class="mb-0">Amount of Deposit</h5>
                  </div>
                  <div class="col-auto">
                    <?php
                      if($salesProposalStatus == 'Draft'){
                        echo '<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-deposit-amount-offcanvas" aria-controls="sales-proposal-deposit-amount-offcanvas" id="add-sales-proposal-deposit-amount">Add Amount of Deposit</button>';
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body p-0 table-body mb-4">
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
            </div>                  
          </div>
        </div>
      </div>
      <div class="tab-pane" id="additional-job-order-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-4">
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center my-2">
                  <div class="col">
                    <div class="progress" style="height: 6px">
                      <div class="progress-bar bg-primary" style="width: 66.65%"></div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <p class="mb-0 h6">Step 4</p>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-warning" id="prev-step-4">Previous</button>
                    <button class="btn btn-primary" id="next-step-4">Next</button>
                  </div>
                </div>
              </div>
              <div class="card-body border-bottom">
                <div class="row align-items-center">
                  <div class="col">
                    <h5 class="mb-0">Additional Job Order</h5>
                  </div>
                  <div class="col-auto">
                    <?php
                      if($salesProposalStatus != 'Rejected' && $salesProposalStatus != 'Cancelled'){
                        echo '<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-offcanvas" aria-controls="sales-proposal-additional-job-order-offcanvas" id="add-sales-proposal-additional-job-order">Add Additional Job Order</button>';
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body p-0 table-body mb-4">
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
            </div>
            <div class="card">
              <div class="card-body py-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item px-0">
                    <div class="float-end">
                      <h5 class="mb-0" id="sales-proposal-additional-job-order-total">--</h5>
                    </div>
                    <h5 class="mb-0 d-inline-block">Total</h5>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="image-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-5">
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center my-2">
                  <div class="col">
                    <div class="progress" style="height: 6px">
                      <div class="progress-bar bg-primary" style="width: 83.31%"></div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <p class="mb-0 h6">Step 5</p>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-warning" id="prev-step-5">Previous</button>
                    <button type="button" class="btn btn-primary" id="next-step-5">Next</button>

                    <?php
                      if($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'For Initial Approval' || $salesProposalStatus == 'Draft'){
                          
                        echo '<button class="btn btn-info m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-new-engine-stencil-offcanvas" aria-controls="sales-proposal-new-engine-stencil-offcanvas" id="sales-proposal-client-confirmation">New Engine Stencil</button>';
                        
                        echo '<button class="btn btn-success m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-client-confirmation-offcanvas" aria-controls="sales-proposal-client-confirmation-offcanvas" id="sales-proposal-client-confirmation">Client Confirmation</button>';

                        if(!empty($clientConfirmation) && $transactionType == 'Bank Financing'){
                          echo '<button class="btn btn-warning m-l-5" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-credit-advice-offcanvas" aria-controls="sales-proposal-credit-advice-offcanvas" id="sales-proposal-credit-advice">Credit Advice</button>';
                        }
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-4">
            <div class="card">
              <div class="card-body py-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item px-0">
                    <h5 class="mb-0">New Engine Stencil </h5>
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
          <div class="col-xl-4">
            <div class="card">
              <div class="card-body py-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item px-0">
                    <h5 class="mb-0">Client Confirmation </h5>
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
          <div class="col-xl-4">
            <div class="card">
              <div class="card-body py-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item px-0">
                    <h5 class="mb-0">Credit Advice </h5>
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
          <div class="col-xl-4">
            <div class="card">
              <div class="card-body py-2">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item px-0">
                    <h5 class="mb-0">Credit Advice </h5>
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
          <div class="col-xl-4">
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
          <div class="col-xl-4">
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
          <div class="col-xl-4">
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
      </div>
      <div class="tab-pane" id="summary-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-6">
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center my-2">
                  <div class="col">
                    <div class="progress" style="height: 6px">
                      <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <p class="mb-0 h6">Step 6</p>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-warning" id="prev-step-6">Previous</button>
                    <a href="javascript:window.print()" class="btn btn-outline-info me-1">Print</a>
                    <?php
                      if($salesProposalStatus == 'Draft' && $forInitialApproval['total'] > 0){
                        echo '<button class="btn btn-primary" id="tag-for-initial-approval">For Initial Approval</button>';
                      }

                      if($salesProposalStatus == 'For Initial Approval' && $initialApproveSalesProposal['total'] > 0 && $initialApprovingOfficer == $contact_id){
                        echo '<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-initial-approval-offcanvas" aria-controls="sales-proposal-initial-approval-offcanvas" id="sales-proposal-initial-approval">Approve</button>';
                      }

                      if(($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI') && $proceedSalesProposal['total'] > 0 && $finalApprovingOfficer == $contact_id && !empty($clientConfirmation)){
                        if($transactionType == 'COD' || $transactionType == 'Installment Sales' || ($transactionType == 'Bank Financing' && !empty($creditAdvice))){
                          echo '<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-final-approval-offcanvas" aria-controls="sales-proposal-final-approval-offcanvas" id="sales-proposal-final-approval">Proceed</button>';
                        }
                      }

                      if((($salesProposalStatus == 'For Initial Approval' && $initialApprovingOfficer == $contact_id) || ($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI') && $finalApprovingOfficer == $contact_id) && $rejectSalesProposal['total'] > 0){
                        echo '<button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-reject-offcanvas" aria-controls="sales-proposal-reject-offcanvas" id="sales-proposal-reject">Reject</button>';
                      }

                      if(($salesProposalStatus == 'Draft' || $salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For CI') && $cancelSalesProposal['total'] > 0){
                        echo ' <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-cancel-offcanvas" aria-controls="sales-proposal-cancel-offcanvas" id="sales-proposal-cancel">Cancel</button>';
                      }

                      if($salesProposalStatus == 'For Final Approval' && $forCISalesProposal['total'] > 0){
                        echo '<button class="btn btn-info" id="for-ci-sales-proposal">For CI</button>';
                      }

                      if($setToDraftSalesProposal['total'] > 0 && ($salesProposalStatus == 'For Final Approval' || $salesProposalStatus == 'For Initial Approval' || $salesProposalStatus == 'For CI' || $salesProposalStatus == 'Rejected')){
                        echo ' <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-set-to-draft-offcanvas" aria-controls="sales-proposal-set-to-draft-offcanvas" id="sales-proposal-set-to-draft">Draft</button>';
                      }

                      if(($salesProposalStatus == 'For CI' || ($salesProposalStatus == 'Proceed' && !empty($forCIDate))) && $tagCIAsComplete['total'] > 0 && empty($ciStatus)) {
                        echo '<button class="btn btn-info" id="complete-ci">Complete CI</button>';
                      }

                      if($salesProposalStatus == 'Ready For Release'){
                        if($tagSalesProposalForDR['total'] > 0 && !empty($outgoingChecklist)){
                          if((($productType == 'Unit' || $productType == 'Repair') && !empty($unitImage)) || (($productType != 'Unit' && $productType != 'Repair')))
                          echo '<button class="btn btn-success m-l-5" id="for-dr-sales-proposal">For DR</button>';
                        }
                      }
                    ?>                                      
                  </div>
                </div>
              </div>
              <div class="card-body border-bottom">
                <h5>Summary</h5>
              </div>
              <div class="card-body print-area">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="table-border-style mw-100">
                      <div class="table-responsive ">
                        <table class="table table-bordered text-uppercase text-wrap">
                          <tbody>
                            <tr>
                              <td colspan="8" class="text-center bg-danger pb-0"><h3 class="font-size-15 fw-bold text-light">SALES PROPOSAL</h3></td>
                            </tr>
                            <tr>
                              <td colspan="4"><b>No. <span id="summary-sales-proposal-number"></span></b></td>
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
                              <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;:"><b>PLATE NO.</b></small><br/><span id="summary-plate-no"></span></td>
                              <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>FOR REGISTRATION?</b></small><br/><span id="summary-for-registration"></span></td>
                              <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>WITH CR?</b></small><br/><span id="summary-with-cr"></span></td>
                              <td class="text-wrap" style="vertical-align: top !important;"><small style="color:#c60206"><b>FOR TRANSFER?</b></small><br/><span id="summary-for-transfer"></span></td>
                            </tr>
                            <tr>
                              <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;:"><b>FUEL TYPE</b></small><br/><span id="summary-fuel-type"></span></td>
                              <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;:"><b>FUEL QUANTITY</b></small><br/><span id="summary-fuel-quantity"></span></td>
                              <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;:"><b>FOR CHANGE COLOR?</b></small><br/><span id="summary-for-change-color"></span></td>
                              <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;:"><b>NEW COLOR</b></small><br/><span id="summary-new-color"></span></td>
                              <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;:"><b>FOR CHANGE BODY?</b></small><br/><span id="summary-for-change-body"></span></td>
                              <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;:"><b>NEW BODY</b></small><br/><span id="summary-new-body"></span></td>
                              <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;:"><b>FOR CHANGE ENGINE?</b></small><br/><span id="summary-for-change-engine"></span></td>
                              <td style="vertical-align: top !important;" class="text-wrap"><small style="color:#c60206;:"><b>NEW ENGINE</b></small><br/><span id="summary-new-engine"></span></td>
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
                                <small style="color:#c60206"><b>AMORTIZATION NET</b></small><br/><span class="text-sm" id="summary-repayment-amount"></span>
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
                                ?></small><br/><span id="summary-created-by" class="text-sm"></span></td>
                              <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>INITIAL APPROVAL BY:</b></small><br/><br/><br/><small><?php
                                  if(!empty($initialApprovalDate)){
                                    echo 'APPROVED THRU SYSTEM<br/>' . $initialApprovalDate;
                                  }
                                ?></small><br/><span id="summary-initial-approval-by" class="text-sm"></span></td>
                              <td colspan="2" style="vertical-align: top !important;" class="text-wrap"><small><b>FINAL APPROVAL BY:</b></small><br/><br/><br/><small><?php
                                  if(!empty($approvalDate)){
                                    echo 'APPROVED THRU SYSTEM<br/>' . $approvalDate;
                                  }
                                ?></small><br/><span id="summary-final-approval-by" class="text-sm"></span></td>
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
                  '. $userModel->generateLogNotes('sales_proposal', $salesProposalID) .'
                </div>
              </div>
            </div>
          </div>';
  ?>
</div>

<?php
    echo '<div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-accessories-offcanvas" aria-labelledby="sales-proposal-accessories-offcanvas-label">
              <div class="offcanvas-header">
              <h2 id="sales-proposal-accessories-offcanvas-label" style="margin-bottom:-0.5rem">Accessories</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                  <div class="row">
                      <div class="col-lg-12">
                      <form id="sales-proposal-accessories-form" method="post" action="#">
                          <div class="form-group row">
                              <div class="col-lg-12">
                                  <label class="form-label">Accessories <span class="text-danger">*</span></label>
                                  <input type="hidden" id="sales_proposal_accessories_id" name="sales_proposal_accessories_id">
                                  <input type="text" class="form-control text-uppercase" id="accessories" name="accessories" maxlength="500" autocomplete="off">
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-12 mt-3 mt-lg-0">
                                  <label class="form-label" for="accessories_cost">Cost <span class="text-danger">*</span></label>
                                  <input type="number" class="form-control" id="accessories_cost" name="accessories_cost" min="0" step="0.01">
                              </div>
                          </div>
                      </form>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-accessories" form="sales-proposal-accessories-form">Submit</button>
                          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </di>
                  </div>
              </div>
          </div>
        </div>
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
                          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-job-order" form="sales-proposal-job-order-form">Submit</button>
                          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </di>
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
                      </di>
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
                      </di>
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
                      </di>
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
                      </di>
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
                      </di>
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
                      </form>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-set-to-draft" form="sales-proposal-set-to-draft-form">Submit</button>
                          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </di>
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
                      </di>
                  </div>
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
                      </di>
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
                      </di>
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
                      </di>
                  </div>
              </div>
          </div>
          </div>
        </div>';
?>