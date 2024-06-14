<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/leave-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$leaveTypeModel = new LeaveTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: leave type table
        # Description:
        # Generates the leave type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'leave type table':
            if(isset($_POST['filter_is_paid'])){
                $filterIsPaid = htmlspecialchars($_POST['filter_is_paid'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateLeaveTypeTable(:filterIsPaid)');
                $sql->bindValue(':filterIsPaid', $filterIsPaid, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $leaveTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 89, 'delete');

                foreach ($options as $row) {
                    $leaveTypeID = $row['leave_type_id'];
                    $leaveTypeName = $row['leave_type_name'];
                    $isPaid = $row['is_paid'];

                    if($isPaid == 'Yes'){
                        $isPaidBadge = '<span class="badge bg-light-success">Yes</span>';
                    }
                    else{
                        $isPaidBadge = '<span class="badge bg-light-warning">No</span>';
                    }

                    $leaveTypeIDEncrypted = $securityModel->encryptData($leaveTypeID);

                    $delete = '';
                    if($leaveTypeDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-leave-type" data-leave-type-id="'. $leaveTypeID .'" title="Delete Job Position">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $leaveTypeID .'">',
                        'LEAVE_TYPE' => $leaveTypeName,
                        'IS_PAID' => $isPaidBadge,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="leave-type.php?id='. $leaveTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        '. $delete .'
                                    </div>'
                    ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>