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

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: incident reports table
        # Description:
        # Generates the incident reports table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'incident reports table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateIncidentReport()'); 
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $fileAs = $row['file_as'];
                $documentName = $row['document_name'];
                $documentFile = $row['document_file'];
                $uploadDate = date('m/d/Y h:i:s a', strtotime($row['upload_date']));

                $response[] = [
                    'EMPLOYEE' => $fileAs,
                    'DOCUMENT' => '<a href="'. $documentFile .'" target="_blank">'. $documentName .'</a>',
                    'UPLOAD_DATE' => $uploadDate,
                ];
            }

            

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>