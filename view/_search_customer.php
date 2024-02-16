<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5>Search Customer</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                        <button type="submit" form="search-customer-form" class="btn btn-success" id="submit-data">Search</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="search-customer-form" method="post" action="#">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" maxlength="100" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" maxlength="100" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" maxlength="100" autocomplete="off">
                            </div>
                        </div>
                    </div>
                 </form>
            </div>
        </div>
    </div>
</div>
<div class="row d-none" id="search-results">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Search Result</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($customerCreateAccess['total'] > 0){
               echo '<li class="list-inline-item align-bottom mr-0"><a href="search-customer.php?new&search" class="btn btn-success">Create</a></li>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="search-customer-result-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Customer</th>
                <th>Actions</th>
              </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>