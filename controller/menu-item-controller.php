<?php
session_start();

/**
* Class MenuItemController
*
* The MenuItemController class handles menu item related operations and interactions.
*/
class MenuItemController {
    private $menuItemModel;
    private $menuGroupModel;
    private $userModel;
    private $securityModel;

    /**
    * Create a new instance of the class.
    *
    * The constructor initializes the object with the provided UserModel and SecurityModel instances.
    * These instances are used for user-related operations and security-related operations, respectively.
    *
    * @param MenuItemModel $menuItemModel     The MenuItemModel instance for menu item related operations.
    * @param UserModel $userModel     The UserModel instance for user related operations.
    * @param SecurityModel $securityModel   The SecurityModel instance for security-related operations.
    * @return void
    */
    public function __construct(MenuItemModel $menuItemModel, MenuGroupModel $menuGroupModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->menuItemModel = $menuItemModel;
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
                case 'save menu item':
                    $this->saveMenuItem();
                    break;
                case 'get menu item details':
                    $this->getMenuItemDetails();
                    break;
                case 'delete menu item':
                    $this->deleteMenuItem();
                    break;
                case 'delete multiple menu item':
                    $this->deleteMultipleMenuItem();
                    break;
                case 'duplicate menu item':
                    $this->duplicateMenuItem();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    /**
    * Saves the menu item.
    * Updates the existing menu item if it exists; otherwise, inserts a new menu item.
    *
    * @return void
    */
    public function saveMenuItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');
        $menuGroupID = htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8');
        $menuItemName = htmlspecialchars($_POST['menu_item_name'], ENT_QUOTES, 'UTF-8');
        $menuItemURL = htmlspecialchars($_POST['menu_item_url'], ENT_QUOTES, 'UTF-8');
        $menuItemIcon = htmlspecialchars($_POST['menu_item_icon'], ENT_QUOTES, 'UTF-8');
        $parentID = htmlspecialchars($_POST['parent_id'], ENT_QUOTES, 'UTF-8');
        $menuItemOrderSequence = htmlspecialchars($_POST['menu_item_order_sequence'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMenuItemExist = $this->menuItemModel->checkMenuItemExist($menuItemID);
        $total = $checkMenuItemExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->menuItemModel->updateMenuItem($menuItemID, $menuItemName, $menuGroupID, $menuItemURL, $parentID, $menuItemIcon, $menuItemOrderSequence, $userID);
            
            echo json_encode(['success' => true, 'menuItemID' => $this->securityModel->encryptData($menuItemID)]);
            exit;
        } 
        else {
            $menuItemID = $this->menuItemModel->insertMenuItem($menuItemName, $menuGroupID, $menuItemURL, $parentID, $menuItemIcon, $menuItemOrderSequence, $userID);

            echo json_encode(['success' => true, 'menuItemID' => $this->securityModel->encryptData($menuItemID)]);
            exit;
        }
    }

    /**
    * Deletes the menu item.
    * Delete the menu item if it exists; otherwise, return an error message.
    *
    * @return void
    */
    public function deleteMenuItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMenuItemExist = $this->menuItemModel->checkMenuItemExist($menuItemID);
        $total = $checkMenuItemExist['total'] ?? 0;

        if($total == 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->menuItemModel->deleteMenuItem($menuItemID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    /**
    * Deletes multiple menu items.
    * Delete the selected menu items if it exists; otherwise, skip it.
    *
    * @return void
    */
    public function deleteMultipleMenuItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $menuItemIDs = $_POST['menu_item_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($menuItemIDs as $menuItemID){
            $this->menuItemModel->deleteMenuItem($menuItemID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }

    /**
    * Deuplicates the menu item.
    * Deuplocates the menu item if it exists; otherwise, return an error message.
    *
    * @return void
    */
    public function duplicateMenuItem() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $menuItemID = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMenuItemExist = $this->menuItemModel->checkMenuItemExist($menuItemID);
        $total = $checkMenuItemExist['total'] ?? 0;

        if($total == 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $menuItemID = $this->menuItemModel->duplicateMenuItem($menuItemID, $userID);

        echo json_encode(['success' => true, 'menuItemID' =>  $this->securityModel->encryptData($menuItemID)]);
        exit;
    }

    /**
    * Retrieves the menu item details.
    * Handles the retrieval of menu item details such as menu item name, order sequence, etc.
    *
    * @return void
    */
    public function getMenuItemDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])) {
            $userID = $_SESSION['user_id'];
            $menuItemID = $_POST['menu_item_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $menuItemDetails = $this->menuItemModel->getMenuItem($menuItemID);
            $menuGroupID = $menuItemDetails['menu_group_id'];
            $parentID = $menuItemDetails['parent_id'];
            $menuItemURL = $menuItemDetails['menu_item_url'];

            $menuGroupDetails = $this->menuGroupModel->getMenuGroup($menuGroupID);
            $menuGroupName = $menuGroupDetails['menu_group_name'];

            $menuGroupIDEncrypted = $this->securityModel->encryptData($menuGroupID);
            $parentIDEncrypted = $this->securityModel->encryptData($parentID);

            if(!empty($parentID)){
                $menuItemParentIDDetails = $this->menuItemModel->getMenuItem($parentID);
                $parentName = $menuItemParentIDDetails['menu_item_name'];

                $parentName = '<a href="menu-item.php?id='. $parentIDEncrypted .'">'. $parentName .'</a>';
            }
            else{
                $parentName = null;
            }

            if(!empty($menuItemURL)){
                $menuItemURLLink = '<a href="'. $menuItemURL .'">'. $menuItemURL .'</a>';
            }
            else{
                $menuItemURLLink = null;
            }

            $response = [
                'success' => true,
                'menuItemName' => $menuItemDetails['menu_item_name'],
                'menuGroupID' => $menuGroupID,
                'menuGroupName' => '<a href="menu-group.php?id='. $menuGroupIDEncrypted .'">'. $menuGroupName .'</a>',
                'menuItemURL' => $menuItemURL,
                'menuItemURLLink' => $menuItemURLLink,
                'parentID' => $parentID,
                'parentName' => $parentName,
                'menuItemIcon' => $menuItemDetails['menu_item_icon'],
                'orderSequence' => $menuItemDetails['order_sequence']
            ];

            echo json_encode($response);
            exit;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/menu-item-model.php';
require_once '../model/menu-group-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new MenuItemController(new MenuItemModel(new DatabaseModel), new MenuGroupModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>