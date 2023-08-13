<?php
session_start();

# -------------------------------------------------------------
#
# Function: RoleController
# Description: 
# The RoleController class handles role related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class RoleController {
    private $roleModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided RoleModel, UserModel and SecurityModel instances.
    # These instances are used for role related operations, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param RoleModel $menuItemModel     The RoleModel instance for role related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(RoleModel $roleModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->roleModel = $roleModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: handleRequest
    # Description: 
    # This method checks the request method and dispatches the corresponding transaction based on the provided transaction parameter.
    # The transaction determines which action should be performed.
    #
    # Parameters:
    # - $transaction (string): The type of transaction.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
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
                case 'add menu item role access':
                    $this->addMenuItemRoleAccess();
                    break;
                case 'add role menu item access':
                    $this->addRoleMenuItemAccess();
                    break;
                case 'save menu item role access':
                    $this->saveMenuItemRoleAccess();
                    break;
                case 'delete menu item role access':
                    $this->deleteMenuItemRoleAccess();
                    break;
                case 'add role user account':
                    $this->addRoleUserAccount();
                    break;
                case 'add user account role':
                    $this->addUserAccountRole();
                    break;
                case 'add system action role access':
                    $this->addSystemActionRoleAccess();
                    break;
                case 'add role system action access':
                    $this->addRoleSystemActionAccess();
                    break;
                case 'save system action role access':
                    $this->saveSystemActionRoleAccess();
                    break;
                case 'delete system action role access':
                    $this->deleteSystemActionRoleAccess();
                    break;
                case 'delete role user account':
                    $this->deleteUserAccountRole();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Save methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveRole
    # Description: 
    # Updates the existing role if it exists; otherwise, inserts a new role.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
        $roleName = htmlspecialchars($_POST['role_name'], ENT_QUOTES, 'UTF-8');
        $roleDescription = htmlspecialchars($_POST['role_description'], ENT_QUOTES, 'UTF-8');
        $assignable = isset($_POST['assignable']) ? htmlspecialchars($_POST['assignable'], ENT_QUOTES, 'UTF-8') : 0;
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkRoleExist = $this->roleModel->checkRoleExist($roleID);
        $total = $checkRoleExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->roleModel->updateRole($roleID, $roleName, $roleDescription, $assignable, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'roleID' => $this->securityModel->encryptData($roleID)]);
            exit;
        } 
        else {
            $roleID = $this->roleModel->insertRole($roleName, $roleDescription, $assignable, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'roleID' => $this->securityModel->encryptData($roleID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    

    # -------------------------------------------------------------
    #
    # Function: saveMenuItemRoleAccess
    # Description:
    # Updates the existing role access of the role.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveMenuItemRoleAccess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $updateMenuItemRoleAccess = $this->userModel->checkSystemActionAccessRights($userID, 1);
        $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
        $accessType = htmlspecialchars($_POST['access_type'], ENT_QUOTES, 'UTF-8');
        $access = htmlspecialchars($_POST['access'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        if($updateMenuItemRoleAccess == 0){
            echo json_encode(['success' => false, 'message' => 'You do not have the necessary permissions to update role access.']);
            exit;
        }

        $checkRoleMenuAccessExist = $this->roleModel->checkRoleMenuAccessExist($menuItemID, $roleID);
        $total = $checkRoleMenuAccessExist['total'] ?? 0;
        
        if ($total === 0) {
            $this->roleModel->insertRoleMenuAccess($menuItemID, $roleID, $userID);
        }

        $this->roleModel->updateRoleMenuAccess($menuItemID, $roleID, $accessType, $access, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSystemActionRoleAccess
    # Description:
    # Updates the existing role access of the role.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSystemActionRoleAccess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $updateSystemActionRoleAccess = $this->userModel->checkSystemActionAccessRights($userID, 3);
        $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
        $access = htmlspecialchars($_POST['access'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        if($updateSystemActionRoleAccess == 0){
            echo json_encode(['success' => false, 'message' => 'You do not have the necessary permissions to update role access.']);
            exit;
        }

        $checkRoleMenuAccessExist = $this->roleModel->checkRoleMenuAccessExist($systemActionID, $roleID);
        $total = $checkRoleMenuAccessExist['total'] ?? 0;
        
        if ($total === 0) {
            $this->roleModel->insertRoleSystemActionAccess($systemActionID, $roleID, $userID);
        }

        $this->roleModel->updateRoleSystemActionAccess($systemActionID, $roleID, $access, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Add methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addMenuItemRoleAccess
    # Description:
    # Add the role to menu item access.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addMenuItemRoleAccess() {
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
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addRoleUserAccount
    # Description:
    # Add the user account to role user account.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addRoleUserAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
        $userAccountIDs = explode(',', $_POST['user_account_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($userAccountIDs as $userAccountID) {
            $checkRoleUserExist = $this->roleModel->checkRoleUserExist($userAccountID, $roleID);
            $total = $checkRoleUserExist['total'] ?? 0;
        
            if ($total === 0) {
                $this->roleModel->insertRoleUser($userAccountID, $roleID, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addUserAccountRole
    # Description:
    # Add the role to role user account.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addUserAccountRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
        $roleIDs = explode(',', $_POST['role_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($roleIDs as $roleID) {
            $checkRoleUserExist = $this->roleModel->checkRoleUserExist($userAccountID, $roleID);
            $total = $checkRoleUserExist['total'] ?? 0;
        
            if ($total === 0) {
                $this->roleModel->insertRoleUser($userAccountID, $roleID, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addRoleMenuItemAccess
    # Description:
    # Add the menu item to menu item role access.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addRoleMenuItemAccess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
        $menuItemIDs = explode(',', $_POST['menu_item_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($menuItemIDs as $menuItemID) {
            $checkRoleMenuAccessExist = $this->roleModel->checkRoleMenuAccessExist($menuItemID, $roleID);
            $total = $checkRoleMenuAccessExist['total'] ?? 0;
        
            if ($total === 0) {
                $this->roleModel->insertRoleMenuAccess($menuItemID, $roleID, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addSystemActionRoleAccess
    # Description:
    # Add the role to system action role access.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addSystemActionRoleAccess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');
        $roleIDs = explode(',', $_POST['role_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($roleIDs as $roleID) {
            $checkSystemActionRoleExist = $this->roleModel->checkSystemActionRoleExist($systemActionID, $roleID);
            $total = $checkSystemActionRoleExist['total'] ?? 0;
        
            if ($total === 0) {
                $this->roleModel->insertRoleSystemActionAccess($systemActionID, $roleID, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addRoleSystemActionAccess
    # Description:
    # Add the system action to system action role access.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addRoleSystemActionAccess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
        $systemActionIDs = explode(',', $_POST['system_action_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($systemActionIDs as $systemActionID) {
            $checkSystemActionRoleExist = $this->roleModel->checkSystemActionRoleExist($systemActionID, $roleID);
            $total = $checkSystemActionRoleExist['total'] ?? 0;
        
            if ($total === 0) {
                $this->roleModel->insertRoleSystemActionAccess($systemActionID, $roleID, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteRole
    # Description:
    # Delete the role if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
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
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleRole
    # Description:
    # Delete the selected roles if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
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
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMenuItemRoleAccess
    # Description:
    # Delete the menu item role access if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMenuItemRoleAccess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
        $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkRoleMenuAccessExist = $this->roleModel->checkRoleMenuAccessExist($menuItemID, $roleID);
        $total = $checkRoleMenuAccessExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->roleModel->deleteMenuItemRoleAccess($menuItemID, $roleID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSystemActionRoleAccess
    # Description:
    # Delete the system action role if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSystemActionRoleAccess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
        $systemActionID = htmlspecialchars($_POST['system_action_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSystemActionRoleExist = $this->roleModel->checkSystemActionRoleExist($systemActionID, $roleID);
        $total = $checkSystemActionRoleExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->roleModel->deleteSystemActionRoleAccess($systemActionID, $roleID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteUserAccountRole
    # Description:
    # Delete the user account role if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteUserAccountRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $roleID = htmlspecialchars($_POST['role_id'], ENT_QUOTES, 'UTF-8');
        $userAccountID = htmlspecialchars($_POST['user_account_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkRoleUserExist = $this->roleModel->checkRoleUserExist($userAccountID, $roleID);
        $total = $checkRoleUserExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->roleModel->deleteRoleUser($userAccountID, $roleID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateRole
    # Description:
    # Duplicates the role if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
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
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getRoleDetails
    # Description:
    # Handles the retrieval of role details such as role name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
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
    # -------------------------------------------------------------
}
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new RoleController(new RoleModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>