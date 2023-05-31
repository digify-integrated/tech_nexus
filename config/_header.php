<header class="pc-header">
    <div class="header-wrapper">
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="pc-h-item">
                    <a href="#" class="pc-head-link me-0" data-bs-toggle="offcanvas" data-bs-target="#announcement" aria-controls="announcement">
                        <svg class="pc-icon"><use xlink:href="#custom-flash"></use></svg>
                    </a>
                </li>
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <svg class="pc-icon"><use xlink:href="#custom-notification"></use></svg>
                    </a>
                    <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Notifications</h5>
                            <a href="JavaScript:void(0);" class="btn btn-link btn-sm">Mark all read</a>
                        </div>
                        <div class="dropdown-body text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
                         
                        </div>
                        <div class="text-center py-2">
                            <a href="JavaScript:void(0);" class="link-danger">Clear all Notifications</a>
                        </div>
                    </div>
                </li>
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside"aria-expanded="false">
                        <img src="./assets/images/default/default-avatar.png" alt="user-image" class="user-avtar" />
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Profile</h5>
                        </div>
                        <div class="dropdown-body">
                            <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0">
                                        <img src="./assets/images/default/default-avatar.png" alt="user-image" class="user-avtar wid-35" />
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><?php echo $file_as; ?></h6>
                                        <span id="email_account"><?php echo $email; ?></span>
                                    </div>
                                </div>
                                <hr class="border-secondary border-opacity-50" />
                                <div class="card">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 d-inline-flex align-items-center">
                                            <svg class="pc-icon text-muted me-2"><use xlink:href="#custom-notification-outline"></use></svg> Notification</h5>
                                            <div class="form-check form-switch form-check-reverse m-0">
                                                <input class="form-check-input f-18" type="checkbox" role="switch" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-span">Manage</p>
                                <a href="#" class="dropdown-item">
                                    <span>
                                        <svg class="pc-icon text-muted me-2"><use xlink:href="#custom-setting-outline"></use></svg>
                                        <span>Settings</span>
                                    </span>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <span>
                                        <svg class="pc-icon text-muted me-2"><use xlink:href="#custom-lock-outline"></use></svg>
                                        <span>Change Password</span>
                                    </span>
                                </a>
                                <hr class="border-secondary border-opacity-50" />
                                <div class="d-grid mb-3">
                                    <a href="logout.php?logout" class="btn btn-primary"><svg class="pc-icon me-2"><use xlink:href="#custom-logout-1-outline"></use></svg>Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>