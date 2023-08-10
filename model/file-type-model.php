<?php
/**
* Class FileTypeModel
*
* The FileTypeModel class handles file type related operations and interactions.
*/
class FileTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateFileType
    # Description: Updates the file type.
    #
    # Parameters:
    # - $p_file_type_id (int): The file type ID.
    # - $p_file_type_name (string): The file type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateFileType($p_file_type_id, $p_file_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateFileType(:p_file_type_id, :p_file_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_file_type_id', $p_file_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_file_type_name', $p_file_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertFileType
    # Description: Inserts the file type.
    #
    # Parameters:
    # - $p_file_type_name (string): The file type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertFileType($p_file_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertFileType(:p_file_type_name, :p_last_log_by, @p_file_type_id)');
        $stmt->bindValue(':p_file_type_name', $p_file_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_file_type_id AS p_file_type_id");
        $p_file_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_file_type_id'];

        return $p_file_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkFileTypeExist
    # Description: Checks if a file type exists.
    #
    # Parameters:
    # - $p_file_type_id (int): The file type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkFileTypeExist($p_file_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkFileTypeExist(:p_file_type_id)');
        $stmt->bindValue(':p_file_type_id', $p_file_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteFileType
    # Description: Deletes the file type.
    #
    # Parameters:
    # - $p_file_type_id (int): The file type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteFileType($p_file_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteFileType(:p_file_type_id)');
        $stmt->bindValue(':p_file_type_id', $p_file_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLinkedFileExtension
    # Description: Deletes all the linked file extension on the file type.
    #
    # Parameters:
    # - $p_file_type_id (int): The file type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLinkedFileExtension($p_file_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLinkedFileExtension(:p_file_type_id)');
        $stmt->bindValue(':p_file_type_id', $p_file_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getFileType
    # Description: Retrieves the details of a file type.
    #
    # Parameters:
    # - $p_file_type_id (int): The file type ID.
    #
    # Returns:
    # - An array containing the file type details.
    #
    # -------------------------------------------------------------
    public function getFileType($p_file_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getFileType(:p_file_type_id)');
        $stmt->bindValue(':p_file_type_id', $p_file_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateFileType
    # Description: Duplicates the file type.
    #
    # Parameters:
    # - $p_file_type_id (int): The file type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateFileType($p_file_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateFileType(:p_file_type_id, :p_last_log_by, @p_new_file_type_id)');
        $stmt->bindValue(':p_file_type_id', $p_file_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_file_type_id AS file_type_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['file_type_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

     # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateFileTypeOptions
    # Description: Generates the file type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateFileTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateFileTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $fileTypeID = $row['file_type_id'];
            $fileTypeName = $row['file_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($fileTypeID, ENT_QUOTES) . '">' . htmlspecialchars($fileTypeName, ENT_QUOTES) . '</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>