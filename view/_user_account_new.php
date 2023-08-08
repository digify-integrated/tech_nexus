 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <h5>User Account</h5>
                  </div>
                  <?php
                      if (empty($userAccountID) && $userAccountCreateAccess['total'] > 0) {
                        echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    <button type="submit" form="user-account-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                                    <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                                </div>';
                      }
                  ?>
                </div>
              </div>
              <div class="card-body">
                <form id="user-account-form" method="post" action="#">
                  <div class="form-group row">
                    <label class="col-lg-1 col-form-label">Name <span class="text-danger">*</span></label>
                    <div class="col-lg-5">
                      <input type="text" class="form-control" id="file_as" name="file_as" maxlength="300" autocomplete="off">
                    </div>
                    <label class="col-lg-1 col-form-label">Email <span class="text-danger">*</span></label>
                    <div class="col-lg-5">
                      <input type="email" class="form-control" id="email" name="email" maxlength="100" autocomplete="off">
                    </div>
                  </div>
                </form>
            </div>
          </div>