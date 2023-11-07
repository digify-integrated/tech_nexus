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
              $dropdown = '<div class="btn-group m-r-5">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                              Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">';
              
              if ($menuItemDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-menu-item">Duplicate Menu Item</button></li>';
              }
                            
              if ($menuItemDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-menu-item-details">Delete Menu Item</button></li>';
              }
                          
              $dropdown .= '</ul>
                            </div>';
                      
              echo $dropdown;

              if ($menuItemWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="menu-item-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($menuItemCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="menu-item.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="menu-item-form" method="post" action="#">
          <?php
            if($menuItemWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
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
                        <label class="col-form-label form-details fw-normal" id="menu_group_id_label"></label>
                        <div class="d-none form-edit">
                          <select class="form-control select2" name="menu_group_id" id="menu_group_id">
                            <option value="">--</option>
                            '. $menuGroupModel->generateMenuGroupOptions() .'
                          </select>
                        </div>
                      </div>
                      <label class="col-lg-2 col-form-label">URL</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="menu_item_url_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="menu_item_url" name="menu_item_url" maxlength="50" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Icon</label>
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
                      <label class="col-lg-2 col-form-label">Name</label>
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
                        <label class="col-form-label form-details fw-normal" id="menu_group_id_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">URL</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="menu_item_url_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Icon</label>
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
  </div>
<?php
  if($updateMenuItemRoleAccess['total'] > 0){
    $menuItemButton = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-menu-item-role-access-offcanvas" aria-controls="add-menu-item-role-access-offcanvas" id="add-menu-item-role-access">Assign Role</button>';
  }

  echo '<div class="col-lg-12">
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
                <table id="sub-menu-item-table" class="table table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>Sub Menu Item</th>
                      <th>Menu Group</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>Role Access</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  '. $menuItemButton .'
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="update-menu-item-role-access-table" class="table table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>Role</th>
                      <th class="all">Read Access</th>
                      <th class="all">Write Access</th>
                      <th class="all">Create Access</th>
                      <th class="all">Delete Access</th>
                      <th class="all">Duplicate Access</th>
                      <th class="all">Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
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
        </div>';

  
  if($updateMenuItemRoleAccess['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="add-menu-item-role-access-offcanvas" aria-labelledby="add-menu-item-role-access-offcanvas-label">
              <div class="offcanvas-header">
                <h2 id="add-menu-item-role-access-offcanvas-label" style="margin-bottom:-0.5rem">Assign Role</h2>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <div class="alert alert-success alert-dismissible mb-4" role="alert">
                  This table is used to specify and assign authorized roles for various system actions, ensuring controlled access and permissions within the system.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <form id="add-menu-item-role-access-form" method="post" action="#">
                      <div class="row">
                        <div class="col-md-12">
                          <table id="add-menu-item-role-access-table" class="table table-hover table-bordered nowrap w-100 dataTable">
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
              </div>
              <div class="row mt-4">
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-add-menu-item-role-access" form="add-menu-item-role-access-form">Submit</button>
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>';
  }
?>
</div>