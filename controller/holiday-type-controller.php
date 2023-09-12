<?php
session_start();

# -------------------------------------------------------------
#
# Function: HolidayTypeController
# Description: 
# The HolidayTypeController class handles holiday type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class HolidayTypeController {
    private $holidayTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided HolidayTypeModel, UserModel and SecurityModel instances.
    # These instances are used for holiday type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param HolidayTypeModel $holidayTypeModel     The HolidayTypeModel instance for holiday type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(HolidayTypeModel $holidayTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->holidayTypeModel = $holidayTypeModel;
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
                case 'save holiday type':
                    $this->saveHolidayType();
                    break;
                case 'get holiday type details':
                    $this->getHolidayTypeDetails();
                    break;
                case 'delete holiday type':
                    $this->deleteHolidayType();
                    break;
                case 'delete multiple holiday type':
                    $this->deleteMultipleHolidayType();
                    break;
                case 'duplicate holiday type':
                    $this->duplicateHolidayType();
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
    # Function: saveHolidayType
    # Description: 
    # Updates the existing holiday type if it exists; otherwise, inserts a new holiday type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveHolidayType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $holidayTypeID = isset($_POST['holiday_type_id']) ? htmlspecialchars($_POST['holiday_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $holidayTypeName = htmlspecialchars($_POST['holiday_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkHolidayTypeExist = $this->holidayTypeModel->checkHolidayTypeExist($holidayTypeID);
        $total = $checkHolidayTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->holidayTypeModel->updateHolidayType($holidayTypeID, $holidayTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'holidayTypeID' => $this->securityModel->encryptData($holidayTypeID)]);
            exit;
        } 
        else {
            $holidayTypeID = $this->holidayTypeModel->insertHolidayType($holidayTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'holidayTypeID' => $this->securityModel->encryptData($holidayTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteHolidayType
    # Description: 
    # Delete the holiday type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteHolidayType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $holidayTypeID = htmlspecialchars($_POST['holiday_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkHolidayTypeExist = $this->holidayTypeModel->checkHolidayTypeExist($holidayTypeID);
        $total = $checkHolidayTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->holidayTypeModel->deleteHolidayType($holidayTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleHolidayType
    # Description: 
    # Delete the selected holiday types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleHolidayType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $holidayTypeIDs = $_POST['holiday_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($holidayTypeIDs as $holidayTypeID){
            $this->holidayTypeModel->deleteHolidayType($holidayTypeID);
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
    # Function: duplicateHolidayType
    # Description: 
    # Duplicates the holiday type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateHolidayType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $holidayTypeID = htmlspecialchars($_POST['holiday_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkHolidayTypeExist = $this->holidayTypeModel->checkHolidayTypeExist($holidayTypeID);
        $total = $checkHolidayTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $holidayTypeID = $this->holidayTypeModel->duplicateHolidayType($holidayTypeID, $userID);

        echo json_encode(['success' => true, 'holidayTypeID' =>  $this->securityModel->encryptData($holidayTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getHolidayTypeDetails
    # Description: 
    # Handles the retrieval of holiday type details such as holiday type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getHolidayTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['holiday_type_id']) && !empty($_POST['holiday_type_id'])) {
            $userID = $_SESSION['user_id'];
            $holidayTypeID = $_POST['holiday_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $holidayTypeDetails = $this->holidayTypeModel->getHolidayType($holidayTypeID);

            $response = [
                'success' => true,
                'holidayTypeName' => $holidayTypeDetails['holiday_type_name']
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
require_once '../model/holiday-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new HolidayTypeController(new HolidayTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>