<div class="row">
  <div class="col-md-12">
    <div class="ecom-wrapper">
      <div class="offcanvas-xxl offcanvas-start ecom-offcanvas" tabindex="-1" id="filter-canvas">
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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#company-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Company
                        </a>
                        <div class="collapse show" id="company-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $companyModel->generateCompanyCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#product-category-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Product Category
                            </a>
                            <div class="collapse show" id="product-category-filter-collapse">
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
                            <div class="collapse show" id="product-subcategory-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $productSubcategoryModel->generateProductSubcategoryCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#warehouse-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Warehouse
                            </a>
                            <div class="collapse show" id="warehouse-filter-collapse">
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
                            <div class="collapse show" id="body-type-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $bodyTypeModel->generateBodyTypeCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#color-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Color
                            </a>
                            <div class="collapse show" id="color-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $colorModel->generateColorCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#product-cost-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Product Cost
                            </a>
                            <div class="collapse show" id="product-cost-filter-collapse">
                                <div class="row py-3">
                                    <div class="col-12">
                                        <input type="number" class="form-control mb-3" autocomplete="off" name="filter_product_cost_min" id="filter_product_cost_min" placeholder="Min" min="0" step="0.01">
                                        <input type="number" class="form-control" autocomplete="off" name="filter_product_cost_max" id="filter_product_cost_max" placeholder="Max" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#product-price-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Product Price
                            </a>
                            <div class="collapse show" id="product-price-filter-collapse">
                                <div class="row py-3">
                                    <div class="col-12">
                                        <input type="number" class="form-control mb-3" autocomplete="off" name="filter_product_price_min" id="filter_product_price_min" placeholder="Min" min="0" step="0.01">
                                        <input type="number" class="form-control" autocomplete="off" name="filter_product_price_max" id="filter_product_price_max" placeholder="Max" min="0" step="0.01">
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
              </ul>
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
                  <button type="button" class="d-xxl-none btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                    Filter
                  </a>
                  <button type="button" class="d-none d-xxl-inline-flex btn btn-warning" data-bs-toggle="collapse" data-bs-target="#filter-canvas">
                    Filter
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row" id="product-card"></div>
          <div class="row" class="d-none" id="load-content">
            <div class="col-lg-12 text-center">
              <div class="spinner-grow text-primary" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>