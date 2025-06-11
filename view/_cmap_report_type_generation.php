<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/cmap-report-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$cmapReportTypeModel = new CMAPReportTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: cmap report type table
        # Description:
        # Generates the cmap report type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'cmap report type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateCMAPReportTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $cmapReportTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 148, 'delete');

            foreach ($options as $row) {
                $cmapReportTypeID = $row['cmap_report_type_id'];
                $cmapReportTypeName = $row['cmap_report_type_name'];

                $cmapReportTypeIDEncrypted = $securityModel->encryptData($cmapReportTypeID);

                $delete = '';
                if($cmapReportTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-cmap-report-type" data-cmap-report-type-id="'. $cmapReportTypeID .'" title="Delete CMAP Report Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $cmapReportTypeID .'">',
                    'CMAP_REPORT_TYPE_NAME' => $cmapReportTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="cmap-report-type.php?id='. $cmapReportTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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