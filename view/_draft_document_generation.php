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
require_once '../model/department-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$documentModel = new DocumentModel($databaseModel);
$employeeModel = new EmployeeModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$documentCategoryModel = new DocumentCategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: draft document table
        # Description:
        # Generates the draft document table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'draft document table':
            if(isset($_POST['filter_upload_date_start_date']) && isset($_POST['filter_upload_date_end_date']) && isset($_POST['document_category_filter']) && isset($_POST['department_filter'])){
                $filterUploadDateStartDate = $systemModel->checkDate('empty', $_POST['filter_upload_date_start_date'], '', 'Y-m-d', '');
                $filterUploadDateEndDate = $systemModel->checkDate('empty', $_POST['filter_upload_date_end_date'], '', 'Y-m-d', '');
                $filterDocumentCategory = $_POST['document_category_filter'];
                $filterDepartment = $_POST['department_filter'];
                $contactID = $_SESSION['contact_id'];
                $employmentDetails = $employeeModel->getEmploymentInformation($contactID);
                $contactDepartment = $employmentDetails['department_id'];

                $viewOwnDraftDocument = $userModel->checkSystemActionAccessRights($user_id, 90);

                if($viewOwnDraftDocument['total'] > 0){
                    $sql = $databaseModel->getConnection()->prepare('CALL generateOwnDraftDocumentTable(:contactID, :contactDepartment, :filterUploadDateStartDate, :filterUploadDateEndDate, :filterDocumentCategory, :filterDepartment)');
                    $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
                    $sql->bindValue(':contactDepartment', $contactDepartment, PDO::PARAM_INT);
                }
                else{
                    $sql = $databaseModel->getConnection()->prepare('CALL generateAllDraftDocumentTable(:filterUploadDateStartDate, :filterUploadDateEndDate, :filterDocumentCategory, :filterDepartment)');
                }
                
                $sql->bindValue(':filterUploadDateStartDate', $filterUploadDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterUploadDateEndDate', $filterUploadDateEndDate, PDO::PARAM_STR);
                $sql->bindValue(':filterDocumentCategory', $filterDocumentCategory, PDO::PARAM_STR);
                $sql->bindValue(':filterDepartment', $filterDepartment, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $draftDocumentDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 57, 'delete');

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

                    $delete = '';
                    if($draftDocumentDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-document" data-document-id="'. $documentID .'" title="Delete Document">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $documentID .'">',
                        'DOCUMENT_NAME' => '<div class="row">
                                                        <div class="col-auto pe-0">
                                                            <img src="'. $documentIcon .'" alt="user-image" class="wid-30 hei-30">
                                                        </div>
                                                        <div class="col">
                                                            <h6 class="mb-0">'. $documentName .'</h6>
                                                            <p class="f-12 mb-0">'. $documentDescription .'</p>
                                                        </div>
                                                    </div>',
                        'DOCUMENT_CATEGORY' => $documentCategoryName,
                        'AUTHOR' => $authorName,
                        'UPLOAD_DATE' => $uploadDate,
                        'DOCUMENT_STATUS' => $documentStatusBadge,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="draft-document.php?id='. $documentIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: document department restriction table
        # Description:
        # Generates the document department restriction table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'document department restriction table':
            if(isset($_POST['document_id']) && !empty($_POST['document_id'])){
                $documentID = $_POST['document_id'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateDocumentDepartmentRestriction(:documentID)');
                $sql->bindValue(':documentID', $documentID, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $deleteDocumentDepartmentRestrictions = $userModel->checkSystemActionAccessRights($user_id, 94);

                foreach ($options as $row) {
                    $documentRestrictionID = $row['document_restriction_id'];
                    $departmentID = $row['department_id'];

                    $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;

                    $delete = '';
                    if($deleteDocumentDepartmentRestrictions['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-department-restrictions" data-document-restriction-id="'. $documentRestrictionID .'" title="Delete Document Department Restriction">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $response[] = [
                        'DEPARTMENT_NAME' => $departmentName,
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: document department restriction excemption table
        # Description:
        # Generates the document department restriction table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'document department restriction excemption table':
            if(isset($_POST['document_id']) && !empty($_POST['document_id'])){
                $documentID = $_POST['document_id'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateDocumentDepartmentRestrictionExcemption(:documentID)');
                $sql->bindValue(':documentID', $documentID, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $deleteDocumentDepartmentRestrictions = $userModel->checkSystemActionAccessRights($user_id, 94);

                foreach ($options as $row) {
                    $departmentID = $row['department_id'];
                    $departmentName = $row['department_name'];

                    $response[] = [
                        'DEPARTMENT_NAME' => $departmentName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input department-excemption" type="checkbox" value="'. $departmentID.'"></div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: document employee restriction table
        # Description:
        # Generates the document employee restriction table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'document employee restriction table':
            if(isset($_POST['document_id']) && !empty($_POST['document_id'])){
                $documentID = $_POST['document_id'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateDocumentEmployeeRestriction(:documentID)');
                $sql->bindValue(':documentID', $documentID, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $deleteDocumentDepartmentRestrictions = $userModel->checkSystemActionAccessRights($user_id, 94);

                foreach ($options as $row) {
                    $documentRestrictionID = $row['document_restriction_id'];
                    $contactID = $row['contact_id'];

                    $employeeDetails = $employeeModel->getPersonalInformation($contactID);
                    $employeeName = $employeeDetails['file_as'] ?? null;

                    $delete = '';
                    if($deleteDocumentDepartmentRestrictions['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-employee-restrictions" data-document-restriction-id="'. $documentRestrictionID .'" title="Delete Document Employee Restriction">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $response[] = [
                        'EMPLOYEE_NAME' => $employeeName,
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: document employee restriction excemption table
        # Description:
        # Generates the document employee restriction table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'document employee restriction excemption table':
            if(isset($_POST['document_id']) && !empty($_POST['document_id'])){
                $documentID = $_POST['document_id'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateDocumentEmployeeRestrictionExcemption(:documentID)');
                $sql->bindValue(':documentID', $documentID, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $deleteDocumentDepartmentRestrictions = $userModel->checkSystemActionAccessRights($user_id, 94);

                foreach ($options as $row) {
                    $contactID = $row['contact_id'];

                    $employeeDetails = $employeeModel->getPersonalInformation($contactID);
                    $employeeName = $employeeDetails['file_as'] ?? null;

                    $response[] = [
                        'EMPLOYEE_NAME' => $employeeName,
                        'ASSIGN' => '<div class="form-check form-switch mb-2"><input class="form-check-input employee-excemption" type="checkbox" value="'. $contactID.'"></div>'
                        ];
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

                    $documentStatus = ($documentVersion == $currentDocumentVersion) ? '<span class="badge bg-light-success">Current Version</span>' : '<span class="badge bg-light-warning">Old Version</span>';

                    $dropdown = '';
                    if($documentVersion != $currentDocumentVersion){
                        $dropdown = '<div class="dropdown">
                                        <a class="avtar avtar-s btn-link-primary dropdown-toggle arrow-none" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti ti-dots-vertical f-18"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="'. $documentPath .'" class="dropdown-item" target="_blank">Preview</a>
                                        </div>
                                    </div>';
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
    }
}

?>