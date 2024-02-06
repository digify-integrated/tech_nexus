<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/document-category-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$documentCategoryModel = new DocumentCategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: document category table
        # Description:
        # Generates the document category table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'document category table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateDocumentCategoryTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $documentCategoryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 43, 'delete');

            foreach ($options as $row) {
                $documentCategoryID = $row['document_category_id'];
                $documentCategoryName = $row['document_category_name'];

                $documentCategoryIDEncrypted = $securityModel->encryptData($documentCategoryID);

                $delete = '';
                if($documentCategoryDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-document-category" data-document-category-id="'. $documentCategoryID .'" title="Delete Document Category">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $documentCategoryID .'">',
                    'DOCUMENT_CATEGORY_NAME' => $documentCategoryName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="document-category.php?id='. $documentCategoryIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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