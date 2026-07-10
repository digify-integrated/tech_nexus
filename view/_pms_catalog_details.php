<?php
  
  $lock_unlock_pms_catalog = $userModel->checkSystemActionAccessRights($user_id, 239);

  $pmsCatalogDetails = $pmsCatalogModel->getPMSCatalog($pmsCatalogID);
  $status = $pmsCatalogDetails['status'] ?? 'open';
  $company_id = $pmsCatalogDetails['company_id'] ?? null;

  $disabled = '';
  if($status == 'locked'){
    $disabled = 'disabled';
  }
?>
<input type="hidden" id="status" value="<?php echo $status; ?>">


<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-4">
            <h5>PMS Catalog</h5>
          </div>
          <div class="col-md-8 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                 
              if ($lock_unlock_pms_catalog['total'] > 0 && $status == 'open') {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="lock-pms-catalog">Lock PMS Catalog</button></li>';
              }
                 
              if ($lock_unlock_pms_catalog['total'] > 0 && $status == 'locked') {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="unlock-pms-catalog">Unlock PMS Catalog</button></li>';
              }
                        
              if ($pmsCatalogDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-pms-catalog-details">Delete PMS Catalog</button></li>';
              }
                      
              $dropdown .= '</ul>
                          </div>';
                  
              echo $dropdown;

              if ($pmsCatalogWriteAccess['total'] > 0) {
                echo '
                      <button type="submit" form="pms-catalog-form" class="btn btn-success" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger me-1">Discard</button>';
              }

              if ($pmsCatalogCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success form-details" href="pms-catalog.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="pms-catalog-form" method="post" action="#">
         <div class="form-group row">
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <input type="text" class="form-control" id="pms_catalog_name" name="pms_catalog_name" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="description" name="description" maxlength="200" autocomplete="off" <?php echo $disabled; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                <select class="form-control select2" name="company_id" id="company_id" <?php echo $disabled; ?>>
                  <option value="">--</option>
                  <?php echo $companyModel->generateCompanyOptions(); ?>
                </select>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Product Category</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="pms-catalog-form" method="post" action="#">
          <div class="form-group row">
              <div class="col-lg-12">
                <select class="form-control select2" name="product_subcategory_id" id="product_subcategory_id" multiple="multiple" <?php echo $disabled; ?>>
                  <?php echo $productSubcategoryModel->generateGroupedProductSubcategoryOptions(); ?>
                </select>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>PMS Matrix</h5>
          </div>
          <div class="col-md-6 text-sm-end">
            <?php
              if($status == 'open'){
                  echo '<button type="button" class="btn btn-outline-secondary" data-bs-toggle="offcanvas" data-bs-target="#pms-matrix-offcanvas" aria-controls="pms-matrix-offcanvas" id="create-pms-matrix">Create</button>';
              }
            ?>            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row" id="pms-matrix-container"></div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pms-matrix-offcanvas" aria-labelledby="pms-matrix-offcanvas-label">

        <div class="offcanvas-header">
            <h2 id="pms-matrix-offcanvas-label" class="mb-0">
                PMS Matrix
            </h2>

            <button type="button"
                    class="btn-close text-reset"
                    data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">

            <form id="pms-matrix-form">

                <input type="hidden"
                       id="pms_matrix_id"
                       name="pms_matrix_id">

                <!-- Odometer -->
                <div class="mb-3">
                    <label class="form-label">
                        Odometer
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                        <input
                            type="number"
                            class="form-control"
                            id="odometer"
                            name="odometer"
                            min="0">

                        <span class="input-group-text">
                            KM
                        </span>
                    </div>
                </div>

                <!-- Months Interval -->
                <div class="mb-3">
                    <label class="form-label">
                        Months Interval
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        id="months_interval"
                        name="months_interval"
                        min="1"
                        value="1">
                </div>

                <!-- Consumables -->
                <div class="mb-3">
                    <label class="form-label">
                        Consumables Cost
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">₱</span>

                        <input
                            type="number"
                            class="form-control"
                            id="consumables"
                            name="consumables"
                            step="0.01"
                            min="0"
                            value="500">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Consumables Quantity
                    </label>

                    <div class="input-group">
                        <input
                            type="number"
                            class="form-control"
                            id="consumables_quantity"
                            name="consumables_quantity"
                            step="0.01"
                            min="0">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Consumables Total
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">₱</span>

                        <input
                            type="number"
                            class="form-control"
                            id="consumables_total"
                            name="consumables_total"
                            step="0.01"
                            min="0" readonly>
                    </div>
                </div>

                <!-- Labor -->
                <div class="mb-3">
                    <label class="form-label">
                        Labor Cost
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">₱</span>

                        <input
                            type="number"
                            class="form-control"
                            id="labor_cost"
                            name="labor_cost"
                            step="0.01"
                            min="0"
                            value="900">
                    </div>
                </div>

                

                <div class="mb-3">
                    <label class="form-label">
                        Labor Hours
                    </label>

                    <div class="input-group">
                        <input
                            type="number"
                            class="form-control"
                            id="labor_quantity"
                            name="labor_quantity"
                            step="0.01"
                            min="0">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Labor Total
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">₱</span>

                        <input
                            type="number"
                            class="form-control"
                            id="labor_total"
                            name="labor_total"
                            step="0.01"
                            min="0" readonly>
                    </div>
                </div>

            </form>

            <div class="mt-5">
                <button
                    type="submit"
                    class="btn btn-primary"
                    id="submit-pms-matrix"
                    form="pms-matrix-form">
                    Submit
                </button>

                <button
                    class="btn btn-light-danger"
                    data-bs-dismiss="offcanvas">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pms-matrix-parts-offcanvas" aria-labelledby="pms-matrix-parts-offcanvas-label">

        <div class="offcanvas-header">
            <h2 id="pms-matrix-parts-offcanvas-label" class="mb-0">
                PMS Matrix Parts
            </h2>

            <button type="button"
                    class="btn-close text-reset"
                    data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">

            <form id="pms-matrix-parts-form">

                <input type="hidden"
                       id="pms_matrix_part_id"
                       name="pms_matrix_part_id">

                <input type="hidden"
                       id="pms_matrix_id2"
                       name="pms_matrix_id2">

                <div class="mb-3">
                    <label class="form-label">
                        Part
                        <span class="text-danger">*</span>
                    </label>

                    <select class="form-control select2" name="part_id" id="part_id">
                      <option value="">--</option>
                      <?php echo $partsModel->generateGeneratePartByCompanyOptions($company_id); ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Qty.
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="number"
                        class="form-control"
                        id="required_quantity"
                        name="required_quantity"
                        min="0.01" step="0.01">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Unit
                        <span class="text-danger">*</span>
                    </label>

                    <select class="form-control select2" name="unit_id" id="unit_id">
                      <option value="">--</option>
                       <?php echo $unitModel->generateUnitOptions(); ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Alternate Parts
                    </label>

                     <select class="form-control select2" name="alternate_part_id[]" id="alternate_part_id" multiple="multiple">
                      <?php echo $partsModel->generateGeneratePartByCompanyOptions($company_id); ?>
                    </select>
                </div>

            </form>

            <div class="mt-5">
                <button
                    type="submit"
                    class="btn btn-primary"
                    id="submit-pms-matrix-parts"
                    form="pms-matrix-parts-form">
                    Submit
                </button>

                <button
                    class="btn btn-light-danger"
                    data-bs-dismiss="offcanvas">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>