 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <h5>User Account</h5>
                  </div>
                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                    <?php                            
                       if (!empty($userAccountID)) {
                          $dropdown = '<div class="btn-group m-r-5">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                      Action
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-end">';
                          
                            if ($userAccountCreateAccess['total'] > 0) {
                                $dropdown .= '<li><a class="dropdown-item" href="user-account.php?new">Create User Account</a></li>';
                            }
                            
                            if ($userAccountDuplicateAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-user-account-id="' . $userAccountID . '" id="duplicate-user-account">Duplicate User Account</button></li>';
                            }
                            
                            if ($userAccountDeleteAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-user-account-id="' . $userAccountID . '" id="delete-user-account-details">Delete User Account</button></li>';
                            }
                          
                          $dropdown .= '</ul>
                              </div>';
                      
                          echo $dropdown;
                      }

                      if (!empty($userAccountID) && $userAccountWriteAccess['total'] > 0) {
                        echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                              <button type="submit" form="user-account-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                              <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                      }          
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form id="user-account-form" method="post" action="#">
                      <?php
                        if(!empty($userAccountID) && $userAccountWriteAccess['total'] > 0){
                           echo '<div class="form-group row">
                                                <input type="hidden" id="user_account_id" name="user_account_id" value="'. $userAccountID .'">
                                                <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="file_as_label"></label>
                                                    <input type="text" class="form-control d-none form-edit" id="file_as" name="file_as" maxlength="300" autocomplete="off">
                                                </div>
                                                <label class="col-lg-2 col-form-label">Email <span class="text-danger d-none form-edit">*</span></label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="email_label"></label>
                                                    <div class="input-group d-none form-edit">
                                                        <input type="text" class="form-control" id="email" name="email" maxlength="100" autocomplete="off">
                                                        <span class="input-group-text" id="basic-addon2">'. DEFAULT_EMAIL_EXTENSION .'</span>
                                                    </div>
                                                </div>
                                            </div>';
                        }
                        else{
                          echo '<div class="form-group row">
                                                <label class="col-lg-2 col-form-label">Name</label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="file_as_label"></label>
                                                </div>
                                                <label class="col-lg-2 col-form-label">Email</label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="email_label"></label>
                                                </div>
                                            </div>';
                        }
                      ?>
                </form>
            </div>
          </div>