<?php
/**
* Class DocumentModel
*
* The DocumentModel class handles document related operations and interactions.
*/
class DocumentModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDocument
    # Description: Updates the document.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    # - $p_document_name (string): The document name.
    # - $p_document_description (string): The document description.
    # - $p_document_category_id (int): The document category id.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateDocument($p_document_id, $p_document_name, $p_document_description, $p_document_category_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDocument(:p_document_id, :p_document_name, :p_document_description, :p_document_category_id, :p_last_log_by)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_description', $p_document_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_category_id', $p_document_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDocumentFile
    # Description: Updates the document file.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    # - $p_document_path (string): The document path file.
    # - $p_document_version (int): The document version.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateDocumentFile($p_document_id, $p_document_path, $p_document_version, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDocumentFile(:p_document_id, :p_document_path, :p_document_version, :p_last_log_by)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_path', $p_document_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_version', $p_document_version, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertDocument
    # Description: Inserts the document.
    #
    # Parameters:
    # - $p_document_name (string): The document name.
    # - $p_document_description (string): The document description.
    # - $p_author (int): The author.
    # - $p_document_path (string): The document path.
    # - $p_document_category_id (int): The document category.
    # - $p_document_extension (string): The document file extension.
    # - $p_document_size (double): The document file size.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertDocument($p_document_name, $p_document_description, $p_author, $p_document_path, $p_document_category_id, $p_document_extension, $p_document_size, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDocument(:p_document_name, :p_document_description, :p_author, :p_document_path, :p_document_category_id, :p_document_extension, :p_document_size, :p_last_log_by, @p_document_id)');
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_description', $p_document_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_author', $p_author, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_path', $p_document_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_category_id', $p_document_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_extension', $p_document_extension, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_size', $p_document_size, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_document_id AS p_document_id");
        $p_document_id = $result->fetch(PDO::FETCH_ASSOC)['p_document_id'];

        return $p_document_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertDocumentVersionHistory
    # Description: Inserts the document.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    # - $p_document_path (string): The document path.
    # - $p_document_version (int): The document version.
    # - $p_uploaded_by (int): The uploaded by.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertDocumentVersionHistory($p_document_id, $p_document_path, $p_document_version, $p_uploaded_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDocumentVersionHistory(:p_document_id, :p_document_path, :p_document_version, :p_uploaded_by)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_path', $p_document_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_version', $p_document_version, PDO::PARAM_INT);
        $stmt->bindValue(':p_uploaded_by', $p_uploaded_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDocumentExist
    # Description: Checks if a document exists.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDocumentExist($p_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDocumentExist(:p_document_id)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDocument
    # Description: Deletes the document.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteDocument($p_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteDocument(:p_document_id)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDocument
    # Description: Retrieves the details of a document.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    #
    # Returns:
    # - An array containing the document details.
    #
    # -------------------------------------------------------------
    public function getDocument($p_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDocument(:p_document_id)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>