<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/document-model.php';
require_once '../model/document-category-model.php';
require_once '../model/employee-model.php';
require_once '../model/company-model.php';
require_once '../model/department-model.php';
require_once '../model/system-setting-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$documentModel = new DocumentModel($databaseModel);
$documentCategoryModel = new DocumentCategoryModel($databaseModel);
$employeeModel = new EmployeeModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: document card
        # Description:
        # Generates the document card.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'document card':
            if(isset($_POST['current_page']) && isset($_POST['document_search']) && isset($_POST['filter_publish_date_start_date']) && isset($_POST['filter_publish_date_end_date']) && isset($_POST['document_category_filter']) && isset($_POST['department_filter'])){
                $initialDocumentPerPage = 9;
                $loadMoreDocumentPerPage = 6;
                $documentPerPage = $initialDocumentPerPage;
                $contactID = $_SESSION['contact_id'];
                
                $currentPage = htmlspecialchars($_POST['current_page'], ENT_QUOTES, 'UTF-8');
                $documentSearch = htmlspecialchars($_POST['document_search'], ENT_QUOTES, 'UTF-8');
                $filterPublishDateStartDate = $systemModel->checkDate('empty', $_POST['filter_publish_date_start_date'], '', 'Y-m-d', '', '', '');
                $filterPublishDateEndDate = $systemModel->checkDate('empty', $_POST['filter_publish_date_end_date'], '', 'Y-m-d', '', '', '');
                $documentCategoryFilter = htmlspecialchars($_POST['document_category_filter'], ENT_QUOTES, 'UTF-8');
                $departmentFilter = htmlspecialchars($_POST['department_filter'], ENT_QUOTES, 'UTF-8');
                $offset = ($currentPage - 1) * $documentPerPage;

                $sql = $databaseModel->getConnection()->prepare('CALL generateDocumentCard(:offset, :documentPerPage, :documentSearch, :filterPublishDateStartDate, :filterPublishDateEndDate, :documentCategoryFilter, :departmentFilter)');
                $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
                $sql->bindValue(':documentPerPage', $documentPerPage, PDO::PARAM_INT);
                $sql->bindValue(':documentSearch', $documentSearch, PDO::PARAM_STR);
                $sql->bindValue(':filterPublishDateStartDate', $filterPublishDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterPublishDateEndDate', $filterPublishDateEndDate, PDO::PARAM_STR);
                $sql->bindValue(':documentCategoryFilter', $documentCategoryFilter, PDO::PARAM_STR);
                $sql->bindValue(':departmentFilter', $departmentFilter, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $documentDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 56, 'delete');

                foreach ($options as $row) {
                    $documentID = $row['document_id'];
                    $documentName = $row['document_name'];
                    $documentCategoryID = $row['document_category_id'];
                    $documentExtension = $row['document_extension'];
                    $documentIcon = $systemModel->getFileExtensionIcon($documentExtension);

                    $documentCategoryName = $documentCategoryModel->getDocumentCategory($documentCategoryID)['document_category_name'] ?? null;
                   
                    $documentIDEncrypted = $securityModel->encryptData($documentID);

                    $delete = '';
                    if($documentDeleteAccess['total'] > 0){
                        $delete = '<div class="btn-prod-cart card-body position-absolute end-0 bottom-0">
                                        <button class="avtar avtar-s btn btn-danger delete-document" data-document-id="'. $documentID .'" title="Delete Employee">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>';
                    }
    
                    $response[] = [
                        'documentCard' => '<div class="col-md-6 col-lg-4 col-xxl-3">
                                            <div class="card file-card">
                                                <div class="card-body">
                                                    <div class="my-3 text-center">
                                                        <a href="document.php?id='. $documentIDEncrypted .'">
                                                            <img src="'. $documentIcon .'" alt="img" class="img-fluid" />
                                                        </a>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between mt-4">
                                                        <div class="w-75 text-truncate">
                                                            <a href="document.php?id='. $documentIDEncrypted .'">
                                                                <h6 class="mb-0"><span class="text-primary">'. $documentName .'</span></h6>
                                                                <p class="mb-0 text-muted"><small>'. $documentCategoryName .'</small></p>
                                                            </a>
                                                        </div>
                                                        '. $delete .'
                                                    </div>
                                                </div>
                                            </div>
                                        </div>'
                    ];
                }

                if ($documentPerPage === $initialDocumentPerPage) {
                    $documentPerPage = $loadMoreDocumentPerPage;
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: document version history summary
        # Description:
        # Generates the document version history summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'document version history summary':
            if(isset($_POST['document_id']) && !empty($_POST['document_id'])){
                $details = '';
                $documentID = htmlspecialchars($_POST['document_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateDocumentVersionHistorySummary(:documentID)');
                $sql->bindValue(':documentID', $documentID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                foreach ($options as $index => $row) {
                    $documentPath = $row['document_path'];
                    $documentVersion = $row['document_version'];
                    $uploadedBy = $row['uploaded_by'];
                    $cityID = $row['city_id'];
                    $isPrimary = $row['is_primary'];

                    $isPrimaryBadge = $isPrimary ? '<span class="badge bg-light-success">Primary</span>' : '<span class="badge bg-light-info">Alternate</span>';

                    $dropdown = '';
                    if ($employeeWriteAccess['total'] > 0) {
                        $update = ($employeeWriteAccess['total'] > 0 && $updateEmployeeAddress['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item update-contact-address" data-bs-toggle="offcanvas" data-bs-target="#contact-address-offcanvas" aria-controls="contact-address-offcanvas" data-contact-address-id="'. $contactAddressID . '">Edit</a>' : '';
                    
                        $tag = ($employeeWriteAccess['total'] > 0 && $tagEmployeeAddress['total'] > 0 && !$isPrimary) ? '<a href="javascript:void(0);" class="dropdown-item tag-contact-address-as-primary" data-contact-address-id="'. $contactAddressID . '">Tag As Primary</a>' : '';
                    
                        $delete = ($employeeWriteAccess['total'] > 0 && $tagEmployeeAddress['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item delete-contact-address" data-contact-address-id="'. $contactAddressID . '">Delete</a>' : '';
                    
                        $dropdown = ($update || $tag || $delete) ? '<div class="dropdown">
                            <a class="avtar avtar-s btn-link-primary dropdown-toggle arrow-none" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                ' . $update . '
                                ' . $tag . '
                                ' . $delete . '
                            </div>
                        </div>' : '';
                    }

                    if ($index === 0) {
                        $listMargin = 'pt-0';
                    }
                    else {
                        $listMargin = '';
                    }

                    $details .= ' <li class="list-group-item '. $listMargin .'">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-2">
                                            <p class="mb-2 text-primary"><b>'. $addressTypeName .'</b></p>
                                            <p class="mb-2">' . $contactAddress . '</p>
                                            '. $isPrimaryBadge .'
                                        </div>
                                        <div class="flex-shrink-0">
                                            '. $dropdown .'
                                        </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No address found.';
                }

                $response[] = [
                    'contactAddressSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>