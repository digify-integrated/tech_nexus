<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {

        # -------------------------------------------------------------
        #
        # Type: user account table
        # Description:
        # Generates the user accounts table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'user account table':
            if(isset($_POST['filter_status']) && isset($_POST['filter_locked']) && isset($_POST['filter_password_expiry_date_start_date']) && isset($_POST['filter_password_expiry_date_end_date'])){
                $filterStatus = htmlspecialchars($_POST['filter_status'], ENT_QUOTES, 'UTF-8');
                $filterLocked = htmlspecialchars($_POST['filter_locked'], ENT_QUOTES, 'UTF-8');
                $filterPasswordExpiryDateStartDate = $systemModel->checkDate('empty', $_POST['filter_password_expiry_date_start_date'], '', 'Y-m-d', '', '', '');
                $filterPasswordExpiryDateEndDate = $systemModel->checkDate('empty', $_POST['filter_password_expiry_date_end_date'], '', 'Y-m-d', '', '', '');

                $filterLastConnectionDateStartDate = $systemModel->checkDate('empty', $_POST['filter_last_connection_date_start_date'], '', 'Y-m-d', '', '', '');
                $filterLastConnectionDateEndDate = $systemModel->checkDate('empty', $_POST['filter_last_connection_date_end_date'], '', 'Y-m-d', '', '', '');
                $filterLastPasswordResetStartDate = $systemModel->checkDate('empty', $_POST['filter_last_password_reset_start_date'], '', 'Y-m-d', '', '', '');
                $filterLastPasswordResetEndDate = $systemModel->checkDate('empty', $_POST['filter_last_password_reset_end_date'], '', 'Y-m-d', '', '', '');
                $filterLastFailedloginAttemptStartDate = $systemModel->checkDate('empty', $_POST['filter_last_failed_login_attempt_start_date'], '', 'Y-m-d', '', '', '');
                $filterLastFailedloginAttemptEndDate = $systemModel->checkDate('empty', $_POST['filter_last_failed_login_attempt_end_date'], '', 'Y-m-d', '', '', '');

                $sql = $databaseModel->getConnection()->prepare('CALL generateUserAccountTable(:filterStatus, :filterLocked, :filterPasswordExpiryDateStartDate, :filterPasswordExpiryDateEndDate, :filterLastConnectionDateStartDate, :filterLastConnectionDateEndDate, :filterLastPasswordResetStartDate, :filterLastPasswordResetEndDate, :filterLastFailedloginAttemptStartDate, :filterLastFailedloginAttemptEndDate)');
                $sql->bindValue(':filterStatus', $filterStatus, PDO::PARAM_STR);
                $sql->bindValue(':filterLocked', $filterLocked, PDO::PARAM_STR);
                $sql->bindValue(':filterPasswordExpiryDateStartDate', $filterPasswordExpiryDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterPasswordExpiryDateEndDate', $filterPasswordExpiryDateEndDate, PDO::PARAM_STR);
                $sql->bindValue(':filterLastConnectionDateStartDate', $filterLastConnectionDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterLastConnectionDateEndDate', $filterLastConnectionDateEndDate, PDO::PARAM_STR);
                $sql->bindValue(':filterLastPasswordResetStartDate', $filterLastPasswordResetStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterLastPasswordResetEndDate', $filterLastPasswordResetEndDate, PDO::PARAM_STR);
                $sql->bindValue(':filterLastFailedloginAttemptStartDate', $filterLastFailedloginAttemptStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterLastFailedloginAttemptEndDate', $filterLastFailedloginAttemptEndDate, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $userAccountDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 8, 'delete');

                foreach ($options as $row) {
                    $userAccountID = $row['user_id'];
                    $fileAs = $row['file_as'];
                    $email = $row['email'];
                    $isActive = $row['is_active'];
                    $isLocked = $row['is_locked'];
                    $dataActivate = $isActive ? 0 : 1;
                    $dataInactive = $isActive ? 1 : 0;
                    $dataLock = $isLocked ? 0 : 1;
                    $dataUnlock = $isLocked ? 1 :0;
                    $passwordExpiryDate = date('m/d/Y', strtotime($row['password_expiry_date']));
                    $profilePicture = ($row['profile_picture'] !== null) ? $row['profile_picture'] : DEFAULT_AVATAR_IMAGE;
                    $lastPasswordReset = ($row['last_password_reset'] !== null) ? date('m/d/Y h:i:s a', strtotime($row['last_password_reset'])) : 'Never Reset';
                    $lastConnectionDate = ($row['last_connection_date'] !== null) ? date('m/d/Y h:i:s a', strtotime($row['last_connection_date'])) : 'Never Connected';
                    $lastFailedLoginAttempt = ($row['last_failed_login_attempt'] !== null) ? date('m/d/Y h:i:s a', strtotime($row['last_failed_login_attempt'])) : 'Never Connected';
                    $userAccountIDEncrypted = $securityModel->encryptData($userAccountID);
                    
                    $isActiveBadge = $isActive ? '<span class="badge bg-light-success">Active</span>' : '<span class="badge bg-light-danger">Inactive</span>';
                    $isLockedBadge = $isLocked ? '<span class="badge bg-light-danger">Yes</span>' : '<span class="badge bg-light-success">No</span>';

                    $delete = '';
                    if($userAccountDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-user-account" data-user-account-id="'. $userAccountID .'" title="Delete User Account">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $userAccountID .'">',
                        'FILE_AS' => '<div class="row">
                                        <div class="col-auto pe-0">
                                        <img src="'. $profilePicture .'" alt="user-image" class="wid-40 rounded-circle">
                                        </div>
                                        <div class="col">
                                        <h6 class="mb-0">'. $fileAs .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $email .'</p>
                                        </div>
                                    </div>',
                        'IS_ACTIVE' => $isActiveBadge,
                        'IS_LOCKED' => $isLockedBadge,
                        'PASSWORD_EXPIRY_DATE' => $passwordExpiryDate,
                        'LAST_CONNECTION_DATE' => $lastConnectionDate,
                        'LAST_PASSWORD_RESET' => $lastPasswordReset,
                        'LAST_FAILED_LOGIN_ATTEMPT' => $lastFailedLoginAttempt,
                        'ACTION' => '<div class="d-flex gap-2">
                                    <a href="user-account.php?id='. $userAccountIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: user account role table
        # Description:
        # Generates the roles assigned to user account.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'user account role table':
            if(isset($_POST['user_account_id']) && !empty($_POST['user_account_id'])){
                $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateUserAccountRoleTable(:userAccountID)');
                $sql->bindValue(':userAccountID', $userAccountID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $deleteUserAccountRole = $userModel->checkSystemActionAccessRights($user_id, 8);

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];

                    $delete = '';
                    if($deleteUserAccountRole['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-user-account-role" data-user-account-id="'. $userAccountID .'" data-role-id="'. $roleID .'" title="Delete Role">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'ROLE_NAME' => $roleName,
                        'ACTION' => '<div class="d-flex gap-2">
                                    '. $delete .'
                                </div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: add user account role table
        # Description:
        # Generates the role not in user account table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'add user account role table':
            if(isset($_POST['user_account_id']) && !empty($_POST['user_account_id'])){
                $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddUserAccountRoleTable(:userAccountID)');
                $sql->bindValue(':userAccountID', $userAccountID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $roleID = $row['role_id'];
                    $roleName = $row['role_name'];
    
                    $response[] = [
                        'ROLE_NAME' => $roleName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input user-account-role" type="checkbox" value="'. $roleID.'"></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: role user account table
        # Description:
        # Generates the user accounts assigned to role.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'role user account table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateRoleUserAccountTable(:roleID)');
                $sql->bindValue(':roleID', $roleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $deleteUserAccountRole = $userModel->checkSystemActionAccessRights($user_id, 6);

                foreach ($options as $row) {
                    $userAccountID = $row['user_id'];
                    $fileAs = $row['file_as'];
                    $email = $row['email'];
                    $lastConnectionDate = ($row['last_connection_date'] !== null) ? date('m/d/Y h:i:s a', strtotime($row['last_connection_date'])) : 'Never Connected';

                    $delete = '';
                    if($deleteUserAccountRole['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-role-user-account" data-user-account-id="'. $userAccountID .'" data-role-id="'. $roleID .'" title="Delete User Account">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'FILE_AS' => $fileAs,
                        'EMAIL' => $email,
                        'LAST_CONNECTION_DATE' => $lastConnectionDate,
                        'ACTION' => '<div class="d-flex gap-2">
                                    '. $delete .'
                                </div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
        
        # -------------------------------------------------------------
        #
        # Type: add user account table
        # Description:
        # Generates the user account table not in role user table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'add role user account table':
            if(isset($_POST['role_id']) && !empty($_POST['role_id'])){
                $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateAddRoleUserAccountTable(:roleID)');
                $sql->bindValue(':roleID', $roleID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $userAccountID = $row['user_id'];
                    $fileAs = $row['file_as'];
                    $email = $row['email'];
    
                    $response[] = [
                        'FILE_AS' => $fileAs,
                        'EMAIL' => $email,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input user-account-role" type="checkbox" value="'. $userAccountID.'"></div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>