<div class="row">
  <div class="col-sm-12">
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
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#password-expiry-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Upload Date
                            </a>
                            <div class="collapse show" id="password-expiry-date-filter-collapse">
                                <div class="row py-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_upload_date_start_date" id="filter_upload_date_start_date" placeholder="Start Date">
                                        <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_upload_date_end_date" id="filter_upload_date_end_date" placeholder="End Date">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#password-expiry-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Publish Date
                            </a>
                            <div class="collapse show" id="password-expiry-date-filter-collapse">
                                <div class="row py-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_publish_date_start_date" id="filter_publish_date_start_date" placeholder="Start Date">
                                        <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_publish_date_end_date" id="filter_publish_date_end_date" placeholder="End Date">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#document-category-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Document Category
                            </a>
                            <div class="collapse show" id="document-category-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $documentCategoryModel->generateDocumentCategoryCheckBox();
                                    ?>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-2">
                            <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#department-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                                Department
                            </a>
                            <div class="collapse show" id="department-filter-collapse">
                                <div class="py-3">
                                    <?php
                                        echo $departmentModel->generateDepartmentCheckBox();
                                    ?>
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
        <div class="card">
          <div class="card-body p-3">
            <div class="d-sm-flex align-items-center">
              <ul class="list-inline me-auto my-1">
                <li class="list-inline-item">
                  <div class="form-search">
                    <i class="ti ti-search"></i>
                    <input type="text" class="form-control" id="document_search" placeholder="Search Document" />
                  </div>
                </li>
              </ul>
              <ul class="list-inline ms-auto my-1">
                <li class="list-inline-item align-bottom">
                  <button type="button" class="d-xxl-none btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                    Filter
                  </a>
                  <button type="button" class="d-none d-xxl-inline-flex btn btn-warning" data-bs-toggle="collapse" data-bs-target="#filter-canvas">
                    Filter
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row" id="document-card"></div>
          <div class="row" class="d-none" id="load-content">
            <div class="col-lg-12 text-center">
              <div class="spinner-grow text-primary" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>