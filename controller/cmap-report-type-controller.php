<?php
session_start();

# -------------------------------------------------------------
#
# Function: CMAPReportTypeController
# Description: 
# The CMAPReportTypeController class handles cmap report type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class CMAPReportTypeController {
    private $cmapReportTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CMAPReportTypeModel, UserModel and SecurityModel instances.
    # These instances are used for cmap report type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param CMAPReportTypeModel $cmapReportTypeModel     The CMAPReportTypeModel instance for cmap report type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(CMAPReportTypeModel $cmapReportTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->cmapReportTypeModel = $cmapReportTypeModel;
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
                case 'save cmap report type':
                    $this->saveCMAPReportType();
                    break;
                case 'get cmap report type details':
                    $this->getCMAPReportTypeDetails();
                    break;
                case 'delete cmap report type':
                    $this->deleteCMAPReportType();
                    break;
                case 'delete multiple cmap report type':
                    $this->deleteMultipleCMAPReportType();
                    break;
                case 'duplicate cmap report type':
                    $this->duplicateCMAPReportType();
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
    # Function: saveCMAPReportType
    # Description: 
    # Updates the existing cmap report type if it exists; otherwise, inserts a new cmap report type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveCMAPReportType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cmapReportTypeID = isset($_POST['cmap_report_type_id']) ? htmlspecialchars($_POST['cmap_report_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $cmapReportTypeName = htmlspecialchars($_POST['cmap_report_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCMAPReportTypeExist = $this->cmapReportTypeModel->checkCMAPReportTypeExist($cmapReportTypeID);
        $total = $checkCMAPReportTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->cmapReportTypeModel->updateCMAPReportType($cmapReportTypeID, $cmapReportTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'cmapReportTypeID' => $this->securityModel->encryptData($cmapReportTypeID)]);
            exit;
        } 
        else {
            $cmapReportTypeID = $this->cmapReportTypeModel->insertCMAPReportType($cmapReportTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'cmapReportTypeID' => $this->securityModel->encryptData($cmapReportTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCMAPReportType
    # Description: 
    # Delete the cmap report type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteCMAPReportType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cmapReportTypeID = htmlspecialchars($_POST['cmap_report_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCMAPReportTypeExist = $this->cmapReportTypeModel->checkCMAPReportTypeExist($cmapReportTypeID);
        $total = $checkCMAPReportTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->cmapReportTypeModel->deleteCMAPReportType($cmapReportTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleCMAPReportType
    # Description: 
    # Delete the selected cmap report types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleCMAPReportType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cmapReportTypeIDs = $_POST['cmap_report_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($cmapReportTypeIDs as $cmapReportTypeID){
            $this->cmapReportTypeModel->deleteCMAPReportType($cmapReportTypeID);
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
    # Function: duplicateCMAPReportType
    # Description: 
    # Duplicates the cmap report type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateCMAPReportType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cmapReportTypeID = htmlspecialchars($_POST['cmap_report_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCMAPReportTypeExist = $this->cmapReportTypeModel->checkCMAPReportTypeExist($cmapReportTypeID);
        $total = $checkCMAPReportTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $cmapReportTypeID = $this->cmapReportTypeModel->duplicateCMAPReportType($cmapReportTypeID, $userID);

        echo json_encode(['success' => true, 'cmapReportTypeID' =>  $this->securityModel->encryptData($cmapReportTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCMAPReportTypeDetails
    # Description: 
    # Handles the retrieval of cmap report type details such as cmap report type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getCMAPReportTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['cmap_report_type_id']) && !empty($_POST['cmap_report_type_id'])) {
            $userID = $_SESSION['user_id'];
            $cmapReportTypeID = $_POST['cmap_report_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $cmapReportTypeDetails = $this->cmapReportTypeModel->getCMAPReportType($cmapReportTypeID);

            $response = [
                'success' => true,
                'cmapReportTypeName' => $cmapReportTypeDetails['cmap_report_type_name']
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
require_once '../model/cmap-report-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CMAPReportTypeController(new CMAPReportTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>