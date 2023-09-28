<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/contact-information-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$contactInformationTypeModel = new ContactInformationTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: contact information type table
        # Description:
        # Generates the contact information type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact information type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateContactInformationTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $contactInformationTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 44, 'delete');

            foreach ($options as $row) {
                $contactInformationTypeID = $row['contact_information_type_id'];
                $contactInformationTypeName = $row['contact_information_type_name'];

                $contactInformationTypeIDEncrypted = $securityModel->encryptData($contactInformationTypeID);

                $delete = '';
                if($contactInformationTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-contact-information-type" data-contact-information-type-id="'. $contactInformationTypeID .'" title="Delete Contact Information Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $contactInformationTypeID .'">',
                    'CONTACT_INFORMATION_TYPE_NAME' => $contactInformationTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="contact-information-type.php?id='. $contactInformationTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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