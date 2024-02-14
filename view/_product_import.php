<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header pb-0">
                <ul class="nav nav-tabs profile-tabs mb-0" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="guide-tab" data-bs-toggle="tab" href="#guide" role="tab" aria-controls="guide" aria-selected="true">Guide</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="references-tab" data-bs-toggle="tab" href="#references" role="tab" aria-controls="references" aria-selected="true">References</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="guide" role="tabpanel" aria-labelledby="guide-tab">
                        <h2 class="mb-3 f-w-500">How it works</h2>
                        <p>Importing products is a simple process that involves preparing your product information in a CSV file and uploading it to our system. Follow these easy steps to seamlessly add your products to your store or database.</p>
                        <ol class="text-md">
                            <li class="mb-2">Begin by downloading this import <a href="./import-files/sample/product.csv" download>file</a> template.</li>
                            <li class="mb-2">
                                <span class="mb-2">Open the downloaded file and fill in the necessary product information.</span>
                                <ul class="mt-2 text-sm">
                                    <li>Product ID</li>
                                    <li>Product Category ID (Required)</li>
                                    <li>Product Subcategory ID (Required)</li>
                                    <li>Company ID (Required)</li>
                                    <li>Product Status</li>
                                    <li>Stock Number (Required)</li>
                                    <li>Engine Number</li>
                                    <li>Chassis Number</li>
                                    <li>Description (Required)</li>
                                    <li>Warehouse ID (Required)</li>
                                    <li>Body Type ID</li>
                                    <li>Length</li>
                                    <li>Length Unit ID (Required if Length is > 0)</li>
                                    <li>Running Hours</li>
                                    <li>Mileage</li>
                                    <li>Color ID</li>
                                    <li>Product Cost</li>
                                    <li>Product Price</li>
                                    <li>Remarks</li>
                                </ul>
                            </li>
                            <li>Save the filled-out file in CSV format. This ensures compatibility with the import process.</li>
                            <li>Locate and click the "Load File" button.</li>
                            <li>Select the CSV file you just saved from your computer.</li>
                            <li>Click the "Submit" button to initiate the import process.</li>
                            <li>Review the data preview and select the specific information you want to import.</li>
                            <li>Click on the "Action" menu and choose "Import Product" from the options.</li>
                            <li>Once the import is complete without errors, confirm that your products are visible and correctly listed.</li>
                        </ol>
                    </div>
                    <div class="tab-pane" id="references" role="tabpanel" aria-labelledby="references-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="accordion card" id="product-category-accordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="product-category-heading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#product-category-collapse" aria-expanded="false" aria-controls="product-category-collapse">
                                                Product Category
                                            </button>
                                        </h2>
                                        <div id="product-category-collapse" class="accordion-collapse collapse" aria-labelledby="product-category-heading" data-bs-parent="#product-category-accordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive dt-responsive">
                                                    <table id="product-category-reference-table" class="table table-hover nowrap w-100">
                                                        <thead>
                                                        <tr>
                                                            <th>Product Category ID</th>
                                                            <th>Product Category</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="product-subcategory-heading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#product-subcategory-collapse" aria-expanded="false" aria-controls="product-subcategory-collapse">
                                                Product Subcategory
                                            </button>
                                        </h2>
                                        <div id="product-subcategory-collapse" class="accordion-collapse collapse" aria-labelledby="product-subcategory-heading" data-bs-parent="#product-subcategory-accordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive dt-responsive">
                                                    <table id="product-subcategory-reference-table" class="table table-hover nowrap w-100">
                                                        <thead>
                                                        <tr>
                                                            <th>Product Subcategory ID</th>
                                                            <th>Product Subcategory</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="company-heading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#company-collapse" aria-expanded="false" aria-controls="company-collapse">
                                                Company
                                            </button>
                                        </h2>
                                        <div id="company-collapse" class="accordion-collapse collapse" aria-labelledby="company-heading" data-bs-parent="#company-accordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive dt-responsive">
                                                    <table id="company-reference-table" class="table table-hover nowrap w-100">
                                                        <thead>
                                                        <tr>
                                                            <th>Company ID</th>
                                                            <th>Company</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="warehouse-heading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#warehouse-collapse" aria-expanded="false" aria-controls="warehouse-collapse">
                                                Warehouse
                                            </button>
                                        </h2>
                                        <div id="warehouse-collapse" class="accordion-collapse collapse" aria-labelledby="warehouse-heading" data-bs-parent="#warehouse-accordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive dt-responsive">
                                                    <table id="warehouse-reference-table" class="table table-hover nowrap w-100">
                                                        <thead>
                                                        <tr>
                                                            <th>Warehouse ID</th>
                                                            <th>Warehouse</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="body-type-heading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#body-type-collapse" aria-expanded="false" aria-controls="body-type-collapse">
                                                Body Type
                                            </button>
                                        </h2>
                                        <div id="body-type-collapse" class="accordion-collapse collapse" aria-labelledby="body-type-heading" data-bs-parent="#body-type-accordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive dt-responsive">
                                                    <table id="body-type-reference-table" class="table table-hover nowrap w-100">
                                                        <thead>
                                                        <tr>
                                                            <th>Body Type ID</th>
                                                            <th>Body Type</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="unit-heading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#unit-collapse" aria-expanded="false" aria-controls="unit-collapse">
                                                Unit
                                            </button>
                                        </h2>
                                        <div id="unit-collapse" class="accordion-collapse collapse" aria-labelledby="unit-heading" data-bs-parent="#unit-accordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive dt-responsive">
                                                    <table id="unit-reference-table" class="table table-hover nowrap w-100">
                                                        <thead>
                                                        <tr>
                                                            <th>Unit ID</th>
                                                            <th>Unit</th>
                                                            <th>Short Name</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="color-heading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#color-collapse" aria-expanded="false" aria-controls="color-collapse">
                                                Color
                                            </button>
                                        </h2>
                                        <div id="color-collapse" class="accordion-collapse collapse" aria-labelledby="color-heading" data-bs-parent="#color-accordion">
                                            <div class="accordion-body">
                                                <div class="table-responsive dt-responsive">
                                                    <table id="color-reference-table" class="table table-hover nowrap w-100">
                                                        <thead>
                                                        <tr>
                                                            <th>Color ID</th>
                                                            <th>Color</th>
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
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
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
                                                <button type="button" class="btn btn-light-success w-100" id="apply-import-filter">Apply</a>
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
                            <div class="col-md-6">
                                <h5>Product Import</h5>
                            </div>
                            <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                <div class="btn-group m-r-10">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                      <li><button class="dropdown-item" type="button" id="import-product">Import Product</button></li>
                                    </ul>
                                </div>
                                <?php
                                    if ($importProduct['total'] > 0) {
                                        echo '
                                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#load-file-offcanvas" aria-controls="load-file-offcanvas" id="add-load-file">Load File</button>';
                                    }
                                ?>
                                <button type="button" class="btn btn-warning" id="filter-button" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dt-responsive">
                            <table id="product-import-table" class="table table-hover nowrap w-100">
                                <thead>
                                    <tr>
                                        <th class="all">
                                            <div class="form-check">
                                                <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                                            </div>
                                        </th>
                                        <th>Product ID</th>
                                        <th>Product Category</th>
                                        <th>Product Subcategory</th>
                                        <th>Company</th>
                                        <th>Product Status</th>
                                        <th>Stock Number</th>
                                        <th>Engine Number</th>
                                        <th>Chassis Number</th>
                                        <th>Description Number</th>
                                        <th>Warehouse</th>
                                        <th>Body Type</th>
                                        <th>Length</th>
                                        <th>Running Hours</th>
                                        <th>Mileage</th>
                                        <th>Color</th>
                                        <th>Product Cost</th>
                                        <th>Product Price</th>
                                        <th>Remarks</th>
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

<?php
if($importProduct['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="load-file-offcanvas" aria-labelledby="load-file-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="load-file-offcanvas-label" style="margin-bottom:-0.5rem">Load Product</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="row mb-2">
                <div class="col-lg-12">
                  <form id="import-product-form" method="post" action="#">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Import File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="import_file" name="import_file">
                        </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-load-file" form="import-product-form">Submit</button>
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>';
}
?>