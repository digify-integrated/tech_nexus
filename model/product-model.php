<?php
/**
* Class ProductModel
*
* The ProductModel class handles product related operations and interactions.
*/
class ProductModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateProduct
    # Description: Updates the product.
    #
    # Parameters:
    # - $p_product_id (int): The product ID.
    # - $p_product_category_id (int): The product category ID.
    # - $p_product_subcategory_id (int): The product subcategory ID.
    # - $p_company_id (int): The company ID.
    # - $p_stock_number (string): The stock number.
    # - $p_engine_number (string): The engine number.
    # - $p_chassis_number (string): The chassis number.
    # - $p_plate_number (string): The plate number.
    # - $p_description (string): The product description.
    # - $p_warehouse_id (int): The warehouse ID.
    # - $p_body_type_id (int): The body type ID.
    # - $p_length (double): The length.
    # - $p_length_unit (int): The length unit.
    # - $p_running_hours (double): The running hours.
    # - $p_mileage (double): The mileage.
    # - $p_color_id (int): The color ID.
    # - $p_product_cost (double): The cost of the product.
    # - $p_product_price (double): The price of the product.
    # - $p_remarks (double): The remarks.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateProduct($p_product_id, $p_product_category_id, $p_product_subcategory_id, $p_company_id, $p_stock_number, $p_engine_number, $p_chassis_number, $p_plate_number, $p_description, $p_warehouse_id, $p_body_type_id, $p_length, $p_length_unit, $p_running_hours, $p_mileage, $p_color_id, $p_product_cost, $p_product_price, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateProduct(:p_product_id, :p_product_category_id, :p_product_subcategory_id, :p_company_id, :p_stock_number, :p_engine_number, :p_chassis_number, :p_plate_number, :p_description, :p_warehouse_id, :p_body_type_id, :p_length, :p_length_unit, :p_running_hours, :p_mileage, :p_color_id, :p_product_cost, :p_product_price, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_stock_number', $p_stock_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_engine_number', $p_engine_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_chassis_number', $p_chassis_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_plate_number', $p_plate_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_description', $p_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_body_type_id', $p_body_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_length', $p_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_length_unit', $p_length_unit, PDO::PARAM_INT);
        $stmt->bindValue(':p_running_hours', $p_running_hours, PDO::PARAM_STR);
        $stmt->bindValue(':p_mileage', $p_mileage, PDO::PARAM_STR);
        $stmt->bindValue(':p_color_id', $p_color_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_cost', $p_product_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_price', $p_product_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateImportedProduct
    # Description: Updates the product.
    #
    # Parameters:
    # - $p_product_id (int): The product ID.
    # - $p_product_category_id (int): The product category ID.
    # - $p_product_subcategory_id (int): The product subcategory ID.
    # - $p_company_id (int): The company ID.
    # - $p_product_status (int): The product status.
    # - $p_stock_number (string): The stock number.
    # - $p_engine_number (string): The engine number.
    # - $p_chassis_number (string): The chassis number.
    # - $p_plate_number (string): The plate number.
    # - $p_description (string): The product description.
    # - $p_warehouse_id (int): The warehouse ID.
    # - $p_body_type_id (int): The body type ID.
    # - $p_length (double): The length.
    # - $p_length_unit (int): The length unit.
    # - $p_running_hours (double): The running hours.
    # - $p_mileage (double): The mileage.
    # - $p_color_id (int): The color ID.
    # - $p_product_cost (double): The cost of the product.
    # - $p_product_price (double): The price of the product.
    # - $p_remarks (double): The remarks.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateImportedProduct($p_product_id, $p_product_category_id, $p_product_subcategory_id, $p_company_id, $p_product_status, $p_stock_number, $p_engine_number, $p_chassis_number, $p_plate_number, $p_description, $p_warehouse_id, $p_body_type_id, $p_length, $p_length_unit, $p_running_hours, $p_mileage, $p_color_id, $p_product_cost, $p_product_price, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateImportedProduct(:p_product_id, :p_product_category_id, :p_product_subcategory_id, :p_company_id, :p_product_status, :p_stock_number, :p_engine_number, :p_chassis_number, :p_plate_number, :p_description, :p_warehouse_id, :p_body_type_id, :p_length, :p_length_unit, :p_running_hours, :p_mileage, :p_color_id, :p_product_cost, :p_product_price, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_status', $p_product_status, PDO::PARAM_INT);
        $stmt->bindValue(':p_stock_number', $p_stock_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_engine_number', $p_engine_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_chassis_number', $p_chassis_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_plate_number', $p_plate_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_description', $p_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_body_type_id', $p_body_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_length', $p_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_length_unit', $p_length_unit, PDO::PARAM_INT);
        $stmt->bindValue(':p_running_hours', $p_running_hours, PDO::PARAM_STR);
        $stmt->bindValue(':p_mileage', $p_mileage, PDO::PARAM_STR);
        $stmt->bindValue(':p_color_id', $p_color_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_cost', $p_product_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_price', $p_product_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateProductImage
    # Description: Updates the product image.
    #
    # Parameters:
    # - $p_product_id (int): The product ID.
    # - $p_product_image (string): The product category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateProductImage($p_product_id, $p_product_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateProductImage(:p_product_id, :p_product_image, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_image', $p_product_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertProduct
    # Description: Inserts the product.
    #
    # Parameters:
    # - $p_product_category_id (int): The product category ID.
    # - $p_product_subcategory_id (int): The product subcategory ID.
    # - $p_company_id (int): The company ID.
    # - $p_stock_number (string): The stock number.
    # - $p_engine_number (string): The engine number.
    # - $p_chassis_number (string): The chassis number.
    # - $p_plate_number (string): The chassis number.
    # - $p_description (string): The product description.
    # - $p_warehouse_id (int): The warehouse ID.
    # - $p_body_type_id (int): The body type ID.
    # - $p_length (double): The length.
    # - $p_length_unit (int): The length unit.
    # - $p_running_hours (double): The running hours.
    # - $p_mileage (double): The mileage.
    # - $p_color_id (int): The color ID.
    # - $p_product_cost (double): The cost of the product.
    # - $p_product_price (double): The price of the product.
    # - $p_remarks (string): The remarks.
    # - $p_last_log_by (int): The last logged user.
    #
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertProduct($p_product_category_id, $p_product_subcategory_id, $p_company_id, $p_stock_number, $p_engine_number, $p_chassis_number, $p_plate_number, $p_description, $p_warehouse_id, $p_body_type_id, $p_length, $p_length_unit, $p_running_hours, $p_mileage, $p_color_id, $p_product_cost, $p_product_price, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertProduct(:p_product_category_id, :p_product_subcategory_id, :p_company_id, :p_stock_number, :p_engine_number, :p_chassis_number, :p_description, :p_warehouse_id, :p_body_type_id, :p_length, :p_length_unit, :p_running_hours, :p_mileage, :p_color_id, :p_product_cost, :p_product_price, :p_remarks, :p_last_log_by, @p_product_id)');
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_stock_number', $p_stock_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_engine_number', $p_engine_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_chassis_number', $p_chassis_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_plate_number', $p_plate_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_description', $p_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_body_type_id', $p_body_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_length', $p_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_length_unit', $p_length_unit, PDO::PARAM_INT);
        $stmt->bindValue(':p_running_hours', $p_running_hours, PDO::PARAM_STR);
        $stmt->bindValue(':p_mileage', $p_mileage, PDO::PARAM_STR);
        $stmt->bindValue(':p_color_id', $p_color_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_cost', $p_product_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_price', $p_product_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_product_id AS p_product_id");
        $p_product_id = $result->fetch(PDO::FETCH_ASSOC)['p_product_id'];

        return $p_product_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertImportedProduct
    # Description: Inserts the product.
    #
    # Parameters:
    # - $p_product_category_id (int): The product category ID.
    # - $p_product_subcategory_id (int): The product subcategory ID.
    # - $p_company_id (int): The company ID.
    # - $p_product_status (int): The product status.
    # - $p_stock_number (string): The stock number.
    # - $p_engine_number (string): The engine number.
    # - $p_chassis_number (string): The chassis number.
    # - $p_plate_number (string): The plate number.
    # - $p_description (string): The product description.
    # - $p_warehouse_id (int): The warehouse ID.
    # - $p_body_type_id (int): The body type ID.
    # - $p_length (double): The length.
    # - $p_length_unit (int): The length unit.
    # - $p_running_hours (double): The running hours.
    # - $p_mileage (double): The mileage.
    # - $p_color_id (int): The color ID.
    # - $p_product_cost (double): The cost of the product.
    # - $p_product_price (double): The price of the product.
    # - $p_remarks (string): The remarks.
    # - $p_last_log_by (int): The last logged user.
    #
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertImportedProduct($p_product_category_id, $p_product_subcategory_id, $p_company_id, $p_product_status, $p_stock_number, $p_engine_number, $p_chassis_number, $p_plate_number, $p_description, $p_warehouse_id, $p_body_type_id, $p_length, $p_length_unit, $p_running_hours, $p_mileage, $p_color_id, $p_product_cost, $p_product_price, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertImportedProduct(:p_product_category_id, :p_product_subcategory_id, :p_company_id, :p_product_status, :p_stock_number, :p_engine_number, :p_chassis_number, :p_plate_number, :p_description, :p_warehouse_id, :p_body_type_id, :p_length, :p_length_unit, :p_running_hours, :p_mileage, :p_color_id, :p_product_cost, :p_product_price, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_status', $p_product_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_stock_number', $p_stock_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_engine_number', $p_engine_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_chassis_number', $p_chassis_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_plate_number', $p_plate_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_description', $p_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_body_type_id', $p_body_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_length', $p_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_length_unit', $p_length_unit, PDO::PARAM_INT);
        $stmt->bindValue(':p_running_hours', $p_running_hours, PDO::PARAM_STR);
        $stmt->bindValue(':p_mileage', $p_mileage, PDO::PARAM_STR);
        $stmt->bindValue(':p_color_id', $p_color_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_cost', $p_product_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_price', $p_product_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertImportProduct
    # Description: Inserts the product.
    #
    # Parameters:
    # - $p_product_id (int): The product ID.
    # - $p_product_category_id (int): The product category ID.
    # - $p_product_subcategory_id (int): The product subcategory ID.
    # - $p_company_id (int): The company ID.
    # - $p_stock_number (string): The stock number.
    # - $p_engine_number (string): The engine number.
    # - $p_chassis_number (string): The chassis number.
    # - $p_plate_number (string): The plate number.
    # - $p_description (string): The product description.
    # - $p_warehouse_id (int): The warehouse ID.
    # - $p_body_type_id (int): The body type ID.
    # - $p_length (double): The length.
    # - $p_length_unit (int): The length unit.
    # - $p_running_hours (double): The running hours.
    # - $p_mileage (double): The mileage.
    # - $p_color_id (int): The color ID.
    # - $p_product_cost (double): The cost of the product.
    # - $p_product_price (double): The price of the product.
    # - $p_remarks (string): The remarks.
    #
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertImportProduct($p_product_id, $p_product_category_id, $p_product_subcategory_id, $p_company_id, $p_product_status, $p_stock_number, $p_engine_number, $p_chassis_number, $p_plate_number, $p_description, $p_warehouse_id, $p_body_type_id, $p_length, $p_length_unit, $p_running_hours, $p_mileage, $p_color_id, $p_product_cost, $p_product_price, $p_remarks) {
        $stmt = $this->db->getConnection()->prepare('CALL insertImportProduct(:p_product_id, :p_product_category_id, :p_product_subcategory_id, :p_company_id, :p_product_status, :p_stock_number, :p_engine_number, :p_chassis_number, :p_plate_number, :p_description, :p_warehouse_id, :p_body_type_id, :p_length, :p_length_unit, :p_running_hours, :p_mileage, :p_color_id, :p_product_cost, :p_product_price, :p_remarks)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_status', $p_product_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_stock_number', $p_stock_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_engine_number', $p_engine_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_chassis_number', $p_chassis_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_plate_number', $p_plate_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_description', $p_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_warehouse_id', $p_warehouse_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_body_type_id', $p_body_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_length', $p_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_length_unit', $p_length_unit, PDO::PARAM_INT);
        $stmt->bindValue(':p_running_hours', $p_running_hours, PDO::PARAM_STR);
        $stmt->bindValue(':p_mileage', $p_mileage, PDO::PARAM_STR);
        $stmt->bindValue(':p_color_id', $p_color_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_cost', $p_product_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_price', $p_product_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkProductExist
    # Description: Checks if a product exists.
    #
    # Parameters:
    # - $p_product_id (int): The product ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkProductExist($p_product_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkProductExist(:p_product_id)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteProduct
    # Description: Deletes the product.
    #
    # Parameters:
    # - $p_product_id (int): The product ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteProduct($p_product_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteProduct(:p_product_id)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteTempProduct
    # Description: Deletes the temporary product import.
    #
    # Parameters: N/A
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteTempProduct() {
        $stmt = $this->db->getConnection()->prepare('CALL deleteTempProduct()');
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getProduct
    # Description: Retrieves the details of a product.
    #
    # Parameters:
    # - $p_product_id (int): The product ID.
    #
    # Returns:
    # - An array containing the product details.
    #
    # -------------------------------------------------------------
    public function getProduct($p_product_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getProduct(:p_product_id)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getProductStatus
    # Description: Retrieves the product status badge.
    #
    # Parameters:
    # - $p_product_status (string): The product status.
    #
    # Returns:
    # - An array containing the product details.
    #
    # -------------------------------------------------------------
    public function getProductStatus($p_product_status) {
        $statusClasses = [
            'In Stock' => 'success',
            'Sold' => 'danger'
        ];
        
        $defaultClass = 'dark';
        
        $class = $statusClasses[$p_product_status] ?? $defaultClass;
        
        return '<span class="badge bg-' . $class . '">' . $p_product_status . '</span>';
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getImportedProduct
    # Description: Retrieves the details of an imported product.
    #
    # Parameters:
    # - $p_temp_product_id (int): The temporary product ID.
    #
    # Returns:
    # - An array containing the product details.
    #
    # -------------------------------------------------------------
    public function getImportedProduct($p_temp_product_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getImportedProduct(:p_temp_product_id)');
        $stmt->bindValue(':p_temp_product_id', $p_temp_product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateProduct
    # Description: Duplicates the product.
    #
    # Parameters:
    # - $p_product_id (int): The product ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateProduct($p_product_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateProduct(:p_product_id, :p_last_log_by, @p_new_product_id)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_product_id AS product_id");
        $productID = $result->fetch(PDO::FETCH_ASSOC)['product_id'];

        return $productID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateProductOptions
    # Description: Generates the product options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateProductOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateProductOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $productID = $row['product_id'];
            $description = $row['description'];
            $stockNumber = $row['stock_number'];

            $htmlOptions .= '<option value="' . htmlspecialchars($productID, ENT_QUOTES) . '">' . htmlspecialchars($stockNumber, ENT_QUOTES) .' - '. htmlspecialchars($description, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateInStockProductOptions
    # Description: Generates the product options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateInStockProductOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateInStockProductOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $productID = $row['product_id'];
            $description = $row['description'];
            $stockNumber = $row['stock_number'];

            $htmlOptions .= '<option value="' . htmlspecialchars($productID, ENT_QUOTES) . '">' . htmlspecialchars($stockNumber, ENT_QUOTES) .' - '. htmlspecialchars($description, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>