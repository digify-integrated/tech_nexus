<?php
/**
* Class ProductSubcategoryModel
*
* The ProductSubcategoryModel class handles product subcategory related operations and interactions.
*/
class ProductSubcategoryModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateProductSubcategory
    # Description: Updates the product subcategory.
    #
    # Parameters:
    # - $p_product_subcategory_id (int): The product subcategory ID.
    # - $p_product_subcategory_name (string): The product subcategory name.
    # - $p_product_subcategory_code (string): The product subcategory code.
    # - $p_product_category_id (int): The product category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateProductSubcategory($p_product_subcategory_id, $p_product_subcategory_name, $p_product_subcategory_code, $p_product_category_id, $p_standard_inclusions, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateProductSubcategory(:p_product_subcategory_id, :p_product_subcategory_name, :p_product_subcategory_code, :p_product_category_id, :p_standard_inclusions, :p_last_log_by)');
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_subcategory_name', $p_product_subcategory_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_subcategory_code', $p_product_subcategory_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_standard_inclusions', $p_standard_inclusions, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertProductSubcategory
    # Description: Inserts the product subcategory.
    #
    # Parameters:
    # - $p_product_subcategory_name (string): The product subcategory name.
    # - $p_product_subcategory_code (string): The product subcategory code.
    # - $p_product_category_id (int): The product category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertProductSubcategory($p_product_subcategory_name, $p_product_subcategory_code, $p_product_category_id, $p_standard_inclusions, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertProductSubcategory(:p_product_subcategory_name, :p_product_subcategory_code, :p_product_category_id, :p_standard_inclusions, :p_last_log_by, @p_product_subcategory_id)');
        $stmt->bindValue(':p_product_subcategory_name', $p_product_subcategory_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_subcategory_code', $p_product_subcategory_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_standard_inclusions', $p_standard_inclusions, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_product_subcategory_id AS p_product_subcategory_id");
        $p_product_subcategory_id = $result->fetch(PDO::FETCH_ASSOC)['p_product_subcategory_id'];

        return $p_product_subcategory_id;
    }
    public function checkPricingThresholdExist($p_product_subcategory_id, $p_year_model, $p_transmission_type, $p_length, $p_length_unit) {
        $stmt = $this->db->getConnection()->prepare('SELECT COUNT(*) AS total FROM quotation_standard_pricing WHERE product_subcategory_id = :p_product_subcategory_id AND year_model = :p_year_model AND transmission_type = :p_transmission_type AND length = :p_length AND length_unit = :p_length_unit');
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_year_model', $p_year_model, PDO::PARAM_STR);
        $stmt->bindValue(':p_transmission_type', $p_transmission_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_length', $p_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_length_unit', $p_length_unit, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'] > 0;
    }

    public function updatePricingThreshold($quotation_standard_pricing_id, $p_product_subcategory_id, $p_year_model, $p_transmission_type, $p_length, $p_length_unit, $p_price_threshold, $p_discount_threshold, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE quotation_standard_pricing SET product_subcategory_id = :p_product_subcategory_id, year_model = :p_year_model, transmission_type = :p_transmission_type, length = :p_length, length_unit = :p_length_unit, price_threshold = :p_price_threshold, discount_threshold = :p_discount_threshold, last_log_by = :p_last_log_by WHERE quotation_standard_pricing_id = :quotation_standard_pricing_id');
        $stmt->bindValue(':quotation_standard_pricing_id', $quotation_standard_pricing_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_year_model', $p_year_model, PDO::PARAM_STR);
        $stmt->bindValue(':p_transmission_type', $p_transmission_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_length', $p_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_length_unit', $p_length_unit, PDO::PARAM_STR);
        $stmt->bindValue(':p_price_threshold', $p_price_threshold, PDO::PARAM_STR);
        $stmt->bindValue(':p_discount_threshold', $p_discount_threshold, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertPricingThreshold($p_product_subcategory_id, $p_year_model, $p_transmission_type, $p_length, $p_length_unit, $p_price_threshold, $p_discount_threshold, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO quotation_standard_pricing (product_subcategory_id, year_model, transmission_type, length, length_unit, price_threshold, discount_threshold, last_log_by) VALUES (:p_product_subcategory_id, :p_year_model, :p_transmission_type, :p_length, :p_length_unit, :p_price_threshold, :p_discount_threshold, :p_last_log_by)');
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_year_model', $p_year_model, PDO::PARAM_STR);
        $stmt->bindValue(':p_transmission_type', $p_transmission_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_length', $p_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_length_unit', $p_length_unit, PDO::PARAM_STR);
        $stmt->bindValue(':p_price_threshold', $p_price_threshold, PDO::PARAM_STR);
        $stmt->bindValue(':p_discount_threshold', $p_discount_threshold, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkProductSubcategoryExist
    # Description: Checks if a product subcategory exists.
    #
    # Parameters:
    # - $p_product_subcategory_id (int): The product subcategory ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkProductSubcategoryExist($p_product_subcategory_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkProductSubcategoryExist(:p_product_subcategory_id)');
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteProductSubcategory
    # Description: Deletes the product subcategory.
    #
    # Parameters:
    # - $p_product_subcategory_id (int): The product subcategory ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteProductSubcategory($p_product_subcategory_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteProductSubcategory(:p_product_subcategory_id)');
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function deletePricingThreshold($p_quotation_standard_pricing_id) {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM quotation_standard_pricing WHERE quotation_standard_pricing_id = :p_quotation_standard_pricing_id');
        $stmt->bindValue(':p_quotation_standard_pricing_id', $p_quotation_standard_pricing_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getProductSubcategory
    # Description: Retrieves the details of a product subcategory.
    #
    # Parameters:
    # - $p_product_subcategory_id (int): The product subcategory ID.
    #
    # Returns:
    # - An array containing the product subcategory details.
    #
    # -------------------------------------------------------------
    public function getProductSubcategory($p_product_subcategory_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getProductSubcategory(:p_product_subcategory_id)');
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getQuotationStandardPricingDetails($p_quotation_standard_pricing_id) {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM quotation_standard_pricing WHERE quotation_standard_pricing_id = :p_quotation_standard_pricing_id');
        $stmt->bindValue(':p_quotation_standard_pricing_id', $p_quotation_standard_pricing_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getQuotationStandardPricing($p_product_subcategory_id, $p_year_model, $p_transmission_type, $p_length, $p_length_unit) {
        $stmt = $this->db->getConnection()->prepare('Select * FROM quotation_standard_pricing WHERE product_subcategory_id = :p_product_subcategory_id AND year_model = :p_year_model AND transmission_type = :p_transmission_type AND length = :p_length AND length_unit = :p_length_unit');
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_year_model', $p_year_model, PDO::PARAM_STR);
        $stmt->bindValue(':p_transmission_type', $p_transmission_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_length', $p_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_length_unit', $p_length_unit, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateProductSubcategory
    # Description: Duplicates the product subcategory.
    #
    # Parameters:
    # - $p_product_subcategory_id (int): The product subcategory ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateProductSubcategory($p_product_subcategory_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateProductSubcategory(:p_product_subcategory_id, :p_last_log_by, @p_new_product_subcategory_id)');
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_product_subcategory_id AS product_subcategory_id");
        $productSubcategoryID = $result->fetch(PDO::FETCH_ASSOC)['product_subcategory_id'];

        return $productSubcategoryID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateProductSubcategoryOptions
    # Description: Generates the product subcategory options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateProductSubcategoryOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateProductSubcategoryOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $productSubcategoryID = $row['product_subcategory_id'];
            $productSubcategoryName = $row['product_subcategory_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($productSubcategoryID, ENT_QUOTES) . '">' . htmlspecialchars($productSubcategoryName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateGroupedProductSubcategoryOptions
    # Description: Generates the product subcategory options.
    #
    # Parameters: None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateGroupedProductSubcategoryOptions() {
        $stmt = $this->db->getConnection()->prepare('SELECT product_category_id, product_category_name FROM product_category ORDER BY product_category_name');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = [];

        foreach ($options as $row) {
            $productCategoryID = $row['product_category_id'];
            $productCategoryName = $row['product_category_name'];

            $htmlOptions[] = '<optgroup label="'. $productCategoryName .'">';

            $subcategoryOptions = $this->generateProductSubcategoryByCategoryOptions($productCategoryID);

            foreach ($subcategoryOptions as $subcategoryOption) {
                $htmlOptions[] = $subcategoryOption;
            }

            $htmlOptions[] = '</optgroup>';
        }

        return implode('', $htmlOptions);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateProductSubcategoryByCategoryOptions
    # Description: Generates the product subcategory check box.
    #
    # Parameters: None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateProductSubcategoryByCategoryOptions($p_product_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL generateProductSubcategoryByCategoryOptions(:p_product_category_id)');
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = [];
        foreach ($options as $row) {
            $productSubcategoryID = $row['product_subcategory_id'];
            $productSubcategoryName = $row['product_subcategory_name'];

            $htmlOptions[] = '<option value="' . htmlspecialchars($productSubcategoryID, ENT_QUOTES) . '">' . htmlspecialchars($productSubcategoryName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateProductSubcategoryCheckbox
    # Description: Generates the product subcategory check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateProductSubcategoryCheckbox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateProductSubcategoryOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $productSubcategoryID = $row['product_subcategory_id'];
            $productSubcategoryName = $row['product_subcategory_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input product-subcategory-filter" type="checkbox" id="product-subcategory-' . htmlspecialchars($productSubcategoryID, ENT_QUOTES) . '" value="' . htmlspecialchars($productSubcategoryID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="product-subcategory-' . htmlspecialchars($productSubcategoryID, ENT_QUOTES) . '">' . htmlspecialchars($productSubcategoryName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    public function generateProductSubcategoryCheckbox2() {
        $stmt = $this->db->getConnection()->prepare('CALL generateProductSubcategoryOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $productSubcategoryID = $row['product_subcategory_id'];
            $productSubcategoryName = $row['product_subcategory_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input product-subcategory-filter-2" type="checkbox" id="product-subcategory-2-' . htmlspecialchars($productSubcategoryID, ENT_QUOTES) . '" value="' . htmlspecialchars($productSubcategoryID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="product-subcategory-2-' . htmlspecialchars($productSubcategoryID, ENT_QUOTES) . '">' . htmlspecialchars($productSubcategoryName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }

    public function updateDefaultImage($p_product_subcategory_id, $p_default_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('Update product_subcategory set default_image = :p_default_image, last_log_by = :p_last_log_by where product_subcategory_id = :p_product_subcategory_id');
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_default_image', $p_default_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function generatePricingThresholdTable(
        $filter_product_subcategory
    ) {

        $query = '
            SELECT *
            FROM quotation_standard_pricing
            WHERE 1=1
        ';

        $params = [];

        if (!empty($filter_product_subcategory)) {

            $query .= " AND product_subcategory_id IN ($filter_product_subcategory)";
        }

        $query .= ' ORDER BY product_subcategory_id DESC';

        $stmt = $this->db->getConnection()->prepare($query);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>