<?php
/**
* Class ProductCategoryModel
*
* The ProductCategoryModel class handles product category related operations and interactions.
*/
class ProductCategoryModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateProductCategory
    # Description: Updates the product category.
    #
    # Parameters:
    # - $p_product_category_id (int): The product category ID.
    # - $p_product_category_name (string): The product category name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateProductCategory($p_product_category_id, $p_product_category_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateProductCategory(:p_product_category_id, :p_product_category_name, :p_last_log_by)');
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_category_name', $p_product_category_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertProductCategory
    # Description: Inserts the product category.
    #
    # Parameters:
    # - $p_product_category_name (string): The product category name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertProductCategory($p_product_category_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertProductCategory(:p_product_category_name, :p_last_log_by, @p_product_category_id)');
        $stmt->bindValue(':p_product_category_name', $p_product_category_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_product_category_id AS p_product_category_id");
        $p_product_category_id = $result->fetch(PDO::FETCH_ASSOC)['p_product_category_id'];

        return $p_product_category_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkProductCategoryExist
    # Description: Checks if a product category exists.
    #
    # Parameters:
    # - $p_product_category_id (int): The product category ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkProductCategoryExist($p_product_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkProductCategoryExist(:p_product_category_id)');
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteProductCategory
    # Description: Deletes the product category.
    #
    # Parameters:
    # - $p_product_category_id (int): The product category ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteProductCategory($p_product_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteProductCategory(:p_product_category_id)');
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getProductCategory
    # Description: Retrieves the details of a product category.
    #
    # Parameters:
    # - $p_product_category_id (int): The product category ID.
    #
    # Returns:
    # - An array containing the product category details.
    #
    # -------------------------------------------------------------
    public function getProductCategory($p_product_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getProductCategory(:p_product_category_id)');
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateProductCategory
    # Description: Duplicates the product category.
    #
    # Parameters:
    # - $p_product_category_id (int): The product category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateProductCategory($p_product_category_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateProductCategory(:p_product_category_id, :p_last_log_by, @p_new_product_category_id)');
        $stmt->bindValue(':p_product_category_id', $p_product_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_product_category_id AS product_category_id");
        $productCategoryID = $result->fetch(PDO::FETCH_ASSOC)['product_category_id'];

        return $productCategoryID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateProductCategoryOptions
    # Description: Generates the product category options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateProductCategoryOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateProductCategoryOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $productCategoryID = $row['product_category_id'];
            $productCategoryName = $row['product_category_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($productCategoryID, ENT_QUOTES) . '">' . htmlspecialchars($productCategoryName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateProductCategoryCheckbox
    # Description: Generates the product category check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateProductCategoryCheckbox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateProductCategoryOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $productCategoryID = $row['product_category_id'];
            $productCategoryName = $row['product_category_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input product-category-filter" type="checkbox" id="product-category-' . htmlspecialchars($productCategoryID, ENT_QUOTES) . '" value="' . htmlspecialchars($productCategoryID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="product-category-' . htmlspecialchars($productCategoryID, ENT_QUOTES) . '">' . htmlspecialchars($productCategoryName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>