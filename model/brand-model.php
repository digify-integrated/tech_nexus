<?php
/**
* Class BrandModel
*
* The BrandModel class handles brand related operations and interactions.
*/
class BrandModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateBrand
    # Description: Updates the brand.
    #
    # Parameters:
    # - $p_brand_id (int): The brand ID.
    # - $p_brand_name (string): The brand name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateBrand($p_brand_id, $p_brand_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBrand(:p_brand_id, :p_brand_name, :p_last_log_by)');
        $stmt->bindValue(':p_brand_id', $p_brand_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_brand_name', $p_brand_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertBrand
    # Description: Inserts the brand.
    #
    # Parameters:
    # - $p_brand_name (string): The brand name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertBrand($p_brand_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertBrand(:p_brand_name, :p_last_log_by, @p_brand_id)');
        $stmt->bindValue(':p_brand_name', $p_brand_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_brand_id AS p_brand_id");
        $p_brand_id = $result->fetch(PDO::FETCH_ASSOC)['p_brand_id'];

        return $p_brand_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkBrandExist
    # Description: Checks if a brand exists.
    #
    # Parameters:
    # - $p_brand_id (int): The brand ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkBrandExist($p_brand_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkBrandExist(:p_brand_id)');
        $stmt->bindValue(':p_brand_id', $p_brand_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBrand
    # Description: Deletes the brand.
    #
    # Parameters:
    # - $p_brand_id (int): The brand ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteBrand($p_brand_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBrand(:p_brand_id)');
        $stmt->bindValue(':p_brand_id', $p_brand_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBrand
    # Description: Retrieves the details of a brand.
    #
    # Parameters:
    # - $p_brand_id (int): The brand ID.
    #
    # Returns:
    # - An array containing the brand details.
    #
    # -------------------------------------------------------------
    public function getBrand($p_brand_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBrand(:p_brand_id)');
        $stmt->bindValue(':p_brand_id', $p_brand_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateBrand
    # Description: Duplicates the brand.
    #
    # Parameters:
    # - $p_brand_id (int): The brand ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateBrand($p_brand_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateBrand(:p_brand_id, :p_last_log_by, @p_new_brand_id)');
        $stmt->bindValue(':p_brand_id', $p_brand_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_brand_id AS brand_id");
        $brandiD = $result->fetch(PDO::FETCH_ASSOC)['brand_id'];

        return $brandiD;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBrandOptions
    # Description: Generates the brand options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBrandOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBrandOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $brandID = $row['brand_id'];
            $brandName = $row['brand_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($brandID, ENT_QUOTES) . '">' . htmlspecialchars($brandName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBrandCheckBox
    # Description: Generates the brand check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBrandCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBrandOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $brandID = $row['brand_id'];
            $brandName = $row['brand_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input brand-filter" type="checkbox" id="brand-' . htmlspecialchars($brandID, ENT_QUOTES) . '" value="' . htmlspecialchars($brandID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="brand-' . htmlspecialchars($brandID, ENT_QUOTES) . '">' . htmlspecialchars($brandName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>