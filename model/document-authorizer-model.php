<?php
/**
* Class DocumentAuthorizerModel
*
* The DocumentAuthorizerModel class handles document authorizer related operations and interactions.
*/
class DocumentAuthorizerModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertDocumentAuthorizer
    # Description: Inserts the document authorizer.
    #
    # Parameters:
    # - $p_department_id (int): The department ID.
    # - $authorizer_id (int): The authorizer ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertDocumentAuthorizer($p_department_id, $authorizer_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDocumentAuthorizer(:p_department_id, :authorizer_id, :p_last_log_by, @p_document_authorizer_id)');
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
        $stmt->bindValue(':authorizer_id', $authorizer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_document_authorizer_id AS p_document_authorizer_id");
        $p_document_authorizer_id = $result->fetch(PDO::FETCH_ASSOC)['p_document_authorizer_id'];

        return $p_document_authorizer_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDocumentAuthorizerExist
    # Description: Checks if a document authorizer exists.
    #
    # Parameters:
    # - $p_document_authorizer_id (int): The document authorizer ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDocumentAuthorizerExist($p_document_authorizer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDocumentAuthorizerExist(:p_document_authorizer_id)');
        $stmt->bindValue(':p_document_authorizer_id', $p_document_authorizer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDocumentDepartmentAuthorizerExist
    # Description: Checks if a document department authorizer exists.
    #
    # Parameters:
    # - $p_department_id (int): The department ID.
    # - $p_authorizer_id (int): The authorizer ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDocumentDepartmentAuthorizerExist($p_department_id, $p_authorizer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDocumentDepartmentAuthorizerExist(:p_department_id, :p_authorizer_id)');
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_INT);
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
    # Function: deleteDocumentAuthorizer
    # Description: Deletes the document authorizer.
    #
    # Parameters:
    # - $p_document_authorizer_id (int): The document authorizer ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteDocumentAuthorizer($p_document_authorizer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteDocumentAuthorizer(:p_document_authorizer_id)');
        $stmt->bindValue(':p_document_authorizer_id', $p_document_authorizer_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDocumentAuthorizer
    # Description: Retrieves the details of a document authorizer.
    #
    # Parameters:
    # - $p_document_authorizer_id (int): The document authorizer ID.
    #
    # Returns:
    # - An array containing the document authorizer details.
    #
    # -------------------------------------------------------------
    public function getDocumentAuthorizer($p_document_authorizer_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDocumentAuthorizer(:p_document_authorizer_id)');
        $stmt->bindValue(':p_document_authorizer_id', $p_document_authorizer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>