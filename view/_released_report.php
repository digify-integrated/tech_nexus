<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Released Sales Proposal List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <div class="btn-group m-r-10">
              <button type="button" 
                        class="btn btn-outline-secondary dropdown-toggle" 
                        data-bs-toggle="dropdown">
                    Export
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item" type="button" id="export-csv">
                            Export
                        </button>
                    </li>
                </ul>
            </div>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#filter-modal">
              Filter
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="released-report-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Customer</th>
                <th>DR No.</th>
                <th>DR Date</th>
                <th>Stock No.</th>
                <th>Engine</th>
                <th>Chassis</th>
                <th>Plate No.</th>
                <th>Description</th>
                <th>SRP</th>
                <th>Discounts</th>
                <th>Add-On</th>
                <th>Net</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade modal-animate" id="filter-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-appraisal-source-form" method="post" action="#">
                    <input type="hidden" id="ci_report_appraisal_source_id" name="ci_report_appraisal_source_id">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Company</label>
                            <div class="row py-3">
                              <div class="col-12">
                                <?php
                                  echo $companyModel->generateCompanyCheckBox();
                                ?>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">DR Date</label>
                            <div class="row py-3">
                              <div class="col-12">
                                <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_release_start_date" id="filter_release_start_date" placeholder="Start Date" value="<?php echo date('m/01/Y'); ?>">
                                <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_release_end_date" id="filter_release_end_date" placeholder="End Date" value="<?php echo date('m/t/Y'); ?>">
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Product Type</label>
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
                                  <input class="form-check-input product-type-filter" type="checkbox" id="product-type-repair" value="Repair"/>
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
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#ci-collateral-modal" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="apply-filter">Apply</button>
            </div>
        </div>
    </div>
</div>