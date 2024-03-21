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

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
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
                        'CUSTOMER' => $customerName,
                        'PRODUCT_TYPE' => $productType,
                        'PRODUCT' => $stockNumber . ' - ' . $productName,
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

        # -------------------------------------------------------------
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
            if(isset($_POST['sales_proposal_status_filter'])){
                $salesProposalStatusFilter = htmlspecialchars($_POST['sales_proposal_status_filter'], ENT_QUOTES, 'UTF-8');

                $deleteSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 119);
                $viewOwnSalesProposal = $userModel->checkSystemActionAccessRights($user_id, 131);

                if($viewOwnSalesProposal['total'] > 0){
                    $contactID = $_SESSION['contact_id'];

                    $sql = $databaseModel->getConnection()->prepare('CALL generateOwnSalesProposalTable(:contactID, :salesProposalStatusFilter)');
                    $sql->bindValue(':contactID', $contactID, PDO::PARAM_STR);
                }
                else{
                    $sql = $databaseModel->getConnection()->prepare('CALL generateAllSalesProposalTable(:salesProposalStatusFilter)');
                }

              
                $sql->bindValue(':salesProposalStatusFilter', $salesProposalStatusFilter, PDO::PARAM_STR);
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
                        'CUSTOMER' => $customerName,
                        'PRODUCT_TYPE' => $productType,
                        'PRODUCT' => $stockNumber,
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
            }
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

                $proceedDate = $systemModel->checkDate('summary', $row['approval_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                    'SALES_PROPOSAL_NUMBER' => '<a href="sales-proposal-change-request.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => $customerName,
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'PROCEED_DATE' => $proceedDate,
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="sales-proposal-change-request-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
                $forCIDate = $systemModel->checkDate('summary', $row['for_ci_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                    'SALES_PROPOSAL_NUMBER' => '<a href="sales-proposal-for-ci.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => $customerName,
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
                $forDRDate = $systemModel->checkDate('summary', $row['for_dr_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'SALES_PROPOSAL_NUMBER' => $salesProposalNumber,
                    'CUSTOMER' => $customerName,
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'FOR_DR_DATE' => $forDRDate,
                    'STATUS' => $salesProposalStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="sales-proposal-for-dr.php?&sales_proposal_id='. $salesProposalIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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

                $salesProposalIDEncrypted = $securityModel->encryptData($salesProposalID);

                $productDetails = $productModel->getProduct($productID);
                $productName = $productDetails['description'] ?? null;
                $stockNumber = $productDetails['stock_number'] ?? null;

                $customerDetails = $customerModel->getPersonalInformation($customerID);
                $customerName = $customerDetails['file_as'] ?? null;

                $proceedDate = $systemModel->checkDate('summary', $row['approval_date'], '', 'm/d/Y h:i:s A', '');

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $salesProposalID .'">',
                    'SALES_PROPOSAL_NUMBER' => '<a href="approved-sales-proposal.php?customer='. $securityModel->encryptData($customerID) .'&id='. $salesProposalIDEncrypted .'">
                                                    '. $salesProposalNumber .'
                                                </a>',
                    'CUSTOMER' => $customerName,
                    'PRODUCT_TYPE' => $productType,
                    'PRODUCT' => $stockNumber,
                    'PROCEED_DATE' => $proceedDate,
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
                    $cost = number_format($row['cost'], 2);

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
                        'ACTION' => $action
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

                    $table .= '<tr>
                                <td class="text-wrap">'. $jobOrder .'</td>
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
                    $cost = number_format($row['cost'], 2);


                    $action = '';
                    if($salesProposalStatus == 'Draft'){
                        $action = '<div class="d-flex gap-2">
                        <button type="button" class="btn btn-icon btn-success update-sales-proposal-additional-job-order" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-additional-job-order-offcanvas" aria-controls="sales-proposal-additional-job-order-offcanvas" data-sales-proposal-additional-job-order-id="'. $salesProposalAdditionalJobOrderID .'" title="Update Additional Job Order">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-additional-job-order" data-sales-proposal-additional-job-order-id="'. $salesProposalAdditionalJobOrderID .'" title="Delete Additional Job Order">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
                    }

                    $response[] = [
                        'JOB_ORDER_NUMBER' => $jobOrderNumber,
                        'JOB_ORDER_DATE' => $jobOrderDate,
                        'PARTICULARS' => $particulars,
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
    
                        $table .= '<tr>
                            <td style="text-align: center !important;" class="text-wrap">'. $jobOrderNumber .'</td>
                            <td style="text-align: center !important;" class="text-wrap">'. $jobOrderDate .'</td>
                            <td style="text-align: center !important;" class="text-wrap">'. $particulars .'</td>
                            <td style="text-align: center !important;" class="text-wrap">'. $cost .'</td>
                        </tr>';
                    }
                }
                else{
                    $table .= '<tr>
                            <td colspan="4"></td>
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

                    if($salesProposalStatus == 'Draft'){
                        $action = '<div class="d-flex gap-2">
                        <button type="button" class="btn btn-icon btn-success update-sales-proposal-deposit-amount" data-bs-toggle="offcanvas" data-bs-target="#sales-proposal-deposit-amount-offcanvas" aria-controls="sales-proposal-deposit-amount-offcanvas" data-sales-proposal-deposit-amount-id="'. $salesProposalDepositAmountID .'" title="Update Amount of Deposit">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger delete-sales-proposal-deposit-amount" data-sales-proposal-deposit-amount-id="'. $salesProposalDepositAmountID .'" title="Delete Amount of Deposit">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
                    }
                    else{
                        $action = '';
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
    }
}

?>