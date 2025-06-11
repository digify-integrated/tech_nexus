<?php
/**
* Class BusinessLocationTypeModel
*
* The BusinessLocationTypeModel class handles business location type related operations and interactions.
*/
class BusinessLocationTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateBusinessLocationType
    # Description: Updates the business location type.
    #
    # Parameters:
    # - $p_business_location_type_id (int): The business location type ID.
    # - $p_business_location_type_name (string): The business location type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateBusinessLocationType($p_business_location_type_id, $p_business_location_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateBusinessLocationType(:p_business_location_type_id, :p_business_location_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_business_location_type_id', $p_business_location_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_business_location_type_name', $p_business_location_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertBusinessLocationType
    # Description: Inserts the business location type.
    #
    # Parameters:
    # - $p_business_location_type_name (string): The business location type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertBusinessLocationType($p_business_location_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertBusinessLocationType(:p_business_location_type_name, :p_last_log_by, @p_business_location_type_id)');
        $stmt->bindValue(':p_business_location_type_name', $p_business_location_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_business_location_type_id AS p_business_location_type_id");
        $p_business_location_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_business_location_type_id'];

        return $p_business_location_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkBusinessLocationTypeExist
    # Description: Checks if a business location type exists.
    #
    # Parameters:
    # - $p_business_location_type_id (int): The business location type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkBusinessLocationTypeExist($p_business_location_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkBusinessLocationTypeExist(:p_business_location_type_id)');
        $stmt->bindValue(':p_business_location_type_id', $p_business_location_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBusinessLocationType
    # Description: Deletes the business location type.
    #
    # Parameters:
    # - $p_business_location_type_id (int): The business location type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteBusinessLocationType($p_business_location_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteBusinessLocationType(:p_business_location_type_id)');
        $stmt->bindValue(':p_business_location_type_id', $p_business_location_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBusinessLocationType
    # Description: Retrieves the details of a business location type.
    #
    # Parameters:
    # - $p_business_location_type_id (int): The business location type ID.
    #
    # Returns:
    # - An array containing the business location type details.
    #
    # -------------------------------------------------------------
    public function getBusinessLocationType($p_business_location_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getBusinessLocationType(:p_business_location_type_id)');
        $stmt->bindValue(':p_business_location_type_id', $p_business_location_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateBusinessLocationType
    # Description: Duplicates the business location type.
    #
    # Parameters:
    # - $p_business_location_type_id (int): The business location type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateBusinessLocationType($p_business_location_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateBusinessLocationType(:p_business_location_type_id, :p_last_log_by, @p_new_business_location_type_id)');
        $stmt->bindValue(':p_business_location_type_id', $p_business_location_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_business_location_type_id AS business_location_type_id");
        $businessLocationTypeID = $result->fetch(PDO::FETCH_ASSOC)['business_location_type_id'];

        return $businessLocationTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateBusinessLocationTypeOptions
    # Description: Generates the business location type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateBusinessLocationTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateBusinessLocationTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $businessLocationTypeID = $row['business_location_type_id'];
            $businessLocationTypeName = $row['business_location_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($businessLocationTypeID, ENT_QUOTES) . '">' . htmlspecialchars($businessLocationTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>