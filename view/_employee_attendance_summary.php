
<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Employee Attendance Summary</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                        Filter
                    </button>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Employee Late & Undertime Summary</h5>
              </div>
            </div>
            
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <table id="daily-employee-late-status-table" class="table table-hover text-wrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>Company</th>
                    <th>Employee</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">TOTAL</th>
                        <!-- day columns will be auto-filled -->
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Employee Absentism Summary</h5>
              </div>
            </div>
            
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <table id="daily-employee-absentism-status-table" class="table table-hover text-wrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>Company</th>
                    <th>Employee</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                <tr>
                    <th colspan="2" class="text-end">TOTAL</th>
                    <!-- day columns will be auto-filled -->
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-start ecom-offcanvas" tabindex="-1" id="filter-canvas">
        <div class="offcanvas-body p-0 sticky-top">
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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#month-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Month
                        </a>
                        <div class="collapse" id="month-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                               <select class="form-control" id="filter_month">
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#year-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Year
                        </a>
                        <div class="collapse" id="year-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                               <select class="form-control" id="filter_year">
                                    <?php echo $systemModel->generateYearOptions(date('Y', strtotime('-10 years')), date('Y')) ?>
                                </select>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#cutoff-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Cut-off Period
                        </a>
                        <div class="collapse" id="cutoff-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                               <select class="form-control" id="filter_cutoff_period">
                                   <option value="1">1st Half</option>
                                   <option value="2">2nd Half</option>
                                </select>
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