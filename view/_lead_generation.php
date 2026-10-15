<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/lead-model.php';
require_once '../model/product-model.php';
require_once '../model/product-subcategory-model.php';
require_once '../model/inquiry-type-model.php';
require_once '../model/lead-status-model.php';
require_once '../model/lead-source-model.php';
require_once '../model/company-model.php';
require_once '../model/supplier-model.php';
require_once '../model/unit-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$leadModel = new LeadModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
$inquiryTypeModel = new InquiryTypeModel($databaseModel);
$leadStatusModel = new LeadStatusModel($databaseModel);
$leadSourceModel = new LeadSourceModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
$supplierModel = new SupplierModel($databaseModel);
$unitModel = new UnitModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = $_POST['type'];
    $response = [];
    
    switch ($type) {

        case 'lead table':
            $userId = $_SESSION['user_id'];
            $filter_created_date_start_date = $systemModel->checkDate('empty', $_POST['filter_created_date_start_date'], '', 'Y-m-d', '');
            $filter_created_date_end_date = $systemModel->checkDate('empty', $_POST['filter_created_date_end_date'], '', 'Y-m-d', '');
            $filter_inquiry_date_start_date = $systemModel->checkDate('empty', $_POST['filter_inquiry_date_start_date'], '', 'Y-m-d', '');
            $filter_inquiry_date_end_date = $systemModel->checkDate('empty', $_POST['filter_inquiry_date_end_date'], '', 'Y-m-d', '');
            $filter_lead_status = $_POST['filter_lead_status'];
            $filter_inquiry_type = $_POST['filter_inquiry_type'];
            $filter_assigned_to = $_POST['filter_assigned_to'];
            $filter_lead_source = $_POST['filter_lead_source'];
            $filter_lead_priority = $_POST['filter_lead_priority'];

            if (!empty($filter_lead_status)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_lead_status)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_lead_status = implode(', ', $quoted_values_array);
            } else {
                $filter_lead_status = null;
            }

            if (!empty($filter_inquiry_type)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_inquiry_type)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_inquiry_type = implode(', ', $quoted_values_array);
            } else {
                $filter_inquiry_type = null;
            }

            if (!empty($filter_assigned_to)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_assigned_to)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_assigned_to = implode(', ', $quoted_values_array);
            } else {
                $filter_assigned_to = null;
            }

            if (!empty($filter_lead_source)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_lead_source)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_lead_source = implode(', ', $quoted_values_array);
            } else {
                $filter_lead_source = null;
            }

            if (!empty($filter_lead_priority)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_lead_priority)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_lead_priority = implode(', ', $quoted_values_array);
            } else {
                $filter_lead_priority = null;
            }

            $viewAll = $userModel->checkSystemActionAccessRights($user_id, 238);

            if($viewAll['total'] > 0){
                $leads = $leadModel->generateAllLeadTable($filter_created_date_start_date, $filter_created_date_end_date, $filter_inquiry_date_start_date, $filter_inquiry_date_end_date, $filter_lead_status, $filter_inquiry_type, $filter_lead_source, $filter_lead_priority, $filter_assigned_to);
            }
            else{
                $leads = $leadModel->generateLeadTable($filter_created_date_start_date, $filter_created_date_end_date, $filter_inquiry_date_start_date, $filter_inquiry_date_end_date, $filter_lead_status, $filter_inquiry_type, $filter_lead_source, $filter_lead_priority, $user_id);
            }            

            $leadDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 191, 'delete');

            foreach ($leads as $row) {
                $leadID = $row['lead_id'];
                $inquiry_type_id = $row['inquiry_type_id'];
                $lead_status_id = $row['lead_status_id'];
                $stock_number = $row['stock_number'];
                $lead_priority = $row['lead_priority'];

                $user = $userModel->getUserByID($row['assigned_to']);
                $fileAs = $user['file_as'] ?? null;

                $inquiry_type_name = $inquiryTypeModel->getInquiryType($inquiry_type_id)['inquiry_type_name'] ?? null;
                $lead_status_name = $leadStatusModel->getLeadStatus($lead_status_id)['lead_status_name'] ?? '--';
                $lead_source_name = $leadSourceModel->getLeadSource($row['lead_source_id'])['lead_source_name'] ?? '--';
                $lead_note = $leadModel->getLastNote($leadID)['note'] ?? '--';
                $lead_note_date = $systemModel->checkDate('empty', $leadModel->getLastNote($leadID)['created_at'] ?? null, '', 'm/d/Y', '');
                $transaction_date = $systemModel->checkDate('empty', $row['created_at'], '', 'm/d/Y', '');
                $inquiry_date = $systemModel->checkDate('empty', $row['inquiry_date'], '', 'm/d/Y', '');

                $productDetails = $productModel->getProduct($stock_number);
                $stock_number = $productDetails['stock_number'] ?? '';

                $leadIDEncrypted = $securityModel->encryptData($leadID);

                $delete = '';
                if($leadDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-lead" data-lead-id="'. $leadID .'">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $leadID .'">',
                    'LEAD_NAME' => $row['file_as'],
                    'PHONE' => $row['phone'],
                    'LEAD_SOURCE' => $lead_source_name,
                    'LEAD_PRIORITY' => $lead_priority,
                    'INQUIRY_TYPE' => $inquiry_type_name,
                    'INQUIRY_DATE' => $inquiry_date,
                    'PRODUCT' => $stock_number,
                    'STATUS' => $lead_status_name,
                    'LEAD_NOTE' => '<span class="text-wrap">' . $lead_note . '</span>',
                    'LEAD_NOTE_DATE' => $lead_note_date,
                    'ASSIGNED_TO' => $fileAs,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="lead-monitoring.php?id='. $leadIDEncrypted .'" class="btn btn-icon btn-primary">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'product inquiry table':
            $deleteProductInquiry = $userModel->checkSystemActionAccessRights($user_id, 244);

            $lead_id = htmlspecialchars($_POST['lead_id'], ENT_QUOTES, 'UTF-8');
            $sql = $databaseModel->getConnection()->prepare('
                SELECT * 
                FROM leads_product_inquiry 
                WHERE lead_id = :lead_id
                ORDER BY unit_description ASC;
            ');
            $sql->bindValue(':lead_id', $lead_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();
    
            foreach ($options as $row) {
                $leads_product_inquiry_id = $row['leads_product_inquiry_id'];
                $product_id = $row['product_id'];
                $product_subcategory_id = $row['product_subcategory_id'];
                $unit_description = $row['unit_description'];
                $engine_no = $row['engine_no'];
                $chassis_no = $row['chassis_no'];
                $color = $row['color'];
                $year_model = $row['year_model'];
                $transmission_type = $row['transmission_type'];
                $length = $row['length'];
                $length_unit = $row['length_unit'];
                $created_at = $systemModel->checkDate('empty', $row['created_at'] ?? null, '', 'm/d/Y', '');

                $unitDetails = $unitModel->getUnit($length_unit);
                $unitShortName = $unitDetails['short_name'] ?? null;

                $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($product_subcategory_id);
                $productSubcategoryName = $productSubcategoryDetails['product_subcategory_name'] ?? null;

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = !empty($productDetails['stock_number'] ?? '') ? $productDetails['stock_number'] . ' - ' : '';

                $delete ='';
                if($deleteProductInquiry['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-inquiry" data-leads-product-inquiry-id="'. $leads_product_inquiry_id .'" title="Delete Inquiry">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'PRODUCT' => '<span class="text-wrap">'. $stock_number . $unit_description . '</span>',
                    'CATEGORY' => $productSubcategoryName,
                    'TRANSMISSION_TYPE' => $transmission_type,
                    'LENGTH' => $length . ' ' . $unitShortName,
                    'ENGINE_NO' => $engine_no,
                    'CHASSIS_NO' => $chassis_no,
                    'COLOR' => $color,
                    'YEAR_MODEL' => $year_model,
                    'CREATED_DATE' => $created_at,
                    'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success add-quotation" data-bs-toggle="modal" data-bs-target="#quotation-modal" data-leads-product-inquiry-id="'. $leads_product_inquiry_id .'" data-product-id="'. $product_id .'" title="Add Quotation">
                                            <i class="ti ti-file-text"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-warning add-cif" data-bs-toggle="modal" data-bs-target="#cif-modal" data-leads-product-inquiry-id="'. $leads_product_inquiry_id .'" title="Add CIF">
                                            <i class="ti ti-file"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                ];
            }
    
            echo json_encode($response);
        break;

        case 'quotation table':
            $lead_id = htmlspecialchars($_POST['lead_id'], ENT_QUOTES, 'UTF-8');
            $sql = $databaseModel->getConnection()->prepare('
                SELECT * 
                FROM quotations 
                WHERE lead_id = :lead_id
                ORDER BY created_at DESC;
            ');
            $sql->bindValue(':lead_id', $lead_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();
 
            $response = [];

            foreach ($options as $row) {
                $quotation_id = $row['quotation_id'];
                $leads_product_inquiry_id = $row['leads_product_inquiry_id'];
                $price = $row['price'];
                $quantity = $row['quantity'];
                $discount = $row['discount'];
                $total = ($price * $quantity) - ($discount * $quantity);
                $status = $row['status'];
                $remarks = $row['remarks'];
                $inclusions = $row['inclusions'];
                $company_id = $row['company_id'];
                $delivery_address = $row['delivery_address'];
                $approval_confirmation = $row['approval_confirmation'];
                $created_at = $systemModel->checkDate('empty', $row['created_at'] ?? null, '', 'm/d/Y', '');
                $expiration_date = $systemModel->checkDate('empty', $row['expiration_date'] ?? null, '', 'm/d/Y', '');

                $companyDetails = $companyModel->getCompany($company_id);
                $companyName = $companyDetails['company_name'] ?? '';

                if ($status === 'Draft') {
                    $statusBadge = '<span class="badge bg-secondary">' . $status . '</span>';
                } else if ($status === 'For Approval') {
                    $statusBadge = '<span class="badge bg-warning">' . $status . '</span>';
                } else {
                    $statusBadge = '<span class="badge bg-success">' . $status . '</span>';
                }

                $leadInquiryDetails = $leadModel->getLeadInquiryDetails($leads_product_inquiry_id);
                $product_id = $leadInquiryDetails['product_id'] ?? null;
                $unit_description = $leadInquiryDetails['unit_description'] ?? null;

                  $productDetails = $productModel->getProduct($product_id);
                $stock_number = !empty($productDetails['stock_number'] ?? '') ? $productDetails['stock_number'] . ' - ' : '';

                // Build Action Buttons dynamically
                $actions = '';

                // Show Update, For Approval, and Delete buttons ONLY if status is Draft
                if ($status === 'Draft') {
                    $actions .= '<button type="button" class="btn btn-icon btn-success update-quotation" data-bs-toggle="modal" data-bs-target="#quotation-modal" data-quotation-id="' . $quotation_id . '" title="Update Quotation">
                                    <i class="ti ti-edit"></i>
                                </button>';

                    $actions .= '<button type="button" class="btn btn-icon btn-warning send-for-approval-quotation" data-quotation-id="' . $quotation_id . '" title="Send for Approval">
                                    <i class="ti ti-send"></i>
                                </button>';
                    
                    $actions .= '<button type="button" class="btn btn-icon btn-danger cancel-quotation" data-quotation-id="' . $quotation_id . '" title="Cancel Quotation">
                                    <i class="ti ti-x"></i>
                                </button>';

                    $actions .= '<button type="button" class="btn btn-icon btn-danger delete-quotation" data-quotation-id="' . $quotation_id . '" title="Delete Quotation">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                // Show Set to Draft and Cancel buttons ONLY if status is For Approval
                if ($status === 'For Approval' || $status === 'For Checking') {
                    $actions .= '<button type="button" class="btn btn-icon btn-danger cancel-quotation" data-quotation-id="' . $quotation_id . '" title="Cancel Quotation">
                                    <i class="ti ti-x"></i>
                                </button>';
                }

                // Show Print button ONLY if Approved AND it is the latest approved record
                if ($status === 'Approved') {
                    $actions .= '<a href="print-inquiry-quotation.php?id=' . $quotation_id . '" target="_blank" class="btn btn-icon btn-info print-quotation" title="Print Quotation">
                                    <i class="ti ti-printer"></i>
                                </a>';
                }

                $approval = '';
                if(!empty($approval_confirmation)){
                    $approval = '<a href="'. $approval_confirmation .'" target="_blank">
                                    View Approval Confirmation
                                </a>';
                }

                $response[] = [
                    'PRODUCT' => '<span class="text-wrap">' . $stock_number . $unit_description . '</span>',
                    'PRICE' => number_format($price, 2),
                    'QUANTITY' => number_format($quantity, 0),
                    'DISCOUNT' => number_format($discount, 2),
                    'TOTAL' => number_format($total, 2),
                    'COMPANY' => $companyName,
                    'DELIVERY_ADDRESS' => $delivery_address,
                    'INCLUSIONS' => $inclusions,
                    'STATUS' => $statusBadge,
                    'EXPIRATION_DATE' => $expiration_date,
                    'APPROVAL_CONFIRMATION' => $approval,
                    'CREATED_DATE' => $created_at,
                    'REMARKS' => '<span class="text-wrap">' . $remarks . '</span>',
                    'ACTION' => '<div class="d-flex gap-2">' . $actions . '</div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'cif table':
            $lead_id = htmlspecialchars($_POST['lead_id'], ENT_QUOTES, 'UTF-8');
            $sql = $databaseModel->getConnection()->prepare('
                SELECT * 
                FROM cif 
                WHERE lead_id = :lead_id
                ORDER BY created_at DESC;
            ');
            $sql->bindValue(':lead_id', $lead_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $response = [];

            foreach ($options as $row) {
                $cif_id = $row['cif_id'];
                $leads_product_inquiry_id = $row['leads_product_inquiry_id'];
                $company_id = $row['company_id'];
                $cif_reference_no = $row['cif_reference_no'];
                $add_ons = $row['add_ons'];
                $status = $row['status'];
                $remarks = $row['remarks'];

                $companyDetails = $companyModel->getCompany($company_id);
                $companyName = $companyDetails['company_name'] ?? '';

                if ($status === 'Draft') {
                    $statusBadge = '<span class="badge bg-secondary">' . $status . '</span>';
                } else if ($status === 'Cancelled') {
                    $statusBadge = '<span class="badge bg-warning">' . $status . '</span>';
                } else {
                    $statusBadge = '<span class="badge bg-success">' . $status . '</span>';
                }

                $leadInquiryDetails = $leadModel->getLeadInquiryDetails($leads_product_inquiry_id);
                $product_id = $leadInquiryDetails['product_id'] ?? null;
                $unit_description = $leadInquiryDetails['unit_description'] ?? null;

                // Fetch supplier IDs from the relational table and lookup their names
                $supplierIds = $leadModel->getCifSuppliers($cif_id);
                $supplierNames = [];

                if (is_array($supplierIds)) {
                    foreach ($supplierIds as $supId) {
                        $supplierDetails = $supplierModel->getSupplier($supId);
                        if (!empty($supplierDetails['supplier_name'])) {
                            $supplierNames[] = $supplierDetails['supplier_name'];
                        }
                    }
                }
                
                // Join multiple supplier names with a comma
                $supplier_name_display = !empty($supplierNames) ? implode(', ', $supplierNames) : '';

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = !empty($productDetails['stock_number'] ?? '') ? $productDetails['stock_number'] . ' - ' : '';

                // Build Action Buttons dynamically
                $actions = '';

                // Show Update, For Approval, and Delete buttons ONLY if status is Draft
                if ($status === 'Draft') {
                    $actions .= '<button type="button" class="btn btn-icon btn-success update-cif" data-bs-toggle="modal" data-bs-target="#cif-modal" data-cif-id="' . $cif_id . '" title="Update CIF">
                                    <i class="ti ti-edit"></i>
                                </button>';
                
                    $actions .= '<button type="button" class="btn btn-icon btn-danger cancel-cif" data-cif-id="' . $cif_id . '" title="Cancel CIF">
                                    <i class="ti ti-x"></i>
                                </button>';

                    $actions .= '<button type="button" class="btn btn-icon btn-success on-process-cif" data-cif-id="' . $cif_id . '" title="On-Process CIF">
                                    <i class="ti ti-check"></i>
                                </button>';

                    $actions .= '<button type="button" class="btn btn-icon btn-danger delete-cif" data-cif-id="' . $cif_id . '" title="Delete CIF">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                if ($status === 'On-Process') {                
                    $actions .= '<button type="button" class="btn btn-icon btn-danger cancel-cif" data-cif-id="' . $cif_id . '" title="Cancel CIF">
                                    <i class="ti ti-x"></i>
                                </button>';

                    $actions .= '<button type="button" class="btn btn-icon btn-success complete-cif" data-cif-id="' . $cif_id . '" title="Complete CIF">
                                    <i class="ti ti-check"></i>
                                </button>';

                    $actions .= ' <a href="print-inquiry-cif.php?id=' . $cif_id . '" target="_blank" class="btn btn-icon btn-info print-quotation" title="Print CIF">
                                        <i class="ti ti-printer"></i>
                                    </a>';
                }

                if ($status === 'Completed') {
                    $actions .= ' <a href="print-inquiry-cif.php?id=' . $cif_id . '" target="_blank" class="btn btn-icon btn-info print-quotation" title="Print CIF">
                                        <i class="ti ti-printer"></i>
                                    </a>';
                }

                $response[] = [
                    'REFERENCE_NO' => $cif_reference_no,
                    'PRODUCT' => '<span class="text-wrap">' . $stock_number . $unit_description . '</span>',
                    'COMPANY' => $companyName,
                    'SUPPLIER' => '<span class="text-wrap">' . $supplier_name_display . '</span>',
                    'ADD_ON' => $add_ons,
                    'STATUS' => $statusBadge,
                    'REMARKS' => '<span class="text-wrap">' . $remarks . '</span>',
                    'ACTION' => '<div class="d-flex gap-2">' . $actions . '</div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'cif table 2':
            $filter_status = $_POST['filter_status'] ?? '';

            if (!empty($filter_status)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_status)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_status = implode(', ', $quoted_values_array);
            } else {
                $filter_status = null;
            }

            $thresholds = $leadModel->generateCIFTable($filter_status);
            $response = [];

            foreach ($thresholds as $row) {
                $cif_id = $row['cif_id'];
                $leads_product_inquiry_id = $row['leads_product_inquiry_id'];
                $company_id = $row['company_id'];
                $cif_reference_no = $row['cif_reference_no'];
                $add_ons = $row['add_ons'];
                $status = $row['status'];
                $remarks = $row['remarks'];
                $lead_id = $row['lead_id'];

                $file_as = $leadModel->getLead($lead_id)['file_as'] ?? null;

                $cifIDEncrypted = $securityModel->encryptData($cif_id);                

                $companyDetails = $companyModel->getCompany($company_id);
                $companyName = $companyDetails['company_name'] ?? '';

                if ($status === 'Draft') {
                    $statusBadge = '<span class="badge bg-secondary">' . $status . '</span>';
                } else if ($status === 'Cancelled') {
                    $statusBadge = '<span class="badge bg-warning">' . $status . '</span>';
                } else {
                    $statusBadge = '<span class="badge bg-success">' . $status . '</span>';
                }

                $leadInquiryDetails = $leadModel->getLeadInquiryDetails($leads_product_inquiry_id);
                $product_id = $leadInquiryDetails['product_id'] ?? null;
                $unit_description = $leadInquiryDetails['unit_description'] ?? null;

                // Fetch supplier IDs from the relational table and lookup their names
                $supplierIds = $leadModel->getCifSuppliers($cif_id);
                $supplierNames = [];

                if (is_array($supplierIds)) {
                    foreach ($supplierIds as $supId) {
                        $supplierDetails = $supplierModel->getSupplier($supId);
                        if (!empty($supplierDetails['supplier_name'])) {
                            $supplierNames[] = $supplierDetails['supplier_name'];
                        }
                    }
                }
                
                // Join multiple supplier names with a comma
                $supplier_name_display = !empty($supplierNames) 
                    ? implode('<br/>', array_map(function($name) {
                        return '<span class="badge bg-secondary">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</span>';
                    }, $supplierNames)) 
                    : '';

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = !empty($productDetails['stock_number'] ?? '') ? $productDetails['stock_number'] . ' - ' : '';

                // Build Action Buttons dynamically
                $actions = '';

                if ($status === 'On-Process') {
                    $actions .= '<button type="button" class="btn btn-icon btn-danger cancel-cif" data-cif-id="' . $cif_id . '" title="Cancel CIF">
                                    <i class="ti ti-x"></i>
                                </button>';

                    $actions .= '<button type="button" class="btn btn-icon btn-success submit-cif" data-cif-id="' . $cif_id . '" title="Submit CIF">
                                    <i class="ti ti-check"></i>
                                </button>';

                    $actions .= ' <a href="print-inquiry-cif.php?id=' . $cif_id . '" target="_blank" class="btn btn-icon btn-info print-quotation" title="Print CIF">
                                        <i class="ti ti-printer"></i>
                                    </a>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $cif_id .'">',
                    'REFERENCE_NO' => $cif_reference_no,
                    'CUSTOMER' => $file_as,
                    'PRODUCT' => '<span class="text-wrap">' . $stock_number . $unit_description . '</span>',
                    'COMPANY' => $companyName,
                    'SUPPLIER' => '<span class="text-wrap">' . $supplier_name_display . '</span>',
                    'ADD_ON' => $add_ons,
                    'STATUS' => $statusBadge,
                    'REMARKS' => '<span class="text-wrap">' . $remarks . '</span>',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="cif.php?id='. $cifIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                    ' . $actions . '</div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'spaf table':
            $userId = $_SESSION['user_id'];
            $filter_status = $_POST['filter_status'];

            if (!empty($filter_status)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_status)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_status = implode(', ', $quoted_values_array);
            } else {
                $filter_status = null;
            }

            $thresholds = $leadModel->generateSPAFTable($filter_status);

            $spafWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 210, 'write');
            $spafDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 210, 'delete');

            foreach ($thresholds as $row) {
                $spaf_id = $row['spaf_id'];
                $cif_id = $row['cif_id'];
                $spaf_reference_no = $row['spaf_reference_no'];
                $unit_price = $row['unit_price'] ?? 0;
                $dealer_discount = $row['dealer_discount'] ?? 0;
                $net_price = $row['net_price'] ?? 0;

                $cifDetails = $leadModel->getCIF($cif_id);
                $lead_id = $cifDetails['lead_id'] ?? null;
                $leads_product_inquiry_id = $cifDetails['leads_product_inquiry_id'] ?? null;

                $leadInquiryDetails = $leadModel->getLeadInquiryDetails($leads_product_inquiry_id);
                $product_id = $leadInquiryDetails['product_id'] ?? null;
                $unit_description = $leadInquiryDetails['unit_description'] ?? null;

                $productDetails = $productModel->getProduct($product_id);
                $stock_number = !empty($productDetails['stock_number'] ?? '') ? $productDetails['stock_number'] . ' - ' : '';

                $file_as = $leadModel->getLead($lead_id)['file_as'] ?? null;

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $spaf_id .'">',
                    'SPAF_REFERENCE' => $spaf_reference_no,
                    'CUSTOMER' => $file_as,
                    'PRODUCT' => '<span class="text-wrap">' . $stock_number . $unit_description . '</span>',
                    'UNIT_PRICE' => number_format($unit_price, 2),
                    'DEALER_DISCOUNT' => number_format($dealer_discount, 2),
                    'TOTAL' => number_format($net_price, 2),
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="print-inquiry-spaf.php?id=' . $spaf_id . '" target="_blank" class="btn btn-icon btn-info print-quotation" title="Print SPAF">
                                        <i class="ti ti-printer"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
        break;

        case 'quotations table':
            $quotations_status_filter = $_POST['filter_quotations_status'] ?? '';

            // Build SQL query dynamically based on whether the filter is populated
            if (!empty($quotations_status_filter)) {
                $values_array = array_filter(array_map('trim', explode(',', $quotations_status_filter)));
                
                if (!empty($values_array)) {
                    // Create parameter placeholders like :status_0, :status_1
                    $placeholders = [];
                    $params = [];
                    
                    foreach ($values_array as $index => $status) {
                        $key = ":status_" . $index;
                        $placeholders[] = $key;
                        $params[$key] = $status;
                    }
                    
                    $in_clause = implode(', ', $placeholders);
                    $where_clause = "WHERE status IN ($in_clause)";
                } else {
                    $where_clause = "";
                    $params = [];
                }
            } else {
                $where_clause = "";
                $params = [];
            }

            // Prepare and execute query with safe parameter binding
            $sql = $databaseModel->getConnection()->prepare("
                SELECT * 
                FROM quotations 
                {$where_clause}
                ORDER BY created_at DESC;
            ");

            $sql->execute($params);
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $response = [];

            foreach ($options as $row) {
                $quotation_id = $row['quotation_id'];
                $referenceNo = 'QT-' . str_pad($quotation_id, 6, '0', STR_PAD_LEFT);
                $leads_product_inquiry_id = $row['leads_product_inquiry_id'];
                $lead_id = $row['lead_id'];
                $price = $row['price'];
                $status = $row['status'];
                $remarks = $row['remarks'];
                $inclusions = $row['inclusions'];
                $company_id = $row['company_id'];
                $delivery_address = $row['delivery_address'];
                $created_at = $systemModel->checkDate('empty', $row['created_at'] ?? null, '', 'm/d/Y', '');
                $expiration_date = $systemModel->checkDate('empty', $row['expiration_date'] ?? null, '', 'm/d/Y', '');

                $leadDetails = $leadModel->getLead($lead_id);
                $user = $userModel->getUserByID($leadDetails['assigned_to']);
                $createdBy = $user['file_as'] ?? null;

                $companyDetails = $companyModel->getCompany($company_id);
                $companyName = $companyDetails['company_name'] ?? '';

                if ($status === 'Draft') {
                    $statusBadge = '<span class="badge bg-secondary">' . $status . '</span>';
                } else if ($status === 'For Approval') {
                    $statusBadge = '<span class="badge bg-warning">' . $status . '</span>';
                } else {
                    $statusBadge = '<span class="badge bg-success">' . $status . '</span>';
                }

                $leadInquiryDetails = $leadModel->getLeadInquiryDetails($leads_product_inquiry_id);
                $product_id = $leadInquiryDetails['product_id'] ?? null;
                $unit_description = $leadInquiryDetails['unit_description'] ?? null;

                $stock_number = !empty($productDetails['stock_number'] ?? '') ? $productDetails['stock_number'] . ' - ' : '';

                // Build Action Buttons dynamically
                $actions = '';

                // Show Update and Delete buttons ONLY if status is Draft
                if ($status === 'Draft') {
                    $actions .= '<button type="button" class="btn btn-icon btn-danger delete-quotation" data-quotation-id="' . $quotation_id . '" title="Delete Quotation">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                // Show Approve button ONLY if status is 'For Approval'
                if ($status === 'For Approval') {
                    $actions .= '<button type="button" class="btn btn-icon btn-success approve-quotation" data-quotation-id="' . $quotation_id . '" title="Approve Quotation">
                                    <i class="ti ti-check"></i>
                                </button>';
                }

                // Show Cancel button if status is 'Draft' or 'For Approval'
                if ($status === 'Draft' || $status === 'For Approval' || $status === 'For Checking') {
                    $actions .= '<button type="button" class="btn btn-icon btn-warning cancel-quotation" data-quotation-id="' . $quotation_id . '" title="Cancel Quotation">
                                    <i class="ti ti-x"></i>
                                </button>';
                }

                $response[] = [
                    'PRODUCT' => '<span class="text-wrap">' . $stock_number . $unit_description . '</span>',
                    'PRICE' => number_format($price, 2),
                    'REFERENCE_NO' => $referenceNo,
                    'COMPANY' => $companyName,
                    'STATUS' => $statusBadge,
                    'CREATED_BY' => $createdBy,
                    'EXPIRATION_DATE' => $expiration_date,
                    'CREATED_DATE' => $created_at,
                    'REMARKS' => '<span class="text-wrap">' . $remarks . '</span>',
                    'ACTION' => '<div class="d-flex gap-2">' . $actions . '
                                    <a href="print-inquiry-quotation.php?id=' . $quotation_id . '" target="_blank" class="btn btn-icon btn-info print-quotation" title="Print Quotation">
                                        <i class="ti ti-printer"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
            break;

        case 'quotations for checking table':
            
            // Prepare and execute query with safe parameter binding
            $sql = $databaseModel->getConnection()->prepare("
                SELECT * 
                FROM quotations 
                WHERE status = 'For Checking'
                ORDER BY created_at DESC;
            ");

            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $response = [];

            foreach ($options as $row) {
                $quotation_id = $row['quotation_id'];
                $referenceNo = 'QT-' . str_pad($quotation_id, 6, '0', STR_PAD_LEFT);
                $leads_product_inquiry_id = $row['leads_product_inquiry_id'];
                $lead_id = $row['lead_id'];
                $price = $row['price'];
                $status = $row['status'];
                $remarks = $row['remarks'];
                $inclusions = $row['inclusions'];
                $company_id = $row['company_id'];
                $delivery_address = $row['delivery_address'];
                $created_at = $systemModel->checkDate('empty', $row['created_at'] ?? null, '', 'm/d/Y', '');
                $expiration_date = $systemModel->checkDate('empty', $row['expiration_date'] ?? null, '', 'm/d/Y', '');

                $leadDetails = $leadModel->getLead($lead_id);
                $user = $userModel->getUserByID($leadDetails['assigned_to']);
                $createdBy = $user['file_as'] ?? null;

                $companyDetails = $companyModel->getCompany($company_id);
                $companyName = $companyDetails['company_name'] ?? '';

                if ($status === 'Draft') {
                    $statusBadge = '<span class="badge bg-secondary">' . $status . '</span>';
                } else if ($status === 'For Approval') {
                    $statusBadge = '<span class="badge bg-warning">' . $status . '</span>';
                } else {
                    $statusBadge = '<span class="badge bg-success">' . $status . '</span>';
                }

                $leadInquiryDetails = $leadModel->getLeadInquiryDetails($leads_product_inquiry_id);
                $product_id = $leadInquiryDetails['product_id'] ?? null;
                $unit_description = $leadInquiryDetails['unit_description'] ?? null;

                $stock_number = !empty($productDetails['stock_number'] ?? '') ? $productDetails['stock_number'] . ' - ' : '';

                // Build Action Buttons dynamically
                $actions = '';

                // Show Update and Delete buttons ONLY if status is Draft
                if ($status === 'Draft') {
                    $actions .= '<button type="button" class="btn btn-icon btn-danger delete-quotation" data-quotation-id="' . $quotation_id . '" title="Delete Quotation">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                // Show Cancel button if status is 'Draft' or 'For Approval'
                if ($status === 'For Checking') {
                    $actions .= '<button type="button" class="btn btn-icon btn-warning for-approval-quotation" data-quotation-id="' . $quotation_id . '" title="Send for Approval">
                                    <i class="ti ti-send"></i>
                                </button>';

                    $actions .= '<button type="button" class="btn btn-icon btn-success approve-quotation" data-quotation-id="' . $quotation_id . '" title="Approve Quotation">
                                    <i class="ti ti-check"></i>
                                </button>';
                }

                $response[] = [
                    'PRODUCT' => '<span class="text-wrap">' . $stock_number . $unit_description . '</span>',
                    'PRICE' => number_format($price, 2),
                    'REFERENCE_NO' => $referenceNo,
                    'COMPANY' => $companyName,
                    'STATUS' => $statusBadge,
                    'CREATED_BY' => $createdBy,
                    'EXPIRATION_DATE' => $expiration_date,
                    'CREATED_DATE' => $created_at,
                    'REMARKS' => '<span class="text-wrap">' . $remarks . '</span>',
                    'ACTION' => '<div class="d-flex gap-2">' . $actions . '
                                    <a href="print-inquiry-quotation.php?id=' . $quotation_id . '" target="_blank" class="btn btn-icon btn-info print-quotation" title="Print Quotation">
                                        <i class="ti ti-printer"></i>
                                    </a>
                                </div>'
                ];
            }

            echo json_encode($response);
            break;

        case 'lead monitoring dashboard list':
            // Default to current date if filter date is not provided
            $filter_lead_date = !empty($_POST['filter_lead_date']) 
                ? $systemModel->checkDate('empty', $_POST['filter_lead_date'], '', 'Y-m-d', '') 
                : date('Y-m-d');

            // Using 5 positional parameters (?) for each date comparison
            $query = "
                SELECT 
                    u.user_id,
                    u.file_as,
                    u.profile_picture,
                    COUNT(DISTINCT CASE WHEN DATE(l.created_at) = ? THEN l.lead_id END) AS new_leads_count,
                    COUNT(DISTINCT CASE WHEN DATE(l.created_at) = ? AND l.lead_priority = 'Hot' THEN l.lead_id END) AS hot_leads_count,
                    COUNT(DISTINCT CASE WHEN DATE(l.created_at) = ? AND l.lead_priority = 'Warm' THEN l.lead_id END) AS warm_leads_count,
                    COUNT(DISTINCT CASE WHEN DATE(l.created_at) = ? AND l.lead_priority = 'Cold' THEN l.lead_id END) AS cold_leads_count,
                    COUNT(DISTINCT CASE WHEN DATE(n.created_at) = ? AND n.deleted_at IS NULL THEN n.lead_note_id END) AS notes_added_count
                FROM users u
                INNER JOIN leads l ON l.assigned_to = u.user_id
                LEFT JOIN lead_notes n ON n.created_by = u.user_id
                GROUP BY u.user_id, u.file_as, u.profile_picture
                ORDER BY u.file_as ASC
            ";

            $stmt = $databaseModel->getConnection()->prepare($query);
            
            // Pass the date array directly into execute() matching the 5 positional parameters
            $stmt->execute([
                $filter_lead_date, // 1st ? -> Total leads
                $filter_lead_date, // 2nd ? -> Hot leads
                $filter_lead_date, // 3rd ? -> Warm leads
                $filter_lead_date, // 4th ? -> Cold leads
                $filter_lead_date  // 5th ? -> Notes added
            ]);
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $list = '';

            foreach ($results as $row) {
                $user_id = $row['user_id'];
                $file_as = htmlspecialchars($row['file_as']);
                $new_leads = (int)$row['new_leads_count'];
                $hot_leads = (int)$row['hot_leads_count'];
                $warm_leads = (int)$row['warm_leads_count'];
                $cold_leads = (int)$row['cold_leads_count'];
                $notes_added = (int)$row['notes_added_count'];
                
                // Check if total activity is less than 5
                $total_activity = $new_leads + $notes_added;
                $is_low_activity = ($total_activity < 5);

                // Apply highlight styles (light red background, border, and padding for soft warning visual)
                $highlight_class = $is_low_activity ? 'bg-light-danger border border-danger rounded p-2' : '';
                
                // Handle avatar fallback using your system model
                $userImage = $systemModel->checkImage($row['profile_picture'], 'profile');

                $list .= '
                <div class="align-middle m-b-25 d-flex align-items-center justify-content-between flex-wrap ' . $highlight_class . '">
                    <div class="d-flex align-items-center m-b-10">
                        <img src="' . $userImage . '" alt="user image" class="img-radius align-top m-r-15" style="width:40px; height:40px; object-fit:cover;">
                        <div>
                            <h6>' . $file_as . '</h6>
                            ' . ($is_low_activity ? '<small class="text-danger font-weight-bold"><i class="feather icon-alert-circle m-r-5"></i>Low Activity (< 5)</small>' : '') . '
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="m-b-5">
                            <span class="badge badge-light-primary m-r-5 fs-6" title="Total New Leads Today">
                                <i class="feather icon-user-plus m-r-5"></i>' . number_format($new_leads, 0) . ' Total Leads
                            </span>
                            <span class="badge badge-light-secondary fs-6" title="Notes Added Today">
                                <i class="feather icon-file-text m-r-5"></i>' . number_format($notes_added, 0) . ' Notes
                            </span>
                        </div>
                        <div class="d-none">
                            <span class="badge badge-light-danger text-danger m-r-5 fs-7" title="Hot Leads Today">
                                <i class="feather icon-zap m-r-5"></i>' . number_format($hot_leads, 0) . ' Hot
                            </span>
                            <span class="badge badge-light-warning text-warning m-r-5 fs-7" title="Warm Leads Today">
                                <i class="feather icon-sun m-r-5"></i>' . number_format($warm_leads, 0) . ' Warm
                            </span>
                            <span class="badge badge-light-info text-info m-r-5 fs-7" title="Cold Leads Today">
                                <i class="feather icon-thermometer m-r-5"></i>' . number_format($cold_leads, 0) . ' Cold
                            </span>
                        </div>
                    </div>
                </div>';
            }

            if (empty($list)) {
                $list = '<h6 class="text-center pb-5">No Assigned Leads Found</h6>';
            }

            echo json_encode(['LIST' => $list]);
            break;

        case 'lead note table':
                $leadID = htmlspecialchars($_POST['lead_id'], ENT_QUOTES, 'UTF-8');

                $notes = $leadModel->generateLeadNotes($leadID);

                $leadNoteDeleteAccess = $userModel->checkMenuItemAccessRights(
                    $user_id,
                    191,
                    'delete'
                );

                foreach ($notes as $row) {

                    $leadNoteID = $row['lead_note_id'];

                    $delete = '';

                    if($leadNoteDeleteAccess['total'] > 0){

                        $delete = '<button type="button"
                                            class="btn btn-sm btn-outline-danger delete-lead-note"
                                            data-lead-note-id="'. $leadNoteID .'">

                                        <i class="ti ti-trash"></i>

                                    </button>';
                    }

                    $response[] = '

                        <div class="border rounded p-3 mb-3">

                            <div class="d-flex justify-content-between align-items-start">

                                <div>

                                    <div class="fw-semibold text-light">
                                        '. htmlspecialchars($row['file_as']) .'
                                    </div>

                                    <small class="text-muted">
                                        '. date('M d, Y h:i A', strtotime($row['created_at'])) .'
                                    </small>

                                </div>

                                '. $delete .'

                            </div>

                            <hr class="my-3">

                            <div class="text-wrap"
                                style="white-space: pre-wrap; line-height: 1.6;">

                                '. nl2br(htmlspecialchars($row['note'])) .'

                            </div>

                        </div>

                    ';
                }

                echo implode('', $response);

            break;
    }
}
?>