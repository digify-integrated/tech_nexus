<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Customer</h5>
          </div>
          <?php
            if ($customerCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="add-customer-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="add-customer-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">First Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="first_name" name="first_name" maxlength="300" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Middle Name</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="middle_name" name="middle_name" maxlength="300" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Last Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="last_name" name="last_name" maxlength="300" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Suffix</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="suffix" name="suffix" maxlength="10" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Business/Corporate Name</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="corporate_name" name="corporate_name" maxlength="300" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Nickname</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="nickname" name="nickname" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Birthday <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="birthday" name="birthday" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
            <label class="col-lg-2 col-form-label">Birth Place <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="birth_place" name="birth_place" maxlength="1000" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Gender <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="gender" id="gender">
                <option value="">--</option>
                <?php echo $genderModel->generateGenderOptions(); ?>
              </select>
            </div>
            <label class="col-lg-2 col-form-label">Civil Status <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="civil_status" id="civil_status">
                <option value="">--</option>
                <?php echo $civilStatusModel->generateCivilStatusOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Religion</label>
            <div class="col-lg-4">
              <select class="form-control select2" name="religion" id="religion">
                <option value="">--</option>
                <?php echo $religionModel->generateReligionOptions(); ?>
              </select>
            </div>
            <label class="col-lg-2 col-form-label">Blood Type</label>
            <div class="col-lg-4">
              <select class="form-control select2" name="blood_type" id="blood_type">
                <option value="">--</option>
                <?php echo $bloodTypeModel->generateBloodTypeOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">            
            <label class="col-lg-2 col-form-label">Height</label>
            <div class="col-lg-4">
              <div class="input-group">
                <input type="number" min="0" step="0.01" class="form-control" id="height" name="height">
                <span class="input-group-text">cm</span>
              </div>
            </div>
            <label class="col-lg-2 col-form-label">Weight</label>
            <div class="col-lg-4">
              <div class="input-group">
                <input type="number" min="0" step="0.01" class="form-control" id="weight" name="weight">
                <span class="input-group-text">kg</span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Bio</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="bio" name="bio" maxlength="1000" rows="5"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>