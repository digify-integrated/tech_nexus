<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs checkout-tabs mb-0" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="sales-proposal-tab-1" data-bs-toggle="tab" href="#customer-details-tab" role="tab" aria-controls="details-tab" aria-selected="true">
                            <div class="media align-items-center">
                                <div class="avtar avtar-s">
                                    <i class="ti ti-file-text"></i>
                                </div>
                                <div class="media-body ms-2">
                                    <h5 class="mb-0">Customer Details</h5>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="sales-proposal-tab-2" data-bs-toggle="tab" href="#stock-details-tab" role="tab" aria-controls="stock-details-tab" aria-selected="true">
                            <div class="media align-items-center">
                                <div class="avtar avtar-s">
                                    <i class="ti ti-truck"></i>
                                </div>
                                <div class="media-body ms-2">
                                    <h5 class="mb-0">Stock Details</h5>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="sales-proposal-tab-3" data-bs-toggle="tab" href="#os-details-tab" role="tab" aria-controls="os-details-tab" aria-selected="true">
                            <div class="media align-items-center">
                                <div class="avtar avtar-s">
                                    <i class="ti ti-file-invoice"></i>
                                </div>
                                <div class="media-body ms-2">
                                    <h5 class="mb-0">Sales Proposal Details</h5>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="sales-proposal-tab-6" data-bs-toggle="tab" href="#other-charges-tab" role="tab" aria-controls="other-charges-tab" aria-selected="true">
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
                        <a class="nav-link" id="sales-proposal-tab-7" data-bs-toggle="tab" href="#pdc-manual-input-tab" role="tab" aria-controls="pdc-manual-input-tab" aria-selected="true">
                            <div class="media align-items-center">
                                <div class="avtar avtar-s">
                                    <i class="ti ti-credit-card"></i>
                                </div>
                                <div class="media-body ms-2">
                                    <h5 class="mb-0">PDC Manual Input</h5>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="sales-proposal-tab-5" data-bs-toggle="tab" href="#release-details-tab" role="tab" aria-controls="release-details-tab" aria-selected="true">
                            <div class="media align-items-center">
                                <div class="avtar avtar-s">
                                    <i class="ti ti-file-check"></i>
                                </div>
                                <div class="media-body ms-2">
                                    <h5 class="mb-0">Release Details</h5>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="sales-proposal-tab-8" data-bs-toggle="tab" href="#printable-reports-tab" role="tab" aria-controls="printable-reports-tab" aria-selected="true">
                            <div class="media align-items-center">
                                <div class="avtar avtar-s">
                                    <i class="ti ti-printer"></i>
                                </div>
                                <div class="media-body ms-2">
                                    <h5 class="mb-0">Printable Reports</h5>
                                </div>
                            </div>
                        </a>
                    </li>

                    <?php
                        if(!empty($startDate) && !empty($drNumber)){
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" id="tag-as-released" href="javascript:void(0);">
                                    <div class="media align-items-center">
                                        <div class="avtar avtar-s">
                                            <i class="ti ti-check"></i>
                                        </div>
                                        <div class="media-body ms-2">
                                            <h5 class="mb-0">Tag As Released</h5>
                                        </div>
                                    </div>
                                </a>
                            </li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane show active" id="customer-details-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12">
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
                                                <div class="col-sm-6" >
                                                    <p class="mb-0" id="customer-id-summary"><?php echo $customerID; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Full Name</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="mb-0" id="customer-name-summary"><?php echo $customerName; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Address</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="mb-0" id="customer-address-summary"><?php echo $customerAddress; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Email</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="mb-0" id="customer-email-summary"><?php echo $customerEmail; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Mobile</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="mb-0" id="customer-mobile-summary"><?php echo $customerMobile; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Telephone</p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="mb-0" id="customer-telephone-summary"><?php echo $customerTelephone; ?></p>
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
            <div class="tab-pane" id="stock-details-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body py-2">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">
                                            <h5 class="mb-0">Product Details </h5>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Stock Number</p>
                                                </div>
                                                <div class="col-sm-6" id="stock-number-summary">
                                                    --
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Engine Number</p>
                                                </div>
                                                <div class="col-sm-6" id="engine-number-summary">
                                                    --
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Chassis Number</p>
                                                </div>
                                                <div class="col-sm-6" id="chassis-number-summary">
                                                    --
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Plate Number</p>
                                                </div>
                                                <div class="col-sm-6" id="plate-number-summary">
                                                    --
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Color</p>
                                                </div>
                                                <div class="col-sm-6" id="color-summary">
                                                --
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center mb-3">
                                                <div class="col-sm-6 mb-sm-0">
                                                    <p class="mb-0">Body Type</p>
                                                </div>
                                                <div class="col-sm-6" id="body-type-summary">
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
            <div class="tab-pane" id="os-details-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body py-2">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Sales Proposal Number :</label>
                                        <label class="col-lg-8 col-form-label" id="sales_proposal_number">--</label>
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Sales Proposal Status :</label>
                                    <div class="col-lg-8">
                                        <?php echo $salesProposalStatusBadge ?>
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Product Type :</label>
                                    <div class="col-lg-8">
                                        <label class="col-form-label" id="product_type_label"></label>
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
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="col-form-label">Old Color:</label>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="col-form-label" id="old_color_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="col-form-label">New Color:</label>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="col-form-label" id="new_color_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">For Change Body? :</label>
                                        <div class="col-lg-2">
                                            <label class="col-form-label" id="for_change_color_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="col-form-label">Old Body:</label>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="col-form-label" id="old_body_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="col-form-label">New Body:</label>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="col-form-label" id="new_body_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">For Change Engine? :</label>
                                        <div class="col-lg-2">
                                            <label class="col-form-label" id="for_change_color_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="col-form-label">Old Engine:</label>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="col-form-label" id="old_engine_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="col-form-label">New Engine:</label>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="col-form-label" id="new_engine_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-lg-4 col-form-label">Remarks :</label>
                                    <div class="col-lg-8">
                                        <label class="col-form-label" id="remarks_label"></label>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="release-details-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h5 class="mb-0">Release Details</h5>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" form="sales-proposal-other-product-details-form" class="btn btn-success" id="submit-other-product-details-data">Submit</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" id="product_id" value="<?php echo $productID ?>">
                                    <form id="sales-proposal-other-product-details-form" method="post" action="#">
                                        <div class="form-group row">
                                            <label class="col-lg-5 col-form-label">DR Number : <span class="text-danger">*</span></label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control text-uppercase" id="dr_number" name="dr_number" maxlength="50" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-5 col-form-label">Actual Start Date : <span class="text-danger">*</span></label>
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
                                            <label class="col-lg-5 col-form-label">Year Model :</label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control text-uppercase" id="year_model" name="year_model" maxlength="20" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-5 col-form-label">CR No :</label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control text-uppercase" id="cr_no" name="cr_no" maxlength="100" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-5 col-form-label">MV File No :</label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control text-uppercase" id="mv_file_no" name="mv_file_no" maxlength="100" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-5 col-form-label">Make :</label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control text-uppercase" id="make" name="make" maxlength="100" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-5 col-form-label">Product Description :</label>
                                            <div class="col-lg-7">
                                                <textarea class="form-control text-uppercase" id="product_description" name="product_description" maxlength="500"></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="other-charges-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-4">
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
                                <div class="form-group row">
                                    <label class="col-lg-5 col-form-label">Deliver Price (AS/IS) :</label>
                                    <div class="col-lg-7">
                                        <label class="col-form-label" id="delivery_price_label"></label>
                                    </div>
                                </div>
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
                                </div>
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
                                <div class="form-group row">
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
                                        <h5 class="mb-0">Renewal Amount</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" id="product_category">
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
                                </table>
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
            <div class="tab-pane" id="pdc-manual-input-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-2">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body border-bottom">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="mb-0">PDC Manual Input</h5>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-pdc-manual-input-offcanvas" aria-controls="sales-proposal-pdc-manual-input-offcanvas" id="add-sales-proposal-pdc-manual-input">Add PDC</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0 table-body mb-4">
                                <div class="table-responsive">
                                <table id="sales-proposal-pdc-manual-input-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Bank/Branch</th>
                                        <th>Check Date</th>
                                        <th>Check Number</th>
                                        <th>Payment For</th>
                                        <th>Gross Amount</th>
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
            <div class="tab-pane" id="printable-reports-tab" role="tabpanel" aria-labelledby="sales-proposal-tab-5">
                <div class="row g-4">
                    <div class="col-md-3 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <li><a class="nav-link active" id="v-printable-online-tab" data-bs-toggle="pill" href="#v-printable-online" role="tab" aria-controls="v-printable-online" aria-selected="true">Online</a></li>
                                    <li><a class="nav-link" id="v-authorization-undertaking-tab" data-bs-toggle="pill" href="#v-authorization-undertaking" role="tab" aria-controls="v-authorization-undertaking" aria-selected="false">Authorization & Undertaking</a></li>
                                    <li><a class="nav-link" id="v-promisory-note-tab" data-bs-toggle="pill" href="#v-promisory-note" role="tab" aria-controls="v-promisory-note" aria-selected="false">Promissory Note</a></li>
                                    <li><a class="nav-link" id="v-disclosure-tab" data-bs-toggle="pill" href="#v-disclosure" role="tab" aria-controls="v-disclosure" aria-selected="false">Disclosure</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-printable-online" role="tabpanel" aria-labelledby="v-printable-online-tab">
                                <div class="card">
                                    <div class="card-body print-area2">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <p class="text-center text-danger"><b>MAHALAGANG PAALALA</b></p>
                                                <p class="text-danger"><b>PAGBAYAD SA BANGKO</b></p>
                                                <p style="text-align: justify !important;">IDEPOSITO ANG INYONG BAYAD SA ITINAKDANG ACCOUNT NG AMING KUMPANYA NA NAKAPANGALAN SA “CHRISTIAN GENERAL MOTORS INC", "N E TRUCK BUILDERS CORPORATION", O “GRACE C. BAGUISA”. PAKITINGNAN PO NG MABUTI KUNG ITO ANG NAKASULAT NA PANGALAN SA INYONG DEPOSIT SLIP.</p>
                                                <p class="text-justify text-danger">ANUMANG BAYAD SA PAMAMAGITAN NG LBC, JRS, O IBA PANG KATULAD NA KUMPANYA AY HINDI PINAHIHINTULUTAN NG AMING KUMPANYA.</p>
                                                <p style="text-align: justify !important;">KUNIN LAMANG PO ANG AMING BANK ACCOUNTS KAY MR. CHRISTIAN EDWARD BAGUISA SA CELLPHONE NO. 0919-062-6563.</p>
                                                <p class="text-danger"><b>TAMANG PARAAN NG PAGBABAYAD SA BANGKO</b></p>
                                                <p style="text-align: justify !important;">ISULAT SA DEPOSIT SLIP, INYONG KOPYA AT KOPYA NG BANGKO, ANG INYONG PANGALAN PARA MAIPROSESO NAMIN NG TAMA ANG INYONG BAYAD.</p>
                                                <p style="text-align: justify !important;">PAGKATAPOS I-TEXT, PAKI-SCAN AT PAKI-EMAIL ANG DEPOSIT SLIP SA v.reyes@christianmotors.ph SA ARAW NG PAGKAKADEPOSITO O SA SUSUNOD NA ARAW.</p>
                                                <p style="text-align: justify !important;">PAKITAGO ANG DEPOSIT SLIP AT PAKIDALA ANG ORIHINAL NA KOPYA SA AMING KUMPANYA. MANGHINGI PO NG OFFICIAL RECEIPT SA AMING KAHERA KAPALIT ANG ORIHINAL NA DEPOSIT SLIP.</p>
                                                <p style="text-align: justify !important;">KUNG SAKALI PO NA HINDI KAYO MAKAKAPUNTA SA AMING OPISINA, SIGURADUHIN PO NA ANG INYONG DEPOSIT SLIPS AY NAKATAGO PARA MAGAMIT NA BATAYAN NG INYONG PAGKAKABAYAD SA HINAHARAP.</p>
                                                <p class="text-danger"><b>BABALA</b></p>
                                                <p style="text-align: justify !important;">MANGYARI PO LAMANG NA IPAGBIGAY ALAM SA AMING KUMPANYA KUNG MAYROONG MAG-UUTOS SA INYO NA BAYARAN ANG INYONG ACCOUNT MALIBAN SA ITINAKDANG DEPOSIT ACCOUNTS NG AMING KUMPANYA SA CELLPHONE NOS. 0919-062-6563/0962-098-4672.</p>
                                                <p style="text-align: justify !important;">ANG AMING KUMPANYA AY WALANG PANANAGUTAN SA ANUMANG BAYAD NA INYONG IPINADALA SA IBANG PARAAN.</p>
                                                <p style="text-align: justify !important;">MARAMING SALAMAT PO SA INYONG PATULOY NA PAGTANGKILIK.</p>
                                                <p style="text-align: justify !important;">TINANGGAP AT LUBOS NA NAUUNAWAAN:</p><br/>
                                                <div class="row">
                                                    <div class="col-4 border-top" style="border-color: #000 !important;">
                                                        <p class="text-center text-uppercase">Lagda / Petsa</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-lg-12">
                                                <p class="text-center text-danger"><b>IMPORTANT REMINDERS</b></p>
                                                <p class="text-danger"><b>ON-LINE DEPOSITS OR PAYMENTS</b></p>
                                                <p style="text-align: justify !important;">FOR ALL ON-LINE PAYMENTS, KINDLY ENSURE THAT ONLY THE DESIGNATED BANK ACCOUNTS WITH ACCOUNT NAMES “CHRISTIAN GENERAL MOTORS, INC.", "N E TRUCK BUILDERS CORPORATION” OR “GRACE BAGUISA” WILL BE REFLECTED IN THE BANK DEPOSIT SLIPS.</p>
                                                <p class="text-justify text-danger">OTHER MODE OF PAYMENTS SUCH AS BUT NOT LIMITED TO LBC, JRS OR ANY OTHER MONEY TRANSFER COMPANIES ARE NOT AUTHORIZED BY THE COMPANY.</p>
                                                <p style="text-align: justify !important;">KINDLY SECURE THE ACCOUNT NUMBER OF THE COMPANY’S DESIGNATED BANK ACCOUNTS ONLY FROM MR. CHRISTIAN EDWARD BAGUISA WITH CONTACT NO. 0919-062-6563.</p>
                                                <p class="text-danger"><b>PROCEDURES IN MAKING ON-LINE PAYMENTS THRU BANKS</b></p>
                                                <p style="text-align: justify !important;">KINDLY INDICATE IN THE BANK DEPOSIT SLIP, BOTH IN DEPOSITOR’S COPY AND BANK COPY YOUR NAME FOR PROPER POSTING OF PAYMENT.</p>
                                                <p style="text-align: justify !important;">PLEASE TEXT YOUR NAME, NAME OF BANK AND BRANCH WHERE THE ON-LINE DEPOSIT WAS MADE, AMOUNT OF DEPOSIT AND DATE OF PAYMENT TO CELLPHONE NOS. 0916-062-6563/0962-098-4672.</p>
                                                <p style="text-align: justify !important;">AFTER TEXTING, KINDLY SCAN AND E-MAIL THE DEPOSIT SLIP TO v.reyes@christianmotors.ph ON THE SAME DAY THE DEPOSIT IS MADE OR THE FOLLOWING DAY.</p>
                                                <p style="text-align: justify !important;">KINDLY SAFEKEEP THE BANK DEPOSIT SLIP AND BRING THE ORIGINAL COPY UPON VISIT TO OUR COMPANY. REQUEST THE CORRESPONDING OFFICIAL RECEIPT ONLY TO OUR DESIGNATED CASHIER UPON SURRENDER OF BANK DEPOSIT SLIP.</p>
                                                <p style="text-align: justify !important;">IN CASE YOU WILL NOT BE ABLE TO VISIT OUR COMPANY, PLEASE ENSURE THAT ALL YOUR BANK DEPOSIT SLIPS ARE SAFEKEPT FOR FUTURE REFERENCE.</p>
                                                <p class="text-danger"><b>WARNINGS</b></p>
                                                <p style="text-align: justify !important;">PLEASE ADVISE OUR COMPANY IMMEDIATELY IF THERE IS AN ATTEMPT TO REQUEST YOU TO ON-LINE YOUR PAYMENT TO ACCOUNTS OTHER THAN OUR DESIGNATED BANK ACCOUNTS THRU CELLPHONE NOS. 0916-062-6563/0962-098-4672.</p>
                                                <p style="text-align: justify !important;">OUR COMPANY HAS NO RESPONSILIBITY OR LIABILITY FOR ALL YOUR PAYMENTS COURSED THRU OTHER COMPANIES OR INDIVIDUALS.</p>
                                                <p style="text-align: justify !important;">THANK YOU VERY MUCH FOR YOUR CONTINUED SUPPORT TO OUR COMPANY.</p>
                                                <p style="text-align: justify !important;">RECEIVED AND CLEARLY UNDERSTOOD BY:</p><br/>
                                                <div class="row">
                                                    <div class="col-4 border-top" style="border-color: #000 !important;">
                                                        <p class="text-center text-uppercase">Signature /  Date</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-authorization-undertaking" role="tabpanel" aria-labelledby="v-authorization-undertaking-tab">
                                <div class="card">
                                    <div class="card-body print-area2">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <p><b>THE MANAGER<br/>CHRISTIAN GENERAL MOTORS, INC<br/>CABANATUAN CITY</b></p>
                                                <p class="text-center"><b>AUTHORIZATION AND UNDERTAKING</b></p>
                                                <p style="text-align: justify !important;">This is to authorize your company to deposit any of the checks I issued upon due date of my monthly installment. In case the check is dishonored by the drawee bank for whatever reason, I agreed to pay additional 3% late payment penalty.</p>
                                                <p style="text-align: justify !important;">If my check is dishonored due to "Account Closed", I hereby undertake to replace the remaining checks to cover my monthly installment without need of demand or notice, otherwise, my entire outstanding obligation will become due and demandable. I also agreed to pay additional 3% late payment penalty.</p>
                                                <p style="text-align: justify !important;">Finally, I hereby authorize the company to indicate any details on the face of the checks provided it is based on the agreed terms and conditions as stipulated in the Deed of Conditional Sale.</p><br/>

                                                <div class="row">
                                                    <div class="col-6 border-top" style="border-color: #000 !important;">
                                                        <p class="text-center text-uppercase">NAME OF CUSTOMER OVER PRINTED NAME</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-promisory-note" role="tabpanel" aria-labelledby="v-promisory-note-tab">
                                <div class="card">
                                    <div class="card-body print-area2">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h4 class="text-center fw-8"><b>PROMISSORY NOTE</b></h4>
                                                <p class="text-left fw-8"><b><?php echo number_format($pnAmount, 2); ?></b></p>
                                                <p style="text-align: justify !important;">For value received, I/we jointly and severally promise to pay without need of demand to the order of Christian General Motors Incorporated at its principal office at Km 112, Maharlika Highway, Brgy Hermogenes Concepcion, Cabanatuan City, Nueva Ecija, Philippines, the sum of <b><?php echo strtoupper($amountInWords->format($pnAmount)) . ' PESOS'; ?> <?php echo '(' .number_format($pnAmount, 2) . ')'; ?></b> payable based on the <b>SCHEDULE OF PAYMENTS</b> as stipulated in the <b>Disclosure Statement on Credit Sales Transaction</b> which I/we duly received, agreed and understood.</p>
                                                <p style="text-align: justify !important;">Time is declared of the essence hereof and in case of default in the payment of any installment due, all the other instalments shall automatically become due and demandable and shall make me liable for the additional sum equivalent to <b>THREE percent (3%) per month</b> based on the total amount due and demandable as penalty, compounded monthly until fully paid; and in case it becomes necessary to collect this note through any Attorney-at-Law, the further sum of <b>TWENTY percent (20%)</b> thereof and <b>ATTORNEY'S FEES of THIRTY percent (30%)</b> of total amount due , exclusive of costs and judicial/extra-judicial expenses; Moreover, I further empower the holder or any of his authorized representative(s), at his option, to hold as security therefore any real or personal property which may be in my possession or control by virtue of any other contract.</p>
                                                <p style="text-align: justify !important;">In case of extraordinary change in value of the peso due to extraordinary inflation or deflation or any other reason, the basis of payment for this note shall be the value of the peso at the time the obligation was incurred as provided in Article 1250 of the New Civil Code.</p>
                                                <p style="text-align: justify !important;">DEMAND AND NOTICE OF DISHONOR WAIVED. I hereby waive any diligence, presentment, demand, protests or notice of non-payment or dishonor pertaining to this note, or any extension or renewal therefore. Holder(s) may accept partial payment(s) and grant renewals or extensions of payment reserving its/their rights against each and all indorsers, and all parties to this note. Acceptance by holder(s) of any partial payment(s) after due date shall not be considered as extending the time for payment or as a modification of any conditions hereof.</p>
                                                <p style="text-align: justify !important;">All actions arising from or connected with this note shall be brought exclusively in the proper courts of CABANATUAN CITY, Philippines; and in case of judicial execution of this obligation or any part of it, the debtor waives all rights under the provisions of Rule 39 Sec. 13, of the Rules of the Court.</p>
                                                <p style="text-align: justify !important;">Signed this ____ day of ___________________, 20___  at_______________________________, Philippines.</p>
                                                <div class="row mt-5">
                                                    <div class="col-5">
                                                        <p class="text-center mb-0"><?php echo $customerName; ?></p>
                                                        <p class="text-center text-uppercase border-top" style="border-color: #000 !important;">MAKER</p>
                                                    </div>
                                                    <div class="col-2"></div>
                                                    <div class="col-5">
                                                        <?php echo $comakerLabel; ?>
                                                        <p class="text-center text-uppercase border-top" style="border-color: #000 !important;">CO-MAKER</p>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-5">
                                                        <small class="text-left mb-0"><?php echo ucwords(strtolower($customerAddress)); ?></small>
                                                        <p class="text-center text-uppercase border-top" style="border-color: #000 !important;">ADDRESS</p>
                                                    </div>
                                                    <div class="col-2"></div>
                                                    <div class="col-5">
                                                        <?php echo $comakerAddressLabel; ?>
                                                        <p class="text-center text-uppercase border-top" style="border-color: #000 !important;">ADDRESS</p>
                                                    </div>
                                                </div>
                                                <p class="text-center fw-8"><b>SIGNED IN THE PRESENCE OF:</b></p>

                                                <div class="row mt-4">
                                                    <div class="col-5">
                                                        <p class="text-center text-uppercase border-top" style="border-color: #000 !important;"></p>
                                                    </div>
                                                    <div class="col-2"></div>
                                                    <div class="col-5">
                                                        <p class="text-center text-uppercase border-top" style="border-color: #000 !important;"></p>
                                                    </div>
                                                </div>
                                                <p style="text-align: justify !important;">SUBSCRIBED AND SWORN TO before me this ____ day of ______, 20__, affiants having shown to me their competent evidence of identity as follows:</p>
                                                <table class="w-100">
                                                    <tr class="text-center">
                                                        <td>Name</td>
                                                        <td>Competent Evidence of Identity</td>
                                                        <td>Date/Place Issued</td>
                                                    </tr>
                                                </table><br/><br/><br/><br/>
                                                <p style="text-align: justify !important;">Doc. No. __________<br/>Page No. __________<br/>Book No. __________<br/>Series of __________</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-disclosure" role="tabpanel" aria-labelledby="v-disclosure-tab">
                                <div class="card">
                                    <div class="card-body print-area3">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h5 class="text-center fw-8"><b>DISCLOSURE STATEMENT ON CREDIT SALES TRANSACTION</b></h5>
                                                <h5 class="text-center fw-8"><b>(As required under R. A. 3765 Truth in Lending Act)</b></h5><br/>
                                                <div class="row">
                                                    <div class="col-3 mb-0">
                                                        <p class="text-left">NAME OF BUYER</p>
                                                    </div>
                                                    <div class="col-9 mb-0">
                                                        <p class="text-left"><?php echo $customerName ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-3">
                                                        <p class="text-left mb-3">ADDRESS</p>
                                                    </div>
                                                    <div class="col-9">
                                                        <p class="text-left mb-3"><?php echo strtoupper($customerAddress); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <p class="text-left ">1. TOTAL PRICE OF ITEM PURCHASED</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p class="text-end "><?php echo number_format($pnAmount, 2); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <p class="text-left ">2. DOWNPAYMENT AND/OR TRADE-IN VALUE</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p class="text-end "><?php echo number_format($downpayment, 2); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <p class="text-left ">3. UNPAID BALANCE OF TOTAL PRICE</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p class="text-end "><?php echo number_format($pnAmount, 2); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p class="text-left ">4. OTHER CHARGES (TO BE COLLECTED SEPARATELY TO BUYER)</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start ">a. Insurance</p>
                                                    </div>
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start "><?php echo number_format($insurancePremium, 2); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start ">b. Handling Fee</p>
                                                    </div>
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start "><?php echo number_format($handlingFee, 2); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start ">c. Transfer Fee</p>
                                                    </div>
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start "><?php echo number_format($transferFee, 2); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start ">d. Miscellaneous/Transaction Fee</p>
                                                    </div>
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start "><?php echo number_format($transactionFee, 2); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start ">e. Insurance Renewal</p>
                                                    </div>
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start "><?php echo number_format($totalInsuranceFee, 2); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start ">f. Registration Renewal</p>
                                                    </div>
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start "><?php echo number_format($totalRenewalFee, 2); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start "><b>TOTAL OTHER CHARGES</b></p>
                                                    </div>
                                                    <div class="col-2"></div>
                                                    <div class="col-4">
                                                        <p class="text-start "><?php echo number_format($totalCharges, 2); ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p class="text-left ">5. CONDITIONAL CHARGES MAYBE IMPOSED</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-10">
                                                        <p class="text-start ">a. Late payment penalty of straight 3% per month based on unpaid installments due. </p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-10">
                                                        <p class="text-start ">b. Attorney's fee - 30% of total amount due; liquidated damages- further sum of 20% in addition to costs and other litigation expenses;</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-2"></div>
                                                    <div class="col-10">
                                                        <p class="text-start ">c. Liquidated damages- further sum of 20% in addition to costs and other litigation expenses</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p class="text-left ">6. OTHER CHARGES</p>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered w-100 text-center" style="border: 1px solid #000 !important;">
                                                    <tr>
                                                        <td>DUE DATE</td>
                                                        <td>AMOUNT DUE</td>
                                                        <td>PAYMENT FOR</td>
                                                    </tr>
                                                    <tbody id="other-charges-rows"></tbody>
                                                </table>
                                                <div class="pagebreak mt-0"></div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p class="text-left ">7. SCHEDULE OF PAYMENTS</p>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered w-100 text-center" style="border: 1px solid #000 !important;">
                                                    <tr>
                                                        <td>DUE DATE</td>
                                                        <td>AMOUNT DUE</td>
                                                        <td>PAYMENT FOR</td>
                                                        <td>OUTSTANDING BALANCE</td>
                                                    </tr>
                                                    <tr>
                                                        <td>--</td>
                                                        <td>--</td>
                                                        <td>--</td>
                                                        <td><?php echo number_format($pnAmount, 2); ?></td>
                                                    </tr>
                                                    <?php
                                                        for ($i = 0; $i < $numberOfPayments; $i++) {
                                                            $pnAmount = $pnAmount - $repaymentAmount;

                                                            if($pnAmount <= 0){
                                                                $pnAmount = 0;
                                                            }

                                                            $dueDate = calculateDueDate($startDate, $paymentFrequency, $i + 1);

                                                            echo '<tr>
                                                                    <td>'. $dueDate .'</td>
                                                                    <td>'. number_format($repaymentAmount, 2) .'</td>
                                                                    <td>ACCT AMORT</td>
                                                                    <td>'. number_format($pnAmount, 2) .'</td>
                                                                  </tr>';
                                                        }
                                                    ?>
                                                </table>
                                                <p style="text-align: justify !important;"><b>I ACKNOWLEDGE RECEIPT OF A COPY OF THIS STATEMENT PRIOR TO THE CONSUMMATION OF THE CREDIT SALES TRANSACTION AND THAT I UNDERSTAND AND FULLY AGREE TO THE TERMS AND CONDITIONS THEREOF.</b></p><br/>
                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <p class="text-center text-uppercase border-top" style="border-color: #000 !important;">SIGNATURE OF BUYER OVER PRINTED NAME</p>
                                                    </div>
                                                </div>
                                                <p style="text-align: justify !important;"><b>This is a system-generated Disclosure Statement on Credit Sales Transaction and does not require a signature from Christian General Motors Inc.</b></p>
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
</div>

<div>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-pdc-manual-input-offcanvas" aria-labelledby="sales-proposal-pdc-manual-input-offcanvas-label">
              <div class="offcanvas-header">
                  <h2 id="sales-proposal-pdc-manual-input-offcanvas-label" style="margin-bottom:-0.5rem">PDC Manual Input</h2>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                  <div class="row">
                      <div class="col-lg-12">
                      <form id="sales-proposal-pdc-manual-input-form" method="post" action="#">
                          <div class="form-group row">
                              <div class="col-lg-12 mt-3 mt-lg-0">
                                <div class="form-group">
                                    <label class="form-label">Payment Frequency <span class="text-danger">*</span></label>
                                    <select class="form-control offcanvas-select2" name="payment_frequency" id="payment_frequency">
                                        <option value="">--</option>
                                        <option value="Daily">Daily</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Yearly">Yearly</option>
                                    </select>
                                </div>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-12 mt-3 mt-lg-0">
                                <div class="form-group">
                                    <label class="form-label">Payment For <span class="text-danger">*</span></label>
                                    <select class="form-control offcanvas-select2" name="payment_for" id="payment_for">
                                        <option value="">--</option>
                                        <option value="Acct Amort">Acct Amort</option>
                                        <option value="Acct Amort W/ OC">Acct Amort W/ OC</option>
                                        <option value="Advances">Advances</option>
                                        <option value="Collateral Check">Collateral Check</option>
                                        <option value="Deposit">Deposit</option>
                                        <option value="Downpayment">Downpayment</option>
                                        <option value="Insurance">Insurance</option>
                                        <option value="Insurance Renewal">Insurance Renewal</option>
                                        <option value="Other Charges">Other Charges</option>
                                        <option value="Parts">Parts</option>
                                        <option value="Registration">Registration</option>
                                        <option value="Registration Renewal">Registration Renewal</option>
                                        <option value="Transaction Fee">Transaction Fee</option>
                                        <option value="Transfer Fee">Transfer Fee</option>
                                    </select>
                                </div>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-12">
                                  <label class="form-label">Bank/Branch <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control text-uppercase" id="bank_branch" name="bank_branch" maxlength="200" autocomplete="off">
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-12 mt-3 mt-lg-0">
                                  <label class="form-label" for="no_of_payments">Number of payments <span class="text-danger">*</span></label>
                                  <input type="number" class="form-control" id="no_of_payments" name="no_of_payments" min="1" step="1">
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-12 mt-3 mt-lg-0">
                                  <label class="form-label" for="first_check_number">First Check Number <span class="text-danger">*</span></label>
                                  <input type="number" class="form-control" id="first_check_number" name="first_check_number" min="1" step="1">
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-12">
                                    <label class="form-label">First Check Date <span class="text-danger">*</span></label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control regular-datepicker" id="first_check_date" name="first_check_date" autocomplete="off">
                                        <span class="input-group-text">
                                            <i class="feather icon-calendar"></i>
                                        </span>
                                    </div>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-lg-12 mt-3 mt-lg-0">
                                  <label class="form-label" for="amount_due">Gross Amount <span class="text-danger">*</span></label>
                                  <input type="number" class="form-control" id="amount_due" name="amount_due" min="1" step="0.01">
                              </div>
                          </div>
                      </form>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-pdc-manual-input" form="sales-proposal-pdc-manual-input-form">Submit</button>
                          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </di>
                  </div>
              </div>
          </div>
        </div>

<?php
function calculateDueDate($startDate, $frequency, $iteration) {
    $date = new DateTime($startDate);
    switch ($frequency) {
        case 'Monthly':
            $date->modify("+$iteration months");
            break;
        case 'Quarterly':
            $date->modify("+$iteration months")->modify('+2 months');
            break;
        case 'Semi-Annual':
            $date->modify("+$iteration months")->modify('+5 months');
            break;
        default:
            break;
    }
    return $date->format('d-M-Y');
}


?>