 <div class="row">
          <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5>File Extension</h5>
                        </div>
                        <?php
                            if (empty($fileExtensionID) && $fileExtensionCreateAccess['total'] > 0) {
                            echo ' <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                        <button type="submit" form="file-extension-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                                        <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                                    </div>';
                            }
                        ?>
                    </div>
                </div>
                <div class="card-body">
                    <form id="file-extension-form" method="post" action="#">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">File Extension Name <span class="text-danger">*</span></label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" id="file_extension_name" name="file_extension_name" maxlength="100" autocomplete="off">
                            </div>
                            <label class="col-lg-2 col-form-label">File Type <span class="text-danger">*</span></label>
                            <div class="col-lg-4">
                                <select class="form-control select2" name="file_type_id" id="file_type_id">
                                    <option value="">--</option>
                                    <?php echo $fileTypeModel->generateFileTypeOptions(); ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>