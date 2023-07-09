 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="sticky-action">
                <div class="card-header">
                  <div class="row align-items-center">
                    <div class="col-md-6">
                      <h5>Menu Item</h5>
                    </div>
                    <?php
                        if (empty($menu_item_id) && $menuItemCreateAccess > 0) {
                          echo ' <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    <button type="submit" form="menu-item-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                                    <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                                </div>';
                        }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form id="menu-item-form" method="post" action="#">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Menu Item Name <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="menu_item_name" name="menu_item_name" maxlength="100" autocomplete="off">
                        </div>
                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <input type="number" class="form-control" id="menu_item_order_sequence" name="menu_item_order_sequence" min="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Menu Group <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <select class="form-control select2" name="menu_group_id" id="menu_group_id">
                                <option value="">--</option>
                                <?php echo $menuGroupModel->generateMenuGroupOptions(); ?>
                            </select>
                        </div>
                        <label class="col-lg-2 col-form-label">URL</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="menu_item_url" name="menu_item_url" maxlength="50" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Menu Item Icon</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="menu_item_icon" name="menu_item_icon" maxlength="150" autocomplete="off">
                        </div>
                        <label class="col-lg-2 col-form-label">Parent Menu Item</label>
                        <div class="col-lg-4">
                            <select class="form-control select2" name="parent_id" id="parent_id">
                                <option value="">--</option>
                                '. $this->generate_menu_item_options() .'
                                <?php echo $menuItemModel->generateMenuItemOptions(); ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
          </div>