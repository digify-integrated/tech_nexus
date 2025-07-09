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
    public function updateProductSubcategory($p_product_subcategory_id, $p_product_subcategory_name, $p_product_subcategory_code, $p_product_category_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateProductSubcategory(:p_product_subcategory_id, :p_product_subcategory_name, :p_product_subcategory_code, :p_product_category_id, :p_last_log_by)');
        $stmt->bindValue(':p_product_subcategory_id', $p_product_subcategory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_subcategory_name', $p_product_subcategory_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_subcategory_code', $p_product_subcategory_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
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
    public function insertProductSubcategory($p_product_subcategory_name, $p_product_subcategory_code, $p_product_category_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertProductSubcategory(:p_product_subcategory_name, :p_product_subcategory_code, :p_product_category_id, :p_last_log_by, @p_product_subcategory_id)');
        $stmt->bindValue(':p_product_subcategory_name', $p_product_subcategory_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_subcategory_code', $p_product_subcategory_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_product_subcategory_id AS p_product_subcategory_id");
        $p_product_subcategory_id = $result->fetch(PDO::FETCH_ASSOC)['p_product_subcategory_id'];

        return $p_product_subcategory_id;
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
    # -------------------------------------------------------------
}
?>