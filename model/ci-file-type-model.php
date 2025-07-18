<?php
/**
* Class CIFileTypeModel
*
* The CIFileTypeModel class handles ci file type related operations and interactions.
*/
class CIFileTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCIFileType
    # Description: Updates the ci file type.
    #
    # Parameters:
    # - $p_ci_file_type_id (int): The ci file type ID.
    # - $p_ci_file_type_name (string): The ci file type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateCIFileType($p_ci_file_type_id, $p_ci_file_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCIFileType(:p_ci_file_type_id, :p_ci_file_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_ci_file_type_id', $p_ci_file_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ci_file_type_name', $p_ci_file_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertCIFileType
    # Description: Inserts the ci file type.
    #
    # Parameters:
    # - $p_ci_file_type_name (string): The ci file type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertCIFileType($p_ci_file_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCIFileType(:p_ci_file_type_name, :p_last_log_by, @p_ci_file_type_id)');
        $stmt->bindValue(':p_ci_file_type_name', $p_ci_file_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_ci_file_type_id AS p_ci_file_type_id");
        $p_ci_file_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_ci_file_type_id'];

        return $p_ci_file_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkCIFileTypeExist
    # Description: Checks if a ci file type exists.
    #
    # Parameters:
    # - $p_ci_file_type_id (int): The ci file type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkCIFileTypeExist($p_ci_file_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCIFileTypeExist(:p_ci_file_type_id)');
        $stmt->bindValue(':p_ci_file_type_id', $p_ci_file_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCIFileType
    # Description: Deletes the ci file type.
    #
    # Parameters:
    # - $p_ci_file_type_id (int): The ci file type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteCIFileType($p_ci_file_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCIFileType(:p_ci_file_type_id)');
        $stmt->bindValue(':p_ci_file_type_id', $p_ci_file_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCIFileType
    # Description: Retrieves the details of a ci file type.
    #
    # Parameters:
    # - $p_ci_file_type_id (int): The ci file type ID.
    #
    # Returns:
    # - An array containing the ci file type details.
    #
    # -------------------------------------------------------------
    public function getCIFileType($p_ci_file_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCIFileType(:p_ci_file_type_id)');
        $stmt->bindValue(':p_ci_file_type_id', $p_ci_file_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateCIFileType
    # Description: Duplicates the ci file type.
    #
    # Parameters:
    # - $p_ci_file_type_id (int): The ci file type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateCIFileType($p_ci_file_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateCIFileType(:p_ci_file_type_id, :p_last_log_by, @p_new_ci_file_type_id)');
        $stmt->bindValue(':p_ci_file_type_id', $p_ci_file_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_ci_file_type_id AS ci_file_type_id");
        $ciFileTypeID = $result->fetch(PDO::FETCH_ASSOC)['ci_file_type_id'];

        return $ciFileTypeID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateCIFileTypeOptions
    # Description: Generates the ci file type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateCIFileTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateCIFileTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $ciFileTypeID = $row['ci_file_type_id'];
            $ciFileTypeName = $row['ci_file_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($ciFileTypeID, ENT_QUOTES) . '">' . htmlspecialchars($ciFileTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>