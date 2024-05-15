<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Contact Directory</h5>
          </div>
          <?php
            if ($contactDirectoryCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="contact-directory-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="contact-directory-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="contact_name" name="contact_name" maxlength="200" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Position</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="position" name="position" maxlength="200" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Location <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="location" id="location">
                    <option value="">--</option>
                    <option value="CGMI">CGMI</option>
                    <option value="Fuso Tarlac">Fuso Tarlac</option>
                    <option value="NE Trucks Yard 1">NE Trucks Yard 1</option>
                    <option value="NE Trucks Yard 2">NE Trucks Yard 2</option>
                    <option value="NE Trucks Yard 3">NE Trucks Yard 3</option>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Directory Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="directory_type" id="directory_type">
                    <option value="">--</option>
                    <option value="Telephone">Telephone</option>
                    <option value="Mobile">Mobile</option>
                    <option value="Email">Email</option>
                </select>
            </div>
          </div>
          <div class="form-group row">
                <label class="col-lg-2 col-form-label">Contact Information <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                <input type="text" class="form-control" id="contact_information" name="contact_information" maxlength="500" autocomplete="off">
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>