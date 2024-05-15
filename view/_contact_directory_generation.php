<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/contact-directory-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$contactDirectoryModel = new ContactDirectoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: contact directory table
        # Description:
        # Generates the contact directory table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact directory table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateContactDirectoryTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $contactDirectoryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 87, 'delete');

            foreach ($options as $row) {
                $contactDirectoryID = $row['contact_directory_id'];
                $contactName = $row['contact_name'];
                $position = $row['position'];
                $location = $row['location'];
                $directoryType = $row['directory_type'];
                $contactInformation = $row['contact_information'];

                $contactDirectoryIDEncrypted = $securityModel->encryptData($contactDirectoryID);

                $delete = '';
                if($contactDirectoryDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-contact-directory" data-contact-directory-id="'. $contactDirectoryID .'" title="Delete Address Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $contactDirectoryID .'">',
                    'CONTACT_DIRECTORY' => '<div class="row">
                                                <div class="col-auto pe-0">
                                                </div>
                                                <div class="col">
                                                <h6 class="mb-0">'. $contactName .'</h6>
                                                <p class="f-12 mb-0">'. $position .'</p>
                                                </div>
                                            </div>',
                    'LOCATION' => $location,
                    'CONTACT_INFORMATION' => $contactInformation,
                    'DIRECTORY_TYPE' => $directoryType,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="contact-directory.php?id='. $contactDirectoryIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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