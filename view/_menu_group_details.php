 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div id="sticky-action" class="sticky-action">
                <div class="card-header">
                  <div class="row align-items-center">
                    <div class="col-md-6">
                      <h5>Menu Group</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                    <?php                            
                       if (!empty($menuGroupID)) {
                          $dropdown = '<div class="btn-group m-r-5">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                      Action
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-end">';
                          
                          if ($menuGroupCreateAccess['total'] > 0) {
                              $dropdown .= '<li><a class="dropdown-item" href="menu-group.php?new">Create Menu Group</a></li>';
                          }
                          
                          if ($menuGroupDuplicateAccess['total'] > 0) {
                              $dropdown .= '<li><button class="dropdown-item" type="button" data-menu-group-id="' . $menuGroupID . '" id="duplicate-menu-group">Duplicate Menu Group</button></li>';
                          }
                          
                          if ($menuGroupDeleteAccess['total'] > 0) {
                              $dropdown .= '<li><button class="dropdown-item" type="button" data-menu-group-id="' . $menuGroupID . '" id="delete-menu-group-details">Delete Menu Group</button></li>';
                          }
                          
                          $dropdown .= '</ul>
                              </div>';
                      
                          echo $dropdown;
                      }

                      if (!empty($menuGroupID) && $menuGroupWriteAccess['total'] > 0) {
                        echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                              <button type="submit" form="menu-group-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                              <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                      }          
                    ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form id="menu-group-form" method="post" action="#">
                      <?php
                        if(!empty($menuGroupID) && $menuGroupWriteAccess['total'] > 0){
                           echo '<div class="form-group row">
                                                <input type="hidden" id="menu_group_id" name="menu_group_id" value="'. $menuGroupID .'">
                                                <label class="col-lg-2 col-form-label">Menu Group Name <span class="text-danger d-none form-edit">*</span></label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="menu_group_name_label"></label>
                                                    <input type="text" class="form-control d-none form-edit" id="menu_group_name" name="menu_group_name" maxlength="100" autocomplete="off">
                                                </div>
                                                <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger d-none form-edit">*</span></label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                                    <input type="number" class="form-control d-none form-edit" id="menu_group_order_sequence" name="menu_group_order_sequence" min="0">
                                                </div>
                                            </div>';
                        }
                        else{
                          echo '<div class="form-group row">
                                                <label class="col-lg-2 col-form-label">Menu Group Name</label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="menu_group_name_label"></label>
                                                </div>
                                                <label class="col-lg-2 col-form-label">Order Sequence</label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                                </div>
                                            </div>';
                        }
                      ?>
                </form>
            </div>
          </div>
          <?php
          if(!empty($menuGroupID)){
            if($menuItemCreateAccess['total'] > 0){
              $menu_item_create = '<button type="button" class="btn btn-success" id="create-menu-item">Create</button>';
            }

            echo '<div class="row">
                    <div class="col-lg-12">
                      <div class="card">
                        <div id="sticky-action" class="sticky-action">
                          <div class="card-header">
                            <div class="row align-items-center">
                              <div class="col-sm-6">
                                <h5>Menu Item</h5>
                              </div>
                              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                '. $menu_item_create .'
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="dt-responsive table-responsive">
                            <table id="menu-item-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Menu Item</th>
                                  <th>Parent Menu Item</th>
                                  <th>Order Sequence</th>
                                  <th>Action</th>
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
                          <div id="sticky-action" class="sticky-action">
                            <div class="card-header">
                              <div class="row align-items-center">
                                <div class="col-sm-6">
                                  <h5>Log Notes</h5>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="log-notes-scroll" style="height: auto; position: relative;">
                            <div class="card-body p-b-0">
                              '. $userModel->generateLogNotes('menu_group', $menuGroupID) .'
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>';
          }

          if($menuItemCreateAccess['total'] > 0 || $menuItemWriteAccess['total'] > 0){
            echo '<div id="menu-item-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="modal-menu-item-modal" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modal-menu-item-modal-title">Menu Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body" id="modal-body">
                        <form id="menu-item-form" method="post" action="#">
                        <div class="form-group">
                            <label class="form-label" for="menu_item_name">Menu Item Name <span class="text-danger">*</span></label>
                            <input type="hidden" id="menu_item_id" name="menu_item_id">
                            <input type="text" class="form-control" id="menu_item_name" name="menu_item_name" maxlength="100" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="menu_item_order_sequence">Order Sequence <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="menu_item_order_sequence" name="menu_item_order_sequence" min="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="menu_item_url">URL</label>
                            <input type="text" class="form-control" id="menu_item_url" name="menu_item_url" maxlength="50" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="menu_item_icon">Menu Item Icon</label>
                            <input type="text" class="form-control" id="menu_item_icon" name="menu_item_icon" maxlength="150" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Parent Menu Item</label>
                            <select class="form-control modal-select2" name="parent_id" id="parent_id">
                                <option value="">--</option>
                                '. $menuItemModel->generateMenuItemOptions() .'
                            </select>
                        </div>
                    </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit-menu-item-form" form="menu-item-form">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>';
          }

          if($assignMenuItemRoleAccess > 0){
            echo '<div id="assign-menu-item-role-access-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="modal-assign-menu-item-role-access-modal" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modal-assign-menu-item-role-access-modal-title">Assign Menu Item Role Access</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body" id="modal-body"><form id="assign-menu-item-role-access-form" method="post" action="#">
                      <div class="row">
                          <div class="col-md-12">
                              <table id="assign-menu-item-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                  <thead>
                                  <tr>
                                      <th class="all">Role</th>
                                      <th class="all">Read</th>
                                      <th class="all">Write</th>
                                      <th class="all">Create</th>
                                      <th class="all">Delete</th>
                                      <th class="all">Duplicate</th>
                                  </tr>
                                  </thead>
                                  <tbody></tbody>
                              </table>
                          </div>
                      </div>
                  </form></div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit-menu-access-form" form="assign-menu-item-role-access-form">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>';
          }
        ?>