 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <h5>Upload Setting</h5>
                  </div>
                  <?php
                      if (empty($uploadSettingID) && $uploadSettingCreateAccess['total'] > 0) {
                        echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    <button type="submit" form="upload-setting-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                                    <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                                </div>';
                      }
                  ?>
                </div>
              </div>
              <div class="card-body">
                <form id="upload-setting-form" method="post" action="#">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Upload Setting Name <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="upload_setting_name" name="upload_setting_name" maxlength="100" autocomplete="off">
                        </div>
                        <label class="col-lg-2 col-form-label">Max File Size <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="number" class="form-control" id="max_file_size" name="max_file_size" min="1">
                                <span class="input-group-text">mb</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Upload Setting Description <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="upload_setting_description" name="upload_setting_description" maxlength="200" autocomplete="off">
                        </div>
                    </div>
                </form>
            </div>
          </div>