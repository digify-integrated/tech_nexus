<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Parts Inquiry</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                 
                        
              if ($partsInquiryDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-parts-inquiry-details">Delete Parts Inquiry</button></li>';
              }
                      
              $dropdown .= '</ul>
                          </div>';
                  
              echo $dropdown;

              if ($partsInquiryWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="parts-inquiry-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="parts-inquiry-form" method="post" action="#">
          <?php
            if($partsInquiryWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Part Number <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="parts_number_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="parts_number" name="parts_number" maxlength="500" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Stock <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="stock_label"></label>
                        <input type="number" class="form-control  d-none form-edit" id="stock" name="stock" min="0" step="1">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="parts_description_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="parts_description" name="parts_description" maxlength="1000" autocomplete="off">
                      </div>
                       <label class="col-lg-2 col-form-label">Price <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="price_label"></label>
                        <input type="number" class="form-control  d-none form-edit" id="price" name="price" min="0.01" step="0.01">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Part Number</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="parts_number_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Stock</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="stock_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="parts_description_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Price</label>
                      <div class="col-lg-4">
                      <label class="col-form-label form-details fw-normal" id="price_label"></label>
                      </div>
                      
                    </div>';
            }
          ?>
        </form>
      </div>
    </div>
  </div>
</div>