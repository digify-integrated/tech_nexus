<?php
session_start();

# -------------------------------------------------------------
#
# Function: DocumentController
# Description: 
# The DocumentController class handles document related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class DocumentController {
    private $databaseModel;
    private $documentModel;
    private $documentCategoryModel;
    private $employeeModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided EmployeeModel, UserModel and SecurityModel instances.
    # These instances are used for document related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param DatabaseModel $databaseModel     The DatabaseModel instance for database operations.
    # - @param DocumentModel $documentModel     The DocumentModel instance for document related operations.
    # - @param DocumentCategoryModel $documentCategoryModel     The DocumentCategoryModel instance for document category related operations.
    # - @param EmployeeModel $employeeModel     The EmployeeModel instance for employee related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(DatabaseModel $databaseModel, DocumentModel $documentModel, DocumentCategoryModel $documentCategoryModel, EmployeeModel $employeeModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->databaseModel = $databaseModel;
        $this->documentModel = $documentModel;
        $this->documentCategoryModel = $documentCategoryModel;
        $this->employeeModel = $employeeModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->securityModel = $securityModel;
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
                case 'add document':
                    $this->addDocument();
                    break;
                case 'update document':
                    $this->updateDocument();
                    break;
                case 'update document file':
                    $this->updateDocumentFile();
                    break;
                case 'add department restriction':
                    $this->addDepartmentRestriction();
                    break;
                case 'add employee restriction':
                    $this->addEmployeeRestriction();
                    break;
                case 'change document password':
                    $this->updateDocumentPassword();
                    break;
                case 'remove document password':
                    $this->updateDocumentPasswordToNull();
                    break;
                case 'preview protected document':
                    $this->previewProtectedDocument();
                    break;
                case 'publish document':
                    $this->publishDocument();
                    break;
                case 'unpublish document':
                    $this->unpublishDocument();
                    break;
                case 'get document details':
                    $this->getDocumentDetails();
                    break;
                case 'delete document':
                    $this->deleteDocument();
                    break;
                case 'delete document restrictions':
                    $this->deleteDocumentRestriction();
                    break;
                case 'delete multiple document':
                    $this->deleteMultipleDocument();
                    break;
                case 'duplicate document':
                    $this->duplicateDocument();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Add methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addDocument
    # Description: 
    # Inserts the document.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $documentName = htmlspecialchars($_POST['document_name'], ENT_QUOTES, 'UTF-8');
        $documentCategoryID = htmlspecialchars($_POST['document_category_id'], ENT_QUOTES, 'UTF-8');
        $isConfidential = htmlspecialchars($_POST['is_confidential'], ENT_QUOTES, 'UTF-8');
        $documentPassword = $this->securityModel->encryptData(htmlspecialchars($_POST['document_password'], ENT_QUOTES, 'UTF-8'));;
        $documentDescription = htmlspecialchars($_POST['document_description'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $documentFileFileName = $_FILES['document_file']['name'];
        $documentFileFileSize = $_FILES['document_file']['size'];
        $documentFileFileError = $_FILES['document_file']['error'];
        $documentFileTempName = $_FILES['document_file']['tmp_name'];
        $documentFileFileExtension = explode('.', $documentFileFileName);
        $documentFileActualFileExtension = strtolower(end($documentFileFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(6);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(6);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($documentFileActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($documentFileTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the document file.']);
            exit;
        }
        
        if($documentFileFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($documentFileFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The document file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $documentFileActualFileExtension;

        $directory = DEFAULT_DOCUMENT_RELATIVE_PATH_FILE . 'current_version/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_DOCUMENT_FULL_PATH_FILE . 'current_version/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        if(!move_uploaded_file($documentFileTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $documentID = $this->documentModel->insertDocument($documentName, $documentDescription, $contactID, $documentPassword, $filePath, $documentCategoryID, $documentFileActualFileExtension, $documentFileFileSize, $isConfidential, $userID);

        $documentVersionDirectory = DEFAULT_DOCUMENT_RELATIVE_PATH_FILE . 'document_version/';
        $documentVersionFileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_DOCUMENT_FULL_PATH_FILE . 'document_version/' . $fileNew;
        $documentVersionFilePath = $documentVersionDirectory . $fileNew;

        $documentVersionDirectoryChecker = $this->securityModel->directoryChecker('.' . $documentVersionDirectory);

        if (!$documentVersionDirectoryChecker) {
            echo json_encode(['success' => false, 'message' => $documentVersionDirectoryChecker]);
            exit;
        }

        if (!copy($fileDestination, $documentVersionFileDestination)) {
            echo json_encode(['success' => false, 'message' => 'There was an error copying your file to the document_version folder.']);
            exit;
        }

        $this->documentModel->insertDocumentVersionHistory($documentID, $documentVersionFilePath, 1, $contactID);

        echo json_encode(['success' => true, 'insertRecord' => true, 'documentID' => $this->securityModel->encryptData($documentID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addDepartmentRestriction
    # Description:
    # Add the document department restriction.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addDepartmentRestriction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');
        $departmentIDs = explode(',', $_POST['department_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($departmentIDs as $departmentID) {
            $checkDocumentDepartmentRestrictionExist = $this->documentModel->checkDocumentDepartmentRestrictionExist($documentID, $departmentID);
            $total = $checkDocumentDepartmentRestrictionExist['total'] ?? 0;
        
            if ($total === 0) {
                $this->documentModel->insertDocumentRestriction($documentID, $departmentID, null, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addEmployeeRestriction
    # Description:
    # Add the document employee restriction.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addEmployeeRestriction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');
        $employeeIDs = explode(',', $_POST['employee_id']);
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        foreach ($employeeIDs as $employeeID) {
            $checkDocumentEmployeeRestrictionExist = $this->documentModel->checkDocumentEmployeeRestrictionExist($documentID, $employeeID);
            $total = $checkDocumentEmployeeRestrictionExist['total'] ?? 0;
        
            if ($total === 0) {
                $this->documentModel->insertDocumentRestriction($documentID, null, $employeeID, $userID);
            }
        }
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDocument
    # Description: 
    # Updates the existing document if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');
        $documentName = htmlspecialchars($_POST['document_name'], ENT_QUOTES, 'UTF-8');
        $documentCategoryID = htmlspecialchars($_POST['document_category_id'], ENT_QUOTES, 'UTF-8');
        $isConfidential = htmlspecialchars($_POST['is_confidential'], ENT_QUOTES, 'UTF-8');
        $documentDescription = htmlspecialchars($_POST['document_description'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentExist = $this->documentModel->checkDocumentExist($documentID);
        $total = $checkDocumentExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->documentModel->updateDocument($documentID, $documentName, $documentDescription, $documentCategoryID, $isConfidential, $userID);
            
        echo json_encode(['success' => true, 'documentID' => $this->securityModel->encryptData($documentID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDocumentFile
    # Description: 
    # Handles the update of the document file.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateDocumentFile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkDocumentExist = $this->documentModel->checkDocumentExist($documentID);
        $total = $checkDocumentExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $documentFileFileName = $_FILES['document_file']['name'];
        $documentFileFileSize = $_FILES['document_file']['size'];
        $documentFileFileError = $_FILES['document_file']['error'];
        $documentFileTempName = $_FILES['document_file']['tmp_name'];
        $documentFileFileExtension = explode('.', $documentFileFileName);
        $documentFileActualFileExtension = strtolower(end($documentFileFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(6);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(6);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($documentFileActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($documentFileTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the document file.']);
            exit;
        }
        
        if($documentFileFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($documentFileFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The document file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $documentFileActualFileExtension;

        $directory = DEFAULT_DOCUMENT_RELATIVE_PATH_FILE . 'current_version/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_DOCUMENT_FULL_PATH_FILE . 'current_version/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        $documentDetails = $this->documentModel->getDocument($documentID);
        $documentVersion = $documentDetails['document_version'] + 1;
        $documentPath = !empty($documentDetails['document_path']) ? '.' . $documentDetails['document_path'] : null;

        if(file_exists($documentPath)){
            if (!unlink($documentPath)) {
                echo json_encode(['success' => false, 'message' => 'Document file cannot be deleted due to an error.']);
                exit;
            }
        }

        if(!move_uploaded_file($documentFileTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->documentModel->updateDocumentFile($documentID, $filePath, $documentVersion, $userID);

        $documentVersionDirectory = DEFAULT_DOCUMENT_RELATIVE_PATH_FILE . 'document_version/';
        $documentVersionFileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_DOCUMENT_FULL_PATH_FILE . 'document_version/' . $fileNew;
        $documentVersionFilePath = $documentVersionDirectory . $fileNew;

        $documentVersionDirectoryChecker = $this->securityModel->directoryChecker('.' . $documentVersionDirectory);

        if (!$documentVersionDirectoryChecker) {
            echo json_encode(['success' => false, 'message' => $documentVersionDirectoryChecker]);
            exit;
        }

        if (!copy($fileDestination, $documentVersionFileDestination)) {
            echo json_encode(['success' => false, 'message' => 'There was an error copying your file to the document_version folder.']);
            exit;
        }

        $this->documentModel->insertDocumentVersionHistory($documentID, $documentVersionFilePath, $documentVersion, $contactID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDocumentPassword
    # Description: 
    # Handles the update of the document password.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateDocumentPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');
        $newDocumentPassword = htmlspecialchars($_POST['new_document_password'], ENT_QUOTES, 'UTF-8');
        $encryptedPassword = $this->securityModel->encryptData($newDocumentPassword);
    
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkDocumentExist = $this->documentModel->checkDocumentExist($documentID);
        $total = $checkDocumentExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->documentModel->updateDocumentPassword($documentID, $encryptedPassword, $userID);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDocumentPasswordToNull
    # Description: 
    # Handles the update of the document password to null.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateDocumentPasswordToNull() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentExist = $this->documentModel->checkDocumentExist($documentID);
        $total = $checkDocumentExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $documentDetails = $this->documentModel->getDocument($documentID);
        $isConfidential = $documentDetails['is_confidential'];
        $documentPassword = $documentDetails['document_password'];
    
        if($isConfidential == 'Yes' && empty($documentPassword)){
            echo json_encode(['success' => false, 'isConfidential' =>  true]);
            exit;
        }

        $this->documentModel->updateDocumentPassword($documentID, null, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Preview methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: previewProtectedDocument
    # Description: 
    # Preview the existing document if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function previewProtectedDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');
        $enteredDocumentPassword = htmlspecialchars($_POST['document_password'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentExist = $this->documentModel->checkDocumentExist($documentID);
        $total = $checkDocumentExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $documentDetails = $this->documentModel->getDocument($documentID);
        $documentPassword =  $this->securityModel->decryptData($documentDetails['document_password']);
        $documentPath = $documentDetails['document_path'];
        $documentStatus = $documentDetails['document_status'];

        if($documentStatus != 'Published'){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        if($enteredDocumentPassword != $documentPassword){
            echo json_encode(['success' => false, 'incorrectPassword' =>  true]);
            exit;
        }
            
        echo json_encode(['success' => true, 'documentLink' => $documentPath]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: publishDocument
    # Description: 
    # Updates the existing document if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function publishDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentExist = $this->documentModel->checkDocumentExist($documentID);
        $total = $checkDocumentExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $documentDetails = $this->documentModel->getDocument($documentID);
        $isConfidential = $documentDetails['is_confidential'];
        $documentPassword = $documentDetails['document_password'];
        $documentStatus = $documentDetails['document_status'];
    
        if($isConfidential == 'Yes' && empty($documentPassword)){
            echo json_encode(['success' => false, 'isConfidential' =>  true]);
            exit;
        }

        if($documentStatus != 'Draft'){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->documentModel->updateDocumentStatus($documentID, 'Published', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Unpublish methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: unpublishDocument
    # Description: 
    # Updates the existing document if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function unpublishDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentExist = $this->documentModel->checkDocumentExist($documentID);
        $total = $checkDocumentExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $documentDetails = $this->documentModel->getDocument($documentID);
        $documentStatus = $documentDetails['document_status'];

        if($documentStatus != 'Published'){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->documentModel->updateDocumentStatus($documentID, 'Draft', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDocument
    # Description: 
    # Delete the document if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentExist = $this->documentModel->checkDocumentExist($documentID);
        $total = $checkDocumentExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $documentDetails = $this->documentModel->getDocument($documentID);
        $documentPath = !empty($documentDetails['document_path']) ? '.' . $documentDetails['document_path'] : null;

        if(file_exists($documentPath)){
            if (!unlink($documentPath)) {
                echo json_encode(['success' => false, 'message' => 'Document file cannot be deleted due to an error.']);
                exit;
            }
        }

        $documentVersionHistoryDetails = $this->documentModel->getDocumentVersionHistoryByDocumentID($documentID);

        foreach ($documentVersionHistoryDetails as $row) {
            $documentPath = !empty($row['document_path']) ? '.' . $row['document_path'] : null;

            if(file_exists($documentPath)){
                if (!unlink($documentPath)) {
                    echo json_encode(['success' => false, 'message' => 'Document file cannot be deleted due to an error.']);
                    exit;
                }
            }
        }
        
        $this->documentModel->deleteDocument($documentID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleDocument
    # Description: 
    # Delete the selected documents if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentIDs = $_POST['document_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($documentIDs as $documentID){
            $checkDocumentExist = $this->documentModel->checkDocumentExist($documentID);
            $total = $checkDocumentExist['total'] ?? 0;
    
            if($total > 0){
                $documentDetails = $this->documentModel->getDocument($documentID);
                $documentPath = !empty($documentDetails['document_path']) ? '.' . $documentDetails['document_path'] : null;

                if(file_exists($documentPath)){
                    if (!unlink($documentPath)) {
                        echo json_encode(['success' => false, 'message' => 'Document file cannot be deleted due to an error.']);
                        exit;
                    }
                }

                $documentVersionHistoryDetails = $this->documentModel->getDocumentVersionHistoryByDocumentID($documentID);

                foreach ($documentVersionHistoryDetails as $row) {
                    $documentPath = !empty($documentDetails['document_path']) ? '.' . $documentDetails['document_path'] : null;

                    if(file_exists($documentPath)){
                        if (!unlink($documentPath)) {
                            echo json_encode(['success' => false, 'message' => 'Document file cannot be deleted due to an error.']);
                            exit;
                        }
                    }
                }
                
                $this->documentModel->deleteDocument($documentID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteDocumentRestriction
    # Description: 
    # Delete the document restriction if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteDocumentRestriction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentRestrictionID = htmlspecialchars($_POST['document_restriction_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumenRestrictionExist = $this->documentModel->checkDocumenRestrictionExist($documentRestrictionID);
        $total = $checkDocumenRestrictionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->documentModel->deleteDocumentRestriction($documentRestrictionID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDocumentDetails
    # Description: 
    # Handles the retrieval of document details such as document name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getDocumentDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['document_id']) && !empty($_POST['document_id'])) {
            $userID = $_SESSION['user_id'];
            $documentID = $_POST['document_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $documentDetails = $this->documentModel->getDocument($documentID);
            $documentName = $documentDetails['document_name'];
            $documentDescription = $documentDetails['document_description'];
            $documentCategoryID = $documentDetails['document_category_id'];
            $isConfidential = $documentDetails['is_confidential'];

            $response = [
                'success' => true,
                'documentName' => $documentName,
                'documentDescription' => $documentDescription,
                'documentCategoryID' => $documentCategoryID,
                'isConfidential' => $isConfidential
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
require_once '../model/document-model.php';
require_once '../model/document-category-model.php';
require_once '../model/employee-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new DocumentController(new DatabaseModel(), new DocumentModel(new DatabaseModel), new DocumentCategoryModel(new DatabaseModel), new EmployeeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>