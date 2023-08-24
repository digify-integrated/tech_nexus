<?php
/**
* Class DistrictModel
*
* The DistrictModel class handles district related operations and interactions.
*/
class DistrictModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDistrict
    # Description: Updates the district.
    #
    # Parameters:
    # - $p_district_id (int): The district ID.
    # - $p_district_name (string): The district name.
    # - $p_city_id (string): The city ID.
    # - $p_state_id (string): The state ID.
    # - $p_country_id (string): The country ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateDistrict($p_district_id, $p_district_name, $p_city_id, $p_state_id, $p_country_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDistrict(:p_district_id, :p_district_name, :p_city_id, :p_state_id, :p_country_id, :p_last_log_by)');
        $stmt->bindValue(':p_district_id', $p_district_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_district_name', $p_district_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_state_id', $p_state_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_country_id', $p_country_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertDistrict
    # Description: Inserts the district.
    #
    # Parameters:
    # - $p_district_name (string): The district name.
    # - $p_state_id (string): The state ID.
    # - $p_city_id (string): The city ID.
    # - $p_country_id (string): The country ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertDistrict($p_district_name, $p_city_id, $p_state_id, $p_country_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDistrict(:p_district_name, :p_city_id, :p_state_id, :p_country_id, :p_last_log_by, @p_district_id)');
        $stmt->bindValue(':p_district_name', $p_district_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_city_id', $p_city_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_state_id', $p_state_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_country_id', $p_country_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_district_id AS p_district_id");
        $p_district_id = $result->fetch(PDO::FETCH_ASSOC)['p_district_id'];

        return $p_district_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDistrictExist
    # Description: Checks if a district exists.
    #
    # Parameters:
    # - $p_district_id (int): The district ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDistrictExist($p_district_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDistrictExist(:p_district_id)');
        $stmt->bindValue(':p_district_id', $p_district_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDistrict
    # Description: Deletes the district.
    #
    # Parameters:
    # - $p_district_id (int): The district ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteDistrict($p_district_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteDistrict(:p_district_id)');
        $stmt->bindValue(':p_district_id', $p_district_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDistrict
    # Description: Retrieves the details of a district.
    #
    # Parameters:
    # - $p_district_id (int): The district ID.
    #
    # Returns:
    # - An array containing the district details.
    #
    # -------------------------------------------------------------
    public function getDistrict($p_district_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDistrict(:p_district_id)');
        $stmt->bindValue(':p_district_id', $p_district_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateDistrict
    # Description: Duplicates the district.
    #
    # Parameters:
    # - $p_district_id (int): The district ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateDistrict($p_district_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateDistrict(:p_district_id, :p_last_log_by, @p_new_district_id)');
        $stmt->bindValue(':p_district_id', $p_district_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_district_id AS district_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['district_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------
}
?>