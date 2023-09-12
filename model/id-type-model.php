<?php
/**
* Class IDTypeModel
*
* The IDTypeModel class handles ID Type related operations and interactions.
*/
class IDTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateIDType
    # Description: Updates the ID Type.
    #
    # Parameters:
    # - $p_id_type_id (int): The ID Type ID.
    # - $p_id_type_name (string): The ID Type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateIDType($p_id_type_id, $p_id_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateIDType(:p_id_type_id, :p_id_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_id_type_id', $p_id_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_id_type_name', $p_id_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertIDType
    # Description: Inserts the ID Type.
    #
    # Parameters:
    # - $p_id_type_name (string): The ID Type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertIDType($p_id_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertIDType(:p_id_type_name, :p_last_log_by, @p_id_type_id)');
        $stmt->bindValue(':p_id_type_name', $p_id_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_id_type_id AS p_id_type_id");
        $p_id_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_id_type_id'];

        return $p_id_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkIDTypeExist
    # Description: Checks if a ID Type exists.
    #
    # Parameters:
    # - $p_id_type_id (int): The ID Type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkIDTypeExist($p_id_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkIDTypeExist(:p_id_type_id)');
        $stmt->bindValue(':p_id_type_id', $p_id_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteIDType
    # Description: Deletes the ID Type.
    #
    # Parameters:
    # - $p_id_type_id (int): The ID Type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteIDType($p_id_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteIDType(:p_id_type_id)');
        $stmt->bindValue(':p_id_type_id', $p_id_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getIDType
    # Description: Retrieves the details of a ID Type.
    #
    # Parameters:
    # - $p_id_type_id (int): The ID Type ID.
    #
    # Returns:
    # - An array containing the ID Type details.
    #
    # -------------------------------------------------------------
    public function getIDType($p_id_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getIDType(:p_id_type_id)');
        $stmt->bindValue(':p_id_type_id', $p_id_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateIDType
    # Description: Duplicates the ID Type.
    #
    # Parameters:
    # - $p_id_type_id (int): The ID Type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateIDType($p_id_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateIDType(:p_id_type_id, :p_last_log_by, @p_new_id_type_id)');
        $stmt->bindValue(':p_id_type_id', $p_id_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_id_type_id AS id_type_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['id_type_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateIDTypeOptions
    # Description: Generates the ID Type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateIDTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateIDTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $idTypeID = $row['id_type_id'];
            $idTypeName = $row['id_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($idTypeID, ENT_QUOTES) . '">' . htmlspecialchars($idTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>