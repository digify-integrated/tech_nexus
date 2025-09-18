<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/parts-model.php';
require_once '../model/parts-category-model.php';
require_once '../model/parts-class-model.php';
require_once '../model/parts-subclass-model.php';
require_once '../model/company-model.php';
require_once '../model/supplier-model.php';
require_once '../model/warehouse-model.php';
require_once '../model/brand-model.php';
require_once '../model/unit-model.php';
require_once '../model/system-setting-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$partsModel = new PartsModel($databaseModel);
$partsClassModel = new PartsClassModel($databaseModel);
$partsCategoryModel = new PartsCategoryModel($databaseModel);
$partsSubclassModel = new PartsSubclassModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
$supplierModel = new SupplierModel($databaseModel);
$warehouseModel = new WarehouseModel($databaseModel);
$brandModel = new BrandModel($databaseModel);
$unitModel = new UnitModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        case 'parts table':
            $parts_search = htmlspecialchars($_POST['parts_search'], ENT_QUOTES, 'UTF-8');
            $company_id = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
                $company_filter = htmlspecialchars($_POST['company_filter'], ENT_QUOTES, 'UTF-8');
                $brand_filter = htmlspecialchars($_POST['brand_filter'], ENT_QUOTES, 'UTF-8');
                $parts_category_filter = htmlspecialchars($_POST['parts_category_filter'], ENT_QUOTES, 'UTF-8');
                $parts_class_filter = htmlspecialchars($_POST['parts_class_filter'], ENT_QUOTES, 'UTF-8');
                $parts_subclass_filter = htmlspecialchars($_POST['parts_subclass_filter'], ENT_QUOTES, 'UTF-8');
                $warehouse_filter = htmlspecialchars($_POST['warehouse_filter'], ENT_QUOTES, 'UTF-8');
                $filter_created_date_start_date = $systemModel->checkDate('empty', $_POST['filter_created_date_start_date'], '', 'Y-m-d', '');
                $filter_created_date_end_date = $systemModel->checkDate('empty', $_POST['filter_created_date_end_date'], '', 'Y-m-d', '');
                $filter_for_sale_date_start_date = $systemModel->checkDate('empty', $_POST['filter_for_sale_date_start_date'], '', 'Y-m-d', '');
                $filter_for_sale_date_end_date = $systemModel->checkDate('empty', $_POST['filter_for_sale_date_end_date'], '', 'Y-m-d', '');
                
                if(empty($_POST['parts_status_filter'])){
                    $partsStatusFilter = null;
                }
                else{
                    $partsStatusFilter = $_POST['parts_status_filter'] ?? null;
                }

                $sql = $databaseModel->getConnection()->prepare('
                    CALL generatePartsTable(
                        :company_filter, 
                        :parts_search, 
                        :brand_filter, 
                        :parts_category_filter, 
                        :parts_class_filter, 
                        :warehouse_filter, 
                        :parts_subclass_filter, 
                        :filter_created_date_start_date, 
                        :filter_created_date_end_date, 
                        :filter_for_sale_date_start_date, 
                        :filter_for_sale_date_end_date, 
                        :partsStatusFilter
                    )
                ');

                $sql->bindValue(':company_filter', $company_filter, PDO::PARAM_STR);
                $sql->bindValue(':parts_search', $parts_search, PDO::PARAM_STR);
                $sql->bindValue(':brand_filter', $brand_filter, PDO::PARAM_STR);
                $sql->bindValue(':parts_category_filter', $parts_category_filter, PDO::PARAM_STR);
                $sql->bindValue(':parts_class_filter', $parts_class_filter, PDO::PARAM_STR);
                $sql->bindValue(':warehouse_filter', $warehouse_filter, PDO::PARAM_STR);
                $sql->bindValue(':parts_subclass_filter', $parts_subclass_filter, PDO::PARAM_STR);
                $sql->bindValue(':filter_created_date_start_date', $filter_created_date_start_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_created_date_end_date', $filter_created_date_end_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_for_sale_date_start_date', $filter_for_sale_date_start_date, PDO::PARAM_STR);
                $sql->bindValue(':filter_for_sale_date_end_date', $filter_for_sale_date_end_date, PDO::PARAM_STR);
                $sql->bindValue(':partsStatusFilter', $partsStatusFilter, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $partsDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 67, 'delete');
                
                foreach ($options as $row) {
                    $partsID = $row['part_id'];
                    $part_category_id = $row['part_category_id'];
                    $part_class_id = $row['part_class_id'];
                    $part_subclass_id = $row['part_subclass_id'];
                    $description = $row['description'];
                    $bar_code = $row['bar_code'];
                    $quantity = $row['quantity'];
                    $part_price = $row['part_price'];
                    $part_cost = $row['part_cost'];
                   
                    $partsStatus = $partsModel->getPartsStatus($row['part_status'], $company_id);
                    $partsImage = $systemModel->checkImage($row['part_image'], 'default');

                    $partCategoryDetails = $partsCategoryModel->getPartsCategory($part_category_id);
                    $part_category_name = $partCategoryDetails['part_category_name'] ?? null;

                    $partClassDetails = $partsClassModel->getPartsClass($part_class_id);
                    $part_class_name = $partClassDetails['part_class_name'] ?? null;

                    $partSubclassDetails = $partsSubclassModel->getPartsSubclass($part_subclass_id);
                    $part_subclass_name = $partSubclassDetails['part_subclass_name'] ?? null;
                   
                    $partsIDEncrypted = $securityModel->encryptData($partsID);

                    $delete = '';
                    if($partsDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-parts" data-parts-id="'. $partsID .'" title="Delete Parts">
                                    <i class="ti ti-trash"></i>
                                </button>';
                    }

                    if($company_id == '1'){
                        $link = 'supplies';
                    }
                    else if($company_id == '2'){
                        $link = 'netruck-parts';
                    }
                    else{
                        $link = 'parts';
                    }

                    if(empty($bar_code)){
                        $bar_code = $partsID;
                    }
    
                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $partsID .'">',
                        'IMAGE' => '<a href="'. $partsImage .'" target="_blank">View Image</a>',
                        'PART' => '<div class="row">
                                        <div class="col">
                                            <h6 class="mb-0">'. $bar_code .'</h6>
                                            <p class="f-12 mb-0 text-wrap">'. $description .'</p>
                                        </div>
                                    </div>',
                        'CATEGORY' => $part_category_name,
                        'CLASS' => $part_class_name . ' <br/>(' . $part_subclass_name . ')',
                        'QUANTITY' => number_format($quantity, 2),
                        'PRODUCT_COST' => number_format($part_cost,2) . ' PHP',
                        'PRODUCT_PRICE' => number_format($part_price,2) . ' PHP',
                        'PRODUCT_STATUS' => $partsStatus,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="'. $link .'.php?id='. $partsIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details" target="_blank">
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
        case 'parts expense table':
            $partsID = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');
            $referenceTypeFilter = htmlspecialchars($_POST['reference_type_filter'], ENT_QUOTES, 'UTF-8');
            $expenseTypeFilter = htmlspecialchars($_POST['expense_type_filter'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generatePartsExpenseTable(:partsID, :referenceTypeFilter, :expenseTypeFilter)');
                $sql->bindValue(':partsID', $partsID, PDO::PARAM_INT);
                $sql->bindValue(':referenceTypeFilter', $referenceTypeFilter, PDO::PARAM_STR);
                $sql->bindValue(':referenceTypeFilter', $referenceTypeFilter, PDO::PARAM_STR);
                $sql->bindValue(':expenseTypeFilter', $expenseTypeFilter, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $deletePartsExpense = $userModel->checkSystemActionAccessRights($user_id, 174);
                
                foreach ($options as $row) {
                    $partsExpenseID = $row['part_expense_id'];
                    $reference_type = $row['reference_type'];
                    $reference_number = $row['reference_number'];
                    $expenseType = $row['expense_type'];
                    $particulars = $row['particulars'];
                    $expense_amount = number_format($row['expense_amount'], 2);

                    $createdDate = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y h:i:s A', '');

                    $delete = '';
                    if($deletePartsExpense['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-parts-expense" data-parts-expense-id="'. $partsExpenseID .'" title="Delete Parts Expense">
                                    <i class="ti ti-trash"></i>
                                </button>';
                    }
    
                    $response[] = [
                        'CREATED_DATE' => $createdDate,
                        'REFERENCE_TYPE' => $reference_type,
                        'REFERENCE_NUMBER' => $reference_number,
                        'EXPENSE_AMOUNT' => $expense_amount,
                        'PARTICULARS' => $particulars,
                        'EXPENSE_TYPE' => $expenseType,
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $delete .'
                                    </div>'
                    ];
                }
    
                echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        case 'parts expense table2':
            $partsID = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');
            $referenceTypeFilter = htmlspecialchars($_POST['reference_type_filter'], ENT_QUOTES, 'UTF-8');
            $expenseTypeFilter = htmlspecialchars($_POST['expense_type_filter'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generatePartsExpenseTable2(:referenceTypeFilter, :expenseTypeFilter)');
                $sql->bindValue(':referenceTypeFilter', $referenceTypeFilter, PDO::PARAM_STR);
                $sql->bindValue(':referenceTypeFilter', $referenceTypeFilter, PDO::PARAM_STR);
                $sql->bindValue(':expenseTypeFilter', $expenseTypeFilter, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $deletePartsExpense = $userModel->checkSystemActionAccessRights($user_id, 174);
                
                foreach ($options as $row) {
                    $partsID = $row['parts_id'];
                    $partsExpenseID = $row['parts_expense_id'];
                    $reference_type = $row['reference_type'];
                    $reference_number = $row['reference_number'];
                    $expenseType = $row['expense_type'];
                    $particulars = $row['particulars'];
                    $expense_amount = number_format($row['expense_amount'], 2);

                    $partsDetails = $partsModel->getParts($partsID);
                    $stockNumber = $partsDetails['stock_number'];
                    $description = $partsDetails['description'];

                    $partsIDEncrypted = $securityModel->encryptData($partsID);

                    $createdDate = $systemModel->checkDate('summary', $row['created_date'], '', 'm/d/Y h:i:s A', '');

                    $delete = '';
                    if($deletePartsExpense['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-parts-expense" data-parts-expense-id="'. $partsExpenseID .'" title="Delete Parts Expense">
                                    <i class="ti ti-trash"></i>
                                </button>';
                    }
    
                    $response[] = [
                        'PRODUCT' => ' <a href="parts.php?id='. $partsIDEncrypted .'"><b>' .$stockNumber . '</b><br/><small class="text-wrap">' . $description .'</small></a>',
                        'CREATED_DATE' => $createdDate,
                        'REFERENCE_TYPE' => $reference_type,
                        'REFERENCE_NUMBER' => $reference_number,
                        'EXPENSE_AMOUNT' => $expense_amount,
                        'PARTICULARS' => '<p class="text-wrap">'.$particulars.'</p>',
                        'EXPENSE_TYPE' => $expenseType,
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $delete .'
                                    </div>'
                    ];
                }
    
                echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        case 'parts document table':
            $partsID = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generatePartsDocument(:partsID)');
                $sql->bindValue(':partsID', $partsID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $deletePartsExpense = $userModel->checkSystemActionAccessRights($user_id, 174);
                $previewPartsDocument = $userModel->checkSystemActionAccessRights($user_id, 186);
                
                foreach ($options as $row) {
                    $partsDocumentID = $row['part_document_id'];
                    $parts_document_type = $row['part_document_type'];
                    $document_path = $row['document_path'];

                    $delete = '';
                    if($deletePartsExpense['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-parts-document" data-parts-document-id="'. $partsDocumentID .'" title="Delete Parts Expense">
                                    <i class="ti ti-trash"></i>
                                </button>';
                    }

                    if($previewPartsDocument['total'] > 0){
                        $documentType = '<a href="'. $document_path .'" target="_blank">' . $parts_document_type . "</a>";
                    }
                    else{
                        $documentType = $parts_document_type;
                    }
    
                    $response[] = [
                        'DOCUMENT_TYPE' => $documentType,
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $delete .'
                                    </div>'
                    ];
                }
    
                echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: import parts table
        # Description:
        # Generates the import parts table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'import parts table':
            if(isset($_POST['parts_category_filter']) && isset($_POST['parts_subcategory_filter']) && isset($_POST['company_filter']) && isset($_POST['warehouse_filter']) && isset($_POST['body_type_filter'])){
                $companyFilter = htmlspecialchars($_POST['company_filter'], ENT_QUOTES, 'UTF-8');
                $partsCategoryFilter = htmlspecialchars($_POST['parts_category_filter'], ENT_QUOTES, 'UTF-8');
                $partsSubcategoryFilter = htmlspecialchars($_POST['parts_subcategory_filter'], ENT_QUOTES, 'UTF-8');
                $warehouseFilter = htmlspecialchars($_POST['warehouse_filter'], ENT_QUOTES, 'UTF-8');
                $bodyTypeFilter = htmlspecialchars($_POST['body_type_filter'], ENT_QUOTES, 'UTF-8');
                $colorFilter = htmlspecialchars($_POST['color_filter'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateImportPartsTable(:partsCategoryFilter, :partsSubcategoryFilter, :companyFilter, :warehouseFilter, :bodyTypeFilter, :colorFilter)');
                $sql->bindValue(':partsCategoryFilter', $partsCategoryFilter, PDO::PARAM_STR);
                $sql->bindValue(':partsSubcategoryFilter', $partsSubcategoryFilter, PDO::PARAM_STR);
                $sql->bindValue(':companyFilter', $companyFilter, PDO::PARAM_STR);
                $sql->bindValue(':warehouseFilter', $warehouseFilter, PDO::PARAM_STR);
                $sql->bindValue(':bodyTypeFilter', $bodyTypeFilter, PDO::PARAM_STR);
                $sql->bindValue(':colorFilter', $colorFilter, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $tempPartsID = $row['temp_parts_id'];
                    $partsID = $row['parts_id'];
                    $partsCategoryID = $row['parts_category_id'];
                    $partsSubcategoryID = $row['parts_subcategory_id'];
                    $companyID = $row['company_id'];
                    $partsStatus = $row['parts_status'];
                    $stockNumber = $row['stock_number'];
                    $engineNumber = $row['engine_number'];
                    $chassisNumber = $row['chassis_number'];
                    $plateNumber = $row['plate_number'];
                    $description = $row['description'];
                    $warehouseID = $row['warehouse_id'];
                    $bodyTypeID = $row['body_type_id'];
                    $length = $row['length'];
                    $lengthUnit = $row['length_unit'];
                    $runningHours = $row['running_hours'];
                    $mileage = $row['mileage'];
                    $colorID = $row['color_id'];
                    $partsCost = $row['parts_cost'];
                    $partsPrice = $row['parts_price'];
                    $remarks = $row['remarks'];

                    $partsCategoryDetails = $partsCategoryModel->getPartsCategory($partsCategoryID);
                    $partsCategoryName = $partsCategoryDetails['parts_category_name'] ?? null;

                    $partsSubcategoryDetails = $partsSubcategoryModel->getPartsSubcategory($partsCategoryID);
                    $partsSubcategoryName = $partsSubcategoryDetails['parts_subcategory_name'] ?? null;

                    $companyDetails = $companyModel->getcompany($companyID);
                    $companyName = $companyDetails['company_name'] ?? null;

                    $warehouseDetails = $warehouseModel->getWarehouse($warehouseID);
                    $warehouseName = $warehouseDetails['warehouse_name'] ?? null;

                    $bodyTypeDetails = $bodyTypeModel->getBodyType($bodyTypeID);
                    $bodyTypeName = $bodyTypeDetails['body_type_name'] ?? null;

                    $unitDetails = $unitModel->getUnit($lengthUnit);
                    $unitShortName = $unitDetails['short_name'] ?? null;

                    $colorDetails = $colorModel->getColor($colorID);
                    $colorName = $colorDetails['color_name'] ?? null;

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $tempPartsID .'">',
                        'PRODUCT_ID' => $partsID,
                        'PRODUCT_CATEGORY' => $partsCategoryName,
                        'PRODUCT_SUBCATEGORY' => $partsSubcategoryName,
                        'COMPANY_NAME' => $companyName,
                        'PRODUCT_STATUS' => $partsStatus,
                        'STOCK_NUMBER' => $stockNumber,
                        'ENGINE_NUMBER' => $engineNumber,
                        'CHASSIS_NUMBER' => $chassisNumber,
                        'PLATE_NUMBER' => $plateNumber,
                        'DESCRIPTION' => $description,
                        'WAREHOUSE_NAME' => $warehouseName,
                        'BODY_TYPE_NAME' => $bodyTypeName,
                        'LENGTH' => $length . ' ' . $unitShortName,
                        'RUNNING_HOURS' => $runningHours,
                        'MILEAGE' => $mileage,
                        'COLOR' => $colorName,
                        'PRODUCT_COST' => number_format($partsCost, 2),
                        'PRODUCT_PRICE' => number_format($partsPrice, 2),
                        'REMARKS' => $remarks
                    ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: import parts table
        # Description:
        # Generates the import parts table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'parts image cards':
            if(isset($_POST['parts_id']) && isset($_POST['parts_id'])){
                $partsID = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generatePartsImage(:partsID)');
                $sql->bindValue(':partsID', $partsID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $partsImage = '';
                foreach ($options as $row) {
                    $parts_image_id = $row['part_image_id'];
                    $parts_image = $row['part_image'];

                    $partsImage .= '<div class="col-sm-auto text-center">
                                        <div class="position-relative me-3 d-inline-flex">
                                        <div class="position-absolute top-50 start-100 translate-middle">
                                            <button class="btn btn-sm btn-primary btn-icon delete-parts-image" data-parts-image-id="'. $parts_image_id .'"><i class="ti ti-trash"></i></button>
                                        </div>
                                        <img src="'. $parts_image .'" alt="user-image" class="wid-80 rounded img-fluid ms-2">
                                        </div>
                                    </div>';

                   
                }

                if(empty($partsImage)){
                    $partsImage = 'No Other Images Found.';
                }

                $response[] = [
                    'OTHER_IMAGE' => $partsImage
                ];

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>