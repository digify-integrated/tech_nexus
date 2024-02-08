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
    # - $p_is_confidential (string): The document confidential status.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateDocument($p_document_id, $p_document_name, $p_document_description, $p_document_category_id, $p_is_confidential, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDocument(:p_document_id, :p_document_name, :p_document_description, :p_document_category_id, :p_is_confidential, :p_last_log_by)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_description', $p_document_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_category_id', $p_document_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_is_confidential', $p_is_confidential, PDO::PARAM_STR);
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
    #
    # Function: updateDocumentStatus
    # Description: Updates the document status.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    # - $p_document_status (string): The document status.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateDocumentStatus($p_document_id, $p_document_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDocumentStatus(:p_document_id, :p_document_status, :p_last_log_by)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_status', $p_document_status, PDO::PARAM_STR);
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
    # - $p_document_password (string): The document password.
    # - $p_document_path (string): The document path.
    # - $p_document_category_id (int): The document category.
    # - $p_document_extension (string): The document file extension.
    # - $p_document_size (double): The document file size.
    # - $p_is_confidential (string): The confidential status.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertDocument($p_document_name, $p_document_description, $p_author, $p_document_password, $p_document_path, $p_document_category_id, $p_document_extension, $p_document_size, $p_is_confidential, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDocument(:p_document_name, :p_document_description, :p_author, :p_document_password, :p_document_path, :p_document_category_id, :p_document_extension, :p_document_size, :p_is_confidential, :p_last_log_by, @p_document_id)');
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_description', $p_document_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_author', $p_author, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_password', $p_document_password, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_path', $p_document_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_category_id', $p_document_category_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_document_extension', $p_document_extension, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_size', $p_document_size, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_confidential', $p_is_confidential, PDO::PARAM_STR);
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
    #
    # Function: insertDocumentRestriction
    # Description: Inserts the document restriction.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    # - $p_department_id (int): The department ID.
    # - $p_contact_id (int): The contact ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertDocumentRestriction($p_document_id, $p_department_id, $p_contact_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDocumentRestriction(:p_document_id, :p_department_id, :p_contact_id, :p_last_log_by)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
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
    #
    # Function: checkDocumenRestrictionExist
    # Description: Checks if a document restriction exists.
    #
    # Parameters:
    # - $p_document_restriction_id (int): The document restriction ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDocumenRestrictionExist($p_document_restriction_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDocumenRestrictionExist(:p_document_restriction_id)');
        $stmt->bindValue(':p_document_restriction_id', $p_document_restriction_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDocumentDepartmentRestrictionExist
    # Description: Checks if a document department restriction exists.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    # - $p_department_id (int): The department ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDocumentDepartmentRestrictionExist($p_document_id, $p_department_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDocumentDepartmentRestrictionExist(:p_document_id, :p_department_id)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDocumentEmployeeRestrictionExist
    # Description: Checks if a document employee restriction exists.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    # - $p_contact_id (int): The contact ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDocumentEmployeeRestrictionExist($p_document_id, $p_contact_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDocumentEmployeeRestrictionExist(:p_document_id, :p_contact_id)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkIfDocumentAuthorizer
    # Description: Checks if the employee is an document autorizer.
    #
    # Parameters:
    # - $p_authorizer_id (int): The authorizer ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkIfDocumentAuthorizer($p_authorizer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkIfDocumentAuthorizer(:p_authorizer_id)');
        $stmt->bindValue(':p_authorizer_id', $p_authorizer_id, PDO::PARAM_INT);
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
    #
    # Function: deleteDocumentRestriction
    # Description: Deletes the document restriction.
    #
    # Parameters:
    # - $p_document_restriction_id (int): The document restriction ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteDocumentRestriction($p_document_restriction_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteDocumentRestriction(:p_document_restriction_id)');
        $stmt->bindValue(':p_document_restriction_id', $p_document_restriction_id, PDO::PARAM_INT);
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

    # -------------------------------------------------------------
    #
    # Function: getDocumentVersionHistoryByDocumentID
    # Description: Retrieves the details of a document version history by document ID.
    #
    # Parameters:
    # - $p_document_id (int): The document ID.
    #
    # Returns:
    # - An array containing the document version history details.
    #
    # -------------------------------------------------------------
    public function getDocumentVersionHistoryByDocumentID($p_document_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDocumentVersionHistoryByDocumentID(:p_document_id)');
        $stmt->bindValue(':p_document_id', $p_document_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDocumentStatus
    # Description: Retrieves the document status badge.
    #
    # Parameters:
    # - $p_document_status (string): The document status.
    #
    # Returns:
    # - An array containing the document details.
    #
    # -------------------------------------------------------------
    public function getDocumentStatus($p_document_status) {
        $statusClasses = [
            'Draft' => 'light-secondary',
            'For Publish' => 'light-primary',
            'Published' => 'light-success'
        ];
        
        $defaultClass = 'light-dark';
        
        $class = $statusClasses[$p_document_status] ?? $defaultClass;
        
        return '<span class="badge bg-' . $class . '">' . $p_document_status . '</span>';
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDocumentConfidentailStatus
    # Description: Retrieves the document confidential status badge.
    #
    # Parameters:
    # - $p_document_status (string): The document status.
    #
    # Returns:
    # - An array containing the document details.
    #
    # -------------------------------------------------------------
    public function getDocumentConfidentailStatus($p_is_confidential) {
        $statusClasses = [
            'Yes' => 'light-danger',
            'No' => 'light-warning',
        ];
        
        $defaultClass = 'light-info';
        
        $class = $statusClasses[$p_is_confidential] ?? $defaultClass;
        
        return '<span class="badge bg-' . $class . '">' . $p_is_confidential . '</span>';
    }
    # -------------------------------------------------------------
}
?>