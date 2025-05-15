<div class="row">
  <div class="col-lg-9">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5>Part Order</h5>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-part-offcanvas" aria-controls="add-part-offcanvas" id="add-part">Add Parts</button>
                    <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#scan-barcode-offcanvas" aria-controls="scan-barcode-offcanvas">Scan Barcode</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="dt-responsive table-responsive">
                <table class="table mb-0" id="parts-item-table">
                    <thead>
                        <tr>
                            <th>Part</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Add-On</th>
                            <th class="text-center">Discount</th>
                            <th class="text-center">Total Discount</th>
                            <th class="text-end">Sub-Total</th>
                            <th class="text-end">Total</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
  <div class="col-lg-3">
    <div class="card">
        <div class="card-body py-2">
            <ul class="list-group list-group-flush">
                <li class="list-group-item px-0">
                    <h5 class="mb-0">Transaction Summary</h5>
                </li>
                <li class="list-group-item px-0">
                    <div class="float-end">
                        <h5 class="mb-0" id="add-on-total-summary">0.00 PHP</h5>
                    </div><span class="text-muted">Add-On Total</span></li>
                <li class="list-group-item px-0">
                    <div class="float-end">
                        <h5 class="mb-0" id="sub-total-summary">0.00 PHP</h5>
                    </div><span class="text-muted">Sub-Total</span></li>
                <li class="list-group-item px-0">
                    <div class="float-end">
                        <h5 class="mb-0" id="total-discount-summary">0.00 PHP</h5>
                    </div><span class="text-muted">Total Discount</span></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body py-2">
            <ul class="list-group list-group-flush">
                <li class="list-group-item px-0">
                    <div class="float-end">
                        <h5 class="mb-0" id="total-summary">0.00 PHP</h5>
                    </div>
                    <h5 class="mb-0 d-inline-block">Total</h5>
                </li>
            </ul>
        </div>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="add-part-offcanvas" aria-labelledby="add-part-offcanvas-label">
    <div class="offcanvas-header">
        <h2 id="add-part-offcanvas-label" style="margin-bottom:-0.5rem">Add Part</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row mb-4 text-center">
            <form id="add-part-form" method="post" action="#"></form>
                <table id="add-part-table" class="table table-hover nowrap w-100 dataTable">
                    <thead>
                        <tr>
                            <th>Part</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th class="all">Add</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </form> 
        </div>
        <div class="row">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary" id="submit-add-part" form="add-part-form">Submit</button>
                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="part-cart-offcanvas" aria-labelledby="part-cart-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="part-cart-offcanvas-label" style="margin-bottom:-0.5rem">Part Item</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="part-item-form" method="post" action="#">
            <input type="hidden" id="part_transaction_cart_id" name="part_transaction_cart_id">
            <input type="hidden" id="part_id" name="part_id">
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Price</label>
                <input type="number" class="form-control" id="part_price" name="part_price" min="0" readonly>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Available Stock</label>
                <input type="number" class="form-control" id="available_stock" name="available_stock" min="0" readonly>
              </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1">
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <label class="form-label">Add-On</label>
                    <input type="number" class="form-control" id="add_on" name="add_on" min="0" step="0.01">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg12 mt-3 mt-lg-0">
                    <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#replenishment-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                        With Discount?
                    </a>
                    <div class="collapse" id="replenishment-date-filter-collapse">
                        <div class="row py-3">
                            <div class="col-lg-4 mt-3 mt-lg-0">
                                <label class="form-label">Discount</label>
                                <input type="number" class="form-control" id="discount" name="discount" min="0" step="0.01">
                            </div>
                            <div class="col-lg-4 mt-3 mt-lg-0">
                                <label class="form-label">Type</label>
                                <select class="form-control offcanvas-select2" name="discount_type" id="discount_type">
                                    <option value="">--</option>
                                    <option value="Percentage">Percentage</option>
                                    <option value="Amount">Amount</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mt-3 mt-lg-0">
                                <label class="form-label">Discount Total</label>
                                <input type="number" class="form-control" id="discount_total" name="discount_total" min="0" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Sub-Total</label>
                <input type="number" class="form-control" id="part_item_subtotal" name="part_item_subtotal" min="0" readonly>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Total</label>
                <input type="number" class="form-control" id="part_item_total" name="part_item_total" min="0" readonly>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-part-item" form="part-item-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>