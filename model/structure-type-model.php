<?php
/**
* Class StructureTypeModel
*
* The StructureTypeModel class handles structure type related operations and interactions.
*/
class StructureTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateStructureType
    # Description: Updates the structure type.
    #
    # Parameters:
    # - $p_structure_type_id (int): The structure type ID.
    # - $p_structure_type_name (string): The structure type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateStructureType($p_structure_type_id, $p_structure_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateStructureType(:p_structure_type_id, :p_structure_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_structure_type_id', $p_structure_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_structure_type_name', $p_structure_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertStructureType
    # Description: Inserts the structure type.
    #
    # Parameters:
    # - $p_structure_type_name (string): The structure type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertStructureType($p_structure_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertStructureType(:p_structure_type_name, :p_last_log_by, @p_structure_type_id)');
        $stmt->bindValue(':p_structure_type_name', $p_structure_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_structure_type_id AS p_structure_type_id");
        $p_structure_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_structure_type_id'];

        return $p_structure_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkStructureTypeExist
    # Description: Checks if a structure type exists.
    #
    # Parameters:
    # - $p_structure_type_id (int): The structure type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkStructureTypeExist($p_structure_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkStructureTypeExist(:p_structure_type_id)');
        $stmt->bindValue(':p_structure_type_id', $p_structure_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteStructureType
    # Description: Deletes the structure type.
    #
    # Parameters:
    # - $p_structure_type_id (int): The structure type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteStructureType($p_structure_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteStructureType(:p_structure_type_id)');
        $stmt->bindValue(':p_structure_type_id', $p_structure_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getStructureType
    # Description: Retrieves the details of a structure type.
    #
    # Parameters:
    # - $p_structure_type_id (int): The structure type ID.
    #
    # Returns:
    # - An array containing the structure type details.
    #
    # -------------------------------------------------------------
    public function getStructureType($p_structure_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getStructureType(:p_structure_type_id)');
        $stmt->bindValue(':p_structure_type_id', $p_structure_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateStructureType
    # Description: Duplicates the structure type.
    #
    # Parameters:
    # - $p_structure_type_id (int): The structure type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateStructureType($p_structure_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateStructureType(:p_structure_type_id, :p_last_log_by, @p_new_structure_type_id)');
        $stmt->bindValue(':p_structure_type_id', $p_structure_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_structure_type_id AS structure_type_id");
        $structureTypeID = $result->fetch(PDO::FETCH_ASSOC)['structure_type_id'];

        return $structureTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateStructureTypeOptions
    # Description: Generates the structure type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateStructureTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateStructureTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $structureTypeID = $row['structure_type_id'];
            $structureTypeName = $row['structure_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($structureTypeID, ENT_QUOTES) . '">' . htmlspecialchars($structureTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>