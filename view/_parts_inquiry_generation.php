<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-inquiry-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$partsInquiryModel = new PartsInquiryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: parts inquiry table
        # Description:
        # Generates the parts inquiry table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'parts inquiry table':
            $sql = $databaseModel->getConnection()->prepare('CALL generatePartsInquiryTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $partsInquiryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 94, 'delete');

            foreach ($options as $row) {
                $partsInquiryID = $row['parts_inquiry_id'];
                $partsNumber = $row['parts_number'];
                $partsDescription = $row['parts_description'];
                $stock = $row['stock'];
                $price = $row['price'];
                $uploadDate = $systemModel->checkDate('empty', $row['upload_date'], '', 'm/d/Y g:i:s a', '');

                $partsInquiryIDEncrypted = $securityModel->encryptData($partsInquiryID);

                $delete = '';
                if($partsInquiryDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-parts-inquiry" data-parts-inquiry-id="'. $partsInquiryID .'" title="Delete Parts Inquiry">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $partsInquiryID .'">',
                    'PARTS_NUMBER' => ' <div class="col">
                                        <h6 class="mb-0">'. strtoupper($partsNumber) .'</h6>
                                        <p class="text-muted f-12 mb-0">'. strtoupper($partsDescription) .'</p>
                                        </div>',
                    'STOCK' => $stock,
                    'PRICE' => number_format($price, 2),
                    'UPLOAD_DATE' => $uploadDate,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="parts-inquiry.php?id='. $partsInquiryIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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