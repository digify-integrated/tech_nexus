<?php
session_start();

# -------------------------------------------------------------
#
# Function: ColorController
# Description: 
# The ColorController class handles color related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ColorController {
    private $colorModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ColorModel, UserModel and SecurityModel instances.
    # These instances are used for color related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ColorModel $colorModel     The ColorModel instance for color related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ColorModel $colorModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->colorModel = $colorModel;
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
                case 'save color':
                    $this->saveColor();
                    break;
                case 'get color details':
                    $this->getColorDetails();
                    break;
                case 'delete color':
                    $this->deleteColor();
                    break;
                case 'delete multiple color':
                    $this->deleteMultipleColor();
                    break;
                case 'duplicate color':
                    $this->duplicateColor();
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
    # Function: saveColor
    # Description: 
    # Updates the existing color if it exists; otherwise, inserts a new color.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveColor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $colorID = isset($_POST['color_id']) ? htmlspecialchars($_POST['color_id'], ENT_QUOTES, 'UTF-8') : null;
        $colorName = htmlspecialchars($_POST['color_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkColorExist = $this->colorModel->checkColorExist($colorID);
        $total = $checkColorExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->colorModel->updateColor($colorID, $colorName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'colorID' => $this->securityModel->encryptData($colorID)]);
            exit;
        } 
        else {
            $colorID = $this->colorModel->insertColor($colorName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'colorID' => $this->securityModel->encryptData($colorID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteColor
    # Description: 
    # Delete the color if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteColor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $colorID = htmlspecialchars($_POST['color_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkColorExist = $this->colorModel->checkColorExist($colorID);
        $total = $checkColorExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->colorModel->deleteColor($colorID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleColor
    # Description: 
    # Delete the selected colors if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleColor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $colorIDs = $_POST['color_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($colorIDs as $colorID){
            $this->colorModel->deleteColor($colorID);
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
    # Function: duplicateColor
    # Description: 
    # Duplicates the color if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateColor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $colorID = htmlspecialchars($_POST['color_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkColorExist = $this->colorModel->checkColorExist($colorID);
        $total = $checkColorExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $colorID = $this->colorModel->duplicateColor($colorID, $userID);

        echo json_encode(['success' => true, 'colorID' =>  $this->securityModel->encryptData($colorID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getColorDetails
    # Description: 
    # Handles the retrieval of color details such as color name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getColorDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['color_id']) && !empty($_POST['color_id'])) {
            $userID = $_SESSION['user_id'];
            $colorID = $_POST['color_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $colorDetails = $this->colorModel->getColor($colorID);

            $response = [
                'success' => true,
                'colorName' => $colorDetails['color_name']
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
require_once '../model/color-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ColorController(new ColorModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>