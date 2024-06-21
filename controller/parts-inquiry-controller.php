<?php
session_start();

# -------------------------------------------------------------
#
# Function: PartsInquiryController
# Description: 
# The PartsInquiryController class handles parts inquiry related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PartsInquiryController {
    private $partsInquiryModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PartsInquiryModel, UserModel and SecurityModel instances.
    # These instances are used for parts inquiry related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PartsInquiryModel $uploadSettingModel     The PartsInquiryModel instance for parts inquiry related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PartsInquiryModel $partsInquiryModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SecurityModel $securityModel) {
        $this->partsInquiryModel = $partsInquiryModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
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
                case 'save parts inquiry':
                    $this->savePartsInquiry();
                    break;
                    case 'save parts inquiry import':
                        $this->saveImportPartsInquiry();
                        break;
                case 'get parts inquiry details':
                    $this->getPartsInquiryDetails();
                    break;
                case 'delete parts inquiry':
                    $this->deletePartsInquiry();
                    break;
                case 'delete multiple parts inquiry':
                    $this->deleteMultiplePartsInquiry();
                    break;
                case 'add parts inquiry file extension':
                    $this->addPartsInquiryFileExtension();
                    break;
                case 'delete parts inquiry file extension':
                    $this->deletePartsInquiryFileExtension();
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
    # Function: savePartsInquiry
    # Description: 
    # Updates the existing parts inquiry if it exists; otherwise, inserts a new parts inquiry.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePartsInquiry() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsInquiryID = isset($_POST['parts_inquiry_id']) ? htmlspecialchars($_POST['parts_inquiry_id'], ENT_QUOTES, 'UTF-8') : null;
        $partsNumber = $_POST['parts_number'];
        $partsDescription = htmlspecialchars($_POST['parts_description'], ENT_QUOTES, 'UTF-8');
        $stock = htmlspecialchars($_POST['stock'], ENT_QUOTES, 'UTF-8');
        $price = htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->partsInquiryModel->updatePartsInquiry($partsInquiryID, $partsNumber, $partsDescription, $stock, $price, $userID);

        echo json_encode(['success' => true, 'insertRecord' => true, 'partsInquiryID' => $this->securityModel->encryptData($partsInquiryID)]);
        exit;
    }
    # -------------------------------------------------------------

     # -------------------------------------------------------------
    #
    # Function: saveImportPartsInquiry
    # Description: 
    # Save the imported attendance record for loading.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveImportPartsInquiry() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $importFileFileName = $_FILES['import_file']['name'];
        $importFileFileSize = $_FILES['import_file']['size'];
        $importFileFileError = $_FILES['import_file']['error'];
        $importFileTempName = $_FILES['import_file']['tmp_name'];
        $importFileFileExtension = explode('.', $importFileFileName);
        $importFileActualFileExtension = strtolower(end($importFileFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(5);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(5);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($importFileActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }

        if(empty($importFileTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the import file.']);
            exit;
        }

        if($importFileFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }

        if($importFileFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The import file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $importData = array_map('str_getcsv', file($importFileTempName));

        array_shift($importData);

        
        $this->partsInquiryModel->deletePartsInquiryTable();

        foreach ($importData as $row) {
            $partsNumber = $row[0] ?? null;
            $partsDescription = str_replace(',', ' ', $row[1]);
            $stock = $row[2] ?? null;
            $price = str_replace(',', '', $row[3] ?? null);
    
            $partsInquiryID = $this->partsInquiryModel->insertPartsInquiry($partsNumber, $partsDescription, $stock, $price, $userID);
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
    # Function: deletePartsInquiry
    # Description: 
    # Delete the parts inquiry if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePartsInquiry() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsInquiryID = htmlspecialchars($_POST['parts_inquiry_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsInquiryExist = $this->partsInquiryModel->checkPartsInquiryExist($partsInquiryID);
        $total = $checkPartsInquiryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->partsInquiryModel->deletePartsInquiry($partsInquiryID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePartsInquiry
    # Description: 
    # Delete the selected parts inquirys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePartsInquiry() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsInquiryIDs = $_POST['parts_inquiry_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($partsInquiryIDs as $partsInquiryID){
            $this->partsInquiryModel->deletePartsInquiry($partsInquiryID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsInquiryDetails
    # Description: 
    # Handles the retrieval of parts inquiry details such as parts inquiry name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPartsInquiryDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_inquiry_id']) && !empty($_POST['parts_inquiry_id'])) {
            $userID = $_SESSION['user_id'];
            $partsInquiryID = $_POST['parts_inquiry_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $uploadSettingDetails = $this->partsInquiryModel->getPartsInquiry($partsInquiryID);

            $response = [
                'success' => true,
                'partsNumber' => $uploadSettingDetails['parts_number'],
                'partsDescription' => $uploadSettingDetails['parts_description'],
                'stock' => $uploadSettingDetails['stock'],
                'price' => $uploadSettingDetails['price']
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
require_once '../model/parts-inquiry-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsInquiryController(new PartsInquiryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>