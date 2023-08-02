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
        # Type: role user account table
        # Description:
        # Generates the user accounts assigned to role.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'user account table':
            if(isset($_POST['filter_status']) && isset($_POST['filter_locked'])){
                $filterStatus = htmlspecialchars($_POST['filter_status'], ENT_QUOTES, 'UTF-8');
                $filterLocked = htmlspecialchars($_POST['filter_locked'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateUserAccountTable(:filterStatus, :filterLocked)');
                $sql->bindValue(':filterStatus', $filterStatus, PDO::PARAM_INT);
                $sql->bindValue(':filterLocked', $filterLocked, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $userAccountDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 8, 'delete');

                foreach ($options as $row) {
                    $userAccountID = $row['user_id'];
                    $fileAs = $row['file_as'];
                    $email = $row['email'];
                    $lastConnectionDate = ($row['last_connection_date'] !== null) ? date('m/d/Y h:i:s a', strtotime($row['last_connection_date'])) : 'Never Connected';

                    $delete = '';
                    if($userAccountDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-user-account" data-user-account-id="'. $userAccountID .'" title="Delete User Account">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'FILE_AS' => $fileAs,
                        'IS_ACTIVE' => $email,
                        'IS_LOCKED' => $email,
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