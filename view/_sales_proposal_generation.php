<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/product-model.php';
require_once '../model/contractor-model.php';
require_once '../model/work-center-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$contractorModel = new ContractorModel($databaseModel);
$workCenterModel = new WorkCenterModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: sales proposal table
        # Description:
        # Generates the sales proposal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal table':
            if(isset($_POST['customer_id']) && !empty($_POST['customer_id']) && isset($_POST['sales_proposal_status_filter'])){
                $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
                $salesProposalStatusFilter = htmlspecialchars($_POST['sales_proposal_status_filter'], ENT_QUOTES, 'UTF-8');

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $deleteSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 119);
                $viewOwnSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 131);

                if($viewOwnSalesProposal['total'] > 0){
                    $contactID = $_SESSION['contact_id'];

                    $sql = $databaseModel->getConnection()->prepare('CALL generateOwnCustomerSalesProposalTable(:customerID, :contactID, :salesProposalStatusFilter)');
                    $sql->bindValue(':contactID', $contactID, PDO::PARAM_STR);
                }
                else{
                    $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalTable(:customerID, :salesProposalStatusFilter)');
                }

                
                $sql->bindValue(':customerID', $customerID, PDO::PARAM_INT);
                $sql->bindValue(':salesProposalStatusFilter', $salesProposalStatusFilter, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalID = $row['sales_proposal_id'];
                    $salesProposalNumber = $row['sales_proposal_number'];
                    $productType = $row['product_type'];
                    $productID = $row['product_id'];
                    $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);
                    $createdDate = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y h:i:s A', '');

                    $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                    $productDetails = $productModel->getProduct($productID);
                    $productName = $productDetails['description'] ?? null;
                    $stockNumber = $productDetails['stock_number'] ?? null;

                    $delete = '';
                    if($deleteSalesProposal['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-sales-proposal" data-sales-proposal-id="'. $salesProposalID .'" title="Delete Sales Proposal">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                        'SALES_PROPOSAL_NUMBER' => '<a href="sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                        '. $salesProposalNumber .'
                                                    </a>',
                        'CREATED_DATE' => $createdDate,
                        'CUSTOMER' => '<div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                    <p class="f-12 mb-0">'. $corporateName .'</p>
                                                </div>',
                        'PRODUCT_TYPE' => $productType,
                        'PRODUCT' => $stockNumber,
                        'STATUS' => $salesProposalStatus,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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

        # ------------------------------------------------------------
        #
        # Type: all sales proposal table
        # Description:
        # Generates the sales proposal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'all sales proposal table':
                $filter_sale_proposal_status = $_POST['filter_sale_proposal_status'];
                $filter_product_type = $_POST['filter_product_type'];
                $filter_company = $_POST['filter_company'];
                $filter_user = $_POST['filter_user'];
                $filter_created_date_start_date = $systemModel->checkDate('empty', $_POST['filter_created_date_start_date'], '', 'Y-m-d', '');
                $filter_created_date_end_date = $systemModel->checkDate('empty', $_POST['filter_created_date_end_date'], '', 'Y-m-d', '');
                $filter_released_date_start_date = $systemModel->checkDate('empty', $_POST['filter_released_date_start_date'], '', 'Y-m-d', '');
                $filter_released_date_end_date = $systemModel->checkDate('empty', $_POST['filter_released_date_end_date'], '', 'Y-m-d', '');

                if (!empty($filter_sale_proposal_status)) {
                    $values_array = array_filter(array_map('trim', explode(',', $filter_sale_proposal_status)));

                    $quoted_values_array = array_map(function($value) {
                        return "'" . addslashes($value) . "'";
                    }, $values_array);

                    $filter_sale_proposal_status = implode(', ', $quoted_values_array);
                }
                else {
                    $filter_sale_proposal_status = null;
                }

                if (!empty($filter_product_type)) {
                    $values_array = array_filter(array_map('trim', explode(',', $filter_product_type)));

                    $quoted_values_array = array_map(function($value) {
                        return "'" . addslashes($value) . "'";
                    }, $values_array);

                    $filter_product_type = implode(', ', $quoted_values_array);
                }
                else {
                    $filter_product_type = null;
                }

                if (!empty($filter_company)) {
                    $values_array = array_filter(array_map('trim', explode(',', $filter_company)));

                    $quoted_values_array = array_map(function($value) {
                        return "'" . addslashes($value) . "'";
                    }, $values_array);

                    $filter_company = implode(', ', $quoted_values_array);
                }
                else {
                    $filter_company = null;
                }

                if (!empty($filter_user)) {
                    $values_array = array_filter(array_map('trim', explode(',', $filter_user)));

                    $quoted_values_array = array_map(function($value) {
                        return "'" . addslashes($value) . "'";
                    }, $values_array);

                    $filter_user = implode(', ', $quoted_values_array);
                }
                else {
                    $filter_user = null;
                }

                $deleteSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 119);
                $viewOwnSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 131);

                if($viewOwnSalesProposal['total'] > 0){
                    $userID = $_SESSION['user_id'];
                    $contactID = $_SESSION['contact_id'];

                    $sql = $databaseModel->getConnection()->prepare('CALL generateOwnSalesProposalTable(:contactID, :userID, :filter_sale_proposal_status, :filter_product_type, :filter_company, :filter_user, :filter_created_date_start_date, :filter_created_date_end_date, :filter_released_date_start_date, :filter_released_date_end_date)');
                    $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
                    $sql->bindValue(':userID', $userID, PDO::PARAM_INT);
                }
                else{
                    $sql = $databaseModel->getConnection()->prepare('CALL generateAllSalesProposalTable(:filter_sale_proposal_status, :filter_product_type, :filter_company, :filter_user, :filter_created_date_start_date, :filter_created_date_end_date, :filter_released_date_start_date, :filter_released_date_end_date)');
                }

              
                $sql->bindValue(':filter_sale_proposal_status', $filter_sale_proposal_status, PDO::PARAM_STR);
                $sql->bindValue(':filter_product_type', $filter_product_type, PDO::PARAM_STR);
                $sql->bindValue(':filter_company',  $filter_company, PDO::PARAM_STR);
                $sql->bindValue(':filter_user',  $filter_user, PDO::PARAM_STR);
                $sql->bindValue(':filter_created_date_start_date',  $filter_created_date_start_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_created_date_end_date',  $filter_created_date_end_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_released_date_start_date',  $filter_released_date_start_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_released_date_end_date',  $filter_released_date_end_date, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalID = $row['sales_proposal_id'];
                    $customerID = $row['customer_id'];
                    $salesProposalNumber = $row['sales_proposal_number'];
                    $productType = $row['product_type'];
                    $productID = $row['product_id'];
                    $createdDate = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y h:i:s A', '');
                    $releasedDate = $systemModel->checkDate('summary', $row['released_date'], '', 'm/d/Y h:i:s A', '');
                    $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                    $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                    $productDetails = $productModel->getProduct($productID);
                    $productName = $productDetails['description'] ?? null;
                    $stockNumber = $productDetails['stock_number'] ?? null;

                    $customerDetails = $customerModel->getPersonalInformation($customerID);
                    $customerName = $customerDetails['file_as'] ?? null;
                    $corporateName = $customerDetails['corporate_name'] ?? null;

                    $delete = '';
                    if($deleteSalesProposal['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-sales-proposal" data-sales-proposal-id="'. $salesProposalID .'" title="Delete Sales Proposal">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                        'SALES_PROPOSAL_NUMBER' => '<a href="all-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                        '. $salesProposalNumber .'
                                                    </a>',
                        'CUSTOMER' => '<div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                    <p class="f-12 mb-0">'. $corporateName .'</p>
                                                </div>',
                        'PRODUCT_TYPE' => $productType,
                        'PRODUCT' => $stockNumber,
                        'CREATED_DATE' => $createdDate,
                        'RELEASED_DATE' => $releasedDate,
                        'STATUS' => $salesProposalStatus,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="all-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        '. $delete .'
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        case 'all sales proposal table2':
                $filter_sale_proposal_status = $_POST['filter_sale_proposal_status'];
                $filter_product_type = $_POST['filter_product_type'];
                $filter_company = $_POST['filter_company'];
                $filter_user = $_POST['filter_user'];
                $filter_created_date_start_date = $systemModel->checkDate('empty', $_POST['filter_created_date_start_date'], '', 'Y-m-d', '');
                $filter_created_date_end_date = $systemModel->checkDate('empty', $_POST['filter_created_date_end_date'], '', 'Y-m-d', '');
                $filter_released_date_start_date = $systemModel->checkDate('empty', $_POST['filter_released_date_start_date'], '', 'Y-m-d', '');
                $filter_released_date_end_date = $systemModel->checkDate('empty', $_POST['filter_released_date_end_date'], '', 'Y-m-d', '');

                if (!empty($filter_sale_proposal_status)) {
                    $values_array = array_filter(array_map('trim', explode(',', $filter_sale_proposal_status)));

                    $quoted_values_array = array_map(function($value) {
                        return "'" . addslashes($value) . "'";
                    }, $values_array);

                    $filter_sale_proposal_status = implode(', ', $quoted_values_array);
                }
                else {
                    $filter_sale_proposal_status = null;
                }

                if (!empty($filter_product_type)) {
                    $values_array = array_filter(array_map('trim', explode(',', $filter_product_type)));

                    $quoted_values_array = array_map(function($value) {
                        return "'" . addslashes($value) . "'";
                    }, $values_array);

                    $filter_product_type = implode(', ', $quoted_values_array);
                }
                else {
                    $filter_product_type = null;
                }

                if (!empty($filter_company)) {
                    $values_array = array_filter(array_map('trim', explode(',', $filter_company)));

                    $quoted_values_array = array_map(function($value) {
                        return "'" . addslashes($value) . "'";
                    }, $values_array);

                    $filter_company = implode(', ', $quoted_values_array);
                }
                else {
                    $filter_company = null;
                }

                if (!empty($filter_user)) {
                    $values_array = array_filter(array_map('trim', explode(',', $filter_user)));

                    $quoted_values_array = array_map(function($value) {
                        return "'" . addslashes($value) . "'";
                    }, $values_array);

                    $filter_user = implode(', ', $quoted_values_array);
                }
                else {
                    $filter_user = null;
                }

                $deleteSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 119);
                $viewOwnSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 131);

                if($viewOwnSalesProposal['total'] > 0){
                    $userID = $_SESSION['user_id'];
                    $contactID = $_SESSION['contact_id'];

                    $sql = $databaseModel->getConnection()->prepare('CALL generateOwnSalesProposalTable(:contactID, :userID, :filter_sale_proposal_status, :filter_product_type, :filter_company, :filter_user, :filter_created_date_start_date, :filter_created_date_end_date, :filter_released_date_start_date, :filter_released_date_end_date)');
                    $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
                    $sql->bindValue(':userID', $userID, PDO::PARAM_INT);
                }
                else{
                    $sql = $databaseModel->getConnection()->prepare('CALL generateAllSalesProposalTable(:filter_sale_proposal_status, :filter_product_type, :filter_company, :filter_user, :filter_created_date_start_date, :filter_created_date_end_date, :filter_released_date_start_date, :filter_released_date_end_date)');
                }

              
                $sql->bindValue(':filter_sale_proposal_status', $filter_sale_proposal_status, PDO::PARAM_STR);
                $sql->bindValue(':filter_product_type', $filter_product_type, PDO::PARAM_STR);
                $sql->bindValue(':filter_company',  $filter_company, PDO::PARAM_STR);
                $sql->bindValue(':filter_user',  $filter_user, PDO::PARAM_STR);
                $sql->bindValue(':filter_created_date_start_date',  $filter_created_date_start_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_created_date_end_date',  $filter_created_date_end_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_released_date_start_date',  $filter_released_date_start_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_released_date_end_date',  $filter_released_date_end_date, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalID = $row['sales_proposal_id'];
                    $customerID = $row['customer_id'];
                    $salesProposalNumber = $row['sales_proposal_number'];
                    $productType = $row['product_type'];
                    $productID = $row['product_id'];
                    $createdDate = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y h:i:s A', '');
                    $releasedDate = $systemModel->checkDate('summary', $row['released_date'], '', 'm/d/Y h:i:s A', '');
                    $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                    $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                    $productDetails = $productModel->getProduct($productID);
                    $productName = $productDetails['description'] ?? null;
                    $stockNumber = $productDetails['stock_number'] ?? null;

                    $customerDetails = $customerModel->getPersonalInformation($customerID);
                    $customerName = $customerDetails['file_as'] ?? null;
                    $corporateName = $customerDetails['corporate_name'] ?? null;

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                        'SALES_PROPOSAL_NUMBER' => '<a href="all-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                        '. $salesProposalNumber .'
                                                    </a>',
                        'CUSTOMER' => '<div class="col">
                                                    <h6 class="mb-0">'. $customerName .'</h6>
                                                    <p class="f-12 mb-0">'. $corporateName .'</p>
                                                </div>',
                        'PRODUCT_TYPE' => $productType,
                        'PRODUCT' => $stockNumber,
                        'RELEASED_DATE' => $releasedDate,
                        'CREATED_DATE' => $createdDate,
                        'STATUS' => $salesProposalStatus,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="all-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" target="_blank" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </div>'
                        ];
                }

                echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        case 'dashboard for initial approval table':
            $contactID = $_SESSION['contact_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generateDashboardForInitialApproval(:contactID)');
            $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                $createdDate = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y h:i:s A', '');
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;
                $response[] = [
                    'SALES_PROPOSAL_NUMBER' => '<a href="all-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => '<div class="col">
                                                <h6 class="mb-0">'. $customerName .'</h6>
                                                <p class="f-12 mb-0">'. $corporateName .'</p>
                                            </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'CREATED_DATE' => $createdDate,
                    'STATUS' => $salesProposalStatus
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        case 'dashboard for final approval table':
            $contactID = $_SESSION['contact_id'];

            $sql = $databaseModel->getConnection()->prepare('CALL generateDashboardForFinalApproval(:contactID)');
            $sql->bindValue(':contactID', $contactID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                $createdDate = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y h:i:s A', '');
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $response[] = [
                    'SALES_PROPOSAL_NUMBER' => '<a href="all-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => '<div class="col">
                                                <h6 class="mb-0">'. $customerName .'</h6>
                                                <p class="f-12 mb-0">'. $corporateName .'</p>
                                            </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'CREATED_DATE' => $createdDate,
                    'STATUS' => $salesProposalStatus
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal change request table
        # Description:
        # Generates the sales proposal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal change request table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalChangeRequestTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $proceedDate = $systemModel->checkDate('summary', $row['approval_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                    'SALES_PROPOSAL_NUMBER' => '<a href="sales-proposal-change-request.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                                                'CUSTOMER' => '<div class="col">
                                                <h6 class="mb-0">'. $customerName .'</h6>
                                                <p class="f-12 mb-0">'. $corporateName .'</p>
                                            </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'PROCEED_DATE' => $proceedDate,
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="sales-proposal-change-request.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal for ci table
        # Description:
        # Generates the sales proposal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal for ci table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalForCITable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;
                $forCIDate = $systemModel->checkDate('summary', $row['for_ci_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                    'SALES_PROPOSAL_NUMBER' => '<a href="sales-proposal-for-ci.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => '<div class="col">
                                        <h6 class="mb-0">'. $customerName .'</h6>
                                        <p class="f-12 mb-0">'. $corporateName .'</p>
                                    </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'FOR_CI_DATE' => $forCIDate,
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="sales-proposal-for-ci.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: installment sales approval table
        # Description:
        # Generates the installment sales approval table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'installment sales approval table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateInstallmentSalesApprovalTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;
                $forCIDate = $systemModel->checkDate('summary', $row['for_ci_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                    'SALES_PROPOSAL_NUMBER' => '<a href="installment-sales-approval.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => '<div class="col">
                                        <h6 class="mb-0">'. $customerName .'</h6>
                                        <p class="f-12 mb-0">'. $corporateName .'</p>
                                    </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'FOR_CI_DATE' => $forCIDate,
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="installment-sales-approval.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal for bank financing table
        # Description:
        # Generates the sales proposal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal for bank financing table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalForBankFinancingTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;
                $forCIDate = $systemModel->checkDate('summary', $row['for_ci_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                    'SALES_PROPOSAL_NUMBER' => '<a href="sales-proposal-bank-financing.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => '<div class="col">
                                        <h6 class="mb-0">'. $customerName .'</h6>
                                        <p class="f-12 mb-0">'. $corporateName .'</p>
                                    </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'FOR_CI_DATE' => $forCIDate,
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="sales-proposal-bank-financing.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal for dr table
        # Description:
        # Generates the sales proposal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal for dr table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalForDRTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;
                $forDRDate = $systemModel->checkDate('summary', $row['for_dr_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'SALES_PROPOSAL_NUMBER' => $salesProposalNumber,
                    'CUSTOMER' => '<div class="col">
                                        <h6 class="mb-0">'. $customerName .'</h6>
                                        <p class="f-12 mb-0">'. $corporateName .'</p>
                                    </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'FOR_DR_DATE' => $forDRDate,
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="sales-proposal-for-dr.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # ------------------------------------------------------------
        #
        # Type: sales proposal released table
        # Description:
        # Generates the sales proposal released table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal released table':
            if(isset($_POST['filter_release_date_start_date']) && isset($_POST['filter_release_date_end_date'])){
                $filterReleasedDateStartDate = $systemModel->checkDate('empty', $_POST['filter_release_date_start_date'], '', 'Y-m-d', '', '', '');
                $filterReleasedDateEndDate = $systemModel->checkDate('empty', $_POST['filter_release_date_end_date'], '', 'Y-m-d', '', '', '');

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalReleasedTable(:filterReleasedDateStartDate, :filterReleasedDateEndDate)');
                $sql->bindValue(':filterReleasedDateStartDate', $filterReleasedDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterReleasedDateEndDate', $filterReleasedDateEndDate, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalID = $row['sales_proposal_id'];
                    $customerID = $row['customer_id'];
                    $salesProposalNumber = $row['sales_proposal_number'];
                    $loanNumber = $row['loan_number'];
                    $productType = $row['product_type'];
                    $productID = $row['product_id'];
                    $drNumber = $row['dr_number'];
                    $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);
    
                    $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);
    
                    $productDetails = $productModel->getProduct($productID);
                    $productName = $productDetails['description'] ?? null;
                    $stockNumber = $productDetails['stock_number'] ?? null;
    
                    $customerDetails = $customerModel->getPersonalInformation($customerID);
                    $customerName = $customerDetails['file_as'] ?? null;
                    $corporateName = $customerDetails['corporate_name'] ?? null;
                    $forDRDate = $systemModel->checkDate('summary', $row['released_date'], '', 'm/d/Y h:i:s A', '');
    
                    $response[] = [
                        'SALES_PROPOSAL_NUMBER' => $salesProposalNumber,
                        'LOAN_NUMBER' => $loanNumber,
                        'CUSTOMER' => '<div class="col">
                                            <h6 class="mb-0">'. $customerName .'</h6>
                                            <p class="f-12 mb-0">'. $corporateName .'</p>
                                        </div>',
                        'DR_NUMBER' => $drNumber,
                        'PRODUCT_TYPE' => $productType,
                        'PRODUCT' => $stockNumber,
                        'RELEASED_DATE' => $forDRDate,
                        'STATUS' => $salesProposalStatus,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="release-summary.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # ------------------------------------------------------------
        #
        # Type: schedule of payments table
        # Description:
        # Generates the sales proposal released table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'schedule of payments table':
            if(isset($_POST['filter_release_date_start_date']) && isset($_POST['filter_release_date_end_date'])){
                $filterReleasedDateStartDate = $systemModel->checkDate('empty', $_POST['filter_release_date_start_date'], '', 'Y-m-d', '', '', '');
                $filterReleasedDateEndDate = $systemModel->checkDate('empty', $_POST['filter_release_date_end_date'], '', 'Y-m-d', '', '', '');

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalReleasedTable(:filterReleasedDateStartDate, :filterReleasedDateEndDate)');
                $sql->bindValue(':filterReleasedDateStartDate', $filterReleasedDateStartDate, PDO::PARAM_STR);
                $sql->bindValue(':filterReleasedDateEndDate', $filterReleasedDateEndDate, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalID = $row['sales_proposal_id'];
                    $salesProposalNumber = $row['sales_proposal_number'];
                    $numberOfPayments = $row['number_of_payments'] ?? null;
                    $releasedDate = $systemModel->checkDate('summary', $row['released_date'], '', 'm/d/Y', '');

                    $pricingComputationDetails = $salesProposalModel->getSalesProposalPricingComputation($salesProposalID);
                    $repaymentAmount = $pricingComputationDetails['repayment_amount'] ?? 0;

                    $paymentFrequency = $row['payment_frequency'] ?? null;
                    $startDate = $row['actual_start_date'] ?? null;
                    $termLength = $row['term_length'] ?? null;

                    for ($i = 0; $i < $numberOfPayments; $i++) {            
                        $dueDate = calculateDueDate($startDate, $termLength, $paymentFrequency, $i + 1);

                            if(($i + 1) <= 9){
                                $iteration = '0'. ($i + 1);
                            }
                            else{
                                $iteration = ($i + 1);
                            }

                            $response[] = [
                                'NUMBER' => $iteration,
                                'SALES_PROPOSAL_NUMBER' => $salesProposalNumber . ' - ' . $iteration,
                                'RELEASE_DATE' => $releasedDate,
                                'DUE_DATE' => $systemModel->checkDate('summary', $dueDate, '', 'm/d/Y', ''),
                                'AMOUNT_DUE' => number_format($repaymentAmount, 2)
                            ];
                    }
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
        
        # -------------------------------------------------------------
        #
        # Type: approved sales proposal table
        # Description:
        # Generates the sales proposal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'approved sales proposal table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateApprovedSalesProposalTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);
                $progress = $salesProposalModel->getJobOrderMonitoringTotalProgress($salesProposalID)['total_progress_percentage'] ?? 0;

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $proceedDate = $systemModel->checkDate('summary', $row['approval_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                    'SALES_PROPOSAL_NUMBER' => '<a href="approved-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => '<div class="col">
                                                <h6 class="mb-0">'. $customerName .'</h6>
                                                <p class="f-12 mb-0">'. $corporateName .'</p>
                                            </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'PROCEED_DATE' => $proceedDate,
                    'PROGRESS' => number_format($progress,2) . '%',
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="approved-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        case 'job order monitoring table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateJobOrderMonitoringTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);
                $progress = $salesProposalModel->getJobOrderMonitoringTotalProgress($salesProposalID)['total_progress_percentage'] ?? 0;

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $proceedDate = $systemModel->checkDate('summary', $row['approval_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'SALES_PROPOSAL_NUMBER' => '<a href="job-order-monitoring.php?id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => '<div class="col">
                                                <h6 class="mb-0">'. $customerName .'</h6>
                                                <p class="f-12 mb-0">'. $corporateName .'</p>
                                            </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'PROGRESS' => number_format($progress,2) . '%',
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="job-order-monitoring.php?id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
        
        # -------------------------------------------------------------
        #
        # Type: incoming sales proposal table
        # Description:
        # Generates the sales proposal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'incoming sales proposal table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateIncomingSalesProposalTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $proceedDate = $systemModel->checkDate('summary', $row['approval_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                    'SALES_PROPOSAL_NUMBER' => '<a href="incoming-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => '<div class="col">
                                                <h6 class="mb-0">'. $customerName .'</h6>
                                                <p class="f-12 mb-0">'. $corporateName .'</p>
                                            </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'PROCEED_DATE' => $proceedDate,
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="incoming-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: released sales proposal table
        # Description:
        # Generates the sales proposal table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'released sales proposal table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateReleasedSalesProposalTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $salesProposalID = $row['sales_proposal_id'];
                $customerID = $row['customer_id'];
                $salesProposalNumber = $row['sales_proposal_number'];
                $productType = $row['product_type'];
                $productID = $row['product_id'];
                $salesProposalStatus = $salesProposalModel->getSalesProposalStatus($row['sales_proposal_status']);

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;
                $corporateName = $customerDetails['corporate_name'] ?? null;

                $releasedDate = $systemModel->checkDate('summary', $row['released_date'], '', 'm/d/Y', '');

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                    'SALES_PROPOSAL_NUMBER' => '<a href="released-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => '<div class="col">
                                                <h6 class="mb-0">'. $customerName .'</h6>
                                                <p class="f-12 mb-0">'. $corporateName .'</p>
                                            </div>',
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'PROCEED_DATE' => $releasedDate,
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="released-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal accessories table
        # Description:
        # Generates the sales proposal accessories table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal accessories table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $salesProposalStatus = $salesProposalDetails['sales_proposal_status'];


                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalAccessoriesTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalAccessoriesID = $row['sales_proposal_accessories_id'];
                    $accessories = $row['accessories'];
                    $cost = number_format($row['cost'], 2);

                    $action = '';
                    if($salesProposalStatus == 'Draft'){
                        $action = '<div class="d-flex gap-2">
                        <button type="button" class="btn btn-icon btn-success update-sales-proposal-accessories" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-accessories-offcanvas" aria-controls="sales-proposal-accessories-offcanvas" data-sales-proposal-accessories-id="'. $salesProposalAccessoriesID .'" title="Update Accessories">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-accessories" data-sales-proposal-accessories-id="'. $salesProposalAccessoriesID .'" title="Delete Accessories">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
                    }

                    $response[] = [
                        'ACCESSORIES' => $accessories,
                        'COST' => $cost,
                        'ACTION' => $action
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: summary accessories table
        # Description:
        # Generates the summary accessories table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'summary accessories table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $table = '<table class="table table-borderless text-sm">
                             <tbody>';

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalAccessoriesTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $accessories = strtoupper($row['accessories']);
                    $cost = number_format($row['cost'], 2);

                    $table .= '<tr>
                                <td class="text-wrap">'. $accessories .'</td>
                                <td class="text-wrap">'. $cost .'</td>
                            </tr>';
                }

                $table .= '</tbody></table>';

                $response[] = [
                    'table' => $table
                ];

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal job order table
        # Description:
        # Generates the sales proposal job order table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal job order table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $salesProposalStatus = $salesProposalDetails['sales_proposal_status'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalJobOrderTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalJobOrderID = $row['sales_proposal_job_order_id'];
                    $jobOrder = $row['job_order'];
                    $progress = $row['progress'];
                    $cost = number_format($row['cost'], 2);
                    $approval_document = $systemModel->checkImage($row['approval_document'], 'default');

                    $action = '';
                    if($salesProposalStatus == 'Draft'){
                        $action = '<div class="d-flex gap-2">
                        <button type="button" class="btn btn-icon btn-success update-sales-proposal-job-order" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-offcanvas" aria-controls="sales-proposal-job-order-offcanvas" data-sales-proposal-job-order-id="'. $salesProposalJobOrderID .'" title="Update Job Order">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-job-order" data-sales-proposal-job-order-id="'. $salesProposalJobOrderID .'" title="Delete Job Order">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
                    }

                    $response[] = [
                        'JOB_ORDER' => $jobOrder,
                        'COST' => $cost,
                        'APPROVAL_DOCUMENT' => '<a href="'. $approval_document .'" target="_blank">View</a>',
                        'PROGRESS' => number_format($progress, 2) . '%',
                        'ACTION' => '<div class="d-flex gap-2">'.
                                    $action . 
                                    '</div>'
                        ];
                }

                echo json_encode($response);
            }
        break;

        case 'sales proposal job order monitoring table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $salesProposalStatus = $salesProposalDetails['sales_proposal_status'];

                $updateJobOrderProgress = $userModel->checkSystemActionAccessRights($user_id, 197);

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalJobOrderTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalJobOrderID = $row['sales_proposal_job_order_id'];
                    $jobOrder = $row['job_order'];
                    $progress = $row['progress'];
                    $contractor_id = $row['contractor_id'];
                    $work_center_id = $row['work_center_id'];
                    $backjob = $row['backjob'];
                    $cancellation_reason = $row['cancellation_reason'];
                    $cost = number_format($row['cost'], 2);
                    $job_cost = number_format($row['job_cost'], 2);
                    $completionDate = $systemModel->checkDate('summary', $row['completion_date'], '', 'm/d/Y', '');
                    $planned_start_date = $systemModel->checkDate('summary', $row['planned_start_date'], '', 'm/d/Y', '');
                    $planned_finish_date = $systemModel->checkDate('summary', $row['planned_finish_date'], '', 'm/d/Y', '');
                    $date_started = $systemModel->checkDate('summary', $row['date_started'], '', 'm/d/Y', '');
                    $cancellation_date = $systemModel->checkDate('summary', $row['cancellation_date'], '', 'm/d/Y', '');

                    if($backjob == 'No'){
                        $backjob =  '<span class="badge bg-success">' . $backjob . '</span>';
                    }
                    else{
                        $backjob =  '<span class="badge bg-danger">' . $backjob . '</span>';
                    }

                    $contractorDetails = $contractorModel->getContractor($contractor_id);
                    $contractor_name = $contractorDetails['contractor_name'] ?? null;

                    $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                    $work_center_name = $workCenterDetails['work_center_name'] ?? null;

                    $action = '';
                    if($updateJobOrderProgress['total'] > 0 && $salesProposalStatus == 'On-Process'){
                        $action = '<button type="button" class="btn btn-icon btn-success update-sales-proposal-job-order-monitoring" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-job-order-monitoring-offcanvas" aria-controls="sales-proposal-job-order-monitoring-offcanvas" data-sales-proposal-job-order-id="'. $salesProposalJobOrderID .'" title="Update Job Order Progress">
                            <i class="ti ti-edit"></i>
                        </button>';

                        if(empty($cancellation_reason)){
                            $action .= '<button type="button" class="btn btn-icon btn-warning cancel-sales-proposal-job-order-monitoring" data-bs-target="#sales-proposal-job-order-cancel-offcanvas" aria-controls="sales-proposal-job-order-cancel-offcanvas" data-bs-toggle="offcanvas" data-sales-proposal-job-order-id="'. $salesProposalJobOrderID .'" title="Cancel Job Order Progress">
                                <i class="ti ti-x"></i>
                            </button>';
                        }
                    }

                    if(!empty($cancellation_reason)){
                        $cancellation_confirmation = $systemModel->checkImage($row['cancellation_confirmation'], 'default');

                        $cancellation_confirmation = '<a href="'. $cancellation_confirmation .'">View Confirmation</a>';
                    }
                    else{
                        $cancellation_confirmation = '';
                    }

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input job-order-checkbox-children" type="checkbox" value="'. $salesProposalJobOrderID .'">',
                        'JOB_ORDER' => $jobOrder,
                        'COST' => $cost,
                        'JOB_COST' => $job_cost,
                        'BACKJOB' => $backjob,
                        'CONTRACTOR' => $contractor_name,
                        'WORK_CENTER' => $work_center_name,
                        'COMPLETION_DATE' => $completionDate,
                        'PLANNED_START_DATE' => $planned_start_date,
                        'PLANNED_FINISH_DATE' => $planned_finish_date,
                        'DATE_STARTED' => $date_started,
                        'PROGRESS' => number_format($progress, 2) . '%',
                        'CANCELLATION_DATE' => $cancellation_date,
                        'CANCELLATION_REASON' => $cancellation_reason,
                        'CANCELLATION_CONFIRMATION' => $cancellation_confirmation,
                        'ACTION' => '<div class="d-flex gap-2">'.
                                    $action . 
                                    '</div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: summary job order table
        # Description:
        # Generates the summary job order table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'summary job order table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $table = '<table class="table table-borderless text-sm ">
                <tbody>';

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalJobOrderTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $jobOrder = strtoupper($row['job_order']);
                    $cost = number_format($row['cost'], 2);
                    $progress = number_format($row['progress'], 2);

                    $table .= '<tr>
                                <td class="text-wrap">'. $jobOrder .'</td>
                                <td class="text-wrap">'. $progress .'%</td>
                                <td class="text-wrap">'. $cost .'</td>
                            </tr>';
                }

                $table .= '</tbody></table>';

                $response[] = [
                    'table' => $table
                ];

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal additional job order table
        # Description:
        # Generates the sales proposal additional job order table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal additional job order table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $salesProposalStatus = $salesProposalDetails['sales_proposal_status'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalAdditionalJobOrderTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalAdditionalJobOrderID = $row['sales_proposal_additional_job_order_id'];
                    $jobOrderNumber = $row['job_order_number'];
                    $jobOrderDate = $systemModel->checkDate('summary', $row['job_order_date'], '', 'F d, Y', '');
                    $particulars = $row['particulars'];
                    $progress = $row['progress'];
                    $cost = number_format($row['cost'], 2);


                    $action = '';
                    if($salesProposalStatus != 'For DR'){
                        $action = '<button type="button" class="btn btn-icon btn-success update-sales-proposal-additional-job-order" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-offcanvas" aria-controls="sales-proposal-additional-job-order-offcanvas" data-sales-proposal-additional-job-order-id="'. $salesProposalAdditionalJobOrderID .'" title="Update Additional Job Order">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-additional-job-order" data-sales-proposal-additional-job-order-id="'. $salesProposalAdditionalJobOrderID .'" title="Delete Additional Job Order">
                            <i class="ti ti-trash"></i>
                        </button>';
                    }

                    $response[] = [
                        'JOB_ORDER_NUMBER' => $jobOrderNumber,
                        'JOB_ORDER_DATE' => $jobOrderDate,
                        'PARTICULARS' => $particulars,
                        'COST' => $cost,
                        'PROGRESS' => number_format($progress, 2) . '%',
                        'ACTION' => '<div class="d-flex gap-2">'.
                                    $action . 
                                    '</div>'
                        ];
                }

                echo json_encode($response);
            }
        break;

        case 'sales proposal additional job order monitoring table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $salesProposalStatus = $salesProposalDetails['sales_proposal_status'];

                $updateJobOrderProgress = $userModel->checkSystemActionAccessRights($user_id, 197);

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalAdditionalJobOrderTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalAdditionalJobOrderID = $row['sales_proposal_additional_job_order_id'];
                    $jobOrderNumber = $row['job_order_number'];
                    $jobOrderDate = $systemModel->checkDate('summary', $row['job_order_date'], '', 'F d, Y', '');
                    $particulars = $row['particulars'];
                    $progress = $row['progress'];
                    $contractor_id = $row['contractor_id'];
                    $work_center_id = $row['work_center_id'];
                    $backjob = $row['backjob'];
                    $cancellation_reason = $row['cancellation_reason'];
                    $cost = number_format($row['cost'], 2);
                    $job_cost = number_format($row['job_cost'], 2);
                    $completionDate = $systemModel->checkDate('summary', $row['completion_date'], '', 'm/d/Y', '');
                    $planned_start_date = $systemModel->checkDate('summary', $row['planned_start_date'], '', 'm/d/Y', '');
                    $planned_finish_date = $systemModel->checkDate('summary', $row['planned_finish_date'], '', 'm/d/Y', '');
                    $date_started = $systemModel->checkDate('summary', $row['date_started'], '', 'm/d/Y', '');
                    $cancellation_date = $systemModel->checkDate('summary', $row['cancellation_date'], '', 'm/d/Y', '');

                    if($backjob == 'No'){
                        $backjob =  '<span class="badge bg-success">' . $backjob . '</span>';
                    }
                    else{
                        $backjob =  '<span class="badge bg-danger">' . $backjob . '</span>';
                    }

                    $contractorDetails = $contractorModel->getContractor($contractor_id);
                    $contractor_name = $contractorDetails['contractor_name'] ?? null;

                    $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
                    $work_center_name = $workCenterDetails['work_center_name'] ?? null;


                    $action = '';
                    if($updateJobOrderProgress['total'] > 0 && $salesProposalStatus == 'On-Process'){
                        $action = '<button type="button" class="btn btn-icon btn-success update-sales-proposal-additional-job-order-monitoring" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-monitoring-offcanvas" aria-controls="sales-proposal-additional-job-order-monitoring-offcanvas" data-sales-proposal-additional-job-order-id="'. $salesProposalAdditionalJobOrderID .'" title="Update Additional Job Order Progress">
                            <i class="ti ti-edit"></i>
                        </button>';

                        if(empty($cancellation_reason)){
                            $action .= '<button type="button" class="btn btn-icon btn-warning cancel-sales-proposal-additional-job-order-monitoring" data-bs-target="#sales-proposal-additional-job-order-cancel-offcanvas" aria-controls="sales-proposal-additional-job-order-cancel-offcanvas" data-bs-toggle="offcanvas" data-sales-proposal-additional-job-order-id="'. $salesProposalAdditionalJobOrderID .'" title="Cancel Additional Job Order Progress">
                                <i class="ti ti-x"></i>
                            </button>';
                        }
                    }

                    if(!empty($cancellation_reason)){
                        $cancellation_confirmation = $systemModel->checkImage($row['cancellation_confirmation'], 'default');

                        $cancellation_confirmation = '<a href="'. $cancellation_confirmation .'">View Confirmation</a>';
                    }
                    else{
                        $cancellation_confirmation = '';
                    }


                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input additional-job-order-checkbox-children" type="checkbox" value="'. $salesProposalAdditionalJobOrderID .'">',
                        'JOB_ORDER_NUMBER' => $jobOrderNumber,
                        'JOB_ORDER_DATE' => $jobOrderDate,
                        'PARTICULARS' => $particulars,
                        'COST' => $cost,
                        'JOB_COST' => $job_cost,
                        'BACKJOB' => $backjob,
                        'CONTRACTOR' => $contractor_name,
                        'WORK_CENTER' => $work_center_name,
                        'COMPLETION_DATE' => $completionDate,
                        'PLANNED_START_DATE' => $planned_start_date,
                        'PLANNED_FINISH_DATE' => $planned_finish_date,
                        'DATE_STARTED' => $date_started,
                        'PROGRESS' => number_format($progress, 2) . '%',
                        'CANCELLATION_DATE' => $cancellation_date,
                        'CANCELLATION_REASON' => $cancellation_reason,
                        'CANCELLATION_CONFIRMATION' => $cancellation_confirmation,
                        'ACTION' => '<div class="d-flex gap-2">'.
                                    $action . 
                                    '</div>'
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: summary additional job order table
        # Description:
        # Generates the summary additional job order table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'summary additional job order table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $table = '<table class="table table-bordered text-sm">
                            <tbody>
                                <tr>
                                    <td style="text-align: center !important;"><small><b>JO NO.</b></small></td>
                                    <td style="text-align: center !important;"><small><b>JO DATE</b></small></td>
                                    <td style="text-align: center !important;"><small><b>PARTICULARS</b></small></td>
                                    <td style="text-align: center !important;"><small><b>PROGRESS</b></small></td>
                                    <td style="text-align: center !important;"><small><b>AMT CHARGED</b></small></td>
                                </tr>';

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalAdditionalJobOrderTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $rowCount = $sql->rowCount();
                $sql->closeCursor();

                if($rowCount > 0){
                    foreach ($options as $row) {
                        $jobOrderNumber = $row['job_order_number'];
                        $jobOrderDate = $systemModel->checkDate('summary', $row['job_order_date'], '', 'F d, Y', '');
                        $particulars = $row['particulars'];
                        $cost = number_format($row['cost'], 2);
                        $progress = number_format($row['progress'], 2);
    
                        $table .= '<tr>
                            <td style="text-align: center !important;" class="text-wrap">'. $jobOrderNumber .'</td>
                            <td style="text-align: center !important;" class="text-wrap">'. $jobOrderDate .'</td>
                            <td style="text-align: center !important;" class="text-wrap">'. $particulars .'</td>
                            <td style="text-align: center !important;" class="text-wrap">'. $progress .'%</td>
                            <td style="text-align: center !important;" class="text-wrap">'. $cost .'</td>
                        </tr>';
                    }
                }
                else{
                    $table .= '<tr>
                            <td colspan="5"></td>
                        </tr>';
                }                

                $table .= '</tbody></table>';

                $response[] = [
                    'table' => $table
                ];

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal deposit amount table
        # Description:
        # Generates the sales proposal deposit amount table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal deposit amount table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $salesProposalStatus = $salesProposalDetails['sales_proposal_status'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalDepositAmountTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalDepositAmountID = $row['sales_proposal_deposit_amount_id'];
                    $depositDate = $systemModel->checkDate('summary', $row['deposit_date'], '', 'F d, Y', '');
                    $referenceNumber = $row['reference_number'];
                    $depositAmount = number_format($row['deposit_amount'], 2);

                    $action = '';

                    if($salesProposalStatus == 'Draft' || $salesProposalStatus == 'For DR'){
                        $action = '<div class="d-flex gap-2">
                        <button type="button" class="btn btn-icon btn-success update-sales-proposal-deposit-amount" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-deposit-amount-offcanvas" aria-controls="sales-proposal-deposit-amount-offcanvas" data-sales-proposal-deposit-amount-id="'. $salesProposalDepositAmountID .'" title="Update Amount of Deposit">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-deposit-amount" data-sales-proposal-deposit-amount-id="'. $salesProposalDepositAmountID .'" title="Delete Amount of Deposit">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
                    }

                   

                    $response[] = [
                        'DEPOSIT_DATE' => $depositDate,
                        'REFERENCE_NUMBER' => $referenceNumber,
                        'DEPOSIT_AMOUNT' => $depositAmount,
                        'ACTION' => $action
                        ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: sales proposal pdc manual input table
        # Description:
        # Generates the sales proposal pdc manual input table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'sales proposal pdc manual input table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $salesProposalStatus = $salesProposalDetails['sales_proposal_status'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalPDCManualInputTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $manualPDCInputID = $row['manual_pdc_input_id'];
                    $accountNumber = $row['account_number'];
                    $bankBranch = $row['bank_branch'];
                    $checkDate = $systemModel->checkDate('summary', $row['check_date'], '', 'F d, Y', '');
                    $checkNumber = $row['check_number'];
                    $paymentFor = $row['payment_for'];
                    $grossAmount = number_format($row['gross_amount'], 2);

                    /*if($salesProposalStatus == 'For DR'){
                        $action = '
                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-manual-pdc-input" data-sales-proposal-manual-pdc-input-id="'. $manualPDCInputID .'" title="Delete PDC Input">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
                    }
                    else{
                        $action = '';
                    }*/

                    if($salesProposalStatus == 'For DR' ||$salesProposalStatus == 'Released'){
                        $action = '
                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-manual-pdc-input" data-sales-proposal-manual-pdc-input-id="'. $manualPDCInputID .'" title="Delete PDC Input">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
                    }
                    else{
                        $action = '';
                    }

                    $response[] = [
                        'ACCOUNT_NUMBER' => $accountNumber,
                        'BANK_BRANCH' => $bankBranch,
                        'CHECK_DATE' => $checkDate,
                        'CHECK_NUMBER' => $checkNumber,
                        'PAYMENT_FOR' => $paymentFor,
                        'GROSS_AMOUNT' => $grossAmount,
                        'ACTION' => $action
                    ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: summary deposit amount table
        # Description:
        # Generates the summary deposit amount table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'summary deposit amount table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $table = '<table class="table table-borderless text-sm ">
                <tbody>
                <tr>
                    <td style="text-align: center !important;"><u>DATE</u></td>
                    <td style="text-align: center !important;"><u>REF NO.</u></td>
                    <td style="text-align: center !important;"><u>AMOUNT</u></td>
                </tr>';

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalDepositAmountTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $salesProposalDepositAmountID = $row['sales_proposal_deposit_amount_id'];
                    $depositDate = $systemModel->checkDate('summary', $row['deposit_date'], '', 'F d, Y', '');
                    $referenceNumber = $row['reference_number'];
                    $depositAmount = number_format($row['deposit_amount'], 2);

                    $table .= '<tr>
                                <td style="text-align: center !important;" class="text-wrap">'. $depositDate .'</td>
                                <td style="text-align: center !important;" class="text-wrap">'. $referenceNumber .'</td>
                                <td style="text-align: center !important;" class="text-wrap">'. $depositAmount .'</td>
                            </tr>';
                }

                $table .= '</tbody></table>';

                $response[] = [
                    'table' => $table
                ];

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: summary disclosure schedule table
        # Description:
        # Generates the summary disclosure schedule table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'summary disclosure schedule table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $numberOfPayments = $salesProposalDetails['number_of_payments'] ?? null;
                $termLength = $salesProposalDetails['term_length'] ?? null;
                $paymentFrequency = $salesProposalDetails['payment_frequency'] ?? null;

                $pricingComputationDetails = $salesProposalModel->getSalesProposalPricingComputation($salesProposalID);
                $repaymentAmount = $pricingComputationDetails['repayment_amount'] ?? 0;
                $startDate = $salesProposalDetails['actual_start_date'] ?? null;
                $pnAmount = $repaymentAmount * $numberOfPayments;

                $table = ' <tr>
                                <td>DUE DATE</td>
                                <td>AMOUNT DUE</td>
                                <td>OUTSTANDING BALANCE</td>
                            </tr>
                            <tr>
                                <td>--</td>
                                <td>--</td>
                                <td>'. number_format($pnAmount, 2) .'</td>
                            </tr>';

                            for ($i = 0; $i < $numberOfPayments; $i++) {
                                $pnAmount = $pnAmount - $repaymentAmount;

                                if($pnAmount <= 0){
                                    $pnAmount = 0;
                                }

                                $dueDate = calculateDueDate($startDate, $termLength, $paymentFrequency, $i + 1);

                                $table .= '<tr>
                                        <td>'. $dueDate .'</td>
                                        <td>'. number_format($repaymentAmount, 2) .'</td>
                                        <td>'. number_format($pnAmount, 2) .'</td>
                                    </tr>';
                            }

                $response[] = [
                    'table' => $table
                ];

                echo json_encode($response);

                
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: summary pdc manual input table
        # Description:
        # Generates the summary pdc manual input table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'summary pdc manual input table':
            if(isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])){
                $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');

                $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID);
                $salesProposalStatus = $salesProposalDetails['sales_proposal_status'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalPDCManualInputTable(:salesProposalID)');
                $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $table = '';

                foreach ($options as $row) {
                    $manualPDCInputID = $row['manual_pdc_input_id'];
                    $accountNumber = $row['account_number'];
                    $bankBranch = $row['bank_branch'];
                    $checkDate = $systemModel->checkDate('summary', $row['check_date'], '', 'd-M-Y', '');
                    $checkNumber = $row['check_number'];
                    $paymentFor = $row['payment_for'];
                    $grossAmount = number_format($row['gross_amount'], 2);

                    $table .= '<tr>
                                    <td>'. $checkDate .'</td>
                                    <td>'. $grossAmount .'</td>
                                    <td>'. $paymentFor .'</td>
                                </tr>';
                }

                $response[] = [
                    'table' => $table
                ];

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

function calculateDueDate($startDate, $termLength, $frequency, $iteration) {
    $date = new DateTime($startDate);
    switch ($frequency) {
        case 'Monthly':
            $date->modify("+$iteration months");
            break;
        case 'Quarterly':
            $date->modify("+$iteration months")->modify('+2 months');
            break;
        case 'Semi-Annual':
            $date->modify("+$iteration months")->modify('+5 months');
            break;
        case 'Lumpsum':
            $date->modify("+$termLength days");
            break;
        default:
            break;
    }
    return $date->format('d-M-Y');
}

?>