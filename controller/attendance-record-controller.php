<?php
session_start();

# -------------------------------------------------------------
#
# Function: AttendanceRecordController
# Description: 
# The AttendanceRecordController class handles attendance record related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class AttendanceRecordController {
    private $databaseModel;
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
    # These instances are used for attendance record related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param DatabaseModel $databaseModel     The DatabaseModel instance for database operations.
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
    public function __construct(DatabaseModel $databaseModel, EmployeeModel $employeeModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->databaseModel = $databaseModel;
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
                case 'save attendance record':
                    $this->saveAttendanceRecord();
                    break;
                case 'save attendance record import':
                    $this->saveImportAttendanceRecord();
                    break;
                case 'save imported attendance record':
                    $this->saveArrangedImportAttendanceRecord();
                    break;
                case 'get attendance record details':
                    $this->getAttendanceRecordDetails();
                    break;
                case 'delete attendance record':
                    $this->deleteAttendanceRecord();
                    break;
                case 'delete multiple attendance record':
                    $this->deleteMultipleAttendanceRecord();
                    break;
                case 'duplicate attendance record':
                    $this->duplicateAttendanceRecord();
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
    # Function: saveAttendanceRecord
    # Description: 
    # Updates the existing attendance record if it exists; otherwise, inserts a new attendance record.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveAttendanceRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceID = isset($_POST['attendance_id']) ? htmlspecialchars($_POST['attendance_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $checkInDate = $_POST['check_in_date'];
        $checkInTime = $_POST['check_in_time'];
        $checkInNotes = htmlspecialchars($_POST['check_in_notes'], ENT_QUOTES, 'UTF-8');
        $checkOutDate = $_POST['check_out_date'];
        $checkOutTime = $_POST['check_out_time'];
        $checkOutNotes = htmlspecialchars($_POST['check_out_notes'], ENT_QUOTES, 'UTF-8');
        $checkIn = $this->systemModel->checkDate('empty',  $checkInDate . ' ' . $checkInTime, '', 'Y-m-d H:i:s', '');

        if(!empty($checkOutDate) && !empty($checkOutTime)){
            $checkOut = $this->systemModel->checkDate('empty', $checkOutDate . ' ' . $checkOutTime, '', 'Y-m-d H:i:s', '');
        }
        else{
            $checkOut = null;
        }
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkAttendanceConflict = $this->employeeModel->checkAttendanceConflict($attendanceID, $employeeID, $checkIn, $checkOut);
        $total = $checkAttendanceConflict['total'] ?? 0;

        if ($total > 0) {
            echo json_encode(['success' => false, 'message' => 'Attendance conflict detected. Please check the date and time.']);
            exit;
        } 
        
        if(!empty($checkIn) && !empty($checkOut)){
            if (strtotime($checkOut) < strtotime($checkIn)) {
                echo json_encode(['success' => false, 'message' => '"Check Out" time cannot be earlier than "Check In" time.']);
                exit;
            }
        }
            
        $checkAttendanceExist = $this->employeeModel->checkAttendanceExist($attendanceID);
        $total = $checkAttendanceExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateManualAttendanceEntry($attendanceID, $employeeID, $checkIn, $checkInNotes, $userID, $checkOut, $checkOutNotes, $userID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'attendanceID' => $this->securityModel->encryptData($attendanceID)]);
            exit;
        } 
        else {
            $attendanceID = $this->employeeModel->insertManualAttendanceEntry($employeeID, $checkIn, $checkInNotes, $userID, $checkOut, $checkOutNotes, $userID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'attendanceID' => $this->securityModel->encryptData($attendanceID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveImportAttendanceRecord
    # Description: 
    # Save the imported attendance record for loading.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveImportAttendanceRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $transaction = htmlspecialchars($_POST['transaction'], ENT_QUOTES, 'UTF-8');
        $importType = htmlspecialchars($_POST['import_type'], ENT_QUOTES, 'UTF-8');
    
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

        $this->employeeModel->deleteBiometricsAttendanceRecord();

        if($importType == 'Biometrics'){
            $importData = array_map('str_getcsv', file($importFileTempName));

            array_shift($importData);

            foreach ($importData as $row) {
                $biometricsID = $row[0];
                $attendanceDate = $this->systemModel->checkDate('empty', $row[1], '', 'Y-m-d H:i:s', '');

                if(!empty($biometricsID) && !empty($companyID) && !empty($attendanceDate)){
                    $this->employeeModel->insertBiometricsAttendanceRecord($biometricsID, $companyID, $attendanceDate);
                }
            }

            $sql = $this->databaseModel->getConnection()->prepare('CALL getBiometricsAttendanceRecord()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $biometricsID = $row['biometrics_id'];
                $companyID = $row['company_id'];
                $attendance_record_date = $this->systemModel->checkDate('empty', $row['attendance_record_date'], '', 'Y-m-d H:i:s', '');

                $employmentInformationDetails = $this->employeeModel->getEmploymentInformationByBiometricsID($biometricsID, $companyID);
                $contactID = $employmentInformationDetails['contact_id'] ?? null;

                if(!empty($contactID)){
                    $checkBiometricsAttendanceRecordExist = $this->employeeModel->checkBiometricsAttendanceRecordExist($contactID, $attendance_record_date);
                    $total = $checkBiometricsAttendanceRecordExist['total'] ?? 0;
        
                    if ($total == 0) {
                        $checkArrangedBiometricsAttendanceRecordExist = $this->employeeModel->checkArrangedBiometricsAttendanceRecordExist($contactID, $attendance_record_date);
                        $total = $checkArrangedBiometricsAttendanceRecordExist['total'] ?? 0;
            
                        if ($total > 0) {
                            $this->employeeModel->updateArrangedBiometricsAttendanceRecord($contactID, $attendance_record_date, $contactID);
                        }
                        else{
                            $this->employeeModel->insertArrangedBiometricsAttendanceRecord($contactID, $attendance_record_date, $contactID);
                        }
                    }
                }
            }
        }
        else{
            $importData = array_map('str_getcsv', file($importFileTempName));

            array_shift($importData);

            foreach ($importData as $row) {
                $contactID = $row[0];
                $checkIn = $this->systemModel->checkDate('empty', $row[1], '', 'Y-m-d H:i:s', '');
                $checkInLocation = htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8');
                $checkInNotes = htmlspecialchars($row[3], ENT_QUOTES, 'UTF-8');
                $checkOut = $this->systemModel->checkDate('empty', $row[4], '', 'Y-m-d H:i:s', '');
                $checkOutLocation = htmlspecialchars($row[5], ENT_QUOTES, 'UTF-8');
                $checkOutNotes = htmlspecialchars($row[6], ENT_QUOTES, 'UTF-8');

                if(!empty($contactID) && !empty($checkIn)){
                    $this->employeeModel->insertRegularImportedAttendanceEntry($contactID, $checkIn, $checkInLocation, $contactID, $importType, $checkInNotes, $checkOut, $checkOutLocation, $contactID, $importType, $checkOutNotes);
                }
            }
        }

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveArrangedImportAttendanceRecord
    # Description: 
    # Delete the selected attendance records if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveArrangedImportAttendanceRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceIDs = $_POST['attendance_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($attendanceIDs as $attendanceID){
            $importedAttendanceRecordDetails = $this->employeeModel->getArrangedImportedAttendanceRecord($attendanceID);
            $contactID = $importedAttendanceRecordDetails['contact_id'];
            $checkIn = $this->systemModel->checkDate('empty',  $importedAttendanceRecordDetails['check_in'], '', 'Y-m-d H:i:s', '');
            $checkInLocation = htmlspecialchars($importedAttendanceRecordDetails['check_in_location'], ENT_QUOTES, 'UTF-8');
            $checkInBy = htmlspecialchars($importedAttendanceRecordDetails['check_in_by'], ENT_QUOTES, 'UTF-8');
            $checkInMode = htmlspecialchars($importedAttendanceRecordDetails['check_in_mode'], ENT_QUOTES, 'UTF-8');
            $checkInNotes = htmlspecialchars($importedAttendanceRecordDetails['check_in_notes'], ENT_QUOTES, 'UTF-8');
            $checkOut = $this->systemModel->checkDate('empty',  $importedAttendanceRecordDetails['check_out'], '', 'Y-m-d H:i:s', '');
            $checkOutLocation = htmlspecialchars($importedAttendanceRecordDetails['check_out_location'], ENT_QUOTES, 'UTF-8');
            $checkOutBy = htmlspecialchars($importedAttendanceRecordDetails['check_out_by'], ENT_QUOTES, 'UTF-8');
            $checkOutMode = htmlspecialchars($importedAttendanceRecordDetails['check_out_mode'], ENT_QUOTES, 'UTF-8');
            $checkOutNotes = htmlspecialchars($importedAttendanceRecordDetails['check_out_notes'], ENT_QUOTES, 'UTF-8');

            $checkAttendanceConflict = $this->employeeModel->checkAttendanceConflict(null, $contactID, $checkIn, $checkOut);
            $total = $checkAttendanceConflict['total'] ?? 0;
    
            if ($total == 0) {
                $this->employeeModel->insertImportedAttendanceEntry($contactID, $checkIn, $checkInLocation, $checkInBy, $checkInMode, $checkInNotes, $checkOut, $checkOutLocation, $checkOutBy, $checkOutMode, $checkOutNotes, $userID);
            }
        }
        
        $this->employeeModel->deleteBiometricsAttendanceRecord();

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteAttendanceRecord
    # Description: 
    # Delete the attendance record if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteAttendanceRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceID = htmlspecialchars($_POST['attendance_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAttendanceExist = $this->employeeModel->checkAttendanceExist($attendanceID);
        $total = $checkAttendanceExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteAttendance($attendanceID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleAttendanceRecord
    # Description: 
    # Delete the selected attendance records if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleAttendanceRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $attendanceIDs = $_POST['attendance_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($attendanceIDs as $attendanceID){
            $this->employeeModel->deleteAttendance($attendanceID);
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
    # Function: getAttendanceRecordDetails
    # Description: 
    # Handles the retrieval of attendance record details such as attendance record name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getAttendanceRecordDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['attendance_id']) && !empty($_POST['attendance_id'])) {
            $userID = $_SESSION['user_id'];
            $attendanceID = $_POST['attendance_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $attendanceRecordDetails = $this->employeeModel->getAttendance($attendanceID);
            $contactID = $attendanceRecordDetails['contact_id'];
            $checkInImage = $this->systemModel->checkImage($attendanceRecordDetails['check_in_image'], 'default');
            $checkInLocation = $attendanceRecordDetails['check_in_location'];
            $checkOutImage = $this->systemModel->checkImage($attendanceRecordDetails['check_out_image'], 'default');
            $checkOutLocation = $attendanceRecordDetails['check_out_location'];

            if(!empty($checkOutLocation)){
                $checkInMap = '<div class="mapouter"><div class="gmap_canvas"><iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&amp;height=200&amp;hl=en&amp;q='. $checkInLocation .'&amp;t=&amp;z=18&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe></div><style>.mapouter{position:relative;text-align:right;width:100%;height:200px;}.gmap_canvas {overflow:hidden;background:none!important;width:100%;height:200px;}.gmap_iframe {height:200px!important;}</style></div>';
            }
            else{
                $checkInMap = '<div class="alert alert-info mb-4" role="alert">No check in location found.</div>';
            }

            if(!empty($checkOutLocation)){
                $checkOutMap = '<div class="mapouter"><div class="gmap_canvas"><iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&amp;height=200&amp;hl=en&amp;q='. $checkOutLocation .'&amp;t=&amp;z=18&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe></div><style>.mapouter{position:relative;text-align:right;width:100%;height:200px;}.gmap_canvas {overflow:hidden;background:none!important;width:100%;height:200px;}.gmap_iframe {height:200px!important;}</style></div>';
            }
            else{
                $checkOutMap = '<div class="alert alert-info mb-4" role="alert">No check out location found.</div>';
            }

            $employeeDetails = $this->employeeModel->getPersonalInformation($contactID);
            $fileAs = $employeeDetails['file_as'];

            $response = [
                'success' => true,
                'contactID' => $contactID,
                'employeeName' => $fileAs,
                'checkInDate' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_in'], '', 'm/d/Y', ''),
                'checkInTime' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_in'], '', 'H:i', ''),
                'checkInTimeLabel' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_in'], '', 'h:i A', ''),
                'checkInNotes' => $attendanceRecordDetails['check_in_notes'],
                'checkOutDate' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_out'], '', 'm/d/Y', ''),
                'checkOutTime' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_out'], '', 'H:i', ''),
                'checkOutTimeLabel' => $this->systemModel->checkDate('empty', $attendanceRecordDetails['check_out'], '', 'h:i A', ''),
                'checkOutNotes' => $attendanceRecordDetails['check_out_notes'],
                'checkInMap' => $checkInMap,
                'checkOutMap' => $checkOutMap,
                'checkInImage' => $checkInImage,
                'checkOutImage' => $checkOutImage
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
require_once '../model/employee-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new AttendanceRecordController(new DatabaseModel(), new EmployeeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>