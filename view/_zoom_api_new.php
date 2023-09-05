<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Zoom API</h5>
          </div>
          <?php
            if ($zoomAPICreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="zoom-api-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="zoom-api-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="zoom_api_name" name="zoom_api_name" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">API Key <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="api_key" name="api_key" maxlength="1000" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="zoom_api_description" name="zoom_api_description" maxlength="200" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">API Secret <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="api_secret" name="api_secret" maxlength="1000" autocomplete="off">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>