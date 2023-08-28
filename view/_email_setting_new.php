<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Email Setting</h5>
          </div>
          <?php
            if ($emailSettingCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="system-setting-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="system-setting-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="system_setting_name" name="system_setting_name" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Mail Host <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="mail_host" name="mail_host" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="system_setting_description" name="system_setting_description" maxlength="200" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Mail Username <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="mail_username" name="mail_username" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Mail Encryption <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="mail_encryption" id="mail_encryption">
                    <option value="">--</option>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">SMTP Authentication <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="smtp_auth" id="smtp_auth">
                    <option value="0">False</option>
                    <option value="1">True</option>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Mail From Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" maxlength="200" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Port <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="port" name="port" maxlength="10" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Mail Password <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="password" class="form-control" id="mail_password" name="mail_password" maxlength="250" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">SMTP Auto TLS <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="smtp_auto_tls" id="smtp_auto_tls">
                    <option value="0">False</option>
                    <option value="1">True</option>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Mail From Email <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="mail_from_email" name="mail_from_email" maxlength="200" autocomplete="off">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>