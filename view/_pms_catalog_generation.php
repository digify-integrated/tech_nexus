<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/pms-catalog-model.php';
require_once '../model/company-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$pmsCatalogModel = new PMSCatalogModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: pms catalog table
        # Description:
        # Generates the pms catalog table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'pms catalog table':
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM pms_catalog ORDER BY pms_catalog_name ASC');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $pmsCatalogDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 202, 'delete');

            foreach ($options as $row) {
                $pmsCatalogID = $row['pms_catalog_id'];
                $pmsCatalogName = $row['pms_catalog_name'];
                $pmsCatalogDescription = $row['description'];
                $company_id = $row['company_id'];

                $companyDetails = $companyModel->getCompany($company_id);
                $company_name = $companyDetails['company_name'] ?? null;

                $pmsCatalogIDEncrypted = $securityModel->encryptData($pmsCatalogID);

                $delete = '';
                if($pmsCatalogDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-pms-catalog" data-pms-catalog-id="'. $pmsCatalogID .'" title="Delete PMS Catalog">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $pmsCatalogID .'">',
                    'PMS_CATALOG' => ' <div class="col">
                                        <h6 class="mb-0">'. $pmsCatalogName .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $pmsCatalogDescription .'</p>
                                        </div>',
                    'COMPANY' => $company_name,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="pms-catalog.php?id='. $pmsCatalogIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: pms catalog file extension table
        # Description:
        # Generates the file extension table on pms catalog page.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'pms catalog file extension table':
            if(isset($_POST['pms_catalog_id']) && !empty($_POST['pms_catalog_id'])){
                $pmsCatalogID = htmlspecialchars($_POST['pms_catalog_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generatePMSCatalogFileExensionTable(:pmsCatalogID)');
                $sql->bindValue(':pmsCatalogID', $pmsCatalogID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $deleteFileExtensionToPMSCatalog = $userModel->checkSystemActionAccessRights($user_id, 16);

                foreach ($options as $row) {
                    $fileExtensionID = $row['file_extension_id'];
                    $fileExtensionName = $row['file_extension_name'];
    
                    $fileExtensionIDEncrypted = $securityModel->encryptData($fileExtensionID);

                    $delete = '';
                    if($deleteFileExtensionToPMSCatalog['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-file-extension" data-pms-catalog-id="'. $pmsCatalogID .'" data-file-extension-id="'. $fileExtensionID .'" title="Delete File Extension">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'FILE_EXTENSION_NAME' => '<a href="file-extension.php?id='. $fileExtensionIDEncrypted .'">'. $fileExtensionName . '</a>',
                        'ACTION' => '<div class="d-flex gap-2">
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