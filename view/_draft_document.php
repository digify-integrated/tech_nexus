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
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Draft Document List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php
                  if($draftDocumentCreateAccess['total'] > 0 || $draftDocumentDeleteAccess['total'] > 0 || $importAttendance['total'] > 0){
                    $action = '';
                                
                    if($draftDocumentDeleteAccess['total'] > 0){
                      $action .= '<div class="btn-group m-r-10">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                      <li><button class="dropdown-item" type="button" id="delete-draft-document">Delete Draft Document</button></li>
                                    </ul>
                                  </div>';
                    }

                    if($draftDocumentCreateAccess['total'] > 0){
                      $action .= '<a href="draft-document.php?new" class="btn btn-success">Create</a>';
                    }
                                
                    echo $action;
                  }
                ?>
                <button type="button" class="d-xxl-none btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
                <button type="button" class="d-none d-xxl-inline-flex btn btn-warning" data-bs-toggle="collapse" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <table id="draft-document-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>Document</th>
                    <th>Document Category</th>
                    <th>Author</th>
                    <th>Upload Date</th>
                    <th>Status</th>
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