<div class="row">
  <div class="col-md-12">
    <div class="ecom-wrapper">
      <div class="offcanvas offcanvas-start ecom-offcanvas" tabindex="-1" id="filter-canvas">
        <div class="offcanvas-body p-0 sticky-xxl-top">
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
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#product-category-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Product Category
                            </a>
                            <div class="collapse " id="product-category-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $productCategoryModel->generateProductCategoryCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#product-subcategory-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Product Subcategory
                            </a>
                            <div class="collapse " id="product-subcategory-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $productSubcategoryModel->generateProductSubcategoryCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                    <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#product-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Product Status
                        </a>
                        <div class="collapse " id="product-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-all" value="" checked />
                                <label class="form-check-label" for="product-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-draft" value="Draft" />
                                <label class="form-check-label" for="product-status-draft">Draft</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-for-sale" value="For Sale" />
                                <label class="form-check-label" for="product-status-for-sale">For Sale</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-with-application" value="With Application" />
                                <label class="form-check-label" for="product-status-with-application">With Application</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-on-process" value="On-Process" />
                                <label class="form-check-label" for="product-status-on-process">On-Process</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-ready-for-release" value="Ready For Release" />
                                <label class="form-check-label" for="product-status-ready-for-release">Ready For Release</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-ready-for-dr" value="For DR" />
                                <label class="form-check-label" for="product-status-ready-for-dr">For DR</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-sold" value="Sold" />
                                <label class="form-check-label" for="product-status-sold">Sold</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-rented" value="Rented" />
                                <label class="form-check-label" for="product-status-rented">Rented</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-returned" value="Returned" />
                                <label class="form-check-label" for="product-status-returned">Returned</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input product-status-filter" type="radio" name="product-status-filter" id="product-status-ropa" value="ROPA" />
                                <label class="form-check-label" for="product-status-ropa">ROPA</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2 d-none">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#company-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Company
                        </a>
                        <div class="collapse " id="company-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $companyModel->generateCompanyCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                        <li class="list-group-item px-0 py-2 d-none">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#warehouse-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Warehouse
                            </a>
                            <div class="collapse " id="warehouse-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $warehouseModel->generateWarehouseCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#body-type-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Body Type
                            </a>
                            <div class="collapse " id="body-type-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $bodyTypeModel->generateBodyTypeCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2 d-none">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#color-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Color
                            </a>
                            <div class="collapse " id="color-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $colorModel->generateColorCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2 d-none">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#product-cost-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Product Cost
                            </a>
                            <div class="collapse " id="product-cost-filter-collapse">
                                <div class="row py-3">
                                    <div class="col-12">
                                        <input type="number" class="form-control mb-3" autocomplete="off" name="filter_product_cost_min" id="filter_product_cost_min" placeholder="Min" min="0" step="0.01">
                                        <input type="number" class="form-control" autocomplete="off" name="filter_product_cost_max" id="filter_product_cost_max" placeholder="Max" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2 d-none">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#product-price-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Product Price
                            </a>
                            <div class="collapse " id="product-price-filter-collapse">
                                <div class="row py-3">
                                    <div class="col-12">
                                        <input type="number" class="form-control mb-3" autocomplete="off" name="filter_product_price_min" id="filter_product_price_min" placeholder="Min" min="0" step="0.01">
                                        <input type="number" class="form-control" autocomplete="off" name="filter_product_price_max" id="filter_product_price_max" placeholder="Max" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2 d-none">
                          <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#created-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                            Transaction Date
                          </a>
                          <div class="collapse " id="created-date-filter-collapse">
                            <div class="row py-3">
                              <div class="col-12">
                                <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_created_date_start_date" id="filter_created_date_start_date" placeholder="Start Date">
                                <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_created_date_end_date" id="filter_created_date_end_date" placeholder="End Date">
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
        <div class="card">
          <div class="card-body p-3">
            <div class="d-sm-flex align-items-center">
              <ul class="list-inline me-auto my-1">
                <li class="list-inline-item">
                  <div class="form-search">
                    <i class="ti ti-search"></i>
                    <input type="text" class="form-control" id="product_search" placeholder="Search Product" />
                  </div>
                </li>
                <li class="list-inline-item">
                  <select class="form-select" id="datatable-length">
                    <option value="-1">All</option>
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                  </select>
                </li>
              </ul>
              <div class="col-auto">
                <ul class="nav nav-pills nav-price" id="pills-tab" role="tablist">
                  <!--<li class="nav-item" role="presentation">
                    <button class="nav-link" id="card-view-tab" data-bs-toggle="pill" data-bs-target="#card-view" type="button" role="tab" aria-controls="card-view" aria-selected="true"><i data-feather="grid"></i></button>
                  </li>-->
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="table-view-tab" data-bs-toggle="pill" data-bs-target="#table-view" type="button" role="tab" aria-controls="table-view" aria-selected="false"><i data-feather="align-justify"></i></button>
                  </li>
                </ul>
              </div>
              <ul class="list-inline ms-auto my-1">
                <?php
                  if($productCreateAccess['total'] > 0){
                    echo '<li class="list-inline-item align-bottom mr-0"><a href="product.php?new" class="btn btn-success">Create</a></li>';
                  }

                  if($importProduct['total'] > 0){
                    echo ' <a href="product.php?import" class="btn btn-info">Import</a>';
                  }
                ?>
                <li class="list-inline-item align-bottom">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li><button class="dropdown-item" type="button" id="print-qr-code">Print QR Code</button></li>
                    </ul>
                </li>
                <li class="list-inline-item align-bottom">
                  <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                    Filter
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="tab-content" id="pills-tabContent">
          <!--<div class="tab-pane fade" id="card-view" role="tabpanel" aria-labelledby="card-view-tab" tabindex="0">
            <div class="row" id="product-card"></div>
              <div class="row" class="d-none" id="load-content">
                <div class="col-lg-12 text-center">
                  <div class="spinner-grow text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>
              </div>
            </div>  
          </div>-->
          <div class="tab-pane fade show active" id="table-view" role="tabpanel" aria-labelledby="table-view-tab" tabindex="0">
            <div class="card table-card">
              <div class="card-body">
                <div class="table-responsive dt-responsive">
                  <table id="product-table" class="table table-hover wrap w-100">
                    <thead>
                      <tr>
                        <th class="all">
                          <div class="form-check">
                            <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                          </div>
                        </th>
                        <th>Image</th>
                        <th>Stock No.</th>
                        <th>Category</th>
                        <th>Engine No.</th>
                        <th>Chassis No.</th>
                        <th>Body Type</th>
                        <th>Color</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Status</th>
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