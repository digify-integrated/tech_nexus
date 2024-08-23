<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/internal-dr-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$internalDRModel = new InternalDRModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: internal DR table
        # Description:
        # Generates the internal DR table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'internal DR table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateInternalDRTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $internalDRDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 78, 'delete');

            foreach ($options as $row) {
                $internalDRID = $row['internal_dr_id'];
                $releaseTo = $row['release_to'];
                $releaseAddress = $row['release_address'];
                $drType = $row['dr_type'];
                $drNumber = $row['dr_number'];
                $drStatus = $row['dr_status'];
                $stockNumber = $row['stock_number'];

                if($drStatus == 'Released'){
                    $releasedDate = $systemModel->checkDate('summary', $row['release_date'], '', 'm/d/Y', '');
                }
                else{
                    $releasedDate = '--';
                }

                $internalDRIDEncrypted = $securityModel->encryptData($internalDRID);

                $delete = '';
                if($internalDRDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-internal-dr" data-internal-dr-id="'. $internalDRID .'" title="Delete Zoom API">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $internalDRID .'">',
                    'RELEASE_TO' => ' <div class="col">
                                        <h6 class="mb-0">'. $releaseTo .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $releaseAddress .'</p>
                                        </div>',
                    'DR_TYPE' => $drType,
                    'DR_NUMBER' => $drNumber,
                    'STOCK_NUMBER' => $stockNumber,
                    'DR_STATUS' => $drStatus,
                    'RELEASED_DATE' => $releasedDate,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="internal-dr.php?id='. $internalDRIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>