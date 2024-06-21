<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/employee-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/leave-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$leaveTypeModel = new LeaveTypeModel($databaseModel);
$employeeModel = new EmployeeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: leave entitlement table
        # Description:
        # Generates the leave entitlement table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'leave entitlement table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateLeaveEntitlementTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $leaveEntitlementDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 90, 'delete');

            foreach ($options as $row) {
                $leaveEntitlementID = $row['leave_entitlement_id'];
                $contactID = $row['contact_id'];
                $leaveTypeID = $row['leave_type_id'];
                $entitlementAmount = $row['entitlement_amount'];
                $remainingEntitlement = $row['remaining_entitlement'];
                $leavePeriodStart = $systemModel->checkDate('empty', $row['leave_period_start'], '', 'm/d/Y', '');
                $leavePeriodEnd = $systemModel->checkDate('empty', $row['leave_period_end'], '', 'm/d/Y', '');

                $leaveEntitlementIDEncrypted = $securityModel->encryptData($leaveEntitlementID);

                $employeeDetails = $employeeModel->getPersonalInformation($contactID);
                $employeeName = $employeeDetails['file_as'] ?? null;

                $leaveTypeDetails = $leaveTypeModel->getLeaveType($leaveTypeID);
                $leaveTypeName = $leaveTypeDetails['leave_type_name'] ?? null;

                $delete = '';
                if($leaveEntitlementDeleteAccess['total'] > 0 && $entitlementAmount == $remainingEntitlement){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-leave-entitlement" data-leave-entitlement-id="'. $leaveEntitlementID .'" title="Delete Leave Entitlement">
                                    <i class="ti ti-trash"></i>
                                </button>'; 
                }

                $response[] = [
                    'EMPLOYEE' => $employeeName,
                    'LEAVE_TYPE' => $leaveTypeName,
                    'ENTITLEMENT' => $remainingEntitlement . ' / ' . $entitlementAmount,
                    'PERIOD_COVERED' => $leavePeriodStart . ' - ' . $leavePeriodEnd,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="leave-entitlement.php?id='. $leaveEntitlementIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>