<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/holiday-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$holidayTypeModel = new HolidayTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: holiday type table
        # Description:
        # Generates the holiday type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'holiday type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateHolidayTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $holidayTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 37, 'delete');

            foreach ($options as $row) {
                $holidayTypeID = $row['holiday_type_id'];
                $holidayTypeName = $row['holiday_type_name'];

                $holidayTypeIDEncrypted = $securityModel->encryptData($holidayTypeID);

                $delete = '';
                if($holidayTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-holiday-type" data-holiday-type-id="'. $holidayTypeID .'" title="Delete Holiday Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $holidayTypeID .'">',
                    'HOLIDAY_TYPE_NAME' => $holidayTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="holiday-type.php?id='. $holidayTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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