<?php
/**
* Class UnitModel
*
* The UnitModel class handles unit related operations and interactions.
*/
class UnitModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateUnit
    # Description: Updates the unit.
    #
    # Parameters:
    # - $p_unit_id (int): The unit ID.
    # - $p_unit_name (string): The unit name.
    # - $p_short_name (string): The short name.
    # - $p_unit_category_id (int): The unit category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateUnit($p_unit_id, $p_unit_name, $p_short_name, $p_unit_category_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateUnit(:p_unit_id, :p_unit_name, :p_short_name, :p_unit_category_id, :p_last_log_by)');
        $stmt->bindValue(':p_unit_id', $p_unit_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_name', $p_unit_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_short_name', $p_short_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_unit_category_id', $p_unit_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertUnit
    # Description: Inserts the unit.
    #
    # Parameters:
    # - $p_unit_name (string): The unit name.
    # - $p_short_name (string): The short name.
    # - $p_unit_category_id (int): The unit category ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertUnit($p_unit_name, $p_short_name, $p_unit_category_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertUnit(:p_unit_name, :p_short_name, :p_unit_category_id, :p_last_log_by, @p_unit_id)');
        $stmt->bindValue(':p_unit_name', $p_unit_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_short_name', $p_short_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_unit_category_id', $p_unit_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_unit_id AS p_unit_id");
        $p_unit_id = $result->fetch(PDO::FETCH_ASSOC)['p_unit_id'];

        return $p_unit_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkUnitExist
    # Description: Checks if a unit exists.
    #
    # Parameters:
    # - $p_unit_id (int): The unit ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkUnitExist($p_unit_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkUnitExist(:p_unit_id)');
        $stmt->bindValue(':p_unit_id', $p_unit_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteUnit
    # Description: Deletes the unit.
    #
    # Parameters:
    # - $p_unit_id (int): The unit ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteUnit($p_unit_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteUnit(:p_unit_id)');
        $stmt->bindValue(':p_unit_id', $p_unit_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getUnit
    # Description: Retrieves the details of a unit.
    #
    # Parameters:
    # - $p_unit_id (int): The unit ID.
    #
    # Returns:
    # - An array containing the unit details.
    #
    # -------------------------------------------------------------
    public function getUnit($p_unit_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getUnit(:p_unit_id)');
        $stmt->bindValue(':p_unit_id', $p_unit_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateUnit
    # Description: Duplicates the unit.
    #
    # Parameters:
    # - $p_unit_id (int): The unit ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateUnit($p_unit_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateUnit(:p_unit_id, :p_last_log_by, @p_new_unit_id)');
        $stmt->bindValue(':p_unit_id', $p_unit_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_unit_id AS unit_id");
        $unitID = $result->fetch(PDO::FETCH_ASSOC)['unit_id'];

        return $unitID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateUnitOptions
    # Description: Generates the unit options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateUnitOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateUnitOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $unitID = $row['unit_id'];
            $unitName = $row['unit_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($unitID, ENT_QUOTES) . '">' . htmlspecialchars($unitName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateUnitByShortNameOptions
    # Description: Generates the unit options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateUnitByShortNameOptions($p_unit_category_id) {
        $stmt = $this->db->getConnection()->prepare('CALL generateUnitByCategoryOptions(:p_unit_category_id)');
        $stmt->bindValue(':p_unit_category_id', $p_unit_category_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $unitID = $row['unit_id'];
            $shortName = $row['short_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($unitID, ENT_QUOTES) . '">' . htmlspecialchars($shortName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>