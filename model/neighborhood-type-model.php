<?php
/**
* Class NeighborhoodTypeModel
*
* The NeighborhoodTypeModel class handles neighborhood type related operations and interactions.
*/
class NeighborhoodTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateNeighborhoodType
    # Description: Updates the neighborhood type.
    #
    # Parameters:
    # - $p_neighborhood_type_id (int): The neighborhood type ID.
    # - $p_neighborhood_type_name (string): The neighborhood type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateNeighborhoodType($p_neighborhood_type_id, $p_neighborhood_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateNeighborhoodType(:p_neighborhood_type_id, :p_neighborhood_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_neighborhood_type_id', $p_neighborhood_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_neighborhood_type_name', $p_neighborhood_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertNeighborhoodType
    # Description: Inserts the neighborhood type.
    #
    # Parameters:
    # - $p_neighborhood_type_name (string): The neighborhood type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertNeighborhoodType($p_neighborhood_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertNeighborhoodType(:p_neighborhood_type_name, :p_last_log_by, @p_neighborhood_type_id)');
        $stmt->bindValue(':p_neighborhood_type_name', $p_neighborhood_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_neighborhood_type_id AS p_neighborhood_type_id");
        $p_neighborhood_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_neighborhood_type_id'];

        return $p_neighborhood_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkNeighborhoodTypeExist
    # Description: Checks if a neighborhood type exists.
    #
    # Parameters:
    # - $p_neighborhood_type_id (int): The neighborhood type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkNeighborhoodTypeExist($p_neighborhood_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkNeighborhoodTypeExist(:p_neighborhood_type_id)');
        $stmt->bindValue(':p_neighborhood_type_id', $p_neighborhood_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteNeighborhoodType
    # Description: Deletes the neighborhood type.
    #
    # Parameters:
    # - $p_neighborhood_type_id (int): The neighborhood type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteNeighborhoodType($p_neighborhood_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteNeighborhoodType(:p_neighborhood_type_id)');
        $stmt->bindValue(':p_neighborhood_type_id', $p_neighborhood_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getNeighborhoodType
    # Description: Retrieves the details of a neighborhood type.
    #
    # Parameters:
    # - $p_neighborhood_type_id (int): The neighborhood type ID.
    #
    # Returns:
    # - An array containing the neighborhood type details.
    #
    # -------------------------------------------------------------
    public function getNeighborhoodType($p_neighborhood_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getNeighborhoodType(:p_neighborhood_type_id)');
        $stmt->bindValue(':p_neighborhood_type_id', $p_neighborhood_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateNeighborhoodType
    # Description: Duplicates the neighborhood type.
    #
    # Parameters:
    # - $p_neighborhood_type_id (int): The neighborhood type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateNeighborhoodType($p_neighborhood_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateNeighborhoodType(:p_neighborhood_type_id, :p_last_log_by, @p_new_neighborhood_type_id)');
        $stmt->bindValue(':p_neighborhood_type_id', $p_neighborhood_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_neighborhood_type_id AS neighborhood_type_id");
        $neighborhoodTypeID = $result->fetch(PDO::FETCH_ASSOC)['neighborhood_type_id'];

        return $neighborhoodTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateNeighborhoodTypeOptions
    # Description: Generates the neighborhood type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateNeighborhoodTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateNeighborhoodTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $neighborhoodTypeID = $row['neighborhood_type_id'];
            $neighborhoodTypeName = $row['neighborhood_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($neighborhoodTypeID, ENT_QUOTES) . '">' . htmlspecialchars($neighborhoodTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>