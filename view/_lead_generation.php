<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/lead-model.php';
require_once '../model/product-model.php';
require_once '../model/inquiry-type-model.php';
require_once '../model/lead-status-model.php';
require_once '../model/lead-source-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$leadModel = new LeadModel($databaseModel);
$inquiryTypeModel = new InquiryTypeModel($databaseModel);
$leadStatusModel = new LeadStatusModel($databaseModel);
$leadSourceModel = new LeadSourceModel($databaseModel);
$productModel = new ProductModel($databaseModel);
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
                $leads = $leadModel->generateAllLeadTable($filter_created_date_start_date, $filter_created_date_end_date, $filter_inquiry_date_start_date, $filter_inquiry_date_end_date, $filter_lead_status, $filter_inquiry_type, $filter_lead_source, $filter_lead_priority);
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
                    'LEAD_NOTE' => $lead_note,
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