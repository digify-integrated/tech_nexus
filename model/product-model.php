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
    public function updateProduct($p_product_id, $p_product_category_id, $p_product_subcategory_id, $p_company_id, $p_stock_number, $p_engine_number, $p_chassis_number, $p_plate_number, $p_description, $p_warehouse_id, $p_body_type_id, $p_length, $p_length_unit, $p_running_hours, $p_mileage, $p_color_id, $p_product_cost, $p_product_price, $p_remarks, $p_orcr_no, $p_orcr_date, $p_orcr_expiry_date, $p_received_from, $p_received_from_address, $p_received_from_id_type, $p_received_from_id_number, $p_unit_description, $p_rr_date, $p_rr_no, $p_supplier_id, $p_ref_no, $p_brand_id, $p_cabin_id, $p_model_id, $p_make_id, $p_class_id, $p_mode_of_acquisition_id, $p_broker, $p_registered_owner, $p_mode_of_registration, $p_year_model, $p_arrival_date, $p_checklist_date, $p_fx_rate, $p_unit_cost, $p_package_deal, $p_taxes_duties, $p_freight, $p_lto_registration, $p_royalties, $p_conversion, $p_arrastre, $p_wharrfage, $p_insurance, $p_aircon, $p_import_permit, $p_others, $p_sub_total, $p_total_landed_cost, $p_with_cr, $p_with_plate, $p_returned_to_supplier, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateProduct(:p_product_id, :p_product_category_id, :p_product_subcategory_id, :p_company_id, :p_stock_number, :p_engine_number, :p_chassis_number, :p_plate_number, :p_description, :p_warehouse_id, :p_body_type_id, :p_length, :p_length_unit, :p_running_hours, :p_mileage, :p_color_id, :p_product_cost, :p_product_price, :p_remarks, :p_orcr_no, :p_orcr_date, :p_orcr_expiry_date, :p_received_from, :p_received_from_address, :p_received_from_id_type, :p_received_from_id_number, :p_unit_description, :p_rr_date, :p_rr_no, :p_supplier_id, :p_ref_no, :p_brand_id, :p_cabin_id, :p_model_id, :p_make_id, :p_class_id, :p_mode_of_acquisition_id, :p_broker, :p_registered_owner, :p_mode_of_registration, :p_year_model, :p_arrival_date, :p_checklist_date, :p_fx_rate, :p_unit_cost, :p_package_deal, :p_taxes_duties, :p_freight, :p_lto_registration, :p_royalties, :p_conversion, :p_arrastre, :p_wharrfage, :p_insurance, :p_aircon, :p_import_permit, :p_others, :p_sub_total, :p_total_landed_cost, :p_with_cr, :p_with_plate, :p_returned_to_supplier, :p_last_log_by)');
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
        $stmt->bindValue(':p_orcr_no', $p_orcr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_orcr_date', $p_orcr_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_orcr_expiry_date', $p_orcr_expiry_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from', $p_received_from, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_address', $p_received_from_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_id_type', $p_received_from_id_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_id_number', $p_received_from_id_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_unit_description', $p_unit_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_date', $p_rr_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_no', $p_rr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ref_no', $p_ref_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_brand_id', $p_brand_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cabin_id', $p_cabin_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_model_id', $p_model_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_make_id', $p_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_class_id', $p_class_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mode_of_acquisition_id', $p_mode_of_acquisition_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_broker', $p_broker, PDO::PARAM_STR);
        $stmt->bindValue(':p_registered_owner', $p_registered_owner, PDO::PARAM_STR);
        $stmt->bindValue(':p_mode_of_registration', $p_mode_of_registration, PDO::PARAM_STR);
        $stmt->bindValue(':p_year_model', $p_year_model, PDO::PARAM_STR);
        $stmt->bindValue(':p_arrival_date', $p_arrival_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_checklist_date', $p_checklist_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_fx_rate', $p_fx_rate, PDO::PARAM_STR);
        $stmt->bindValue(':p_unit_cost', $p_unit_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_package_deal', $p_package_deal, PDO::PARAM_STR);
        $stmt->bindValue(':p_taxes_duties', $p_taxes_duties, PDO::PARAM_STR);
        $stmt->bindValue(':p_freight', $p_freight, PDO::PARAM_STR);
        $stmt->bindValue(':p_lto_registration', $p_lto_registration, PDO::PARAM_STR);
        $stmt->bindValue(':p_royalties', $p_royalties, PDO::PARAM_STR);
        $stmt->bindValue(':p_conversion', $p_conversion, PDO::PARAM_STR);
        $stmt->bindValue(':p_arrastre', $p_arrastre, PDO::PARAM_STR);
        $stmt->bindValue(':p_wharrfage', $p_wharrfage, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance', $p_insurance, PDO::PARAM_STR);
        $stmt->bindValue(':p_aircon', $p_aircon, PDO::PARAM_STR);
        $stmt->bindValue(':p_import_permit', $p_import_permit, PDO::PARAM_STR);
        $stmt->bindValue(':p_others', $p_others, PDO::PARAM_STR);
        $stmt->bindValue(':p_sub_total', $p_sub_total, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_landed_cost', $p_total_landed_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_with_cr', $p_with_cr, PDO::PARAM_STR);
        $stmt->bindValue(':p_with_plate', $p_with_plate, PDO::PARAM_STR);
        $stmt->bindValue(':p_returned_to_supplier', $p_returned_to_supplier, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    public function updateProductDetails($p_product_id, $p_product_category_id, $p_product_subcategory_id, $p_company_id, $p_stock_number, $p_engine_number, $p_chassis_number, $p_plate_number, $p_description, $p_warehouse_id, $p_body_type_id, $p_length, $p_length_unit, $p_running_hours, $p_mileage, $p_color_id, $p_remarks, $p_orcr_no, $p_orcr_date, $p_orcr_expiry_date, $p_received_from, $p_received_from_address, $p_received_from_id_type, $p_received_from_id_number, $p_unit_description, $p_rr_date, $p_rr_no, $p_supplier_id, $p_ref_no, $p_brand_id, $p_cabin_id, $p_model_id, $p_make_id, $p_class_id, $p_mode_of_acquisition_id, $p_broker, $p_registered_owner, $p_mode_of_registration, $p_year_model, $p_arrival_date, $p_checklist_date, $p_with_cr, $p_with_plate, $p_returned_to_supplier, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateProductDetails(:p_product_id, :p_product_category_id, :p_product_subcategory_id, :p_company_id, :p_stock_number, :p_engine_number, :p_chassis_number, :p_plate_number, :p_description, :p_warehouse_id, :p_body_type_id, :p_length, :p_length_unit, :p_running_hours, :p_mileage, :p_color_id, :p_remarks, :p_orcr_no, :p_orcr_date, :p_orcr_expiry_date, :p_received_from, :p_received_from_address, :p_received_from_id_type, :p_received_from_id_number, :p_unit_description, :p_rr_date, :p_rr_no, :p_supplier_id, :p_ref_no, :p_brand_id, :p_cabin_id, :p_model_id, :p_make_id, :p_class_id, :p_mode_of_acquisition_id, :p_broker, :p_registered_owner, :p_mode_of_registration, :p_year_model, :p_arrival_date, :p_checklist_date, :p_with_cr, :p_with_plate, :p_returned_to_supplier, :p_last_log_by)');
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
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_orcr_no', $p_orcr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_orcr_date', $p_orcr_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_orcr_expiry_date', $p_orcr_expiry_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from', $p_received_from, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_address', $p_received_from_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_id_type', $p_received_from_id_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_id_number', $p_received_from_id_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_unit_description', $p_unit_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_date', $p_rr_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_no', $p_rr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ref_no', $p_ref_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_brand_id', $p_brand_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cabin_id', $p_cabin_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_model_id', $p_model_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_make_id', $p_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_class_id', $p_class_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mode_of_acquisition_id', $p_mode_of_acquisition_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_broker', $p_broker, PDO::PARAM_STR);
        $stmt->bindValue(':p_registered_owner', $p_registered_owner, PDO::PARAM_STR);
        $stmt->bindValue(':p_mode_of_registration', $p_mode_of_registration, PDO::PARAM_STR);
        $stmt->bindValue(':p_year_model', $p_year_model, PDO::PARAM_STR);
        $stmt->bindValue(':p_arrival_date', $p_arrival_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_checklist_date', $p_checklist_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_with_cr', $p_with_cr, PDO::PARAM_STR);
        $stmt->bindValue(':p_with_plate', $p_with_plate, PDO::PARAM_STR);
        $stmt->bindValue(':p_returned_to_supplier', $p_returned_to_supplier, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateProductLandedCost($p_product_id, $p_product_cost, $p_product_price, $p_fx_rate, $p_unit_cost, $p_package_deal, $p_taxes_duties, $p_freight, $p_lto_registration, $p_royalties, $p_conversion, $p_arrastre, $p_wharrfage, $p_insurance, $p_aircon, $p_import_permit, $p_others, $p_sub_total, $p_total_landed_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateProductLandedCost(:p_product_id, :p_product_cost, :p_product_price, :p_fx_rate, :p_unit_cost, :p_package_deal, :p_taxes_duties, :p_freight, :p_lto_registration, :p_royalties, :p_conversion, :p_arrastre, :p_wharrfage, :p_insurance, :p_aircon, :p_import_permit, :p_others, :p_sub_total, :p_total_landed_cost, :p_last_log_by)');
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_cost', $p_product_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_price', $p_product_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_fx_rate', $p_fx_rate, PDO::PARAM_STR);
        $stmt->bindValue(':p_unit_cost', $p_unit_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_package_deal', $p_package_deal, PDO::PARAM_STR);
        $stmt->bindValue(':p_taxes_duties', $p_taxes_duties, PDO::PARAM_STR);
        $stmt->bindValue(':p_freight', $p_freight, PDO::PARAM_STR);
        $stmt->bindValue(':p_lto_registration', $p_lto_registration, PDO::PARAM_STR);
        $stmt->bindValue(':p_royalties', $p_royalties, PDO::PARAM_STR);
        $stmt->bindValue(':p_conversion', $p_conversion, PDO::PARAM_STR);
        $stmt->bindValue(':p_arrastre', $p_arrastre, PDO::PARAM_STR);
        $stmt->bindValue(':p_wharrfage', $p_wharrfage, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance', $p_insurance, PDO::PARAM_STR);
        $stmt->bindValue(':p_aircon', $p_aircon, PDO::PARAM_STR);
        $stmt->bindValue(':p_import_permit', $p_import_permit, PDO::PARAM_STR);
        $stmt->bindValue(':p_others', $p_others, PDO::PARAM_STR);
        $stmt->bindValue(':p_sub_total', $p_sub_total, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_landed_cost', $p_total_landed_cost, PDO::PARAM_STR);
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
    public function insertProductImage($p_product_id, $p_product_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertProductImage(:p_product_id, :p_product_image, :p_last_log_by)');
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
    public function insertProduct($p_product_category_id, $p_product_subcategory_id, $p_company_id, $p_stock_number, $p_engine_number, $p_chassis_number, $p_plate_number, $p_description, $p_warehouse_id, $p_body_type_id, $p_length, $p_length_unit, $p_running_hours, $p_mileage, $p_color_id, $p_product_cost, $p_product_price, $p_remarks, $p_orcr_no, $p_orcr_date, $p_orcr_expiry_date, $p_received_from, $p_received_from_address, $p_received_from_id_type, $p_received_from_id_number, $p_unit_description, $p_rr_date, $p_rr_no, $p_supplier_id, $p_ref_no, $p_brand_id, $p_cabin_id, $p_model_id, $p_make_id, $p_class_id, $p_mode_of_acquisition_id, $p_broker, $p_registered_owner, $p_mode_of_registration, $p_year_model, $p_arrival_date, $p_checklist_date, $p_fx_rate, $p_unit_cost, $p_package_deal, $p_taxes_duties, $p_freight, $p_lto_registration, $p_royalties, $p_conversion, $p_arrastre, $p_wharrfage, $p_insurance, $p_aircon, $p_import_permit, $p_others, $p_sub_total, $p_total_landed_cost, $p_with_cr, $p_with_plate, $p_returned_to_supplier, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertProduct(:p_product_category_id, :p_product_subcategory_id, :p_company_id, :p_stock_number, :p_engine_number, :p_chassis_number, :p_plate_number, :p_description, :p_warehouse_id, :p_body_type_id, :p_length, :p_length_unit, :p_running_hours, :p_mileage, :p_color_id, :p_product_cost, :p_product_price, :p_remarks, :p_orcr_no, :p_orcr_date, :p_orcr_expiry_date, :p_received_from, :p_received_from_address, :p_received_from_id_type, :p_received_from_id_number, :p_unit_description, :p_rr_date, :p_rr_no, :p_supplier_id, :p_ref_no, :p_brand_id, :p_cabin_id, :p_model_id, :p_make_id, :p_class_id, :p_mode_of_acquisition_id, :p_broker, :p_registered_owner, :p_mode_of_registration, :p_year_model, :p_arrival_date, :p_checklist_date, :p_fx_rate, :p_unit_cost, :p_package_deal, :p_taxes_duties, :p_freight, :p_lto_registration, :p_royalties, :p_conversion, :p_arrastre, :p_wharrfage, :p_insurance, :p_aircon, :p_import_permit, :p_others, :p_sub_total, :p_total_landed_cost, :p_with_cr, :p_with_plate, :p_returned_to_supplier, :p_last_log_by, @p_product_id)');
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
        $stmt->bindValue(':p_orcr_no', $p_orcr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_orcr_date', $p_orcr_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_orcr_expiry_date', $p_orcr_expiry_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from', $p_received_from, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_address', $p_received_from_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_id_type', $p_received_from_id_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_id_number', $p_received_from_id_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_unit_description', $p_unit_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_date', $p_rr_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_rr_no', $p_rr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_supplier_id', $p_supplier_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ref_no', $p_ref_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_brand_id', $p_brand_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cabin_id', $p_cabin_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_model_id', $p_model_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_make_id', $p_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_class_id', $p_class_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mode_of_acquisition_id', $p_mode_of_acquisition_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_broker', $p_broker, PDO::PARAM_STR);
        $stmt->bindValue(':p_registered_owner', $p_registered_owner, PDO::PARAM_STR);
        $stmt->bindValue(':p_mode_of_registration', $p_mode_of_registration, PDO::PARAM_STR);
        $stmt->bindValue(':p_year_model', $p_year_model, PDO::PARAM_STR);
        $stmt->bindValue(':p_arrival_date', $p_arrival_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_checklist_date', $p_checklist_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_fx_rate', $p_fx_rate, PDO::PARAM_STR);
        $stmt->bindValue(':p_unit_cost', $p_unit_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_package_deal', $p_package_deal, PDO::PARAM_STR);
        $stmt->bindValue(':p_taxes_duties', $p_taxes_duties, PDO::PARAM_STR);
        $stmt->bindValue(':p_freight', $p_freight, PDO::PARAM_STR);
        $stmt->bindValue(':p_lto_registration', $p_lto_registration, PDO::PARAM_STR);
        $stmt->bindValue(':p_royalties', $p_royalties, PDO::PARAM_STR);
        $stmt->bindValue(':p_conversion', $p_conversion, PDO::PARAM_STR);
        $stmt->bindValue(':p_arrastre', $p_arrastre, PDO::PARAM_STR);
        $stmt->bindValue(':p_wharrfage', $p_wharrfage, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance', $p_insurance, PDO::PARAM_STR);
        $stmt->bindValue(':p_aircon', $p_aircon, PDO::PARAM_STR);
        $stmt->bindValue(':p_import_permit', $p_import_permit, PDO::PARAM_STR);
        $stmt->bindValue(':p_others', $p_others, PDO::PARAM_STR);
        $stmt->bindValue(':p_sub_total', $p_sub_total, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_landed_cost', $p_total_landed_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_with_cr', $p_with_cr, PDO::PARAM_STR);
        $stmt->bindValue(':p_with_plate', $p_with_plate, PDO::PARAM_STR);
        $stmt->bindValue(':p_returned_to_supplier', $p_returned_to_supplier, PDO::PARAM_STR);
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
    public function checkProductImageExist($p_product_image_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkProductImageExist(:p_product_image_id)');
        $stmt->bindValue(':p_product_image_id', $p_product_image_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
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
    public function checkSalesProposalProduct($p_product_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalProduct(:p_product_id)');
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
    public function deleteProductImage($p_product_image_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteProductImage(:p_product_image_id)');
        $stmt->bindValue(':p_product_image_id', $p_product_image_id, PDO::PARAM_INT);
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
    public function getProductImage($p_product_image_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getProductImage(:p_product_image_id)');
        $stmt->bindValue(':p_product_image_id', $p_product_image_id, PDO::PARAM_INT);
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