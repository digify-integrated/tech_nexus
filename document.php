<?php
    require('config/_required_php_file.php');
    require('config/_check_user_active.php');
    require('model/document-model.php');
    require('model/document-category-model.php');
    require('model/document-authorizer-model.php');

    $documentModel = new DocumentModel($databaseModel);
    $documentCategoryModel = new DocumentCategoryModel($databaseModel);
    $documentAuthorizerModel = new DocumentAuthorizerModel($databaseModel);

    $pageTitle = 'Document';
    
    $documentReadAccess = $userModel->checkMenuItemAccessRights($user_id, 56, 'read');
    $updateDocumentFile = $userModel->checkSystemActionAccessRights($user_id, 89);
    $unpublishDocument = $userModel->checkSystemActionAccessRights($user_id, 96);
    $fullAccessToDocuments = $userModel->checkSystemActionAccessRights($user_id, 97);

    if ($documentReadAccess['total'] == 0) {
        header('location: 404.php');
        exit;
    }

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
            header('location: document.php');
            exit;
        }

        $documentID = $securityModel->decryptData($_GET['id']);

        $checkDocumentExist = $documentModel->checkDocumentExist($documentID);
        $total = $checkDocumentExist['total'] ?? 0;

        $documentDetails = $documentModel->getDocument($documentID);
        $documentName = $documentDetails['document_name'];
        $documentDescription = $documentDetails['document_description'] ?? '--';
        $author = $documentDetails['author'];
        $documentPath = $documentDetails['document_path'];
        $documentCategoryID = $documentDetails['document_category_id'];
        $documentExtension = $documentDetails['document_extension'];
        $documentSize = $documentDetails['document_size'];
        $documentVersion = $documentDetails['document_version'];
        $isConfidential = $documentDetails['is_confidential'];
        $documentPassword = $documentDetails['document_password'];
        $uploadDate = $systemModel->checkDate('summary', $documentDetails['upload_date'], '', 'F j, Y h:i:s A', '');
        $publishDate = $systemModel->checkDate('summary', $documentDetails['publish_date'], '', 'F j, Y h:i:s A', '');
        $documentStatus = $documentDetails['document_status'];

        $confidentialBadge = $documentModel->getDocumentConfidentailStatus($isConfidential);

        $authorDetails = $employeeModel->getPersonalInformation($author);
        $authorName = $authorDetails['file_as'] ?? null;
        $auhtorDetails = $employeeModel->getEmploymentInformation($author);
        $authorDepartment = $auhtorDetails['department_id'];

        $documentIcon = $systemModel->getFileExtensionIcon($documentExtension);
        $documentCategoryName = $documentCategoryModel->getDocumentCategory($documentCategoryID)['document_category_name'] ?? null;

        if($total == 0 || $documentStatus != 'Published'){
            header('location: 404.php');
            exit;
        }
    }
    else{
        $documentID = null;
    }

    $newRecord = isset($_GET['new']);

    require('config/_interface_settings.php');
    require('config/_user_account_details.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('config/_title.php'); ?>
    <link rel="stylesheet" href="./assets/css/plugins/select2.min.css">
    <link rel="stylesheet" href="./assets/css/plugins/datepicker-bs5.min.css">
    <?php include_once('config/_required_css.php'); ?>
    <link rel="stylesheet" href="./assets/css/plugins/dataTables.bootstrap5.min.css">
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="false" data-pc-theme="<?php echo $darkLayout; ?>">
    <?php 
        include_once('config/_preloader.html'); 
        include_once('config/_navbar.php'); 
        include_once('config/_header.php');
        include_once('config/_announcement.php'); 
    ?>   

    <section class="pc-container">
      <div class="pc-content">
        <div class="page-header">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item">Document Management</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="document.php"><?php echo $pageTitle; ?></a></li>
                    <?php
                        if(!$newRecord && !empty($documentID)){
                            echo '<li class="breadcrumb-item" id="document-id">'. $documentID .'</li>';
                        }

                        if($newRecord){
                            echo '<li class="breadcrumb-item">New</li>';
                        }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0 text-primary"><?php echo $pageTitle; ?></h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
            if($newRecord && $documentCreateAccess['total'] > 0){
                require_once('view/_document_new.php');
            }
            else if(!empty($documentID)){
                require_once('view/_document_details.php');
            }
            else{
                require_once('view/_document.php');
            }
        ?>
      </div>
    </section>
    
    <?php 
        include_once('config/_footer.php'); 
        include_once('config/_change_password_modal.php');
        include_once('config/_error_modal.php');
        include_once('config/_required_js.php'); 
        include_once('config/_customizer.php'); 
    ?>
    <script src="./assets/js/plugins/bootstrap-maxlength.min.js"></script>
    <script src="./assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="./assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="./assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="./assets/js/plugins/datepicker-full.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/document.js?v=<?php echo rand(); ?>"></script>
</body>

</html>