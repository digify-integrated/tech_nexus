<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="#" class="b-brand text-primary"><img src="<?php echo $login_logo; ?>" /></a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="./assets/images/default/default-avatar.png" alt="user-image" class="user-avtar wid-45 rounded-circle" />
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0"><?php echo $file_as; ?></h6>
                            <small>Status: <?php echo $user_status; ?></small>
                        </div>
                        <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse" href="#pc_sidebar_userlink">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-sort-outline"></use>
                        </svg>
                        </a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        <div class="pt-3">
                            <a href="JavaScript:void(0);">
                                <i class="ti ti-user"></i>
                                <span>My Account</span>
                            </a>
                            <a href="JavaScript:void(0);">
                                <i class="ti ti-settings"></i>
                                <span>Settings</span>
                            </a>
                            <a href="logout.php?logout">
                                <i class="ti ti-power"></i>
                                <span>Logout</span>
                            </a>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="pc-navbar">
                    <li class="pc-item pc-caption">
                        <label>Navigation</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item">
                        <a href="dashboard.php" class="pc-link">
                            <span class="pc-micon">
                                <i data-feather="home"></i>
                            </span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <?php echo $api->build_menu($email); ?>
                </ul>
            </div>
        </div>
    </div>
</nav>