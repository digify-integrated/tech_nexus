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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#employment-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Employment Status
                        </a>
                        <div class="collapse show" id="employment-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input employment-status-filter" type="radio" name="employment-status-filter" id="employment-status-all" value="all" checked />
                                <label class="form-check-label" for="employment-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input employment-status-filter" type="radio" name="employment-status-filter" id="employment-status-active" value="active" />
                                <label class="form-check-label" for="employment-status-active">Active</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input employment-status-filter" type="radio" name="employment-status-filter" id="employment-status-inactive" value="inactive" />
                                <label class="form-check-label" for="employment-status-inactive">Inactive</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#company-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Company
                        </a>
                        <div class="collapse show" id="company-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $companyModel->generateCompanyCheckBox();
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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#job-position-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Job Position
                        </a>
                        <div class="collapse show" id="job-position-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $jobPositionModel->generateJobPositionCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#branch-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Branch
                        </a>
                        <div class="collapse show" id="branch-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $branchModel->generateBranchCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#employee-type-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Employee Type
                        </a>
                        <div class="collapse show" id="employee-type-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $employeeTypeModel->generateEmployeeTypeCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#job-level-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Job Level
                        </a>
                        <div class="collapse show" id="job-level-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $jobLevelModel->generateJobLevelCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#age-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Age
                        </a>
                        <div class="collapse show" id="age-filter-collapse">
                          <div class="py-3">
                            <input id="age-filter" type="text" class="span2" value="" data-slider-min="0" data-slider-max="200" data-slider-step="1" data-slider-value="[0,100]">
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#gender-filter-collapse">
                          <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Gender
                        </a>
                        <div class="collapse show" id="gender-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $genderModel->generateGenderCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#civil-status-filter-collapse">
                          <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Civil Status
                        </a>
                        <div class="collapse show" id="civil-status-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $civilStatusModel->generateCivilStatusCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#blood-type-filter-collapse">
                          <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Blood Type
                        </a>
                        <div class="collapse show" id="blood-type-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $bloodTypeModel->generateBloodTypeCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#religion-filter-collapse">
                          <div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Religion
                        </a>
                        <div class="collapse show" id="religion-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $religionModel->generateReligionCheckBox();
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
                    <input type="text" class="form-control" id="employee_search" placeholder="Search Employee" />
                  </div>
                </li>
              </ul>
              <ul class="list-inline ms-auto my-1">
                <?php
                  if($employeeCreateAccess['total'] > 0){
                    echo '<li class="list-inline-item align-bottom mr-0"><a href="employee.php?new" class="btn btn-success">Create</a></li>';
                  }
                ?>
                <li class="list-inline-item align-bottom">
                  <button type="button" class="d-xxl-none btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                    Filter
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row" id="employee-card"></div>
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