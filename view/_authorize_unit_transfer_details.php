<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Authorize Unit Transfer</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';
                            
                if ($authorizeUnitTransferDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-authorize-unit-transfer-details">Delete Authorize Unit Transfer</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($authorizeUnitTransferCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="authorize-unit-transfer.php?new">Create</a>';
                }
            ?>
          </div>
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
                '. $userModel->generateLogNotes('authorize_unit_transfer', $authorizeUnitTransferID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>