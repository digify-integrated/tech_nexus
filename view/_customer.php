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
                    <input type="text" class="form-control" id="customer_search" placeholder="Search Customer" />
                  </div>
                </li>
              </ul>
              <ul class="list-inline ms-auto my-1">
                <?php
                  if($customerCreateAccess['total'] > 0){
                    echo '<li class="list-inline-item align-bottom mr-0"><a href="customer.php?new" class="btn btn-success">Create</a></li>';
                  }
                ?>
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
        <div class="row" id="customer-card"></div>
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