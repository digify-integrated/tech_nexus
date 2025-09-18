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
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#parts-brand-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Brand
                            </a>
                            <div class="collapse " id="parts-brand-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $brandModel->generateBrandCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#parts-category-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                <?php echo $cardLabel; ?> Category
                            </a>
                            <div class="collapse " id="parts-category-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $partsCategoryModel->generatePartsCategoryCheckbox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#parts-subclass-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                <?php echo $cardLabel; ?> Subclass
                            </a>
                            <div class="collapse " id="parts-subclass-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $partsSubclassModel->generatePartsSubclassCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#parts-class-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                <?php echo $cardLabel; ?> Class
                            </a>
                            <div class="collapse " id="parts-class-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $partsClassModel->generatePartsClassCheckbox();
                                    ?>
                                </div>
                            </div>
                        </li>
                    <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#parts-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          <?php echo $cardLabel; ?> Status
                        </a>
                        <div class="collapse " id="parts-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input parts-status-filter" type="radio" name="parts-status-filter" id="parts-status-all" value="" checked />
                                <label class="form-check-label" for="parts-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input parts-status-filter" type="radio" name="parts-status-filter" id="parts-status-draft" value="Draft" />
                                <label class="form-check-label" for="parts-status-draft">Draft</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input parts-status-filter" type="radio" name="parts-status-filter" id="parts-status-for-sale" value="For Sale" />
                                <label class="form-check-label" for="parts-status-for-sale">For Sale/Available</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input parts-status-filter" type="radio" name="parts-status-filter" id="parts-status-out-of-stock" value="Out of Stock" />
                                <label class="form-check-label" for="parts-status-out-of-stock">Out of Stock</label>
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
                              if($company == '1'){
                                echo '<div class="form-check my-2">
                                          <input class="form-check-input company-filter" type="checkbox" id="company-1" value="1" checked/>
                                          <label class="form-check-label" for="company-1">Christian General Motors Inc.</label>
                                      </div>';
                              }
                              else if($company == '2'){
                                echo '<div class="form-check my-2">
                                          <input class="form-check-input company-filter" type="checkbox" id="company-2" value="2" checked/>
                                          <label class="form-check-label" for="company-2">NE TRUCK</label>
                                      </div>';
                              }
                              else{
                                echo '<div class="form-check my-2">
                                          <input class="form-check-input company-filter" type="checkbox" id="company-3" value="3" checked/>
                                          <label class="form-check-label" for="company-3">FUSO TARLAC</label>
                                      </div>';
                              }
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
                          <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#for-sale-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                            For Sale Date
                          </a>
                          <div class="collapse " id="for-sale-date-filter-collapse">
                            <div class="row py-3">
                              <div class="col-12">
                                <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_for_sale_date_start_date" id="filter_for_sale_date_start_date" placeholder="Start Date">
                                <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_for_sale_date_end_date" id="filter_for_sale_date_end_date" placeholder="End Date">
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
                    <input type="text" class="form-control" id="parts_search" placeholder="Search <?php echo $cardLabel; ?>" />
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
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="table-view-tab" data-bs-toggle="pill" data-bs-target="#table-view" type="button" role="tab" aria-controls="table-view" aria-selected="false"><i data-feather="align-justify"></i></button>
                  </li>
                </ul>
              </div>
              <ul class="list-inline ms-auto my-1">
                <?php
                  if($partsCreateAccess['total'] > 0){
                    if($company == '1'){
                      echo '<li class="list-inline-item align-bottom mr-0"><a href="supplies.php?new" class="btn btn-success">Create</a></li>';
                    }
                    else if($company == '2'){
                      echo '<li class="list-inline-item align-bottom mr-0"><a href="netruck-parts.php?new" class="btn btn-success">Create</a></li>';
                    }
                    else{
                      echo '<li class="list-inline-item align-bottom mr-0"><a href="parts.php?new" class="btn btn-success">Create</a></li>';
                    }
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
          <?php
            $viewSalesProposalPartCost = $userModel->checkSystemActionAccessRights($user_id, 130);
          ?>
          <div class="tab-pane fade show active" id="table-view" role="tabpanel" aria-labelledby="table-view-tab" tabindex="0">
            <div class="card table-card">
              <div class="card-body">
                <div class="table-responsive dt-responsive">
                  <table id="parts-table" class="table table-hover wrap w-100">
                    <thead>
                      <tr>
                        <th>
                          <div class="form-check">
                            <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                          </div>
                        </th>
                        <th>Image</th>
                        <th class="w-100"><?php echo $cardLabel; ?></th>
                        <th class="w-100">Category</th>
                        <th class="w-100">Class</th>
                        <th class="w-100">Quantity</th>
                        <?php
                          if($viewSalesProposalPartCost['total'] > 0){
                            echo '<th id="cost_column" class="w-100">Cost</th>';
                          }
                        ?>
                        <th class="w-100">Price</th>
                        <th class="w-100">Status</th>
                        <th class="w-100">Actions</th>
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