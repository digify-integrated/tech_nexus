<?php
/**
* Class ContactDirectoryModel
*
* The ContactDirectoryModel class handles contact directory related operations and interactions.
*/
class ContactDirectoryModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateContactDirectory
    # Description: Updates the contact directory.
    #
    # Parameters:
    # - $p_contact_directory_id (int): The contact directory ID.
    # - $p_contact_directory_name (string): The contact directory name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateContactDirectory($p_contact_directory_id, $p_contact_name, $p_position, $p_location, $p_directory_type, $p_contact_information, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateContactDirectory(:p_contact_directory_id, :p_contact_name, :p_position, :p_location, :p_directory_type, :p_contact_information, :p_last_log_by)');
        $stmt->bindValue(':p_contact_directory_id', $p_contact_directory_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_name', $p_contact_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_position', $p_position, PDO::PARAM_STR);
        $stmt->bindValue(':p_location', $p_location, PDO::PARAM_STR);
        $stmt->bindValue(':p_directory_type', $p_directory_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_contact_information', $p_contact_information, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertContactDirectory
    # Description: Inserts the contact directory.
    #
    # Parameters:
    # - $p_contact_directory_name (string): The contact directory name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertContactDirectory($p_contact_name, $p_position, $p_location, $p_directory_type, $p_contact_information, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertContactDirectory(:p_contact_name, :p_position, :p_location, :p_directory_type, :p_contact_information, :p_last_log_by, @p_contact_directory_id)');
        $stmt->bindValue(':p_contact_name', $p_contact_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_position', $p_position, PDO::PARAM_STR);
        $stmt->bindValue(':p_location', $p_location, PDO::PARAM_STR);
        $stmt->bindValue(':p_directory_type', $p_directory_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_contact_information', $p_contact_information, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_contact_directory_id AS p_contact_directory_id");
        $p_contact_directory_id = $result->fetch(PDO::FETCH_ASSOC)['p_contact_directory_id'];

        return $p_contact_directory_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkContactDirectoryExist
    # Description: Checks if a contact directory exists.
    #
    # Parameters:
    # - $p_contact_directory_id (int): The contact directory ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkContactDirectoryExist($p_contact_directory_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkContactDirectoryExist(:p_contact_directory_id)');
        $stmt->bindValue(':p_contact_directory_id', $p_contact_directory_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactDirectory
    # Description: Deletes the contact directory.
    #
    # Parameters:
    # - $p_contact_directory_id (int): The contact directory ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteContactDirectory($p_contact_directory_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteContactDirectory(:p_contact_directory_id)');
        $stmt->bindValue(':p_contact_directory_id', $p_contact_directory_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactDirectory
    # Description: Retrieves the details of a contact directory.
    #
    # Parameters:
    # - $p_contact_directory_id (int): The contact directory ID.
    #
    # Returns:
    # - An array containing the contact directory details.
    #
    # -------------------------------------------------------------
    public function getContactDirectory($p_contact_directory_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getContactDirectory(:p_contact_directory_id)');
        $stmt->bindValue(':p_contact_directory_id', $p_contact_directory_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>