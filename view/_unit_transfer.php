<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Unit Transfer List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($unitTransferCreateAccess['total'] > 0){
                $action = '';

                if($unitTransferCreateAccess['total'] > 0){
                  $action .= '<a href="javascript:void(0);" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#scanModal" id="scan-modal">Scan</a>';
                }
                              
                echo $action;
              }
            ?>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="unit-transfer-table" class="table table-hover text-wrap w-100">
            <thead>
              <tr>
                <th>Unit</th>
                <th>Transfer</th>
                <th>Transfer Details</th>
                <th>Receive Details</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="text-wrap"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade modal-animate" id="scanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="reader" class="w-full"></div>
            </div>
            <audio id="scanSound" src="./assets/audio/scan.mp3"></audio>
            <div class="modal-footer">
                <button type="button" id="closeScannerBtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="unitTransferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="unit-transfer-form" method="post" action="#">
                            <div class="form-group row d-none" id="transferred-from-row">
                                <div class="col-lg-12">
                                    <label class="form-label">Transfer From <span class="text-danger">*</span></label>
                                    <input type="hidden" id="product_id" name="product_id">
                                    <select class="form-control select2" name="transferred_from" id="transferred_from">
                                        <option value="">--</option>
                                        <?php echo $warehouseModel->generateWarehouseOptions(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="form-label">Transfer To <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="transferred_to" id="transferred_to">
                                        <option value="">--</option>
                                        <?php echo $warehouseModel->generateWarehouseOptions(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="form-label">Remarks</label>
                                    <textarea class="form-control" id="transfer_remarks" name="transfer_remarks" maxlength="500"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="submit-unit-transfer" form="unit-transfer-form">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="unitReceiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="unit-receive-form" method="post" action="#">
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <label class="form-label">Remarks</label>
                                    <input type="hidden" id="transfer_unit_id" name="transfer_unit_id">
                                    <textarea class="form-control" id="receive_remarks" name="receive_remarks" maxlength="500"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="submit-unit-receive" form="unit-receive-form">Submit</button>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="unit-transfer-offcanvas" aria-labelledby="unit-transfer-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="unit-transfer-offcanvas-label" style="margin-bottom:-0.5rem">Update Unit Transfer</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="update-unit-transfer-form" method="post" action="#">
            <div class="form-group row">
                <div class="col-lg-12">
                    <label class="form-label">Transfer To <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="transferred_to_update" id="transferred_to_update">
                        <option value="">--</option>
                        <?php echo $warehouseModel->generateWarehouseOptions(); ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12">
                    <label class="form-label">Remarks</label>
                    <textarea class="form-control" id="transfer_remarks_update" name="transfer_remarks_update" maxlength="500"></textarea>
                </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-update-unit-transfer" form="update-unit-transfer-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>