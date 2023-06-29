<?php
session_start();

/**
* Class MenuGroupController
*
* The MenuGroupController class handles menu group related operations and interactions.
*/
class MenuGroupController {
    private $menuGroupModel;
    private $userModel;
    private $securityModel;

    /**
    * Create a new instance of the class.
    *
    * The constructor initializes the object with the provided UserModel and SecurityModel instances.
    * These instances are used for user-related operations and security-related operations, respectively.
    *
    * @param MenuGroupModel $menuGroupModel     The MenuGroupModel instance for menu group related operations.
    * @param UserModel $userModel     The UserModel instance for user related operations.
    * @param SecurityModel $securityModel   The SecurityModel instance for security-related operations.
    * @return void
    */
    public function __construct(MenuGroupModel $menuGroupModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->menuGroupModel = $menuGroupModel;
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
                case 'save menu group':
                    $this->saveMenuGroup();
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

    /**
    * Saves the menu group.
    * Updates the existing menu group if it exists; otherwise, inserts a new menu group.
    *
    * @return void
    */
    public function saveMenuGroup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $menuGroupID = isset($_POST['menu_group_id']) ? htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8') : null;
        $menuGroupName = htmlspecialchars($_POST['menu_group_name'], ENT_QUOTES, 'UTF-8');
        $menuGroupOrderSequence = htmlspecialchars($_POST['menu_group_order_sequence'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMenuGroupExist = $this->menuGroupModel->checkMenuGroupExist($menuGroupID);
        $total = $checkUICustomizationSettingExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->menuGroupModel->updateMenuGroup($menuGroupID, $menuGroupName, $menuGroupOrderSequence, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $menuGroupID = $this->menuGroupModel->insertMenuGroup($menuGroupName, $menuGroupOrderSequence, $userID);

            echo json_encode(['success' => true, 'menuGroupID' =>  $this->securityModel->encryptData($menuGroupID)]);
            exit;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/menu-group-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';

$controller = new MenuGroupController(new MenuGroupModel(new DatabaseModel), new UserModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>