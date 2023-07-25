 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <h5>Menu Item</h5>
                  </div>
                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                    <?php                            
                       if (!empty($menuItemID)) {
                          $dropdown = '<div class="btn-group m-r-5">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                      Action
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-end">';
                          
                            if ($menuItemCreateAccess['total'] > 0) {
                                $dropdown .= '<li><a class="dropdown-item" href="menu-item.php?new">Create Menu Item</a></li>';
                            }
                            
                            if ($menuItemDuplicateAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-menu-item-id="' . $menuItemID . '" id="duplicate-menu-item">Duplicate Menu Item</button></li>';
                            }
                            
                            if ($menuItemDeleteAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-menu-item-id="' . $menuItemID . '" id="delete-menu-item-details">Delete Menu Item</button></li>';
                            }
                          
                            $dropdown .= '</ul>
                              </div>';
                      
                          echo $dropdown;
                      }

                      if (!empty($menuItemID) && $menuItemWriteAccess['total'] > 0) {
                        echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                              <button type="submit" form="menu-item-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                              <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                      }          
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form id="menu-item-form" method="post" action="#">
                      <?php
                        if(!empty($menuItemID) && $menuItemWriteAccess['total'] > 0){
                           echo '<div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Menu Item Name <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="menu_item_name_label"></label>
                                        <input type="text" class="form-control d-none form-edit" id="menu_item_name" name="menu_item_name" maxlength="100" autocomplete="off">
                                    </div>
                                    <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                        <input type="number" class="form-control d-none form-edit" id="menu_item_order_sequence" name="menu_item_order_sequence" min="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Menu Group <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <div class="col-form-label form-details fw-normal" id="menu_group_id_label"></div>
                                        <div class="d-none form-edit">
                                            <select class="form-control select2" name="menu_group_id" id="menu_group_id">
                                                <option value="">--</option>
                                                '. $menuGroupModel->generateMenuGroupOptions() .'
                                            </select>
                                        </div>
                                    </div>
                                    <label class="col-lg-2 col-form-label">URL</label>
                                    <div class="col-lg-4">
                                        <div class="col-form-label form-details fw-normal" id="menu_item_url_label"></div>
                                        <input type="text" class="form-control d-none form-edit" id="menu_item_url" name="menu_item_url" maxlength="50" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Menu Item Icon</label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="menu_item_icon_label"></label>
                                        <input type="text" class="form-control d-none form-edit" id="menu_item_icon" name="menu_item_icon" maxlength="150" autocomplete="off">
                                    </div>
                                    <label class="col-lg-2 col-form-label">Parent Menu Item</label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="parent_id_label"></label>
                                        <div class="d-none form-edit">
                                            <select class="form-control select2 d-none form-edit" name="parent_id" id="parent_id">
                                                <option value="">--</option>
                                                '. $menuItemModel->generateMenuItemOptions() .'
                                            </select>
                                        </div>
                                    </div>
                                </div>';
                        }
                        else{
                          echo '<div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Menu Item Name</label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="menu_item_label"></label>
                                    </div>
                                    <label class="col-lg-2 col-form-label">Order Sequence</label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Menu Group</label>
                                    <div class="col-lg-4">
                                        <div class="col-form-label form-details fw-normal" id="menu_group_id_label"></div>
                                    </div>
                                    <label class="col-lg-2 col-form-label">URL</label>
                                    <div class="col-lg-4">
                                        <div class="col-form-label form-details fw-normal" id="menu_item_url_label"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Menu Item Icon</label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="menu_item_icon_label"></label>
                                    </div>
                                    <label class="col-lg-2 col-form-label">Parent Menu Item</label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="parent_id_label"></label>
                                    </div>
                                </div>';
                        }
                      ?>
                </form>
            </div>
          </div>
          <?php
          if(!empty($menuItemID)){
            if($updateMenuItemRoleAccess['total'] > 0){
                $menu_item_button = '<button type="button" class="btn btn-warning" data-menu-item-id="' . $menuItemID . '" id="add-role-access">Add Role</button>
                              <button type="submit" class="btn btn-info edit-access-details" id="edit-access">Edit</button>
                              <button type="submit" form="update-role-access-form" class="btn btn-success update-access d-none" id="submit-menu-access">Save</button>
                              <button type="button" id="discard-access-update" class="btn btn-outline-danger update-access d-none">Discard</button>';
            }

            echo '<div class="row">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-header">
                          <div class="row align-items-center">
                            <div class="col-sm-12">
                              <h5>Sub Menu Item</h5>
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="dt-responsive table-responsive">
                            <table id="sub-menu-item-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                              <thead>
                                <tr>
                                  <th>Sub Menu Item</th>
                                  <th>Menu Group</th>
                                  <th>Order Sequence</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-header">
                          <div class="row align-items-center">
                            <div class="col-sm-6">
                              <h5>Role Access</h5>
                            </div>
                            <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                            '. $menu_item_button .'
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                        <form id="update-role-access-form" method="post" action="#">
                          <div class="dt-responsive table-responsive">
                              <table id="update-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                <thead>
                                  <tr>
                                    <th>Role</th>
                                    <th class="all">Read Access</th>
                                    <th class="all">Write Access</th>
                                    <th class="all">Create Access</th>
                                    <th class="all">Delete Access</th>
                                    <th class="all">Duplicate Access</th>
                                    <th class="all">Action</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                              </table>
                          </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
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
                            '. $userModel->generateLogNotes('menu_item', $menuItemID) .'
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>';


                    if($updateMenuItemRoleAccess['total'] > 0){
                      echo '<div id="add-role-access-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="modal-add-role-access-modal" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                                  <div class="modal-content">
                                  <div class="modal-header">
                                      <h5 class="modal-title" id="modal-add-role-access-modal-title">Assign Role Access</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body" id="modal-body">
                                    <form id="add-role-access-form" method="post" action="#">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <table id="add-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                                  <thead>
                                                  <tr>
                                                      <th class="all">Role</th>
                                                      <th class="all">Assign</th>
                                                  </tr>
                                                  </thead>
                                                  <tbody></tbody>
                                              </table>
                                          </div>
                                      </div>
                                    </form>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      <button type="submit" class="btn btn-primary" id="add-menu-access" form="add-role-access-form">Submit</button>
                                  </div>
                                  </div>
                              </div>
                              </div>';
                  }
           
            }
        ?>