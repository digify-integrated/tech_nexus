 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div id="sticky-action" class="sticky-action">
                <div class="card-header">
                  <div class="row align-items-center">
                    <div class="col-md-6">
                      <h5>Menu Group New</h5>
                    </div>
                    <?php                            
                        if (empty($menu_group_id) && $menuGroupCreateAccess > 0) {
                          echo ' <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    <button type="submit" form="menu-group-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                                    <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                                </div>';
                        }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="form-group row">
                    <form id="menu-group-form" method="post" action="#">
                        <label class="col-lg-2 col-form-label">Menu Group Name <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="menu_group_name" name="menu_group_name" maxlength="100" autocomplete="off">
                        </div>
                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger">*</span></label>
                            <div class="col-lg-4">
                                <input type="number" class="form-control" id="menu_group_order_sequence" name="menu_group_order_sequence" min="0">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
          </div>