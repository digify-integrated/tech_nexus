<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Lead</h5>
          </div>

          <?php
            if ($leadCreateAccess['total'] > 0) {
              echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="lead-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>

      <div class="card-body">
        <form id="lead-form" method="post" action="#">

        <div class="mb-4">
          

    <div class="mb-2">
      <h6 class="text-muted fw-semibold mb-0">Lead Details</h6>
      <small class="text-muted">Qualification and status</small>
    </div>

    <div class="row mb-4">

      <div class="col-lg-4">
        <label class="form-label small text-muted">Inquiry Type <span class="text-danger">*</span></label>
          <select class="form-control select2"
                  id="inquiry_type_id" name="inquiry_type_id">
            <option value="">Select</option>
            <?php echo $inquiryTypeModel->generateInquiryTypeOptions(); ?>
          </select>
        </div>


      <div class="col-lg-4">
        <label class="form-label small text-muted">Inquiry Date <span class="text-danger">*</span></label>
          <div class="input-group date">
            <input type="text" class="form-control future-date-restricted-datepicker" id="inquiry_date" name="inquiry_date" autocomplete="off">
            <span class="input-group-text">
              <i class="feather icon-calendar"></i>
            </span>
          </div>
        </div>

        <div class="col-lg-4">
          <label class="form-label small text-muted">Stock Number</label>
          <select class="form-control select2"
                  id="stock_number" name="stock_number">
            <option value="">Select stock</option>
            <?php echo $productModel->generateForSaleProductOptions(); ?>
          </select>
        </div>
      </div>

    <div class="row mb-4">
        <div class="col-lg-4">
          <label class="form-label small text-muted">Lead Status <span class="text-danger">*</span></label>
          <select class="form-control select2"
                  id="lead_status_id" name="lead_status_id">
            <option value="">Select</option>
            <?php echo $leadStatusModel->generateLeadStatusOptions('Lead'); ?>
          </select>
        </div>
        <div class="col-lg-4">
          <label class="form-label small text-muted">Lead Source <span class="text-danger">*</span></label>
          <select class="form-control select2"
                  id="lead_source_id" name="lead_source_id">
            <option value="">Select</option>
            <?php echo $leadSourceModel->generateLeadSourceOptions(); ?>
          </select>
        </div>
        <div class="col-lg-4">
          <label class="form-label small text-muted">Lead Priority </label>
          <select class="form-control select2" id="lead_priority" name="lead_priority">
            <option value="">Select</option>
            <option value="Hot">Hot</option>
            <option value="Warm">Warm</option>
            <option value="Cold">Cold</option>
          </select>
        </div>
      </div>

  <!-- PERSONAL INFORMATION -->
  <div class="mb-4">

    <div class="mb-2">
      <h6 class="text-muted fw-semibold mb-0">Personal Information</h6>
      <small class="text-muted">Basic identity details</small>
    </div>

    <div class="row g-2">

      <div class="col-lg-6">
        <label class="form-label small text-muted">First Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control"
               id="first_name" name="first_name"
               placeholder="Enter first name">
      </div>

      <div class="col-lg-6">
        <label class="form-label small text-muted">Last Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control"
               id="last_name" name="last_name"
               placeholder="Enter last name">
      </div>

      <div class="col-lg-6">
        <label class="form-label small text-muted">Middle Name</label>
        <input type="text" class="form-control"
               id="middle_name" name="middle_name"
               placeholder="Optional">
      </div>

      <div class="col-lg-6">
        <label class="form-label small text-muted">Gender</label>
        <select class="form-control select2"
                id="gender_id" name="gender_id">
          <option value="">Select gender</option>
          <?php echo $genderModel->generateGenderOptions(); ?>
        </select>
      </div>

    </div>
  </div>

  <!-- COMPANY -->
  <div class="mb-4">

    <div class="mb-2">
      <h6 class="text-muted fw-semibold mb-0">Company</h6>
      <small class="text-muted">If applicable</small>
    </div>

    <div class="row g-2">

      <div class="col-lg-12">
        <label class="form-label small text-muted">Corporate Name</label>
        <input type="text" class="form-control"
               id="corporate_name" name="corporate_name"
               placeholder="Company or organization name">
      </div>

    </div>
  </div>

  <!-- CONTACT -->
  <div class="mb-4">

    <div class="mb-2">
      <h6 class="text-muted fw-semibold mb-0">Contact</h6>
      <small class="text-muted">How we can reach this lead</small>
    </div>

    <div class="row g-2">

      <div class="col-lg-6">
        <label class="form-label small text-muted">Email Address</label>
        <input type="email" class="form-control"
               id="email" name="email"
               placeholder="example@email.com">
      </div>

      <div class="col-lg-6">
        <label class="form-label small text-muted">Phone Number</label>
        <input type="text" class="form-control"
               id="phone" name="phone"
               placeholder="+63 912 345 6789">
      </div>

      <div class="col-lg-12">
        <label class="form-label small text-muted">Address</label>
        <textarea class="form-control"
                  id="address" name="address"
                  rows="2"
                  placeholder="Complete address"></textarea>
      </div>

      <div class="col-lg-6">
        <label class="form-label small text-muted">City</label>
        <select class="form-control select2"
                id="city_id" name="city_id">
          <option value="">Select city</option>
          <?php echo $cityModel->generateCityOptions(); ?>
        </select>
      </div>

    </div>
  </div>

  <!-- LEAD DETAILS -->
  <div class="mb-3">

      <div class="col-lg-12">
        <label class="form-label small text-muted">Remarks</label>
        <textarea class="form-control"
                  id="remarks" name="remarks"
                  rows="2"
                  placeholder="Notes or additional details"></textarea>
      </div>

    </div>
  </div>

</form>
      </div>
    </div>
  </div>
</div>