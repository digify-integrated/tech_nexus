<?php
session_start();

# -------------------------------------------------------------
#
# Function: MenuGroupController
# Description: 
# The MenuGroupController class handles menu group related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class MenuGroupController {
    private $menuGroupModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided MenuGroupModel, UserModel and SecurityModel instances.
    # These instances are used for menu group related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param MenuGroupModel $menuGroupModel     The MenuGroupModel instance for menu group related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(MenuGroupModel $menuGroupModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->menuGroupModel = $menuGroupModel;
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
                case 'save menu group':
                    $this->saveMenuGroup();
                    break;
                case 'get menu group details':
                    $this->getMenuGroupDetails();
                    break;
                case 'delete menu group':
                    $this->deleteMenuGroup();
                    break;
                case 'delete multiple menu group':
                    $this->deleteMultipleMenuGroup();
                    break;
                case 'duplicate menu group':
                    $this->duplicateMenuGroup();
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
    # Function: saveMenuGroup
    # Description: 
    # Updates the existing menu group if it exists; otherwise, inserts a new menu group.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveMenuGroup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $menuGroupID = isset($_POST['menu_group_id']) ? htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8') : null;
        $menuGroupName = htmlspecialchars($_POST['menu_group_name'], ENT_QUOTES, 'UTF-8');
        $menuGroupOrderSequence = htmlspecialchars($_POST['menu_group_order_sequence'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMenuGroupExist = $this->menuGroupModel->checkMenuGroupExist($menuGroupID);
        $total = $checkMenuGroupExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->menuGroupModel->updateMenuGroup($menuGroupID, $menuGroupName, $menuGroupOrderSequence, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'menuGroupID' => $this->securityModel->encryptData($menuGroupID)]);
            exit;
        } 
        else {
            $menuGroupID = $this->menuGroupModel->insertMenuGroup($menuGroupName, $menuGroupOrderSequence, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'menuGroupID' => $this->securityModel->encryptData($menuGroupID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMenuGroup
    # Description: 
    # Delete the menu group if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMenuGroup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $menuGroupID = htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMenuGroupExist = $this->menuGroupModel->checkMenuGroupExist($menuGroupID);
        $total = $checkMenuGroupExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->menuGroupModel->deleteMenuGroup($menuGroupID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleMenuGroup
    # Description: 
    # Delete the selected menu groups if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleMenuGroup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $menuGroupIDs = $_POST['menu_group_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($menuGroupIDs as $menuGroupID){
            $this->menuGroupModel->deleteMenuGroup($menuGroupID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateMenuGroup
    # Description: 
    # Duplicates the menu group if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateMenuGroup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $menuGroupID = htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMenuGroupExist = $this->menuGroupModel->checkMenuGroupExist($menuGroupID);
        $total = $checkMenuGroupExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $menuGroupID = $this->menuGroupModel->duplicateMenuGroup($menuGroupID, $userID);

        echo json_encode(['success' => true, 'menuGroupID' =>  $this->securityModel->encryptData($menuGroupID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getMenuGroupDetails
    # Description: 
    # Handles the retrieval of menu group details such as menu group name, order sequence, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getMenuGroupDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['menu_group_id']) && !empty($_POST['menu_group_id'])) {
            $userID = $_SESSION['user_id'];
            $menuGroupID = $_POST['menu_group_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $menuGroupDetails = $this->menuGroupModel->getMenuGroup($menuGroupID);

            $response = [
                'success' => true,
                'menuGroupName' => $menuGroupDetails['menu_group_name'],
                'orderSequence' => $menuGroupDetails['order_sequence']
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
require_once '../model/menu-group-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new MenuGroupController(new MenuGroupModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();

?>