<?php
/**
* Class FileExtensionModel
*
* The FileExtensionModel class handles file extension related operations and interactions.
*/
class FileExtensionModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    } 

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateFileExtensionOptions
    # Description: Generates the file extension options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateFileExtensionOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateFileExtensionOptions()');
        $stmt->execute();
        $count = $stmt->rowCount();

        if($count > 0){
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $htmlOptions = '';

            foreach ($options as $row) {
                $fileExtensionID = $row['file_extension_id'];
                $fileExtensionName = $row['file_extension_name'];

                $htmlOptions .= "<option value='". htmlspecialchars($fileExtensionID, ENT_QUOTES) ."'>". htmlspecialchars($fileExtensionName, ENT_QUOTES) ."</option>";
            }

            return $htmlOptions;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateFileExtension
    # Description: Updates the file extension.
    #
    # Parameters:
    # - $p_file_extension_id (int): The file extension ID.
    # - $p_file_extension_name (string): The file extension name.
    # - $p_file_type_id (int): The file type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateFileExtension($p_file_extension_id, $p_file_extension_name, $p_file_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateFileExtension(:p_file_extension_id, :p_file_extension_name, :p_file_type_id, :p_last_log_by)');
        $stmt->bindValue(':p_file_extension_id', $p_file_extension_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_file_extension_name', $p_file_extension_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_file_type_id', $p_file_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertFileExtension
    # Description: Inserts the file extension.
    #
    # Parameters:
    # - $p_file_extension_name (string): The file extension name.
    # - $p_file_type_id (int): The file type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertFileExtension($p_file_extension_name, $p_file_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertFileExtension(:p_file_extension_name, :p_file_type_id, :p_last_log_by, @p_file_extension_id)');
        $stmt->bindValue(':p_file_extension_name', $p_file_extension_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_file_type_id', $p_file_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_file_extension_id AS file_extension_id");
        $fileExtensionID = $result->fetch(PDO::FETCH_ASSOC)['file_extension_id'];

        return $fileExtensionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkFileExtensionExist
    # Description: Checks if a file extension exists.
    #
    # Parameters:
    # - $p_file_extension_id (int): The file extension ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkFileExtensionExist($p_file_extension_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkFileExtensionExist(:p_file_extension_id)');
        $stmt->bindValue(':p_file_extension_id', $p_file_extension_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteFileExtension
    # Description: Deletes the file extension.
    #
    # Parameters:
    # - $p_file_extension_id (int): The file extension ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteFileExtension($p_file_extension_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteFileExtension(:p_file_extension_id)');
        $stmt->bindValue(':p_file_extension_id', $p_file_extension_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getFileExtension
    # Description: Retrieves the details of a file extension.
    #
    # Parameters:
    # - $p_file_extension_id (int): The file extension ID.
    #
    # Returns:
    # - An array containing the file extension details.
    #
    # -------------------------------------------------------------
    public function getFileExtension($p_file_extension_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getFileExtension(:p_file_extension_id)');
        $stmt->bindValue(':p_file_extension_id', $p_file_extension_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateFileExtension
    # Description: Duplicates the file extension.
    #
    # Parameters:
    # - $p_file_extension_id (int): The file extension ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateFileExtension($p_file_extension_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateFileExtension(:p_file_extension_id, :p_last_log_by, @p_new_file_extension_id)');
        $stmt->bindValue(':p_file_extension_id', $p_file_extension_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_file_extension_id AS file_extension_id");
        $fileExtensionID = $result->fetch(PDO::FETCH_ASSOC)['file_extension_id'];

        return $fileExtensionID;
    }
    # -------------------------------------------------------------
}
?>