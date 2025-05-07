<?php
/**
* Class PartsCategoryModel
*
* The PartsCategoryModel class handles parts category related operations and interactions.
*/
class PartsCategoryModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsCategory
    # Description: Updates the parts category.
    #
    # Parameters:
    # - $p_parts_category_id (int): The parts category ID.
    # - $p_parts_category_name (string): The parts category name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePartsCategory($p_parts_category_id, $p_parts_category_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePartsCategory(:p_parts_category_id, :p_parts_category_name, :p_last_log_by)');
        $stmt->bindValue(':p_parts_category_id', $p_parts_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_parts_category_name', $p_parts_category_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPartsCategory
    # Description: Inserts the parts category.
    #
    # Parameters:
    # - $p_parts_category_name (string): The parts category name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPartsCategory($p_parts_category_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPartsCategory(:p_parts_category_name, :p_last_log_by, @p_parts_category_id)');
        $stmt->bindValue(':p_parts_category_name', $p_parts_category_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_parts_category_id AS p_parts_category_id");
        $p_parts_category_id = $result->fetch(PDO::FETCH_ASSOC)['p_parts_category_id'];

        return $p_parts_category_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkPartsCategoryExist
    # Description: Checks if a parts category exists.
    #
    # Parameters:
    # - $p_parts_category_id (int): The parts category ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkPartsCategoryExist($p_parts_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkPartsCategoryExist(:p_parts_category_id)');
        $stmt->bindValue(':p_parts_category_id', $p_parts_category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePartsCategory
    # Description: Deletes the parts category.
    #
    # Parameters:
    # - $p_parts_category_id (int): The parts category ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePartsCategory($p_parts_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePartsCategory(:p_parts_category_id)');
        $stmt->bindValue(':p_parts_category_id', $p_parts_category_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsCategory
    # Description: Retrieves the details of a parts category.
    #
    # Parameters:
    # - $p_parts_category_id (int): The parts category ID.
    #
    # Returns:
    # - An array containing the parts category details.
    #
    # -------------------------------------------------------------
    public function getPartsCategory($p_parts_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPartsCategory(:p_parts_category_id)');
        $stmt->bindValue(':p_parts_category_id', $p_parts_category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicatePartsCategory
    # Description: Duplicates the parts category.
    #
    # Parameters:
    # - $p_parts_category_id (int): The parts category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicatePartsCategory($p_parts_category_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicatePartsCategory(:p_parts_category_id, :p_last_log_by, @p_new_parts_category_id)');
        $stmt->bindValue(':p_parts_category_id', $p_parts_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_parts_category_id AS parts_category_id");
        $partsCategoryID = $result->fetch(PDO::FETCH_ASSOC)['part_category_id'];

        return $partsCategoryID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsCategoryOptions
    # Description: Generates the parts category options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsCategoryOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsCategoryOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsCategoryID = $row['part_category_id'];
            $partsCategoryName = $row['part_category_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($partsCategoryID, ENT_QUOTES) . '">' . htmlspecialchars($partsCategoryName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePartsCategoryCheckbox
    # Description: Generates the parts category check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePartsCategoryCheckbox() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePartsCategoryOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $partsCategoryID = $row['part_category_id'];
            $partsCategoryName = $row['part_category_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input parts-category-filter" type="checkbox" id="parts-category-' . htmlspecialchars($partsCategoryID, ENT_QUOTES) . '" value="' . htmlspecialchars($partsCategoryID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="parts-category-' . htmlspecialchars($partsCategoryID, ENT_QUOTES) . '">' . htmlspecialchars($partsCategoryName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>