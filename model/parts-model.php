<?php
/**
* Class PartsModel
*
* The PartsModel class handles parts related operations and interactions.
*/
class PartsModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    public function updateParts($p_part_id, $p_part_category_id, $p_part_class_id, $p_part_subclass_id, $p_description, $p_bar_code, $p_part_number, $p_company_id, $p_unit_sale, $p_stock_alert, $p_product_price, $p_quantity, $p_brand_id, $p_warehouse_id, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateParts(:p_part_id, :p_part_category_id, :p_part_class_id, :p_part_subclass_id, :p_description, :p_bar_code, :p_part_number, :p_company_id, :p_unit_sale, :p_stock_alert, :p_product_price, :p_quantity, :p_brand_id, :p_warehouse_id, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_part_id', $p_part_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_part_category_id', $p_part_category_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_class_id', $p_part_class_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_subclass_id', $p_part_subclass_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_description', $p_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_bar_code', $p_bar_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_number', $p_part_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_sale', $p_unit_sale, PDO::PARAM_INT);
        $stmt->bindValue(':p_stock_alert', $p_stock_alert, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_price', $p_product_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_quantity', $p_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_brand_id', $p_brand_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsImage
    # Description: Updates the parts image.
    #
    # Parameters:
    # - $p_parts_id (int): The parts ID.
    # - $p_parts_image (string): The parts category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePartsImage($p_parts_id, $p_parts_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsImage(:p_parts_id, :p_parts_image, :p_last_log_by)');
        $stmt->bindValue(':p_parts_id', $p_parts_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_parts_image', $p_parts_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    public function insertPartsImage($p_parts_id, $p_parts_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsImage(:p_parts_id, :p_parts_image, :p_last_log_by)');
        $stmt->bindValue(':p_parts_id', $p_parts_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_parts_image', $p_parts_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    # -------------------------------------------------------------
    public function insertPartsDocument($p_parts_id, $p_document_type, $p_parts_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsDocument(:p_parts_id, :p_document_type, :p_parts_image, :p_last_log_by)');
        $stmt->bindValue(':p_parts_id', $p_parts_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_type', $p_document_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_parts_image', $p_parts_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    public function updatePartsStatus($p_parts_id, $p_parts_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsStatus(:p_parts_id, :p_parts_status, :p_last_log_by)');
        $stmt->bindValue(':p_parts_id', $p_parts_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_parts_status', $p_parts_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    public function insertPartsExpense($p_parts_id, $p_reference_type, $p_reference_number, $p_expense_amount, $p_expense_type, $p_particulars, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsExpense(:p_parts_id, :p_reference_type, :p_reference_number, :p_expense_amount, :p_expense_type, :p_particulars, :p_last_log_by)');
        $stmt->bindValue(':p_parts_id', $p_parts_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_type', $p_reference_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_amount', $p_expense_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_expense_type', $p_expense_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertParts
    # Description: Inserts the parts.
    #
    # Parameters:
    # - $p_parts_category_id (int): The parts category ID.
    # - $p_parts_subcategory_id (int): The parts subcategory ID.
    # - $p_company_id (int): The company ID.
    # - $p_stock_number (string): The stock number.
    # - $p_engine_number (string): The engine number.
    # - $p_chassis_number (string): The chassis number.
    # - $p_plate_number (string): The chassis number.
    # - $p_description (string): The parts description.
    # - $p_warehouse_id (int): The warehouse ID.
    # - $p_body_type_id (int): The body type ID.
    # - $p_length (double): The length.
    # - $p_length_unit (int): The length unit.
    # - $p_running_hours (double): The running hours.
    # - $p_mileage (double): The mileage.
    # - $p_color_id (int): The color ID.
    # - $p_parts_cost (double): The cost of the parts.
    # - $p_parts_price (double): The price of the parts.
    # - $p_remarks (string): The remarks.
    # - $p_last_log_by (int): The last logged user.
    #
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertParts($p_part_category_id, $p_part_class_id, $p_part_subclass_id, $p_description, $p_bar_code, $p_part_number, $p_company_id, $p_unit_sale, $p_stock_alert, $p_quantity, $p_brand_id, $p_warehouse_id, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertParts(:p_part_category_id, :p_part_class_id, :p_part_subclass_id, :p_description, :p_bar_code, :p_part_number, :p_company_id, :p_unit_sale, :p_stock_alert, :p_quantity, :p_brand_id, :p_warehouse_id, :p_remarks, :p_last_log_by, @p_part_id)');
        $stmt->bindValue(':p_part_category_id', $p_part_category_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_class_id', $p_part_class_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_subclass_id', $p_part_subclass_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_description', $p_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_bar_code', $p_bar_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_part_number', $p_part_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_sale', $p_unit_sale, PDO::PARAM_INT);
        $stmt->bindValue(':p_stock_alert', $p_stock_alert, PDO::PARAM_STR);
        $stmt->bindValue(':p_quantity', $p_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_brand_id', $p_brand_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_part_id AS p_part_id");
        $p_part_id = $result->fetch(PDO::FETCH_ASSOC)['p_part_id'];

        return $p_part_id;
    }
    # -------------------------------------------------------------

  

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPartsExist
    # Description: Checks if a parts exists.
    #
    # Parameters:
    # - $p_parts_id (int): The parts ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPartsExist($p_parts_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsExist(:p_parts_id)');
        $stmt->bindValue(':p_parts_id', $p_parts_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    public function checkPartsImageExist($p_parts_image_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsImageExist(:p_parts_image_id)');
        $stmt->bindValue(':p_parts_image_id', $p_parts_image_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    public function checkPartsDocumentExist($p_parts_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsDocumentExist(:p_parts_document_id)');
        $stmt->bindValue(':p_parts_document_id', $p_parts_document_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteParts
    # Description: Deletes the parts.
    #
    # Parameters:
    # - $p_parts_id (int): The parts ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteParts($p_parts_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteParts(:p_parts_id)');
        $stmt->bindValue(':p_parts_id', $p_parts_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    # -------------------------------------------------------------
    public function deletePartsImage($p_parts_image_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsImage(:p_parts_image_id)');
        $stmt->bindValue(':p_parts_image_id', $p_parts_image_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    public function deletePartsExpense($p_parts_expense_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsExpense(:p_parts_expense_id)');
        $stmt->bindValue(':p_parts_expense_id', $p_parts_expense_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    # -------------------------------------------------------------
    public function deletePartsDocument($p_parts_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsDocument(:p_parts_document_id)');
        $stmt->bindValue(':p_parts_document_id', $p_parts_document_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getParts
    # Description: Retrieves the details of a parts.
    #
    # Parameters:
    # - $p_parts_id (int): The parts ID.
    #
    # Returns:
    # - An array containing the parts details.
    #
    # -------------------------------------------------------------
    public function getParts($p_parts_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getParts(:p_parts_id)');
        $stmt->bindValue(':p_parts_id', $p_parts_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
    # -------------------------------------------------------------
    public function getPartsImage($p_parts_image_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsImage(:p_parts_image_id)');
        $stmt->bindValue(':p_parts_image_id', $p_parts_image_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
    # -------------------------------------------------------------
    public function getPartsDocument($p_parts_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsDocument(:p_parts_document_id)');
        $stmt->bindValue(':p_parts_document_id', $p_parts_document_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsStatus
    # Description: Retrieves the parts status badge.
    #
    # Parameters:
    # - $p_parts_status (string): The parts status.
    #
    # Returns:
    # - An array containing the parts details.
    #
    # -------------------------------------------------------------
    public function getPartsStatus($p_parts_status, $company_id) {
        if($company_id == 1){
            $statusClasses = [
                'Draft' => 'info',
                'Available' => 'success',
                'Out of Stock' => 'danger'
            ];
        }
        else{
            $statusClasses = [
                'Draft' => 'info',
                'For Sale' => 'success',
                'Out of Stock' => 'danger'
            ];
        }
        
        $defaultClass = 'dark';
        
        $class = $statusClasses[$p_parts_status] ?? $defaultClass;
        
        return '<span class="badge bg-' . $class . '">' . $p_parts_status . '</span>';
    }
    # -------------------------------------------------------------

    
    # -------------------------------------------------------------
    public function getTotalPartsCost($p_parts_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getTotalPartsCost(:p_parts_id)');
        $stmt->bindValue(':p_parts_id', $p_parts_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsOptions
    # Description: Generates the parts options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsID = $row['parts_id'];
            $description = $row['description'];
            $stockNumber = $row['stock_number'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsID, ENT_QUOTES) . '">' . htmlspecialchars($stockNumber, ENT_QUOTES) .' - '. htmlspecialchars($description, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateInStockPartsOptions
    # Description: Generates the parts options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateInStockPartsOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateInStockPartsOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsID = $row['parts_id'];
            $description = $row['description'];
            $stockNumber = $row['stock_number'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsID, ENT_QUOTES) . '">' . htmlspecialchars($stockNumber, ENT_QUOTES) .' - '. htmlspecialchars($description, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    public function generateAllPartsOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateAllPartsOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsID = $row['parts_id'];
            $description = $row['description'];
            $stockNumber = $row['stock_number'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsID, ENT_QUOTES) . '">' . htmlspecialchars($stockNumber, ENT_QUOTES) .' - '. htmlspecialchars($description, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    public function generateNotDraftPartsOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateNotDraftPartsOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsID = $row['parts_id'];
            $description = $row['description'];
            $stockNumber = $row['stock_number'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsID, ENT_QUOTES) . '">' . htmlspecialchars($stockNumber, ENT_QUOTES) .' - '. htmlspecialchars($description, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>