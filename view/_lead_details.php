<div class="row">
  <div class="col-lg-12">
  <div class="row g-4">

    <!-- =========================
        LEFT: LEAD FORM
    ========================== -->
    <div class="col-lg-8">

      <div class="card shadow-sm border-0">

        <!-- HEADER -->
        <div class="card-header border-bottom">
          <div class="d-flex justify-content-between align-items-center">

            <div>
              <h5 class="mb-0 fw-semibold">Lead Details</h5>
              <small class="text-muted">Manage all lead information</small>
            </div>

            <div class="d-flex gap-2 flex-wrap">

              <?php
                if ($leadWriteAccess['total'] > 0) {
                  echo '
                  <button type="submit" form="lead-form" class="btn btn-success id="submit-data">
                    Save
                  </button>
                  <button type="button" id="discard-update" class="btn btn-light border">
                    Discard
                  </button>';
                }

                if ($leadCreateAccess['total'] > 0) {
                  echo '<a href="lead-monitoring.php?new" class="btn btn-primary">+ New</a>';
                }
              ?>

            </div>

          </div>
        </div>

        <!-- BODY -->
        <div class="card-body">

          <?php $isReadOnly = ($leadWriteAccess['total'] == 0) ? 'disabled' : ''; ?>

          <form id="lead-form" method="post">

            <div class="row g-3">
              <!-- LEAD DETAILS -->
              <div class="col-12 mt-3">
                <h6 class="fw-semibold text-warning mb-2">Lead Details</h6>
              </div>

              <div class="col-md-6">
                <select class="form-select select2" name="inquiry_type_id" id="inquiry_type_id" <?= $isReadOnly ?>>
                  <option value="">Inquiry Type</option>
                  <?= $inquiryTypeModel->generateInquiryTypeOptions(); ?>
                </select>
              </div>

              <div class="col-md-6">
                <div class="input-group date">
                  <input type="text" class="form-control future-date-restricted-datepicker" id="inquiry_date" name="inquiry_date" autocomplete="off">
                  <span class="input-group-text" <?= $isReadOnly ?>>
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
              </div>

              <div class="col-md-6">
                <select class="form-control select2"
                id="stock_number" name="stock_number" id="stock_number">
                <option value="">Select stock</option>
                <?php echo $productModel->generateForSaleProductOptions(); ?>.
                <?= $isReadOnly ?>
              </select>
              </div>

              <div class="col-md-6">
                <select class="form-select select2" name="lead_status_id" id="lead_status_id" <?= $isReadOnly ?>>
                  <option value="">Status</option>
                  <?= $leadStatusModel->generateLeadStatusOptions('Lead'); ?>
                </select>
              </div>

              <div class="col-md-6">
                <select class="form-select select2" name="lead_source_id" id="lead_source_id" <?= $isReadOnly ?>>
                  <option value="">Lead Source</option>
                  <?= $leadSourceModel->generateLeadSourceOptions(); ?>
                </select>
              </div>

              <div class="col-md-6">
                <select class="form-select select2" name="lead_priority" id="lead_priority" <?= $isReadOnly ?>>
                  <option value="">Lead Priority</option>
                  <option value="Hot">Hot</option>
                  <option value="Warm">Warm</option>
                  <option value="Cold">Cold</option>
                </select>
              </div>

              <!-- PERSONAL -->
              <div class="col-12 mt-3">
                <h6 class="fw-semibold text-primary mb-2">Personal</h6>
              </div>

              <div class="col-md-6">
                <input class="form-control" name="first_name" id="first_name" placeholder="First Name" <?= $isReadOnly ?>>
              </div>

              <div class="col-md-6">
                <input class="form-control" name="last_name" id="last_name" placeholder="Last Name" <?= $isReadOnly ?>>
              </div>

              <div class="col-md-6">
                <input class="form-control" name="middle_name" id="middle_name" placeholder="Middle Name" <?= $isReadOnly ?>>
              </div>

              <div class="col-md-6">
                <select class="form-select select2" name="gender_id" id="gender_id" <?= $isReadOnly ?>>
                  <option value="">Gender</option>
                  <?= $genderModel->generateGenderOptions(); ?>
                </select>
              </div>

              <!-- COMPANY -->
              <div class="col-12 mt-3">
                <h6 class="fw-semibold text-secondary mb-2">Company</h6>
              </div>

              <div class="col-12">
                <input class="form-control" name="corporate_name" id="corporate_name" placeholder="Company Name" <?= $isReadOnly ?>>
              </div>

              <!-- CONTACT -->
              <div class="col-12 mt-3">
                <h6 class="fw-semibold text-info mb-2">Contact</h6>
              </div>

              <div class="col-md-6">
                <input class="form-control" name="email" id="email" placeholder="Email" <?= $isReadOnly ?>>
              </div>

              <div class="col-md-6">
                <input class="form-control" name="phone" id="phone" placeholder="Phone" <?= $isReadOnly ?>>
              </div>

              <div class="col-12">
                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" <?= $isReadOnly ?>></textarea>
              </div>

              <div class="col-md-6">
                <select class="form-select select2" name="city_id" id="city_id" <?= $isReadOnly ?>>
                  <option value="">City</option>
                  <?= $cityModel->generateCityOptions(); ?>
                </select>
              </div>


              <div class="col-12">
                <textarea class="form-control" name="remarks" id="remarks" rows="4" placeholder="Remarks" <?= $isReadOnly ?>></textarea>
              </div>

            </div>

          </form>

        </div>
      </div>

    </div>

    <!-- =========================
        RIGHT: SIDEBAR
    ========================== -->
    <div class="col-lg-4">

      <!-- INTERNAL NOTES -->
      <div class="card shadow-sm border-0">

        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0 fw-semibold">Internal Notes</h6>

          <button class="btn btn-warning"
                  data-bs-toggle="modal"
                  data-bs-target="#update-lead-status-modal">
            + Add
          </button>
        </div>

        <div class="card-body" style="max-height: 600px; overflow-y: auto;">

          <div id="lead-notes-container" class="vstack gap-3"></div>

          <div class="text-center text-muted py-4 d-none" id="no-lead-notes">
            No internal notes yet
          </div>

        </div>

      </div>

    </div>

  </div>
</div>

<div class="modal fade" id="add-internal-note-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">

    <div class="modal-content border-0 shadow">

      <!-- HEADER -->
      <div class="modal-header border-bottom">
        <div>
          <h5 class="modal-title mb-0">Add Internal Note</h5>
          <small class="text-muted">Visible only to internal users</small>
        </div>

        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <form id="lead-note-form">

          <!-- hidden lead id (optional fallback if you prefer DOM-based) -->
          <input type="hidden" name="lead_id" id="modal-lead-id">

          <div class="mb-3">
            <label class="form-label">Note</label>

            <textarea
              class="form-control"
              name="lead_note"
              id="lead_note"
              rows="5"
              placeholder="Write your internal note here..."
            ></textarea>

            <small class="text-muted">
              Keep notes clear and actionable for your team.
            </small>
          </div>

        </form>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer border-top">

        <button type="button"
                class="btn btn-light border"
                data-bs-dismiss="modal">
          Cancel
        </button>

        <button type="submit"
                form="lead-note-form"
                id="submit-lead-note"
                class="btn btn-warning">
          Save Note
        </button>

      </div>

    </div>

  </div>
</div>

<div class="modal fade" id="update-lead-status-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">

    <div class="modal-content border-0 shadow">

      <!-- HEADER -->
      <div class="modal-header border-bottom">
        <div>
          <h5 class="modal-title mb-0">Update Lead Status</h5>
          <small class="text-muted">Before adding notes kindly update the lead status</small>
        </div>

        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <form id="lead-status-form">

          <!-- hidden lead id (optional fallback if you prefer DOM-based) -->
          <input type="hidden" name="lead_id" id="modal-lead-id2">

          <div class="mb-3">
            <label class="form-label">Lead Status</label>

            <select class="form-select select2" name="lead_status_id2" id="lead_status_id2">
              <option value="">Status</option>
              <?= $leadStatusModel->generateLeadStatusOptions('Lead'); ?>
            </select>
          </div>

        </form>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer border-top">

        <button type="button"
                class="btn btn-light border"
                data-bs-dismiss="modal">
          Cancel
        </button>

        <button type="submit"
                form="lead-status-form"
                id="submit-lead-status"
                class="btn btn-warning">
          Save
        </button>

      </div>

    </div>

  </div>
</div>

<?php
echo '<div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h5>Log Notes</h5>
          </div>
          <div class="log-notes-scroll" style="max-height: 450px;">
            <div class="card-body p-b-0">
              '. $userModel->generateLogNotes('leads', $leadID) .'
            </div>
          </div>
        </div>
      </div>';
?>
</div>