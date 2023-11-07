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
                            <img src="<?php echo $userAccountProfileImage; ?>" alt="user-image" class="user-avtar wid-40 hei-40 rounded-circle" />
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0 text-primary"><?php echo $fileAs; ?></h6>
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
                        <label class="text-primary"><b>Navigation</b></label>
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
                    <?php
                        $menu = '';
                        $sql = $databaseModel->getConnection()->prepare('CALL buildMenuGroup(:userID)');
                        $sql->bindValue(':userID', $user_id);
                        $sql->execute();
                        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                        $sql->closeCursor();
            
                        foreach ($options as $row) {
                            $_menuGroupID = $row['menu_group_id'];
                            $_menuGroupName = $row['menu_group_name'];
            
                            $menu .= '<li class="pc-item pc-caption">
                                        <label class="text-primary"><b>'. $_menuGroupName .'</b></label>
                                    </li>';
            
                            $menu .= $menuItemModel->buildMenuItem($user_id, $_menuGroupID);
                        }
            
                        echo $menu;
                    ?>
                </ul>
            </div>
        </div>
    </div>
</nav>