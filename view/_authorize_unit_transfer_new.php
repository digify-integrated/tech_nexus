<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Authorize Unit Transfer</h5>
          </div>
          <?php
            if ($authorizeUnitTransferCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="authorize-unit-transfer-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="authorize-unit-transfer-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Warehouse <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <select class="form-control select2" name="warehouse_id" id="warehouse_id">
                    <option value="">--</option>
                    <?php echo $warehouseModel->generateWarehouseOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">User <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <select class="form-control select2" name="user_id1" id="user_id1">
                    <option value="">--</option>
                    <?php echo $userModel->generateUnitTransferUserOption(); ?>
                </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>