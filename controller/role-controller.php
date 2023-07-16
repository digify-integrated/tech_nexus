<?php
session_start();

/**
* Class RoleController
*
* The RoleController class handles role related operations and interactions.
*/
class RoleController {
    private $roleModel;
    private $userModel;
    private $securityModel;

    /**
    * Create a new instance of the class.
    *
    * The constructor initializes the object with the provided UserModel and SecurityModel instances.
    * These instances are used for user-related operations and security-related operations, respectively.
    *
    * @param RoleModel $menuItemModel     The RoleModel instance for role related operations.
    * @param UserModel $userModel     The UserModel instance for user related operations.
    * @param SecurityModel $securityModel   The SecurityModel instance for security-related operations.
    * @return void
    */
    public function __construct(RoleModel $roleModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->roleModel = $roleModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }

    /**
    * Handle the incoming request.
    *
    * This method checks the request method and dispatches the corresponding transaction based on the provided transaction parameter.
    * The transaction determines which action should be performed.
    *
    * @return void
    */
    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'add role access':
                    $this->addRoleAccess();
                    break;
                case 'save role':
                    $this->saveRole();
                    break;
                case 'delete role':
                    $this->deleteRole();
                    break;
                case 'delete multiple role':
                    $this->deleteMultipleRole();
                    break;
                case 'duplicate role':
                    $this->duplicateRole();
                    break;
                case 'get role details':
                    $this->getRoleDetails();
                    break;
                case 'save role access':
                    $this->saveRoleAccess();
                    break;
                case 'save role system action access':
                    $this->saveRoleSystemActionAccess();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    /**
    * Saves the role.
    * Updates the existing role if it exists; otherwise, inserts a new role.
    *
    * @return void
    */
    public function saveRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
        $roleName = htmlspecialchars($_POST['role_name'], ENT_QUOTES, 'UTF-8');
        $roleDescription = htmlspecialchars($_POST['role_description'], ENT_QUOTES, 'UTF-8');
        $assignable = htmlspecialchars($_POST['assignable'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkRoleExist = $this->roleModel->checkRoleExist($roleID);
        $total = $checkRoleExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->roleModel->updateRole($roleID, $roleName, $roleDescription, $assignable, $userID);
            
            echo json_encode(['success' => true, 'roleID' => $this->securityModel->encryptData($roleID)]);
            exit;
        } 
        else {
            $roleID = $this->roleModel->insertRole($roleName, $roleDescription, $assignable, $userID);

            echo json_encode(['success' => true, 'roleID' => $this->securityModel->encryptData($roleID)]);
            exit;
        }
    }

    /**
    * Deletes the role.
    * Delete the role if it exists; otherwise, return an error message.
    *
    * @return void
    */
    public function deleteRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkRoleExist = $this->roleModel->checkRoleExist($roleID);
        $total = $checkRoleExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->roleModel->deleteRole($roleID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    /**
    * Deletes multiple roles.
    * Delete the selected roles if it exists; otherwise, skip it.
    *
    * @return void
    */
    public function deleteMultipleRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $roleIDs = $_POST['role_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($roleIDs as $roleID){
            $this->roleModel->deleteRole($roleID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }

    /**
    * Deuplicates the role.
    * Deuplocates the role if it exists; otherwise, return an error message.
    *
    * @return void
    */
    public function duplicateRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkRoleExist = $this->roleModel->checkRoleExist($roleID);
        $total = $checkRoleExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $roleID = $this->roleModel->duplicateRole($roleID, $userID);

        echo json_encode(['success' => true, 'roleID' =>  $this->securityModel->encryptData($roleID)]);
        exit;
    }

    /**
    * Retrieves the role details.
    * Handles the retrieval of role details such as role name, etc.
    *
    * @return void
    */
    public function getRoleDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['role_id']) && !empty($_POST['role_id'])) {
            $userID = $_SESSION['user_id'];
            $roleID = $_POST['role_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $roleDetails = $this->roleModel->getRole($roleID);
            $roleName = $roleDetails['role_name'];
            $roleDescription = $roleDetails['role_description'];
            $assignable = $roleDetails['assignable'];

            if($assignable){
                $assignableLabel = 'Yes';
            }
            else{
                $assignableLabel = 'No';
            }

            $response = [
                'success' => true,
                'roleName' => $roleDetails['role_name'],
                'roleDescription' => $roleDetails['role_description'],
                'assignable' => $roleDetails['assignable'],
                'assignableLabel' => $assignableLabel
            ];

            echo json_encode($response);
            exit;
        }
    }

    /**
    * Adds the role access.
    * Add the role access.
    *
    * @return void
    */
    public function addRoleAccess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');
        $roleIDs = explode(',', $_POST['role_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($roleIDs as $roleID) {
            $checkRoleMenuAccessExist = $this->roleModel->checkRoleMenuAccessExist($menuItemID, $roleID);
            $total = $checkRoleMenuAccessExist['total'] ?? 0;
        
            if ($total === 0) {
                $this->roleModel->insertRoleMenuAccess($menuItemID, $roleID, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }

    /**
    * Saves the role access.
    * Updates the existing role access of the role.
    *
    * @return void
    */
    public function saveRoleAccess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');
        $permissions = explode(',', $_POST['permission']);
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach ($permissions as $permission) {
            $parts = explode('-', $permission);
            $roleID = $parts[0];
            $accessType = $parts[1];
            $access = $parts[2];

            $checkRoleMenuAccessExist = $this->roleModel->checkRoleMenuAccessExist($menuItemID, $roleID);
            $total = $checkRoleMenuAccessExist['total'] ?? 0;
        
            if ($total === 0) {
                $this->roleModel->insertRoleMenuAccess($menuItemID, $roleID, $userID);
            }

            $this->roleModel->updateRoleMenuAccess($menuItemID, $roleID, $accessType, $access, $userID);
        }

        echo json_encode(['success' => true]);
        exit;
    }

    /**
    * Saves the role system action access.
    * Updates the system action access of the role.
    *
    * @return void
    */
    public function saveRoleSystemActionAccess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');
        $roles = explode(',', $_POST['role']);
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->roleModel->deleteAllRoleSystemActionAccessRights($systemActionID);

        foreach ($roles as $role) {
            $this->roleModel->insertRoleSystemActionAccessRights($systemActionID, $role);
        }

        echo json_encode(['success' => true]);
        exit;
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new RoleController(new RoleModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>