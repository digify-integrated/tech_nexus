<?php
session_start();

# -------------------------------------------------------------
#
# Function: InternalDRController
# Description: 
# The InternalDRController class handles internal DR related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class InternalDRController {
    private $internalDRModel;
    private $userModel;
    private $securityModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided InternalDRModel, UserModel and SecurityModel instances.
    # These instances are used for internal DR related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param InternalDRModel $internalDRModel     The InternalDRModel instance for internal DR related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(InternalDRModel $internalDRModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->internalDRModel = $internalDRModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemModel = $systemModel;
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
                case 'save internal DR':
                    $this->saveInternalDR();
                    break;
                case 'save internal dr unit image':
                    $this->saveInternalDRUnitImage();
                    break;
                case 'get internal DR details':
                    $this->getInternalDRDetails();
                    break;
                case 'tag for release':
                    $this->tagInternalDRAsReleased();
                    break;
                case 'tag for cancelled':
                    $this->tagInternalDRAsCancelled();
                    break;
                case 'delete internal DR':
                    $this->deleteInternalDR();
                    break;
                case 'delete multiple internal DR':
                    $this->deleteMultipleInternalDR();
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
    # Function: saveInternalDR
    # Description: 
    # Updates the existing internal DR if it exists; otherwise, inserts a new internal DR.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveInternalDR() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $internalDRID = isset($_POST['internal_dr_id']) ? htmlspecialchars($_POST['internal_dr_id'], ENT_QUOTES, 'UTF-8') : null;
        $releaseTo = htmlspecialchars($_POST['release_to'], ENT_QUOTES, 'UTF-8');
        $releaseMobile = htmlspecialchars($_POST['release_mobile'], ENT_QUOTES, 'UTF-8');
        $releaseAddress = htmlspecialchars($_POST['release_address'], ENT_QUOTES, 'UTF-8');
        $drNumber = htmlspecialchars($_POST['dr_number'], ENT_QUOTES, 'UTF-8');
        $drType = htmlspecialchars($_POST['dr_type'], ENT_QUOTES, 'UTF-8');
        $stockNumber = htmlspecialchars($_POST['stock_number'], ENT_QUOTES, 'UTF-8');
        $engineNumber = htmlspecialchars($_POST['engine_number'], ENT_QUOTES, 'UTF-8');
        $chassisNumber = htmlspecialchars($_POST['chassis_number'], ENT_QUOTES, 'UTF-8');
        $plateNumber = htmlspecialchars($_POST['plate_number'], ENT_QUOTES, 'UTF-8');
        $productDescription = htmlspecialchars($_POST['product_description'], ENT_QUOTES, 'UTF-8');
        $backjob_monitoring_id = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInternalDRExist = $this->internalDRModel->checkInternalDRExist($internalDRID);
        $total = $checkInternalDRExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->internalDRModel->updateInternalDR($internalDRID, $releaseTo, $releaseMobile, $releaseAddress, $drNumber, $drType, $backjob_monitoring_id, $stockNumber, $productDescription, $engineNumber, $chassisNumber, $plateNumber, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'internalDRID' => $this->securityModel->encryptData($internalDRID)]);
            exit;
        } 
        else {
            $internalDRID = $this->internalDRModel->insertInternalDR($releaseTo, $releaseMobile, $releaseAddress, $drNumber, $drType, $backjob_monitoring_id, $stockNumber, $productDescription, $engineNumber, $chassisNumber, $plateNumber, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'internalDRID' => $this->securityModel->encryptData($internalDRID)]);
            exit;
        }
    }
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveInternalDRUnitImage
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveInternalDRUnitImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $internalDRID = htmlspecialchars($_POST['internal_dr_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInternalDRExist = $this->internalDRModel->checkInternalDRExist($internalDRID);
        $total = $checkInternalDRExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['unit_image_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['unit_image_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['unit_image_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['unit_image_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $internalDRDetails = $this->internalDRModel->getInternalDR($internalDRID);
            $clientoutgoingChecklistImage = !empty($internalDRDetails['unit_image']) ? '.' . $internalDRDetails['unit_image'] : null;
    
            if(file_exists($clientoutgoingChecklistImage)){
                if (!unlink($clientoutgoingChecklistImage)) {
                    echo json_encode(['success' => false, 'message' => 'Unit image cannot be deleted due to an error.']);
                    exit;
                }
            }

            $uploadSetting = $this->uploadSettingModel->getUploadSetting(15);
            $maxFileSize = $uploadSetting['max_file_size'];

            $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(15);
            $allowedFileExtensions = [];

            foreach ($uploadSettingFileExtension as $row) {
                $fileExtensionID = $row['file_extension_id'];
                $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
                $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
            }

            if (!in_array($outgoingChecklistImageActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
            
            if(empty($outgoingChecklistImageTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the unit image.']);
                exit;
            }
            
            if($outgoingChecklistImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($outgoingChecklistImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The unit image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $outgoingChecklistImageActualFileExtension;

            $directory = DEFAULT_SALES_PROPOSAL_RELATIVE_PATH_FILE.'/unit_image/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_SALES_PROPOSAL_FULL_PATH_FILE . '/unit_image/' . $fileNew;
            $filePath = $directory . $fileNew;

            $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

            if(!$directoryChecker){
                echo json_encode(['success' => false, 'message' => $directoryChecker]);
                exit;
            }

            if(!move_uploaded_file($outgoingChecklistImageTempName, $fileDestination)){
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $this->internalDRModel->updateInternalDRUnitImage($internalDRID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The sales proposal does not exists.']);
            exit;
        }
    }

    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteInternalDR
    # Description: 
    # Delete the internal DR if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteInternalDR() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $internalDRID = htmlspecialchars($_POST['internal_dr_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInternalDRExist = $this->internalDRModel->checkInternalDRExist($internalDRID);
        $total = $checkInternalDRExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->internalDRModel->deleteInternalDR($internalDRID);
            
        echo json_encode(['success' => true]);
        exit;
    }


    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleInternalDR
    # Description: 
    # Delete the selected internal DRs if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleInternalDR() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $internalDRIDs = $_POST['internal_dr_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($internalDRIDs as $internalDRID){
            $this->internalDRModel->deleteInternalDR($internalDRID);
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
    # Function: tagInternalDRAsReleased
    # Description: 
    # Updates the existing internal dr accessories if it exists; otherwise, inserts a new internal dr accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagInternalDRAsReleased() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $internalDRID = htmlspecialchars($_POST['internal_dr_id'], ENT_QUOTES, 'UTF-8');
        $releaseRemarks = $_POST['release_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInternalDRExist = $this->internalDRModel->checkInternalDRExist($internalDRID);
        $total = $checkInternalDRExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->internalDRModel->updateInternalDRAsReleased($internalDRID, 'Released', $releaseRemarks, $userID);

        $internalDRDetails = $this->internalDRModel->getInternalDR($internalDRID);
        $drType = $internalDRDetails['dr_type'];
        $backjob_monitoring_id = $internalDRDetails['backjob_monitoring_id'];

        if($drType === 'Backjob' || $drType === 'Warranty'){
            $this->internalDRModel->updateSalesProposalBackjobProgress($backjob_monitoring_id, $userID);
        }

        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagInternalDRAsCancelled
    # Description: 
    # Updates the existing internal dr accessories if it exists; otherwise, inserts a new internal dr accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagInternalDRAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $internalDRID = htmlspecialchars($_POST['internal_dr_id'], ENT_QUOTES, 'UTF-8');
        $cancellationReason = $_POST['cancellation_reason'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInternalDRExist = $this->internalDRModel->checkInternalDRExist($internalDRID);
        $total = $checkInternalDRExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->internalDRModel->updateInternalDRAsCancelled($internalDRID, 'Cancelled', $cancellationReason, $userID);

        echo json_encode(['success' => true]);
    }

    # -------------------------------------------------------------
    #
    # Function: tagInternalDRAsCancelled
    # Description: 
    # Updates the existing internal dr accessories if it exists; otherwise, inserts a new internal dr accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getInternalDRDetails
    # Description: 
    # Handles the retrieval of internal DR details such as internal DR name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getInternalDRDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['internal_dr_id']) && !empty($_POST['internal_dr_id'])) {
            $userID = $_SESSION['user_id'];
            $internalDRID = $_POST['internal_dr_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $internalDRDetails = $this->internalDRModel->getInternalDR($internalDRID);

            $response = [
                'success' => true,
                'releaseTo' => $internalDRDetails['release_to'],
                'releaseMobile' => $internalDRDetails['release_mobile'],
                'releaseAddress' => $internalDRDetails['release_address'],
                'drNumber' => $internalDRDetails['dr_number'],
                'drType' => $internalDRDetails['dr_type'],
                'stockNumber' => $internalDRDetails['stock_number'],
                'productDescription' => $internalDRDetails['product_description'],
                'engineNumber' => $internalDRDetails['engine_number'],
                'chassisNumber' => $internalDRDetails['chassis_number'],
                'plateNumber' => $internalDRDetails['plate_number'],
                'backjobMonitoringID' => $internalDRDetails['backjob_monitoring_id'],
                'unitImage' =>  $this->systemModel->checkImage($internalDRDetails['unit_image'], 'default')
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
require_once '../model/internal-dr-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new InternalDRController(new InternalDRModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>