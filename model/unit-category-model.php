<?php
/**
* Class UnitCategoryModel
*
* The UnitCategoryModel class handles unit category related operations and interactions.
*/
class UnitCategoryModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateUnitCategory
    # Description: Updates the unit category.
    #
    # Parameters:
    # - $p_unit_category_id (int): The unit category ID.
    # - $p_unit_category_name (string): The unit category name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateUnitCategory($p_unit_category_id, $p_unit_category_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateUnitCategory(:p_unit_category_id, :p_unit_category_name, :p_last_log_by)');
        $stmt->bindValue(':p_unit_category_id', $p_unit_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_category_name', $p_unit_category_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertUnitCategory
    # Description: Inserts the unit category.
    #
    # Parameters:
    # - $p_unit_category_name (string): The unit category name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertUnitCategory($p_unit_category_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertUnitCategory(:p_unit_category_name, :p_last_log_by, @p_unit_category_id)');
        $stmt->bindValue(':p_unit_category_name', $p_unit_category_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_unit_category_id AS p_unit_category_id");
        $p_unit_category_id = $result->fetch(PDO::FETCH_ASSOC)['p_unit_category_id'];

        return $p_unit_category_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkUnitCategoryExist
    # Description: Checks if a unit category exists.
    #
    # Parameters:
    # - $p_unit_category_id (int): The unit category ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkUnitCategoryExist($p_unit_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkUnitCategoryExist(:p_unit_category_id)');
        $stmt->bindValue(':p_unit_category_id', $p_unit_category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteUnitCategory
    # Description: Deletes the unit category.
    #
    # Parameters:
    # - $p_unit_category_id (int): The unit category ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteUnitCategory($p_unit_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteUnitCategory(:p_unit_category_id)');
        $stmt->bindValue(':p_unit_category_id', $p_unit_category_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUnitCategory
    # Description: Retrieves the details of a unit category.
    #
    # Parameters:
    # - $p_unit_category_id (int): The unit category ID.
    #
    # Returns:
    # - An array containing the unit category details.
    #
    # -------------------------------------------------------------
    public function getUnitCategory($p_unit_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getUnitCategory(:p_unit_category_id)');
        $stmt->bindValue(':p_unit_category_id', $p_unit_category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateUnitCategory
    # Description: Duplicates the unit category.
    #
    # Parameters:
    # - $p_unit_category_id (int): The unit category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateUnitCategory($p_unit_category_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateUnitCategory(:p_unit_category_id, :p_last_log_by, @p_new_unit_category_id)');
        $stmt->bindValue(':p_unit_category_id', $p_unit_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_unit_category_id AS unit_category_id");
        $unitCategoryID = $result->fetch(PDO::FETCH_ASSOC)['unit_category_id'];

        return $unitCategoryID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateUnitCategoryOptions
    # Description: Generates the unit category options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateUnitCategoryOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateUnitCategoryOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $unitCategoryID = $row['unit_category_id'];
            $unitCategoryName = $row['unit_category_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($unitCategoryID, ENT_QUOTES) . '">' . htmlspecialchars($unitCategoryName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>