<?php
/**
* Class BuildingMakeModel
*
* The BuildingMakeModel class handles building make related operations and interactions.
*/
class BuildingMakeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateBuildingMake
    # Description: Updates the building make.
    #
    # Parameters:
    # - $p_building_make_id (int): The building make ID.
    # - $p_building_make_name (string): The building make name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateBuildingMake($p_building_make_id, $p_building_make_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBuildingMake(:p_building_make_id, :p_building_make_name, :p_last_log_by)');
        $stmt->bindValue(':p_building_make_id', $p_building_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_building_make_name', $p_building_make_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertBuildingMake
    # Description: Inserts the building make.
    #
    # Parameters:
    # - $p_building_make_name (string): The building make name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertBuildingMake($p_building_make_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertBuildingMake(:p_building_make_name, :p_last_log_by, @p_building_make_id)');
        $stmt->bindValue(':p_building_make_name', $p_building_make_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_building_make_id AS p_building_make_id");
        $p_building_make_id = $result->fetch(PDO::FETCH_ASSOC)['p_building_make_id'];

        return $p_building_make_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkBuildingMakeExist
    # Description: Checks if a building make exists.
    #
    # Parameters:
    # - $p_building_make_id (int): The building make ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkBuildingMakeExist($p_building_make_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkBuildingMakeExist(:p_building_make_id)');
        $stmt->bindValue(':p_building_make_id', $p_building_make_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBuildingMake
    # Description: Deletes the building make.
    #
    # Parameters:
    # - $p_building_make_id (int): The building make ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteBuildingMake($p_building_make_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBuildingMake(:p_building_make_id)');
        $stmt->bindValue(':p_building_make_id', $p_building_make_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBuildingMake
    # Description: Retrieves the details of a building make.
    #
    # Parameters:
    # - $p_building_make_id (int): The building make ID.
    #
    # Returns:
    # - An array containing the building make details.
    #
    # -------------------------------------------------------------
    public function getBuildingMake($p_building_make_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBuildingMake(:p_building_make_id)');
        $stmt->bindValue(':p_building_make_id', $p_building_make_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateBuildingMake
    # Description: Duplicates the building make.
    #
    # Parameters:
    # - $p_building_make_id (int): The building make ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateBuildingMake($p_building_make_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateBuildingMake(:p_building_make_id, :p_last_log_by, @p_new_building_make_id)');
        $stmt->bindValue(':p_building_make_id', $p_building_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_building_make_id AS building_make_id");
        $buildingMakeID = $result->fetch(PDO::FETCH_ASSOC)['building_make_id'];

        return $buildingMakeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBuildingMakeOptions
    # Description: Generates the building make options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBuildingMakeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBuildingMakeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $buildingMakeID = $row['building_make_id'];
            $buildingMakeName = $row['building_make_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($buildingMakeID, ENT_QUOTES) . '">' . htmlspecialchars($buildingMakeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>