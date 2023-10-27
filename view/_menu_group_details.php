<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Menu Group</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                              Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">';
        
                if ($menuGroupDuplicateAccess['total'] > 0) {
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-menu-group">Duplicate Menu Group</button></li>';
                }
                        
                if ($menuGroupDeleteAccess['total'] > 0) {
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-menu-group-details">Delete Menu Group</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($menuGroupWriteAccess['total'] > 0) {
                  echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                        <button type="submit" form="menu-group-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                }

                if ($menuGroupCreateAccess['total'] > 0) {
                  echo '<a class="btn btn-success m-r-5 form-details" href="menu-group.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="menu-group-form" method="post" action="#">
          <?php
            if($menuGroupWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
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
                      <label class="col-lg-2 col-form-label">Name</label>
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
  </div>
<?php
  if($menuItemCreateAccess['total'] > 0){
    $menuItemCreate = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#menu-item-offcanvas" aria-controls="menu-item-offcanvas" id="add-menu-item">Add Menu Item</button>';
  }

  echo '<div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>Menu Item</h5>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                  '. $menuItemCreate .'
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="menu-item-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>Menu Item</th>
                      <th>Parent Menu Item</th>
                      <th>Actions</th>
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
                '. $userModel->generateLogNotes('menu_group', $menuGroupID) .'
              </div>
            </div>
          </div>
        </div>';

  if($menuItemCreateAccess['total'] > 0 || $menuItemWriteAccess['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="menu-item-offcanvas" aria-labelledby="menu-item-offcanvas-label">
              <div class="offcanvas-header">
                <h2 id="menu-item-offcanvas-label" style="margin-bottom:-0.5rem">Menu Item</h2>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <div class="alert alert-success alert-dismissible mb-4" role="alert">
                  The menu item is a navigational element used to add options or links within a menu group, facilitating easy navigation and access to specific content or features.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <form id="menu-item-form" method="post" action="#">
                      <div class="form-group">
                        <label class="form-label" for="menu_item_name">Name <span class="text-danger">*</span></label>
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
                        <label class="form-label" for="menu_item_icon">Icon</label>
                        <input type="text" class="form-control" id="menu_item_icon" name="menu_item_icon" maxlength="150" autocomplete="off">
                      </div>
                      <div class="form-group">
                        <label class="form-label">Parent Menu Item</label>
                        <select class="form-control offcanvas-select2" name="parent_id" id="parent_id">
                          <option value="">--</option>
                          '. $menuItemModel->generateMenuItemOptions() .'
                        </select>
                      </div>
                    </form>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-menu-item-form" form="menu-item-form">Submit</button>
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>';
  }
?>
</div>