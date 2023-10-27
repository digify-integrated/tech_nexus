<div class="offcanvas offcanvas-end" tabindex="-1" id="change-password-offcanvas" aria-labelledby="change-password-offcanvas-label">
    <div class="offcanvas-header">
        <h2 id="change-password-offcanvas-label" style="margin-bottom:-0.5rem">Change Password</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            This form allows you to change your password, ensuring it meets security requirements, including a minimum length and a combination of lowercase, uppercase letters, numbers, and special characters.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form id="change-password-shortcut-form" method="post" action="#">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">Old Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="shortcut_old_password" name="shortcut_old_password">
                            </div>
                            <div class="form-group">
                                <label class="form-label">New Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="shortcut_new_password" name="shortcut_new_password">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="shortcut_confirm_password" name="shortcut_confirm_password">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary" id="submit-change-password-shortcut-form" form="change-password-shortcut-form">Update Password</button>
                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
            </div>
        </div>
    </div>
</div>