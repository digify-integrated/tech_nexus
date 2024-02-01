<div class="row">
    <div class="col-lg-12">
        <div class="ecom-wrapper">
            <div class="offcanvas-xxl offcanvas-start ecom-offcanvas" tabindex="-1" id="filter-canvas">
                <div class="offcanvas-body p-0 sticky-xxl-top">
                    <div id="ecom-filter" class="show collapse collapse-horizontal">
                        <div class="ecom-filter">
                            <div class="card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5>Filter</h5>
                                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="offcanvas" data-bs-target="#filter-canvas">
                                        <i class="ti ti-x f-20"></i>
                                    </a>
                                </div>
                                <div class="scroll-block">
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 py-2">
                                                <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#attendance-record-date-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                                    Attendance Record Date
                                                </a>
                                                <div class="collapse show" id="attendance-record-date-date-filter-collapse">
                                                    <div class="row py-3">
                                                        <div class="col-12">
                                                            <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_attendance_record_date_start_date" id="filter_attendance_record_date_start_date" placeholder="Start Date">
                                                            <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_attendance_record_date_end_date" id="filter_attendance_record_date_end_date" placeholder="End Date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0 py-2">
                                                <button type="button" class="btn btn-light-success w-100" id="apply-filter">Apply</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ecom-content w-100">
                <div class="card table-card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5>Attendance Record Import</h5>
                            </div>
                            <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                <?php
                                    if ($importAttendance['total'] > 0) {
                                        echo '
                                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#load-file-offcanvas" aria-controls="load-file-offcanvas" id="add-load-file">Load File</button>';
                                    }
                                ?>
                                <button type="button" class="d-none btn btn-warning" id="filter-button" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dt-responsive d-none">
                            <table id="attendance-record-import-table" class="table table-hover nowrap w-100">
                                <thead>
                                    <tr>
                                        <th class="all">
                                            <div class="form-check">
                                                <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                                            </div>
                                        </th>
                                        <th>Biometrics ID</th>
                                        <th>Employee</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
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
</div>

<?php
if($importAttendance['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="load-file-offcanvas" aria-labelledby="load-file-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="load-file-offcanvas-label" style="margin-bottom:-0.5rem">Import Attendance Record</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="alert alert-success alert-dismissible mb-4" role="alert">
                The Import Attendance Record feature facilitates the seamless integration of attendance data into the system, allowing efficient management and analysis of employee attendance records.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row mb-2">
                <div class="col-lg-12">
                  <form id="import-attendance-form" method="post" action="#">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-control offcanvas-select2" name="company_id" id="company_id">
                                <option value="">--</option>
                                '. $companyModel->generateCompanyOptions() .'
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Import File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="import_file" name="import_file">
                        </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-load-file" form="import-attendance-form">Submit</button>
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>';
}
?>