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
            if(isset($_POST['current_page']) && isset($_POST['document_search']) && isset($_POST['filter_upload_date_start_date']) && isset($_POST['filter_upload_date_end_date']) && isset($_POST['filter_publish_date_start_date']) && isset($_POST['filter_publish_date_end_date']) && isset($_POST['document_category_filter']) && isset($_POST['department_filter'])){
                $initialDocumentPerPage = 8;
                $loadMoreDocumentPerPage = 4;
                $documentPerPage = $initialDocumentPerPage;
                $contactID = $_SESSION['contact_id'];
                $employmentDetails = $employeeModel->getEmploymentInformation($contactID);
                $contactDepartment = $employmentDetails['department_id'] ?? null;
                
                $currentPage = htmlspecialchars($_POST['current_page'], ENT_QUOTES, 'UTF-8');
                $documentSearch = htmlspecialchars($_POST['document_search'], ENT_QUOTES, 'UTF-8');
                $filterUploadDateStartDate = $systemModel->checkDate('empty', $_POST['filter_upload_date_start_date'], '', 'Y-m-d', '', '', '');
                $filterUploadDateEndDate = $systemModel->checkDate('empty', $_POST['filter_upload_date_end_date'], '', 'Y-m-d', '', '', '');
                $filterPublishDateStartDate = $systemModel->checkDate('empty', $_POST['filter_publish_date_start_date'], '', 'Y-m-d', '', '', '');
                $filterPublishDateEndDate = $systemModel->checkDate('empty', $_POST['filter_publish_date_end_date'], '', 'Y-m-d', '', '', '');
                $documentCategoryFilter = htmlspecialchars($_POST['document_category_filter'], ENT_QUOTES, 'UTF-8');
                $departmentFilter = htmlspecialchars($_POST['department_filter'], ENT_QUOTES, 'UTF-8');
                $offset = ($currentPage - 1) * $documentPerPage;

                $sql = $databaseModel->getConnection()->prepare('CALL generateDocumentCard(:offset, :documentPerPage, :documentSearch, :contactID, :contactDepartment, :filterUploadDateStartDate, :filterUploadDateEndDate, :filterPublishDateStartDate, :filterPublishDateEndDate, :documentCategoryFilter, :departmentFilter)');
                $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
                $sql->bindValue(':documentPerPage', $documentPerPage, PDO::PARAM_INT);
                $sql->bindValue(':documentSearch', $documentSearch, PDO::PARAM_STR);
                $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
                $sql->bindValue(':contactDepartment', $contactDepartment, PDO::PARAM_INT);
                $sql->bindValue(':filterUploadDateStartDate', $filterUploadDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterUploadDateEndDate', $filterUploadDateEndDate, PDO::PARAM_STR);
                $sql->bindValue(':filterPublishDateStartDate', $filterPublishDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterPublishDateEndDate', $filterPublishDateEndDate, PDO::PARAM_STR);
                $sql->bindValue(':documentCategoryFilter', $documentCategoryFilter, PDO::PARAM_STR);
                $sql->bindValue(':departmentFilter', $departmentFilter, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                foreach ($options as $row) {
                    $documentID = $row['document_id'];
                    $documentName = $row['document_name'];
                    $documentCategoryID = $row['document_category_id'];
                    $documentExtension = $row['document_extension'];
                    $publishDate = $systemModel->checkDate('empty', $row['publish_date'], '', 'F j, Y h:i:s A', '');
                    $documentIcon = $systemModel->getFileExtensionIcon($documentExtension);

                    $documentCategoryName = $documentCategoryModel->getDocumentCategory($documentCategoryID)['document_category_name'] ?? null;
                   
                    $documentIDEncrypted = $securityModel->encryptData($documentID);
    
                    $response[] = [
                        'documentCard' => '<div class="col-lg-3">
                                            <a href="document.php?id='. $documentIDEncrypted .'">
                                                <div class="card file-card">
                                                    <div class="card-body">
                                                        <div class="my-3 text-center">
                                                            <img src="'. $documentIcon .'" alt="img" class="img-fluid" />
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between mt-4">
                                                            <div class="w-100 text-wrap">
                                                                <h6 class="mb-0"><span class="text-primary">'. $documentName .'</span></h6>
                                                                <p class="mb-0 text-muted"><small>'. $documentCategoryName .'</small></p>
                                                                <p class="mb-0 text-muted"><small>'. $publishDate .'</small></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
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
                    $documentID = $row['document_id'];
                    $documentPath = $row['document_path'];
                    $documentVersion = $row['document_version'];
                    $uploadedBy = $row['uploaded_by'];
                    $uploadDate = $systemModel->checkDate('empty', $row['upload_date'], '', 'F j, Y h:i:s A', '');

                    $uploadedByDetails = $employeeModel->getPersonalInformation($uploadedBy);
                    $uploadedByName = $uploadedByDetails['file_as'] ?? null;

                    $documentDetails = $documentModel->getDocument($documentID);
                    $currentDocumentVersion = $documentDetails['document_version'];
                    $documentPassword = $documentDetails['document_password'];
                    $isConfidential = $documentDetails['is_confidential'];

                    $documentStatus = ($documentVersion == $currentDocumentVersion) ? '<span class="badge bg-light-success">Current Version</span>' : '<span class="badge bg-light-warning">Old Version</span>';

                    $dropdown = '';
                    if($documentVersion != $currentDocumentVersion){
                        if($isConfidential == 'No' || empty($documentPassword)){
                                $dropdown = '<div class="dropdown">
                                <a class="avtar avtar-s btn-link-primary dropdown-toggle arrow-none" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical f-18"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="'. $documentPath .'" class="dropdown-item" target="_blank">Preview</a>
                                </div>
                            </div>';
                        }
                    }

                    $listMargin = ($index === 0) ? 'pt-0' : '';

                    $details .= ' <li class="list-group-item '. $listMargin .'">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-2">
                                            <p class="mb-2 text-primary"><b>Version: '. $documentVersion .'</b></p>
                                            <p class="mb-2">Upload Date: ' . $uploadDate . '</p>
                                            <p class="mb-3">Uploaded By: ' . $uploadedByName . '</p>
                                            '. $documentStatus .'
                                        </div>
                                        <div class="flex-shrink-0">
                                            '. $dropdown .'
                                        </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No version history found.';
                }

                $response[] = [
                    'documentVersionHistorySummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: dashboard document table
        # Description:
        # Generates the dashboard document table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'dashboard document table':
            $contactID = $_SESSION['contact_id'];
                $employmentDetails = $employeeModel->getEmploymentInformation($contactID);
                $contactDepartment = $employmentDetails['department_id'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateDashboardDocumentTable(:contactID, :contactDepartment)');
                $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
                $sql->bindValue(':contactDepartment', $contactDepartment, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $documentID = $row['document_id'];
                    $documentName = $row['document_name'];
                    $documentDescription = $row['document_description'];
                    $author = $row['author'];
                    $documentCategoryID = $row['document_category_id'];
                    $documentStatus = $row['document_status'];
                    $documentExtension = $row['document_extension'];
                    $uploadDate = $systemModel->checkDate('empty', $row['upload_date'], '', 'm/d/Y g:i:s a', '');
                    $documentIcon = $systemModel->getFileExtensionIcon($documentExtension);

                    $documentStatusBadge = $documentModel->getDocumentStatus($documentStatus);

                    $documentCategoryName = $documentCategoryModel->getDocumentCategory($documentCategoryID)['document_category_name'] ?? null;

                    $authorDetails = $employeeModel->getPersonalInformation($author);
                    $authorName = $authorDetails['file_as'] ?? null;

                    $documentIDEncrypted = $securityModel->encryptData($documentID);
                    $response[] = [
                        'DOCUMENT_NAME' => '<a href="document.php?id='. $documentIDEncrypted .'" ><div class="row">
                                                        
                                                            <div class="col-auto pe-0">
                                                                <img src="'. $documentIcon .'" alt="user-image" class="wid-30 hei-30">
                                                            </div>
                                                            <div class="col">
                                                                <h6 class="mb-0">'. $documentName .'</h6>
                                                                <p class="f-12 mb-0">'. $documentDescription .'</p>
                                                            </div>
                                                    </div>
                                                    </a>',
                        'DOCUMENT_CATEGORY' => $documentCategoryName,
                        'AUTHOR' => $authorName,
                        'UPLOAD_DATE' => $uploadDate,
                        'DOCUMENT_STATUS' => $documentStatusBadge
                    ];
                }

                echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>