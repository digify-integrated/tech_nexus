<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: search result table
        # Description:
        # Generates the search result table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'search result table':
            if(isset($_POST['first_name']) && isset($_POST['middle_name']) && isset($_POST['last_name'])){
                $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
                $middleName = htmlspecialchars($_POST['middle_name'], ENT_QUOTES, 'UTF-8');
                $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateCustomerSearchResultTable(:firstName, :middleName, :lastName)');
                $sql->bindValue(':firstName', $firstName, PDO::PARAM_STR);
                $sql->bindValue(':middleName', $middleName, PDO::PARAM_STR);
                $sql->bindValue(':lastName', $lastName, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
    
                foreach ($options as $row) {
                    $contactID = $row['contact_id'];
                    $fileAs = $row['file_as'];
    
                    $contactIDEncrypted = $securityModel->encryptData($contactID);
    
                    $response[] = [
                        'FILE_AS' => $fileAs,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="customer.php?search&id='. $contactIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
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