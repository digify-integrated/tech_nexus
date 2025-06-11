<?php
/**
* Class ResidenceTypeModel
*
* The ResidenceTypeModel class handles residence type related operations and interactions.
*/
class ResidenceTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateResidenceType
    # Description: Updates the residence type.
    #
    # Parameters:
    # - $p_residence_type_id (int): The residence type ID.
    # - $p_residence_type_name (string): The residence type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateResidenceType($p_residence_type_id, $p_residence_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateResidenceType(:p_residence_type_id, :p_residence_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_residence_type_id', $p_residence_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_residence_type_name', $p_residence_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertResidenceType
    # Description: Inserts the residence type.
    #
    # Parameters:
    # - $p_residence_type_name (string): The residence type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertResidenceType($p_residence_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertResidenceType(:p_residence_type_name, :p_last_log_by, @p_residence_type_id)');
        $stmt->bindValue(':p_residence_type_name', $p_residence_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_residence_type_id AS p_residence_type_id");
        $p_residence_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_residence_type_id'];

        return $p_residence_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkResidenceTypeExist
    # Description: Checks if a residence type exists.
    #
    # Parameters:
    # - $p_residence_type_id (int): The residence type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkResidenceTypeExist($p_residence_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkResidenceTypeExist(:p_residence_type_id)');
        $stmt->bindValue(':p_residence_type_id', $p_residence_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteResidenceType
    # Description: Deletes the residence type.
    #
    # Parameters:
    # - $p_residence_type_id (int): The residence type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteResidenceType($p_residence_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteResidenceType(:p_residence_type_id)');
        $stmt->bindValue(':p_residence_type_id', $p_residence_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getResidenceType
    # Description: Retrieves the details of a residence type.
    #
    # Parameters:
    # - $p_residence_type_id (int): The residence type ID.
    #
    # Returns:
    # - An array containing the residence type details.
    #
    # -------------------------------------------------------------
    public function getResidenceType($p_residence_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getResidenceType(:p_residence_type_id)');
        $stmt->bindValue(':p_residence_type_id', $p_residence_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateResidenceType
    # Description: Duplicates the residence type.
    #
    # Parameters:
    # - $p_residence_type_id (int): The residence type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateResidenceType($p_residence_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateResidenceType(:p_residence_type_id, :p_last_log_by, @p_new_residence_type_id)');
        $stmt->bindValue(':p_residence_type_id', $p_residence_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_residence_type_id AS residence_type_id");
        $residenceTypeID = $result->fetch(PDO::FETCH_ASSOC)['residence_type_id'];

        return $residenceTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateResidenceTypeOptions
    # Description: Generates the residence type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateResidenceTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateResidenceTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $residenceTypeID = $row['residence_type_id'];
            $residenceTypeName = $row['residence_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($residenceTypeID, ENT_QUOTES) . '">' . htmlspecialchars($residenceTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>