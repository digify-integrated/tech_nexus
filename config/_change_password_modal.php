<div id="change-password-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="system-error-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="system-error-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="change-password-shortcut-form" method="post" action="#">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Old Password</label>
                                    <input type="password" class="form-control" id="shortcut_old_password" name="shortcut_old_password">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="shortcut_new_password" name="shortcut_new_password">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="shortcut_confirm_password" name="shortcut_confirm_password">
                                </div>
                            </div>
                            <div class="col-sm-6">
                            <h5>New password must contain:</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><i class="ti ti-circle-check text-success f-16 me-2"></i> At least 8
                                characters</li>
                                <li class="list-group-item"><i class="ti ti-circle-check text-success f-16 me-2"></i> At least 1
                                lower letter (a-z)</li>
                                <li class="list-group-item"><i class="ti ti-circle-check text-success f-16 me-2"></i> At least 1
                                uppercase letter(A-Z)</li>
                                <li class="list-group-item"><i class="ti ti-circle-check text-success f-16 me-2"></i> At least 1
                                number (0-9)</li>
                                <li class="list-group-item"><i class="ti ti-circle-check text-success f-16 me-2"></i> At least 1
                                special characters</li>
                            </ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submit-password-form" form="change-password-shortcut-form">Update Password</button>
                </div>
            </div>
        </div>
    </div>