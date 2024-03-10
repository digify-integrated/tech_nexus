<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/employee-model.php';
require_once '../model/department-model.php';
require_once '../model/transmittal-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$transmittalModel = new TransmittalModel($databaseModel);
$employeeModel = new EmployeeModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: transmittal table
        # Description:
        # Generates the transmittal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'transmittal table':
            if(isset($_POST['filter_transmittal_date_start_date']) && isset($_POST['filter_transmittal_date_end_date']) && isset($_POST['transmittal_status_filter'])){
                $filterTransmmittalDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transmittal_date_start_date'], '', 'Y-m-d', '');
                $filterTransmmittalDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transmittal_date_end_date'], '', 'Y-m-d', '');
                $filterTransmittalStatus = $_POST['transmittal_status_filter'];
                $contactID = $_SESSION['contact_id'];
                $employmentDetails = $employeeModel->getEmploymentInformation($contactID);
                $contactDepartment = $employmentDetails['department_id'] ?? '';

                $viewOwnTransmittal = $userModel->checkSystemActionAccessRights($user_id, 88);

                $sql = $databaseModel->getConnection()->prepare('CALL generateAllTransmittalTable(:filterTransmmittalDateStartDate, :filterTransmmittalDateEndDate, :filterTransmittalStatus)');
                $sql->bindValue(':filterTransmmittalDateStartDate', $filterTransmmittalDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterTransmmittalDateEndDate', $filterTransmmittalDateEndDate, PDO::PARAM_STR);
                $sql->bindValue(':filterTransmittalStatus', $filterTransmittalStatus, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $transmittalDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 53, 'delete');

                foreach ($options as $row) {
                    $transmittalID = $row['transmittal_id'];
                    $transmittalDescription = $row['transmittal_description'];
                    $transmitterID = $row['transmitter_id'];
                    $transmitterDepartment = $row['transmitter_department'];
                    $receiverID = $row['receiver_id'];
                    $receiverDepartment = $row['receiver_department'];
                    $transmittalStatus = $row['transmittal_status'];
                    $transmittalDate = $systemModel->checkDate('empty', $row['transmittal_date'], '', 'm/d/Y g:i:s a', '');

                    $transmittalStatusBadge = $transmittalModel->getTransmittalStatus($transmittalStatus);

                    $employeeDetails = $employeeModel->getPersonalInformation($transmitterID);
                    $transmitterName = $employeeDetails['file_as'] ?? null;

                    $employeeDetails = $employeeModel->getPersonalInformation($receiverID);
                    $receiverName = $employeeDetails['file_as'] ?? 'Anyone';

                    $transmitterDepartmentName = $departmentModel->getDepartment($transmitterDepartment)['department_name'] ?? null;
                    $receiverDepartmentName = $departmentModel->getDepartment($receiverDepartment)['department_name'] ?? null;

                    $transmittalIDEncrypted = $securityModel->encryptData($transmittalID);

                    $delete = '';
                    if($transmittalDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-transmittal" data-transmittal-id="'. $transmittalID .'" title="Delete Transmittal">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    if(($viewOwnTransmittal['total'] > 0 && ($createdBy == $contactID || ($transmitterID == $contactID && $transmitterDepartment == $contactDepartment) || ($receiverDepartment == $contactDepartment && !empty($receiverID) && !empty($receiverDepartment) && $receiverID == $contactID) || (empty($receiverID) && $receiverDepartment == $contactDepartment))) || $viewOwnTransmittal['total'] == 0){
                        $response[] = [
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $transmittalID .'">',
                            'TRANSMITTAL_DESCRIPTION' => $transmittalDescription,
                            'TRANSMITTED_FROM' => '<div class="col">
                                                        <h6 class="mb-0">'. $transmitterName .'</h6>
                                                        <p class="f-12 mb-0">'. $transmitterDepartmentName .'</p>
                                                    </div>',
                            'TRANSMITTED_TO' => '<div class="col">
                                                    <h6 class="mb-0">'. $receiverName .'</h6>
                                                    <p class="f-12 mb-0">'. $receiverDepartmentName .'</p>
                                                </div>',
                            'TRANSMITTAL_DATE' => $transmittalDate,
                            'STATUS' => $transmittalStatusBadge,
                            'ACTION' => '<div class="d-flex gap-2">
                                            <a href="transmittal.php?id='. $transmittalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            '. $delete .'
                                        </div>'
                            ];
                    }

                    
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: transmittal summary table
        # Description:
        # Generates the transmittal summary table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'transmittal summary table':
            if(isset($_POST['filter_transmittal_date_start_date']) && isset($_POST['filter_transmittal_date_end_date']) && isset($_POST['transmittal_status_filter'])){
                $filterTransmmittalDateStartDate = $systemModel->checkDate('empty', $_POST['filter_transmittal_date_start_date'], '', 'Y-m-d', '');
                $filterTransmmittalDateEndDate = $systemModel->checkDate('empty', $_POST['filter_transmittal_date_end_date'], '', 'Y-m-d', '');
                $filterTransmittalStatus = $_POST['transmittal_status_filter'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateAllTransmittalTable(:filterTransmmittalDateStartDate, :filterTransmmittalDateEndDate, :filterTransmittalStatus)');
                $sql->bindValue(':filterTransmmittalDateStartDate', $filterTransmmittalDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterTransmmittalDateEndDate', $filterTransmmittalDateEndDate, PDO::PARAM_STR);
                $sql->bindValue(':filterTransmittalStatus', $filterTransmittalStatus, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $transmittalID = $row['transmittal_id'];
                    $transmittalDescription = $row['transmittal_description'];
                    $transmitterID = $row['transmitter_id'];
                    $transmitterDepartment = $row['transmitter_department'];
                    $receiverID = $row['receiver_id'];
                    $receiverDepartment = $row['receiver_department'];
                    $transmittalStatus = $row['transmittal_status'];
                    $transmittalDate = $systemModel->checkDate('empty', $row['transmittal_date'], '', 'm/d/Y g:i:s a', '');

                    $transmittalStatusBadge = $transmittalModel->getTransmittalStatus($transmittalStatus);

                    $employeeDetails = $employeeModel->getPersonalInformation($transmitterID);
                    $transmitterName = $employeeDetails['file_as'];

                    $employeeDetails = $employeeModel->getPersonalInformation($receiverID);
                    $receiverName = $employeeDetails['file_as'] ?? 'Anyone';

                    $transmitterDepartmentName = $departmentModel->getDepartment($transmitterDepartment)['department_name'] ?? null;
                    $receiverDepartmentName = $departmentModel->getDepartment($receiverDepartment)['department_name'] ?? null;

                    $transmittalIDEncrypted = $securityModel->encryptData($transmittalID);

                    if($transmittalStatus == 'Transmitted' || $transmittalStatus == 'Re-Transmitted'){
                        $timeElapsed = $systemModel->timeElapsedString($transmittalDate);
                    }
                    else{
                        $timeElapsed = '--';
                    }

                    $response[] = [
                        'TRANSMITTAL_DESCRIPTION' => $transmittalDescription,
                        'TRANSMITTED_FROM' => '<div class="col">
                                                    <h6 class="mb-0">'. $transmitterName .'</h6>
                                                    <p class="f-12 mb-0">'. $transmitterDepartmentName .'</p>
                                                </div>',
                        'TRANSMITTED_TO' => '<div class="col">
                                                <h6 class="mb-0">'. $receiverName .'</h6>
                                                <p class="f-12 mb-0">'. $receiverDepartmentName .'</p>
                                            </div>',
                        'TRANSMITTAL_DATE' => $transmittalDate,
                        'TIME_ELAPSED' => $timeElapsed,
                        'STATUS' => $transmittalStatusBadge
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>