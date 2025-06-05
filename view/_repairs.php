<div class="row">
  <div class="col-lg-12">
    <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" id="analytics-tab-1" data-bs-toggle="tab" data-bs-target="#analytics-tab-1-pane" type="button" role="tab" aria-controls="analytics-tab-1-pane" aria-selected="true">Sales Proposal</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="analytics-tab-2" data-bs-toggle="tab" data-bs-target="#analytics-tab-2-pane" type="button" role="tab" aria-controls="analytics-tab-2-pane" aria-selected="false" tabindex="-1">Internal Job Order</button></li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade active show" id="analytics-tab-1-pane" role="tabpanel" aria-labelledby="analytics-tab-1" tabindex="0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ecom-wrapper">
                    <div class="offcanvas offcanvas-start ecom-offcanvas" tabindex="-1" id="filter-canvas">
                        <div class="offcanvas-body p-0">
                        <div id="ecom-filter" class="show collapse collapse-horizontal">
                            <div class="ecom-filter">
                            <div class="card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                <h5>Filter</h5>
                                <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="offcanvas" data-bs-target="#filter-canvas">
                                    <i class="ti ti-x f-20"></i>
                                </a>
                                </div>
                                <div class="scroll-block">
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 py-2">
                                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#sales-proposal-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                        Sales Proposal Status
                                        </a>
                                        <div class="collapse" id="sales-proposal-status-filter-collapse">
                                        <div class="row py-3">
                                            <div class="col-12">
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-draft" value="Draft"/>
                                                <label class="form-check-label" for="sales-proposal-status-draft">Draft</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-for-review" value="For Review"/>
                                                <label class="form-check-label" for="sales-proposal-status-for-review">For Review</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-for-initial-approval" value="For Initial Approval"/>
                                                <label class="form-check-label" for="sales-proposal-status-for-initial-approval">For Initial Approval</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-for-final-approval" value="For Final Approval"/>
                                                <label class="form-check-label" for="sales-proposal-status-for-final-approval">For Final Approval</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-for-ci" value="For CI"/>
                                                <label class="form-check-label" for="sales-proposal-status-for-ci">For CI</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-proceed" value="Proceed"/>
                                                <label class="form-check-label" for="sales-proposal-status-proceed">Proceed</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-on-process" value="On-Process"/>
                                                <label class="form-check-label" for="sales-proposal-status-on-process">On-Process</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-ready-for-release" value="Ready For Release"/>
                                                <label class="form-check-label" for="sales-proposal-status-ready-for-release">Ready For Release</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-for-dr" value="For DR"/>
                                                <label class="form-check-label" for="sales-proposal-status-for-dr">For DR</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-rejected" value="Rejected"/>
                                                <label class="form-check-label" for="sales-proposal-status-rejected">Rejected</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-cancelled" value="Cancelled"/>
                                                <label class="form-check-label" for="sales-proposal-status-cancelled">Cancelled</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input sales-proposal-status-filter" type="checkbox" id="sales-proposal-status-released" value="Released"/>
                                                <label class="form-check-label" for="sales-proposal-status-released">Released</label>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 py-2 d-none">
                                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#product-type-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                        Product Type
                                        </a>
                                        <div class="collapse" id="product-type-filter-collapse">
                                        <div class="row py-3">
                                            <div class="col-12">
                                            <div class="form-check my-2">
                                                <input class="form-check-input product-type-filter" type="checkbox" id="product-type-unit" value="Unit"/>
                                                <label class="form-check-label" for="product-type-unit">Unit</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input product-type-filter" type="checkbox" id="product-type-fuel" value="Fuel"/>
                                                <label class="form-check-label" for="product-type-fuel">Fuel</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input product-type-filter" type="checkbox" id="product-type-parts" value="Parts"/>
                                                <label class="form-check-label" for="product-type-parts">Parts</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input product-type-filter" type="checkbox" id="product-type-repair" value="Repair" checked/>
                                                <label class="form-check-label" for="product-type-repair">Repair</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input product-type-filter" type="checkbox" id="product-type-rental" value="Rental"/>
                                                <label class="form-check-label" for="product-type-rental">Rental</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input product-type-filter" type="checkbox" id="product-type-consignment" value="Consignment"/>
                                                <label class="form-check-label" for="product-type-consignment">Consignment</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input product-type-filter" type="checkbox" id="product-type-brand-new" value="Brand New"/>
                                                <label class="form-check-label" for="product-type-brand-new">Brand New</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input product-type-filter" type="checkbox" id="product-type-refinancing" value="Refinancing"/>
                                                <label class="form-check-label" for="product-type-refinancing">Refinancing</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input product-type-filter" type="checkbox" id="product-type-restructure" value="Restructure"/>
                                                <label class="form-check-label" for="product-type-restructure">Restructure</label>
                                            </div>
                                            <div class="form-check my-2">
                                                <input class="form-check-input product-type-filter" type="checkbox" id="product-type-real-estate" value="Real Estate"/>
                                                <label class="form-check-label" for="product-type-real-estate">Real Estate</label>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 py-2">
                                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#company-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                        Company
                                        </a>
                                        <div class="collapse" id="company-filter-collapse">
                                        <div class="py-3">
                                            <?php
                                            echo $companyModel->generateCompanyCheckBox();
                                            ?>
                                        </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 py-2">
                                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#created-by-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                        Created By
                                        </a>
                                        <div class="collapse" id="created-by-filter-collapse">
                                        <div class="py-3">
                                            <?php
                                            echo $userModel->generateSalesOptions();
                                            ?>
                                        </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 py-2">
                                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#created-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                        Created Date
                                        </a>
                                        <div class="collapse" id="created-date-filter-collapse">
                                        <div class="row py-3">
                                            <div class="col-12">
                                            <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_created_date_start_date" id="filter_created_date_start_date" placeholder="Start Date">
                                            <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_created_date_end_date" id="filter_created_date_end_date" placeholder="End Date" >
                                            </div>
                                        </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 py-2">
                                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#released-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                        Released Date
                                        </a>
                                        <div class="collapse " id="released-date-filter-collapse">
                                        <div class="row py-3">
                                            <div class="col-12">
                                            <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_released_date_start_date" id="filter_released_date_start_date" placeholder="Start Date">
                                            <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_released_date_end_date" id="filter_released_date_end_date" placeholder="End Date" >
                                            </div>
                                        </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 py-2">
                                        <button type="button" class="btn btn-light-success w-100" id="apply-filter">Apply</a>
                                    </li>
                                    </ul>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="ecom-content w-100">
                        <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h5>Sales Proposal List</h5>
                            </div>
                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                                Filter
                                </button>
                            </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                            <input type="hidden" id="customer_id" value="<?php echo $customerID; ?>">
                            <table id="all-sales-proposal-table" class="table table-hover nowrap w-100 text-uppercase">
                                <thead>
                                <tr>
                                    <th class="all">
                                    <div class="form-check">
                                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                                    </div>
                                    </th>
                                    <th>Customer</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>OS Number</th>
                                    <th>Product Type</th>
                                    <th>Released Date</th>
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
            </div>
        </div>
        <div class="tab-pane fade" id="analytics-tab-2-pane" role="tabpanel" aria-labelledby="analytics-tab-2" tabindex="0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card table-card">
                    <div class="card-header">
                        <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h5>Internal Job Order List</h5>
                        </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dt-responsive">
                        <table id="backjob-monitoring-table" class="table table-hover nowrap w-100">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Sales Proposal</th>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Created Date</th>
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
    </div>
  </div>
</div>